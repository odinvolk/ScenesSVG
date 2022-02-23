<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Мониторинг MegaD</title>

	<script>
var err = {};
var eip = "192.168.0.14", old_ip = "192.168.0.14", old_conf_file = "megad_14.cfg", conf_file = "megad_14.cfg",old_cmnts = "comments_14.txt",cmnts = "comments_14.txt",sec = "sec";

/*
 * void show_err() - показывает ошибки
 */
function show_err() {
	if(Object.keys(err).length){
		document.getElementById('err').style.display='block';
		document.getElementById('err').innerHTML="";

		for(key in err) {
			//console.log(key + " = " + err[key]);
			document.getElementById('err').innerHTML+=err[key];
		}
	}
	else{
	  document.getElementById('err').style.display='none';
	  document.getElementById('err').innerHTML="";
	}

}


/*
 * void fwrite - запись в файл
 * параметры те же, что и у file_put_contents
 */
function fwrite(file, msg, flags="") {

	var data=JSON.stringify({
		filename: file,
		content: msg,
		flags: ""
	});

	const url = "fwrite.php";
	const request = new XMLHttpRequest();
	request.open('POST', url, true);
	request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=utf-8');
/*	request.addEventListener("readystatechange", () => {
		if (request.readyState === 4 && request.status === 200) {
			console.log( request.responseText );
		}
		else
			if(request.status != 200)
			console.log( "Ошибка:" + request.responseText );
	});
*/
	request.send('data='+data);
}



/*
 * void read_conf - чтение конфигурации устройства
 */
function read_conf() {

	document.getElementById('read_conf').style.display='block';

	const request = new XMLHttpRequest();
	const url = "read_conf.php?ip="+eip+"&sec="+sec+"&read-conf="+conf_file;
	request.open('GET', url);
	request.setRequestHeader('Content-Type', 'application/x-www-form-url');
	request.addEventListener("readystatechange", () => {
	if (request.readyState === 4 && request.status === 200) {
		if(request.responseText=='OK'){
			//alert( 'Конфигурация прочитана.' );
			//delete err['cfg'];
			location.reload();
		}
		else{
			err['cfg']='<br>Чтение конфирурации: ' + request.responseText+"<br>";
			document.getElementById('err').innerHTML +=  err['cfg'];
			show_err();

			document.getElementById('mdid').innerHTML="";
			document.getElementById('srv').innerHTML="";
			document.getElementById('srv_t').innerHTML="";
			document.getElementById('scrpt').innerHTML="";
			document.getElementById('wdog').innerHTML="";
			document.getElementById('uart').innerHTML="";

			if(document.getElementById('main_info'))
				document.getElementById('main_info').innerHTML="";
			document.getElementById('read_conf').style.display='none';
		}
    }
	else if(request.readyState === 4 && request.status != 200)
	{
		err['cfg']="Чтение конфигурации: сервер недоступен. Код ответа: "+request.status+"<br>";
		show_err();
		document.getElementById('read_conf').style.display='none';
	}
});
request.send();
}



/*
 * void megad_com - посылает команды устройству
 */
function megad_com(cmd="") {

	const url = "md_com.php?ip=" + eip + "&sec=" + sec + "&cmd=" + cmd;
	const request = new XMLHttpRequest();
	request.open('GET', url);
	request.setRequestHeader('Content-Type', 'application/x-www-form-url');
	request.addEventListener("readystatechange", () => {
		if (request.readyState === 4 && request.status === 200) {
			//console.log( request.responseText );
			if(request.responseText.indexOf('Done')==-1){
				alert("Команда устройству: "+request.responseText);
			}
			else{
				if(cmd.indexOf('misc')!=-1)
					alert('Готово. Не забудьте перечитать конфигурацию.');
			}
		}
		else
			if(request.status != 200)
			alert("Команда устройству: cервер недоступен.");
	});
	request.send();
}


/*
 * void get_uptime - получает uptime и температуру контроллера
 */
function get_uptime() {
 if(!err['cfg1'] && !err['cfg2']){
	const url = "md_com.php?ip=" + eip + "&sec=" + sec + "/?cf=1";
	const request = new XMLHttpRequest();
	request.open('GET', url);
	request.setRequestHeader('Content-Type', 'application/x-www-form-url');
	request.addEventListener("readystatechange", () => {
		if (request.readyState === 4 && request.status === 200) {
			if(request.responseText.indexOf('Нет связи')==-1){
				document.getElementById('uptime').innerHTML = request.responseText.match(/Uptime: ([^<]*)/m)[0].replace('Uptime: ','').replace('d',' дней').replace(':',' час ')+' мин';
				document.getElementById('in_temp').innerHTML = request.responseText.match(/Temp: ([^<]*)/m)[0].replace('Temp: ','');
			}
		}
	});
	request.send();
 }
}



/*
 * void quest - обновляем состояние портов и значения датчиков
 */
function quest(){
 if(!err['cfg1'] && !err['cfg2']){
	const request = new XMLHttpRequest();
	const url = "md_com.php?ip=" + eip + "&sec=" + sec + "&cmd=cmd=all";
	request.open('GET', url);
	request.setRequestHeader('Content-Type', 'application/x-www-form-url');
	request.addEventListener("readystatechange", () => {
	if (request.readyState === 4 && request.status === 200) {
//console.log( request.responseText );
		var arr = request.responseText.split(";");
		if(arr.length<2){
			err['get1']="Опрос состояний: "+request.responseText+"<br>";
			show_err();
		}
		else{
			for (var i = 0; i < arr.length; i++){
				//входы
				if (document.getElementById('in_'+i)){
					if(arr[i].indexOf('OFF')!= -1)
						document.getElementById('in_'+i).innerHTML='<span class="lcd gray"></span>';
					if(arr[i].indexOf('ON')!= -1)
						document.getElementById('in_'+i).innerHTML='<span class="lcd green"></span>';
				document.getElementById('in_cnt'+i).innerHTML=(arr[i].split('/')[1]);
				}
				//выходы
				if (document.getElementById('out_'+i)){
					if(arr[i].indexOf('OFF')!= -1)
						document.getElementById('out_'+i).checked=false;
					if(arr[i].indexOf('ON')!= -1)
						document.getElementById('out_'+i).checked=true;
				}

				if (document.getElementById('out_'+i+'A') && document.getElementById('out_'+i+'B')){
					let arr1=arr[i].split('/');
					if(arr1[0].indexOf('NA')!= -1){
						document.getElementById('out_'+i+'A').style.display='none';
						document.getElementById('outS_'+i+'A').innerHTML='NA';
					}
					else{
						if(arr1[0].indexOf('OFF')!= -1)
							document.getElementById('out_'+i+'A').checked=false;
						if(arr1[0].indexOf('ON')!= -1)
							document.getElementById('out_'+i+'A').checked=true;
						document.getElementById('outS_'+i+'A').innerHTML='';
						document.getElementById('out_'+i+'A').style.display='inline-block';
					}
					if(arr1[1].indexOf('NA')!= -1){
						document.getElementById('out_'+i+'B').style.display='none';
						document.getElementById('outS_'+i+'B').innerHTML='NA';
					}
					else{
						if(arr1[1].indexOf('OFF')!= -1)
							document.getElementById('out_'+i+'B').checked=false;
						if(arr1[1].indexOf('ON')!= -1)
							document.getElementById('out_'+i+'B').checked=true;
						document.getElementById('outS_'+i+'B').innerHTML='';
						document.getElementById('out_'+i+'B').style.display='inline-block';
					}


				}


				//прочее
				if (document.getElementById('val_'+i)){
					document.getElementById('val_'+i).innerHTML=arr[i];
				}
			}
			delete err['get'];
			delete err['get1'];
			show_err();
		}
	}
	else
		if (request.status != 200){
			err['get']="Опрос состояний: сервер недоступен.<br>";
			show_err();
		}
	});
	request.send();
 }
 show_err();
}

window.onload = function(){ get_uptime(); quest();}
var intervalID = setInterval( quest, 10000); //задержка перед повтором, мс
setInterval( get_uptime, 60000);

	</script>

	<style>
html{
	font-family:sans-serif;
	-webkit-text-size-adjust:100%;
	-ms-text-size-adjust:100%;
	font-size:15px;
	background: linear-gradient(to top, #cacaca 36%, #eeeff0 100%) no-repeat fixed;
	height: 100%;
}


#err {
	text-align: center;
	background-color: pink;
    border: solid;
	border-color: #ea757e;
    margin: 12px;
    padding: 12px;
	display: none;
}

#read_conf {
	text-align: center;
	background-color: aliceblue;
    border: solid;
	border-color: blue;
    margin: 12px;
    padding: 12px;
	display: none;
}

.port{
	width: 167px;
	margin: 5px;
	padding: 5px;
	border: inset;
	border-radius: 15px;
	vertical-align: top;
	display: inline-block;

}
.in{
	background: #ecfce5;
	height: 193px;
}
.out{
	background: #fce4e4;
	height: 111px;
}
.misc{
	background: #edfafa;
	height: 207px;
}
.misc_txt{
    background: #000;
    color: transparent;
    text-shadow: 0px 1px 2px #898686;
	-webkit-background-clip: text;
    -moz-background-clip: text;
    margin-bottom: 3px;
	float: right;
}


.big{
	font-size:larger;
}

.red {
  background-color: red;
}
.green {
  background-color: #0afe46;
}
.gray {
  background-color: #05ef051a;
}
.lcd {
    height: 27px;
    width: 27px;
    border-radius: 50%;
	margin-bottom: 3px;
	float: right;
    display: inline;
    box-shadow: inset -1px -5px 10px #000;
}


.checkbox{
  display: inline-block;
  padding: 5px 5px 5px 42px;
  margin-bottom: 1px;
  position: relative;
  float: right;
  cursor: pointer;
}
.checkbox input{
  position: absolute;
  opacity: 0;
  cursor: inherit;
}
.checkbox span{
  display: inline-block;
  font: normal 12px/16px Arial;
  padding: 4px 0;
}
.checkbox span:before,
.checkbox span:after{
  content: '';
  position: absolute;
  top: 50%;
  transition: .3s;
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
}
.checkbox span:before{
  left: 0;
  height: 24px;
  margin-top: -12px;
  width: 46px;
  border-radius: 12px;
  background: #999;
  box-shadow: inset 0 1px 3px rgba(0,0,0,.4);
}
.checkbox span:after{
  left: 1px;
  height: 22px;
  width: 22px;
  margin-top: -11px;
  background: #fff;
  border-radius: 50%;
  box-shadow: 0 1px 2px rgba(0,0,0,.3);
}

.checkbox input:checked + span:before{
  background-color: #ff1a1f;
}
.checkbox input:checked + span:after{
  left: 23px;
}

.checkbox input:focus + span:before{
  box-shadow: 0 0 0 3px rgba(50,150,255,.2);
}

.checkbox input:disabled + span{
  opacity: .35;
}
.checkbox input:disabled + span:before{
  background: #999;
}
		</style>
	</head>
  <body>
<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


/**
 * void object2file - функция записи объекта в файл
 *
 * @param mixed value - объект, массив и т.д.
 * @param string filename - имя файла куда будет произведена запись данных
 * @return void
 *
 */
function object2file($value, $filename)
{
	$str_value = serialize($value);
	$f = fopen($filename, 'w');
	fwrite($f, $str_value);
	fclose($f);
}


/**
 * mixed object_from_file - функция восстановления данных объекта из файла
 *
 * @param string filename - имя файла откуда будет производиться восстановление данных
 * @return mixed
 *
 */
function object_from_file($filename)
{
	$file = file_get_contents($filename);
	$value = unserialize($file);
	return $value;
}
/**
 * mixed ONOFF2bool - функция преобразует "ON" и "OFF" в TRUE и FALSE
 *
 *
 */
function ONOFF2bool($m)
{
	if(strpos($m,"ON")===0)
		return TRUE;
	if(strpos($m,"OFF")===0)
		return FALSE;
	return NULL;
}
/**
 * mixed DS_mode - функция возвращает режим реакции порта на переход порога по номеру, записанному в файле конфигурации
 *
 *
 */
function DS_mode($m)
{
	switch ($m) {
		case 0:
			return " NA ";
			break;
		case 1:
			return ' &gt; ';
			break;
		case 2:
			return ' &lt; ';
			break;
		case 3:
			return ' &lt;&gt; ';
			break;
	}
	return NULL;
}
/**
 * mixed prg_op - функция возвращает режим реакции порта на переход порога по номеру, записанному в файле конфигурации
 *
 *
 */
function prg_op($prc)
{
	switch ($prc) {
		case 0:
			return " n ";
			break;
		case 1:
			return ' &gt; ';
			break;
		case 2:
			return ' &lt; ';
			break;
		case 3:
			return ' == ';
			break;
	}
	return NULL;
}
/**
 * mixed in_mode - функция возвращает режим порта в режиме ВХОД по номеру, записанному в файле конфигурации
 *
 *
 */
function in_mode($m)
{
	switch ($m) {
		case 0:
			return "Замыкание";
			break;
		case 1:
			return "Зам / разм";
			break;
		case 2:
			return "Размыкание";
			break;
		case 3:
			return "Клик";
			break;
	}
	return NULL;
}
/**
 * mixed out_mode - функция возвращает режим порта в режиме ВЫХОД по номеру, записанному в файле конфигурации
 *
 *
 */
function out_mode($m)
{
	switch ($m) {
		case 0:
			return "SW";
			break;
		case 1:
			return "PWM";
			break;
		case 2:
			return "DS2413";
			break;
		case 3:
			return "SW LINK";
			break;
	}
	return NULL;
}

/**
 * mixed pt_mode - функция возвращает тип порта по номеру, записанному в файле конфигурации
 *
 *
 */
function pt_mode($pty, $m, $d)
{
	switch ($pty) {
		case 0:
			return "In";
			break;
		case 1:
			return "Out";
			break;
		case 2:
			return "ADC";
			break;
		case 3:
			switch ($d){
				case 1:
					return "DHT11";
					break;
				case 2:
					return "DHT22";
					break;
				case 3:
					return "DS18B20";
					break;
				case 4:
					return "iB";
					break;
				case 5:
					return "1W BUS";
					break;
				case 6:
					return "W26";
					break;
			}
			return "DSen";
			break;
		case 4:
			switch ($m){
				case 1:
					return "I2C, SDA";
					break;
				case 2:
					return "I2C, SCL";
					break;
			}

			return "I2C";
			break;
	}
	return NULL;
}


/**
 * string highlight_prg - функция подсвечивает ситаксис программ
 *
 *
 */
function highlight_prg($str)
{
	$blue=array('if','&','±','NA','<','&lt;','>','&gt;','<>','&lt;&gt;','==','*','|',"^", "v", "x","+","-","~");

	$arr=explode(" ", $str);
	for($i=0; $i<count($arr); $i++){


		if (in_array($arr[$i],$blue))
			$arr[$i]='<span style="color: #0000FB">'.$arr[$i].'</span>';

		elseif(strpos($arr[$i],"P")===0 || $arr[$i]=='Время')
			$arr[$i]='<span style="color: #007F00">'.$arr[$i].'</span>';

		elseif($arr[$i]=='(' || $arr[$i]==')' || $arr[$i]=='{' || $arr[$i]=='}')
			$arr[$i]='<span style="color: #5F005F">'.$arr[$i].'</span>';

		elseif (is_numeric($arr[$i]))
			$arr[$i]='<span style="color: #FF0000">'.$arr[$i].'</span>';

		elseif(strpos($arr[$i],":")){
			$arr1=explode(";", $arr[$i]);
			for($j=0; $j<count($arr1); $j++){
				if(strpos($arr1[$j],"p")!==FALSE){
					$arr1[$j]=ltrim($arr1[$j],"p");
					$arr1[$j]='<span style="color: #0000FB">пауза </span><span style="color: #FF0000">'.($arr1[$j]/10).'</span>';
					$arr[$i]=implode(" " , $arr1);
				}
				elseif(strpos($arr1[$j],"r")!==FALSE){
					$arr1[$j]=ltrim($arr1[$j],"r");
					$arr1[$j]='<span style="color: #0000FB">повтор </span><span style="color: #FF0000">'.($arr1[$j]).'</span>';
					$arr[$i]=implode(" " , $arr1);
				}
				elseif(strpos($arr1[$j],":")!==FALSE){
					$arr2=explode(":", $arr1[$j]);
					if ($arr2[0]=="a"){
						$arr2[0]='<span style="color: #007F00"> ВСЕ ПОРТЫ </span>';
					}
					elseif (strpos($arr2[0],"g")===0){
						$arr2[0]=ltrim($arr2[0],"g");
						$arr2[0]='<span style="color: #007F00"> Группа '.$arr2[0].' </span>';
					}
					else
						$arr2[0]='<span style="color: #007F00"> P'.$arr2[0].' </span>';
					$arr2[1]='<span style="color: #FF0000"> '.$arr2[1].'</span>';
					$arr1[$j]=implode('<span style="color: #0000FF">:</span>' , $arr2);
				}
			$arr[$i]=implode("; " , $arr1);
			}
		}
		else
			$arr[$i]='<span style="color: #FF0000">'.$arr[$i].'</span>';
	}
	return implode(" " , $arr);
}


/**
 * string highlight_prg - функция подсвечивает время в cron
 *
 *
 */
function highlight_time($str)
{
	$arr=explode(":", $str);
	if($arr[0][0]=='/'){
		$arr[0]=substr($arr[0],1);
		$arr[0]='<span style="color: #0000FF"> Каждые </span><span style="color: #FF0000">'.$arr[0].'час в </span>';
		$arr[1]='<span style="color: #FF0000">'.$arr[1].' мин</span>';
	}
	elseif($arr[0][0]=='*'){
		$arr[0]="";
		$arr[1]=substr($arr[1],1);
		$arr[1]='<span style="color: #0000FF"> Каждые </span><span style="color: #FF0000">'.$arr[1].'мин</span>';
	}
	else{
		$arr[0]='<span style="color: #FF0000">'.$arr[0].':</span>';
		$arr[1]='<span style="color: #FF0000">'.$arr[1].' </span>';
	}
	if($arr[2]==0)
		$arr[2]='<span style="color: #00657f"> Каждый день</span>';
	else{
		$arr[2]=str_replace('1','Пн',$arr[2]);
		$arr[2]=str_replace('2','Вт',$arr[2]);
		$arr[2]=str_replace('3','Ср',$arr[2]);
		$arr[2]=str_replace('4','Чт',$arr[2]);
		$arr[2]=str_replace('5','Пт',$arr[2]);
		$arr[2]=str_replace('6','Сб',$arr[2]);
		$arr[2]=str_replace('7','Вс',$arr[2]);
		$arr[2]='<span style="color: #00657f"> '.$arr[2].'</span>';
	}

	return implode("" , $arr);
}

/*////////////////////////////////////////////////////////////////////////////////////////////////////////
*
*
*
*////////////////////////////////////////////////////////////////////////////////////////////////////////


	$comments=array();
	if (count($_POST)>0){
		$options['eip']=$_POST['ip'];
		$options['pwd']=$_POST['sec'];
		$options['read-conf']=$_POST['read-conf'];
		$options['cmnts']=$_POST['cmnts'];

		echo '<script>
			old_ip=eip="'.$_POST['ip'].'";
			sec="'.$_POST['sec'].'";
			old_conf_file=conf_file="'.$_POST['read-conf'].'";
			old_cmnts=cmnts="'.$_POST['cmnts'].'";
			</script>';

		if($_POST['save_cmnts']){
			foreach ($_POST as $key => $value){
				if (strpos($key,"text_")===0){
					$comments[$key]=$value;
				}
			}
			object2file($comments, 'data/'.$options['cmnts']);
		}
	}
	elseif(file_exists('data/lastfiles.txt')){
		$lastfiles=file('data/lastfiles.txt');
		$options['eip']=trim($lastfiles[0]);
		$options['pwd']=trim($lastfiles[1]);
		$options['read-conf']=trim($lastfiles[2]);
		$options['cmnts']=trim($lastfiles[3]);
		echo '<script>old_ip=eip="'.$options['eip'].'"; sec="'.$options['pwd'].'"; old_conf_file=conf_file="'.$options['read-conf'].'"; old_cmnts=cmnts="'.$options['cmnts'].'";
			</script>';
	}
	else{
		$options['eip']="192.168.0.14";
		$options['pwd']="sec";
		$options['read-conf']="megad_14.cfg";
		$options['cmnts']="comments_14.txt";
	}


	if(file_exists('data/'.$options['cmnts'])){
		$comments=object_from_file('data/'.$options['cmnts']);
	}


	echo '<form action="index.php" method="POST" id="mainform">';
	echo '<div style="background: #ffffff; margin: 5px; padding: 15px; border: inset; border-radius: 15px; width: 340px; height: 262px; display: inline-block; vertical-align: top;">';

	echo '<p> Период опроса контроллера (сек) <input type="number" id="refresh" value="5" style="width: 50px;" oninput="if(document.getElementById(\'refresh\').value<1){document.getElementById(\'refresh\').value=1;}"> </p>';

	echo '<p>IP: <input type="text" value='.$options['eip'].' id="ip"  name="ip"  style="width: 91px;" oninput="document.getElementById(\'read-conf\').value=\'megad_\'+(document.getElementById(\'ip\').value.split(\'.\')[3])+\'.cfg\';document.getElementById(\'cmnts\').value=\'comments_\'+(document.getElementById(\'ip\').value.split(\'.\')[3])+\'.txt\'" onchange="eip=this.value" required>';
	echo ' Пароль: <input type="text" value='.$options['pwd'].' size="2" id="sec" name="sec" onchange="sec=this.value" required></p>';
	echo '<p> Файл с конфигурацией: <input type="text" value='.$options['read-conf'].' id="read-conf" name="read-conf" style="width: 150px; float: right;"  onchange="conf_file=this.value" required></p>';
	echo '<p> Файл с комментариями: <input type="text" value='.$options['cmnts'].' id="cmnts" name="cmnts" style="width: 150px; float: right;" onchange="cmnts=this.value" required></p>';
	echo '<input type="hidden" value="TRUE" id="save_cmnts" name="save_cmnts">';
	echo '<script>
	function OK_btn_clk(){
		clearInterval(intervalID);
		intervalID = setInterval(quest, (document.getElementById(\'refresh\').value)*1000);
		eip = document.getElementById(\'ip\').value;
		sec = document.getElementById(\'sec\').value;
		conf_file = document.getElementById(\'read-conf\').value;
		cmnts = document.getElementById(\'cmnts\').value;
		if(eip!=old_ip || old_conf_file!=conf_file || old_cmnts!=cmnts){
			document.getElementById(\'save_cmnts\').value=\'\';
			document.forms["mainform"].submit();
		}
		fwrite("data/lastfiles.txt",eip+"\n"+sec+"\n"+conf_file+"\n"+cmnts);
		quest();
	}
	</script>';
	echo '<div style="text-align: center">';
	echo '<p> <button type="button" onclick="OK_btn_clk()" style="width: 300px;">Применить.</button></p>';
	echo '<p> <button type="button" onclick="
		eip = document.getElementById(\'ip\').value;
		sec = document.getElementById(\'sec\').value;
		conf_file = document.getElementById(\'read-conf\').value;
		read_conf();
		" style="width: 300px;">Прочитать конфигурацию устройства.</button></p>';
	echo '<p> <button type = "submit" style="width: 300px;">Сохранить комментарии</button></p>';
	echo '</div></div> ';

	echo '<div style="background: #ffffff; margin: 5px; padding: 15px; border: inset; border-radius: 15px; width: 340px; height: 262px; display: inline-block; vertical-align: top;">';
	echo '<p>ID контроллера: <b><span id="mdid"></span></b></p>
		<p>Сервер: <b><span id="srv"></span></b></p>
		<p>Тип сервера: <b><span id="srv_t"></span></b></p>
		<p>Скрипт на сервере: <b>/<span id="scrpt"></span></b></p>
		<p>Порт Watchdog: <b><span id="wdog"></span></b></p>
		<p>Режим UART-портов (P32/P33): <b><span id="uart"></span></b></p>
		<p>Uptime: <b><span id="uptime"></span></b></p>
		<p>Температура контроллера: <b><span id="in_temp"></span> °С</b></p>';

	echo '</div><br>';


	echo '<div id="err">!!</div>';
	echo '<div id="read_conf">Читаем конфигурацию...</div>';

	if(!file_exists('data/'.$options['read-conf'])){
		die ('
		<script>
			if(!err[\'cfg\']){
				err[\'cfg1\']="Файл конфигурации не найден ( '.$options['read-conf'].' ).";
				document.getElementById(\'err\').style.display=\'block\';
				document.getElementById(\'err\').innerHTML=err[\'cfg1\'];
			}
		</script>');
	}

	else
	{
		$lines=file('data/'.$options['read-conf']);
		parse_str($lines[0],$options);
		if(count($options)<3){
			die ('
		<script>
			if(!err[\'cfg\']){
				err[\'cfg2\']="Файл конфигурации повреждён или не может быть прочитан.";
				document.getElementById(\'err\').style.display=\'block\';
				document.getElementById(\'err\').innerHTML=err[\'cfg2\'];
			}
		</script>');
		}

		echo '
		<script>
			document.getElementById(\'err\').innerHTML="";
			document.getElementById(\'err\').style.display=\'none\';
		</script>';

		parse_str($lines[1],$cf2);
		echo '
		<script>
			document.getElementById(\'mdid\').innerHTML="'.(isset($cf2['mdid'])?$cf2['mdid']:'').'";
			document.getElementById(\'srv\').innerHTML="'.(isset($options['sip'])?$options['sip']:'').'";
			document.getElementById(\'srv_t\').innerHTML="'.(isset($options['srvt'])?($options['srvt']=='0' ? 'HHTP':'MQTT'):'').'";
			document.getElementById(\'scrpt\').innerHTML="'.(isset($options['sct'])?$options['sct']:'').'";
			document.getElementById(\'wdog\').innerHTML="'.(isset($options['pr'])?$options['pr']:'').'";';
		if($options['gsm'][0]=='0')
			$options['gsm']='Disabled';
		elseif($options['gsm'][0]=='1')
			$options['gsm']='GSM';
		elseif($options['gsm'][0]=='2')
			$options['gsm']='RS485';
		echo'
			document.getElementById(\'uart\').innerHTML="'.$options['gsm'].'";
		</script>';



		echo '<div id="main_info">';

		echo '<div style="border-radius: 15px; border: outset; background: #e1e1e1; text-align: center; margin: 5px;" onclick="if(document.getElementById(\'ports\').style.display==\'none\'){
					document.getElementById(\'ports\').style.display=\'block\';
					this.innerHTML=\'Порты\';
				}
				else {
					document.getElementById(\'ports\').style.display=\'none\';
					this.innerHTML+=\' ...\';
				}

			"> Порты </div>';

		echo '<div id="ports">';

		//входы
		foreach ($lines as $line)
		{

			if(strpos($line,"pn=")===0 && strpos($line,"pty=0"))
			{
				echo '<div class="port in">';
				parse_str($line,$params);
				echo '<a class="big" href="http://'.$options['eip'].'/'.$options['pwd'].'/?pt='.$params['pn'].'" target="_blank">Порт '.$params['pn'].'</a> ';
				echo '<span id="in_'.$params['pn'].'"><span class="lcd gray"> </span></span> <br>';

				echo '<input type="text" style="width: 160px;" id="text_'.$params['pn'].'" name="text_'.$params['pn'].'" value="'.(isset($comments['text_'.$params['pn']])? $comments['text_'.$params['pn']]:"").'" /><br>';

				echo 'Счётчик: <b><span id="in_cnt'.$params['pn'].'" style="color: #b40606;"></span></b><br>';

				echo '<div title = "Флажок (чекбокс) справа от поля Act определяет логику работы сценария. Если он не установлен (по умолчанию), то сценарий выполняется ТОЛЬКО если сервер не прописан, недоступен или HTTP-статус отличен от 200. Если флажок установлен, то сценарий выполняется всегда независимо от наличия сервера. Контроллер в этом случае будет сообщать на сервер о событиях, но его ответные команды в рамках одной TCP-сессии будут проигнорированы.">
				Action:<input type=checkbox '.($params['af']? "checked":"").' onclick="this.checked=!this.checked;"><br><b style="color: #0044ec;"> '.($params['ecmd']? $params['ecmd']:'нет действия').'</b><br></div>';
				echo '<div title = "Флажок (чекбокс) справа от поля Net указывает, что NetAction будет вызван ТОЛЬКО при недоступности сервера (или когда HTTP-статус ответа отличен от 200). По умолчанию вызывается всегда.">
				NetAction:<input type=checkbox '.($params['naf']? "checked":"").' onclick="this.checked=!this.checked;"><br><b style="color: #0044ec;"> '.($params['eth']? $params['eth']:'нет').'</b><br></div>';
				echo '<div title = "Флажок (чекбокс) справа от поля Mode указывает, что при наличии сервера, устройство отправляет на сервер сообщения всегда в режиме P&R, а при его отсутствии Action выполняется только в том режиме, который установлен в Mode. Данная опция не доступна для Click Mode.">
				Режим: <b>'.in_mode($params['m']).'</b> <input type=checkbox '.($params['misc']? "checked":"").' onclick="this.checked=!this.checked;"> </div>';
				echo '<span title = "Raw - параметр отключает встроенную защиту от дребезга.">
				Raw: <b> '.($params['d']? "ДА":"НЕТ")."</b></span>\n";
				echo '<span title = "Параметр отключает отправку информации на сервер о переключениях входа.">
				Mute:<b> '.($params['mt']? "ДА":"НЕТ")."</b></span><br>\n";

				if (isset($params['disp']) && $params['disp']!=0){
					echo "SDA дисплея: ".$params['disp']."\n";
				}
				else
					echo "<br>\n";
				echo'</div>';

			}

		}
		echo '<br>';


		//выходы
		echo '<script>
			function toggle_out(out){
				if (document.getElementById(\'out_\'+out).checked)
					megad_com(\'cmd=\'+out+\':1\');
				else
					megad_com(\'cmd=\'+out+\':0\');
			}
				</script>';

		foreach ($lines as $line)
		{

			if(strpos($line,"pn=")===0 && strpos($line,"pty=1"))
			{
				echo '<div class="port out">';
				parse_str($line,$params);
				echo '<a class="big" href="http://'.$options['eip'].'/'.$options['pwd'].'/?pt='.$params['pn'].'" target="_blank">Порт '.$params['pn'].'</a>';
				if (out_mode($params['m'])=="SW")
					echo '<label class="checkbox"> <input type="checkbox" id="out_'.$params['pn'].'"'.' onchange ="toggle_out('.$params['pn'].')"> <span></span> </label><br>';
				elseif(out_mode($params['m'])=="DS2413")
					echo '<span style="margin-left: 15px;">
					A: <input type="checkbox" id="out_'.$params['pn'].'A'.'"'.' onchange ="toggle_out(\''.$params['pn'].'A'.'\')"><span id="outS_'.$params['pn'].'A'.'" style="color: crimson;"></span>
					B: <input type="checkbox" id="out_'.$params['pn'].'B'.'"'.' onchange ="toggle_out(\''.$params['pn'].'B'.'\')"><span id="outS_'.$params['pn'].'B'.'" style="color: crimson;"></span>
					</span><br>';


				echo '<input type="text" style="width: 160px;" id="text_'.$params['pn'].'" name="text_'.$params['pn'].'" value="'.(isset($comments['text_'.$params['pn']])? $comments['text_'.$params['pn']]:"").'" /><br>';

				if(out_mode($params['m'])=="DS2413")
					echo '<a href="http://'.$options['eip'].'/'.$options['pwd'].'/?pt='.$params['pn'].'&cmd=list" target="_blank">Список устройств</a><br>';

				if(isset($params['grp']))
					echo "Группа: ".$params['grp']."<br>";
				if (isset($params['d']))
					echo "По умолчанию: ".$params['d']."<br>\n";
				echo "Режим: ".out_mode($params['m'])."<br>\n";
				if (isset($params['disp']) && $params['disp']!=0){
					echo "SDA дисплея: ".$params['disp']."<br>\n";
				}
				else
					echo "<br>\n";
				echo'</div>';
			}
		}

		echo '<br>';

		//Прочее
		foreach ($lines as $line)
		{
			if(strpos($line,"pn=")===0 && !strpos($line,"pty=255") && (strpos($line,"pty=2") || strpos($line,"pty=3") || strpos($line,"pty=4")))
			{
				echo '<div class="port misc">';
				parse_str($line,$params);
				echo '<span class="big"><a href="http://'.$options['eip'].'/'.$options['pwd'].'/?pt='.$params['pn'].'" target="_blank">Порт '.$params['pn'].'</a> &nbsp;&nbsp; ';
				$pt_m=pt_mode($params['pty'],(isset($params['m'])? $params['m']:"" ),isset($params['d'])? $params['d']:"" );
				if ($pt_m=="I2C, SCL")
				{
					echo '<b class="misc_txt">I2C, SCL</b><br>';
					echo '<input type="text" style="width: 160px;" id="text_'.$params['pn'].'" name="text_'.$params['pn'].'" value="'.(isset($comments['text_'.$params['pn']])? $comments['text_'.$params['pn']]:"").'" /></span><br>';
				}
				elseif ($pt_m=="I2C, SDA")
				{
					echo '<b class="misc_txt">I2C, SDA</b><br>';
					echo '<input type="text" style="width: 160px;" id="text_'.$params['pn'].'" name="text_'.$params['pn'].'" value="'.(isset($comments['text_'.$params['pn']])? $comments['text_'.$params['pn']]:"").'" /></span><br>';
					echo 'SCL: '.$params['misc'].'<br>
					<a href ="http://'.$options['eip'].'/'.$options['pwd'].'/?pt='.$params['pn'].'&cmd=scan" target="_blank">I2C Scan</a>';
				}
				elseif ($pt_m=="DS18B20" || $pt_m=="ADC")
				{
					echo '<b class="misc_txt">'.$pt_m.'</b><br>';
					echo '<input type="text" style="width: 160px;" id="text_'.$params['pn'].'" name="text_'.$params['pn'].'" value="'.(isset($comments['text_'.$params['pn']])? $comments['text_'.$params['pn']]:"").'" /></span><br>';

					echo '<b><span id="val_'.$params['pn'].'" style="color: #b40606;"></span></b><br>';

					echo '<input type="number" style="width: 67px;" id=misc_'.$params['pn'].'  value="'.$params['misc'].'"> ';
					echo '<button type="button"
					onclick="megad_com(&quot;pt='.$params['pn'].'%26misc=&quot;+document.getElementById(\'misc_'.$params['pn'].'\').value)"
					>Применить</button><br>';
					echo 'Режим:<b>'.DS_mode($params['m']).'</b><br>';
					echo 'Гистерезис:<b>'.$params['hst'].'</b>';
					echo '
					<div title = "Флажок (чекбокс) справа от поля Act определяет логику работы сценария. Если он не установлен (по умолчанию), то сценарий выполняется ТОЛЬКО если сервер не прописан, недоступен или HTTP-статус отличен от 200. Если флажок установлен, то сценарий выполняется всегда независимо от наличия сервера. Контроллер в этом случае будет сообщать на сервер о событиях, но его ответные команды в рамках одной TCP-сессии будут проигнорированы.">
					Action:
					<input type=checkbox '.($params['af']? "checked":"").' onclick="this.checked=!this.checked;">
					<br><b style="color: #0044ec;"> '.($params['ecmd']? $params['ecmd']:'нет действия').'</b><br>
					</div>';
					echo '<div title = "Флажок (чекбокс) справа от поля Net указывает, что NetAction будет вызван ТОЛЬКО при недоступности сервера (или когда HTTP-статус ответа отличен от 200). По умолчанию вызывается всегда.">
					NetAction:<input type=checkbox '.($params['naf']? "checked":"").' onclick="this.checked=!this.checked;"><br><b style="color: #0044ec;"> '.($params['eth']? $params['eth']:'нет').'</b><br></div>';
					if (isset($params['disp']) && $params['disp']!=0){
						echo "SDA дисплея: ".$params['disp']."<br>\n";
					}
					else
						echo "<br>\n";
				}
				else
				{
					echo '<b class="misc_txt">'.$pt_m.'</b><br>';
					echo '<input type="text" style="width: 160px;" id="text_'.$params['pn'].'" name="text_'.$params['pn'].'" value="'.(isset($comments['text_'.$params['pn']])? $comments['text_'.$params['pn']]:"").'" /></span><br>';

					echo '<b><span id="val_'.$params['pn'].'" style="color: #b40606;"></span></b><br>';
					if($pt_m=="1W BUS")
					{
						echo '<a href="http://'.$options['eip'].'/'.$options['pwd'].'/?pt='.$params['pn'].'&cmd=list" target="_blank">Список устройств</a>';
					}
					if($pt_m=="iB")
					{
						echo '<div title = "Флажок (чекбокс) справа от поля Act определяет логику работы сценария. Если он не установлен (по умолчанию), то сценарий выполняется ТОЛЬКО если сервер не прописан, недоступен или HTTP-статус отличен от 200. Если флажок установлен, то сценарий выполняется всегда независимо от наличия сервера. Контроллер в этом случае будет сообщать на сервер о событиях, но его ответные команды в рамках одной TCP-сессии будут проигнорированы.">
						Action:<input type=checkbox '.($params['af']? "checked":"").' onclick="this.checked=!this.checked;"><br><b> '.($params['ecmd']? $params['ecmd']:'НЕТ ДЕЙСТВИЯ').'</b><br></div>';
						echo '<div title = "Флажок (чекбокс) справа от поля Net указывает, что NetAction будет вызван ТОЛЬКО при недоступности сервера (или когда HTTP-статус ответа отличен от 200). По умолчанию вызывается всегда.">
						NetAction:<input type=checkbox '.($params['naf']? "checked":"").' onclick="this.checked=!this.checked;"><br><b> '.($params['eth']? $params['eth']:'НЕТ ДЕЙСТВИЯ').'</b><br></div>';
					}
				}
			echo'</div>';
			}
		}
		echo '<div style="clear: both;"></div>';
		echo '</div>';

		echo '<div style="border-radius: 15px; border: outset; background: #e1e1e1; text-align: center; margin: 5px;" onclick="if(document.getElementById(\'acts\').style.display==\'none\'){
					document.getElementById(\'acts\').style.display=\'block\';
					this.innerHTML=\' Сценарии \';
				}
				else {
					document.getElementById(\'acts\').style.display=\'none\';
					this.innerHTML+=\' ...\';
				}

			"> Сценарии </div>';

		echo '<div id="acts" style="background: #eef6ea; display: block; margin: 5px; padding: 15px; border: inset; border-radius: 15px;">';
		echo '<button type="button" onclick="if(document.getElementById(\'help\').style.display==\'none\')document.getElementById(\'help\').style.display=\'block\';else document.getElementById(\'help\').style.display=\'none\'">?</button>';


		echo '<div id="help" style="display:none; padding: 30px; background: #f6f6f6; border: inset; border-radius: 15px;">

<a href="https://ab-log.ru/smart-house/ethernet/megad-2561">https://ab-log.ru/smart-house/ethernet/megad-2561</a>
<p>
В поле Action через точку с запятой можно описать до пяти действий.</p>
<p>Формат поля Action следующий: <strong>X:Y;X:Y;X:Y</strong><br />
где, X - <strong>номер порта</strong>, а Y - <strong>действие/команда</strong></p>
<p>Если вместо номера порта написать "а", команда будет применена ко всем выходам. </p>

<strong>Возможные команды:</strong>

<ul>

	<li>"0" - <strong>выключить</strong>;</li>
	<li>"1" - <strong>включить</strong>;</li>
	<li>"2" - изменить состояние на противоположное <strong>(переключить)</strong>, т.е. если было включено выключить и наоборот</li>
	<li>"3" - прямая синхронизация выхода со входом (кнопка нажата - лампа включена; кнопка отпущена - лампа выключена)</li>
	<li>"4" - обратная синхронизация выхода со входов (кнопка нажата - лампа выключена; кнопка отпущена - лампа включена)</li>
	<li>[0..255] - для порта в режиме ШИМ установить значение</li>
	<li>"*" - для порта в режиме ШИМ <strong>переключить</strong> 0/текущее значение (аналогично команде "2" для обычного порта)</li>
	<li>"рХ" - пауза в сценарии (Х * 0,1с)</li>
	<li>"rХ" - повтор (Х раз)</li>
	<li>"|" - разделитель для действий при повторном и длительном нажатии в режилме "Р" и "Click Mode". Одинарное нажатие | двойное нажатие | удержание(только "Click Mode").</li>
</ul>

<strong>Команды для управления диммируемыми каналами:</strong>
<ul>
	<li>[0..255] -  установить значение диммера</li>
	<li>"+" - увеличить</li>
	<li>"-" - уменьшить</li>
	<li>"~" - при использовании одной кнопки нажатие - ВКЛ/ВЫКЛ, удержание - увеличить или уменьшить </li>
	<li>"*" - установить на максимум</li>
	<li>"^[1..9]" запускает процесс увеличения (можно задать скорость, по умолчанию 5)</li>
	<li>"v[1..9]" запускает процесс уменьшения </li>
	<li>"x" останавливает ранее запущенный процесс изменения</li>

</ul>

<p>Для того, чтоб работали команды "3", "4" и "~" выход должен быть а режиме P&amp;R</p>

<p>Паузы в полном объеме и без ограничений работают только в сценариях по умолчанию (Action).<br />
Начиная с версии прошивки 4.16b8 паузы также поддерживаются и в командах, поступающих извне. Но в этом случае одновременно может выполняться только один сценарий, содержащий паузы.
<br>
При выполнении сценария, содержащего паузу, работа контроллера не блокируется. Паузы выполняются в фоновом режиме.</p>


<p>Возможна организация бесконечного цикла - &quot;r0&quot;.<br />
В текущий момент времени в прошивке нет процедуры отмены запущенного бесконечного цикла.</p>


<button type="button" onclick="if(document.getElementById(\'help\').style.display==\'none\')document.getElementById(\'help\').style.display=\'block\';else document.getElementById(\'help\').style.display=\'none\'">?</button>

</div>';


		echo '<h3><a href="http://'.$options['eip'].'/'.$options['pwd'].'/?cf=9" target="_blank">Program</a></h3>';
		$prg=array();
		foreach ($lines as $line)
		{
			if(strpos($line,"cf=10")===0 )
				array_push($prg,$line);
		}
		for ($i = 0; $i < 10 ; $i++)
		{
			parse_str($prg[$i],$params);
			if(isset($params['prp']) && $params['prp']!=="" && !$params['prs'])
			{
				echo '<b>';
				$str= 'if ( P'.$params['prp'].prg_op($params['prc']).$params['prv'];

				while (strpos($params['prd'],'&')===0)
				{
					$j=substr($params['prd'],(strpos($params['prd'],'&')+1));
					parse_str($prg[$j],$params);
					$prg[$j]="";
					if ($params['prp']=="")
						$str.= '<span style="color:red;"> & !!</span>';
					else
						$str.= ' & P'.$params['prp'].prg_op($params['prc']).$params['prv'];
				}
				$str.= ' ) { '.$params['prd'].' }';
				echo highlight_prg($str);

				echo '<br></b><input type="text" id="text_pr'.$i.'" name="text_pr'.$i.'" value="'.(isset($comments['text_pr'.$i])? $comments['text_pr'.$i]:"").'" style="color: #088b0e; width: -webkit-fill-available; min-width: 400px;"/><br>';
			}
		}



		echo "<h3>По портам</h3>";
		$prg=array();
		foreach ($lines as $line)
		{
			if(strpos($line,"pn=")===0 && strpos($line,"ecmd"))
			{

				parse_str($line,$params);
				if($params['ecmd']){
					echo'<b>';
					$str= 'if ( P'.$params['pn'];
					if($params['pty']!=0)
						$str.= ''.DS_mode($params['m']).'';
					else
						$str.= ' == '.in_mode($params['m']);
					$str.= ''.$params['misc'].'';
					if (isset($params['hst']))
						$str.= ' ± '.$params['hst'];
					$str.= ' ) { ' .$params['ecmd'].' } ';

					echo highlight_prg($str);

					echo '
					<span title = "Флажок (чекбокс) справа от поля Act определяет логику работы сценария. Если он не установлен (по умолчанию), то сценарий выполняется ТОЛЬКО если сервер не прописан, недоступен или HTTP-статус отличен от 200. Если флажок установлен, то сценарий выполняется всегда независимо от наличия сервера. Контроллер в этом случае будет сообщать на сервер о событиях, но его ответные команды в рамках одной TCP-сессии будут проигнорированы."> Всегда
					<input type=checkbox '.($params['af']? "checked":"").' onclick="this.checked=!this.checked;">
					</span>';
					echo '</b><br><input type="text" id="text_prp'.$params['pn'].'" name="text_prp'.$params['pn'].'" value="'.(array_key_exists('text_prp'.$params['pn'],$comments)? $comments['text_prp'.$params['pn']]:"").'" style="color: #088b0e; width: -webkit-fill-available; min-width: 400px;"/><br>';
				}
			}
		}


		echo '<h3><a href="http://'.$options['eip'].'/'.$options['pwd'].'/?cf=7" target="_blank">Расписание (Cron)</a></h3>';
		foreach ($lines as $line)
		{
			if(strpos($line,"cf=7")===0 )
			{
				parse_str($line,$params);
				for ($i = 0; $i < 5 ; $i++)
				{
					if($params['crnt'.$i])
					{
						echo '<b>'.highlight_prg('if ( Время == ').highlight_time($params['crnt'.$i]).highlight_prg(' ) { '.$params['crna'.$i].' }').'</b><br>';
						echo '<input type="text" id="text_cron'.$i.'" name="text_cron'.$i.'" value="'.(isset($comments['text_cron'.$params['crnt'.$i]])? $comments['text_cron'.$params['crnt'.$i]]:"").'" style="color: #088b0e; width: -webkit-fill-available; min-width: 400px;" /><br>';
					}
				}
			}

		}

		echo '</div>
		</div>';
		echo '</form>';

	}
?>
  </body>
</html>
