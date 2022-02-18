# Сценарии


## Служебные

### MegaD_time
Но если вы решили использовать контроллер без часов, то синхронизировать время можно от сервера.
Достаточно отправить контроллеру запрос вида

``` php
//http://192.168.0.14/sec/?cf=7&stime=10:57:06:4
//$time3 = date('H:i:s:w', time()); // 14:50:29:0
$time4 = strftime("%H:%M:%S:%u<br>"); // 14:50:29:0
file_get_contents("http://192.168.10.100/sec/?cf=7&stime=".$time4);
file_get_contents("http://192.168.10.101/sec/?cf=7&stime=".$time4);
//print $time3.'<br>';
//echo strftime("%H:%M:%S:%u<br>");
//http://192.168.10.101/sec/?pt=34&ext=13&epwm=4095  levelSaved levelWork
//http://192.168.10.101/sec/?pt=34&ext=1&epwm=4
//file_get_contents("http://".$ipAddress."/".$Password."/?pt=".$Port."&ext=".$Ext."&epwm=".$value);
//file_get_contents("http://".$ipAddress."/".$Password."/?cmd=".$Port."e".$Ext.":".$value);


//$d = getdate(); // использовано текущее время
//foreach ( $d as $key => $val ) echo "$key = $val<br>";
//echo "<hr>Сегодня: $d[mday].$d[mon].$d[year]";
```

### updateTodayText
Устанавливает текст дня недели и даты

``` php
$days=array('воскресенье','понедельник','вторник','среда','четверг','пятница','суббота');
$months=array(1=>'январь','февраль','март','апрель','май','июнь','июль','август','сентябрь','октябрь','ноябрь','декабрь');
$months2=array(1=>'января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
$daymonth=array(1=>'первое','второе','третье','четвертое','пятое','шестое','седьмое','восьмое','девятое','десятое','одинадцатое','двенадцатое','тринадцатое','четырнадцатое','пятнадцатое','шестнадцатое','семнадцатое','восемнадцатое','девятнадцатое','двадцатое','двадцать первое','двадцать второе','двадцать третье','двадцать четвертое','двадцать пятое','двадцать шестое','двадцать седьмое','двадцать восьмое','двадцать девятое','тридцатое','тридцать первое');
$day_num=(int)date('w');
$month_num=(int)date('m');
$day_month=(int)date('j');
$day_of_month=$daymonth[$day_month];
$day_of_week=$days[$day_num];
$month_txt1=$months[$month_num];
$month_txt2=$months2[$month_num];
setGlobal('Time.MonthNum',$month_num);
setGlobal('Time.MonthTXT',$month_txt1);
setGlobal('Time.MonthTXTalt',$month_txt2);
setGlobal('Time.DayOfMonthNum',$day_month);
setGlobal('Time.DayOfMonthTXT',$day_of_month);
setGlobal('Time.DayOfWeekTXT',$day_of_week);
setGlobal('Time.DayOfWeekNum',$day_num);

// ================= раскидываем дату и время если изменилось в Windows =========================================
$h=(int)date('G',time());

if (gg('Time.DateD') != date("d", time())) {

   $day = array(
       'воскресенье', 'понедельник', 'вторник', 'среда',
       'четверг', 'пятница', 'суббота'
   );

   $month = array(
       1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля',
       5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа',
       9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря'
   );

   $monthAlt = array(
       1 => 'ЯНВАРЬ', 2 => 'ФЕВРАЛЬ', 3 => 'МАРТ', 4 => 'АПРЕЛЬ',
       5 => 'МАЙ', 6 => 'ИЮНЬ', 7 => 'ИЮЛЬ', 8 => 'АВГУСТ',
       9 => 'СЕНТЯБРЬ', 10 => 'ОКТЯБРЬ', 11 => 'НОЯБРЬ', 12 => 'ДЕКАБРЬ'
   );
   // раскидываем дату по свойствам  
   sg("Time.DateD",date( "d", time()));
   sg("Time.DateM",date( "m", time()));
   sg("Time.DateY",date( "Y", time()));
   sg("Time.Date",date( "d.m.Y", time()));
   sg("Time.Day",$day[date("w")]);
   sg("Time.Month",$month[date("n")]);
   sg("Time.MonthAlt",$monthAlt[date("n")]);

}
```

### moonephase

``` php
//require_once("lib/moonPhase.class.php");
include("lib/moonePhase.class.php");
// создать экземпляр класса, и использовать текущее время
$moon = new MoonePhase();
//$age = round( $moon->age(), 1 );
$age = round( $moon->age(), 0 );
$stage = $moon->phase() < 0.5 ? 'растущая' : 'убывающая';
$distance = round( $moon->distance(), 2 );
$nextnewtime = gmdate( 'G:i:s', $moon->next_new_moon() );
$nextnewdate = gmdate( 'd.m.Y', $moon->next_new_moon() );
$nextfulltime = gmdate( 'G:i:s', $moon->next_full_moon() );
$nextfulldate = gmdate( 'd.m.Y', $moon->next_full_moon() );
$newmoon = gmdate('d.m.Y G:i:s', $moon->new_moon() );
$fullmoon = gmdate('d.m.Y G:i:s', $moon->full_moon() );
$phasename = $moon->phase_name();
$phaseimg = $moon->phase_img();
$illumination = round( $moon->illumination()*100, 2 );
sg('Moon.MoonPhase',$illumination); //Фаза луны %
sg('Moon.MoonAge',$age); //Возраст луны (лунный день)
sg('Moon.MoonStage',$stage); //Стадия луны
sg('Moon.MoonDistance',$distance); //Дистанция до луны
sg('Moon.MoonNextNewTime',$nextnewtime); //Время следующего полнолуния
sg('Moon.MoonNextNewDate',$nextnewdate); //Новолуние
sg('Moon.MoonNextFullTime',$nextfulltime); //Длительность лунного дня
sg('Moon.MoonNextFullDate',$nextfulldate); //Дата следующего полнолуния
sg('Moon.MoonPhaseName',$phasename); //Фаза луны
sg('Moon.MoonPhaseImg',$phaseimg); //Фаза луны image file
sg('Moon.MoonNew',$newmoon); //Новолуние
sg('Moon.MoonFull',$fullmoon); //Время следующего полнолуния

if($moon->full_moon() > time()){
 sg('Moon.fullNewMoon',$fullmoond);
}else{
 sg('Moon.fullNewMoon',$nextfulldate);
}
if($moon->new_moon() > time()){
 sg('Moon.nextNewMoon',$newmoond);
}else{
 sg('Moon.nextNewMoon',$nextnewdate);
}

```

### serialphp

``` php
//include 'PhpSerial.php'; php_serial.class.php
include './scripts/PhpSerial.php';
$serial = new PhpSerial;
    $serial->deviceSet("/dev/ttyS0");
    $serial->confBaudRate(9600);
    $serial->confParity("none");
    $serial->confCharacterLength(8);
    $serial->confStopBits(1);
    $serial->confFlowControl("none");

$serial->deviceOpen();
$readx = $serial->readPortLine(100,"\n");
echo nl2br("\n".$readx."\n\n");
$read0 = $serial->readPortLine(24,"");
echo nl2br("\n".$read0."\n\n");
//$read1 = $serial->readPortLine(24,"");
$serial -> deviceClose();
$readxx = explode(" ",$readx);
print_r($readxx);
echo nl2br("\n".$readxx[0]."\n"); //test
$read00 = explode(" ",$read0);
print_r($read00);
//$read11 = explode(" ",$read1);
$arrCount = count($read00);
for($x=0;$x<$arrCount;$x++){
    $m=1;
    if ($read00[$x]>0){
        $m=$read00[$x];
        break;
    }
}
$massCharNum = $x;
for($x=0;$x<$arrCount;$x++){
    $unit=1;
    if (ctype_alpha($read00[$x])){
        $unit=$read00[$x];
        break;
    }
}

date_default_timezone_set('Europa/Moscov');
echo nl2br("\n\nDebug String: ".$read0."\n");
echo nl2br("Mass: ".$m."\n");
echo nl2br("Unit: ".$unit."\n");
echo nl2br("Time: ".date("n/j/y H:i:s",time())."\n\n");
```

### CheckYaExport
Проверка YaExport
https://connect.smartliving.ru/profile/1717/scripts.html#

``` php
//if (gg('ThisComputer.InternetAccess') != '1'){ //Если нет интернета
//rs("no_internet", "Яндекс пробки");
//clearTimeout('CheckYaExport');
//}else{

//setTimeOut('CheckYaExport','runScript("CheckYaExport");',10*60);

sg('reg_2.updatedDate',date("m.d.y"));

 $data_file="http://export.yandex.ru/bar/reginfo.xml?region=2";
	$xml = simplexml_load_file($data_file);
	$title=$xml->region->title;
	$level=$xml->traffic->region->level;
	$hint=$xml->traffic->region->hint;
	$icon=$xml->traffic->region->icon;
	$url=$xml->traffic->region->url;
	$updatetime=$xml->traffic->region->time;
 	$weather=$xml->weather->source;
 	$sun_rise=$xml->weather->day->sun_rise;
 	$sunset=$xml->weather->day->sunset;
 	$weather_type=$xml->weather->day->day_part->weather_type;
 	$temperature=$xml->weather->day->day_part->temperature;
 	$dampness=$xml->weather->day->day_part->dampness;
 	$wind_speed=$xml->weather->day->day_part->wind_speed;
 	$pressure=$xml->weather->day->day_part->pressure;
 	$wind_direction=$xml->weather->day->day_part->wind_direction;
 	$imagev2=$xml->weather->day->day_part->image;


//echo $sunset;
//echo $title;

 if ($level!=""){
	sg('reg_2.title',$title);
	sg('reg_2.level',$level);
	sg('reg_2.hint',$hint);
	sg('reg_2.icon',$icon);
	sg('reg_2.url',$url);
	sg('reg_2.sun_rise',$sun_rise);
	sg('reg_2.sunset',$sunset);
	sg('reg_2.weather_type',$weather_type);
	sg('reg_2.wind_speed',$wind_speed);
	sg('reg_2.wind_direction',$wind_direction);
	sg('reg_2.temperature',$temperature);
	sg('reg_2.imagev2',$imagev2);
	sg('reg_2.pressure',$pressure);
	sg('reg_2.dampness',$dampness);
	sg('reg_2.updatedTime',$updatetime);
  	sg('reg_2.data_update',$updatetime.":00 ".date("d.m.Y"));
 }else{
	sg('reg_2.title',"Нет связи");
	sg('reg_2.level',"NA");
	sg('reg_2.hint', "Нет связи");
	sg('reg_2.icon',"yellow");
	sg('reg_2.url',$url);
	sg('reg_2.sun_rise',"NA");
	sg('reg_2.sunset',"NA");
	sg('reg_2.weather_type',"NA");
	sg('reg_2.wind_speed',"NA");
	sg('reg_2.wind_direction',"NA");
	sg('reg_2.temperature',"NA");
	sg('reg_2.imagev2',"NA");
	sg('reg_2.pressure',"NA");

	sg('reg_2.updatedTime',$updatetime);
 }
//}
runScript('calculate_realfeel');
```

### calculate_realfeel

Расчет температуры как ощущается
Скрипт для сцены Погода от OpenWeatherMap
http://majordomo.smartliving.ru/forum/viewtopic.php?f=4&t=2950

``` php
$t = gg('current_weather.temperature'); // температура на улице
$h = gg('current_weather.humidity'); // влажность на улице
$w = gg('current_weather.wind_speed'); // данные о ветре из прогноза OpenWeather
$w=$w*3.6/1.609;//m/s -> mph
$tF = $t *1.8+32; // перевод температуры в Farenheit

if ($t > 26.6){
 // Считаем HeatIndex по Rothfusz
 $_f=-42.379 + 2.04901523*$tF + 10.14333127*$h - 0.22475541*$tF*$h - 0.00683783*$tF*$tF - 0.05481717*$h*$h + 0.00122874*$tF*$tF*$h + 0.00085282*$tF*$h*$h -0.00000199*$tF*$tF*$h*$h;
   if(($tF<112)&&($h<13)){
   // If the RH is less than 13% and the temperature is between 80 and 112 degrees F, then the following adjustment is subtracted from HI:
     $_f=$_f-((13-$h)/4)*sqrt((17-abs($tF-95.))/17);
   }
   if(($tF<88)&&($h>85)){
    // On the other hand, if the RH is greater than 85% and the temperature is between 80 and 87 degrees F, then the following adjustment is added to HI:
    //ADJUSTMENT = [(RH-85)/10] * [(87-T)/5]
    $_f=$_f+(($h-85)/10)*((87-$tF)/5);
   }
}else if ($t <= 10){
 // Считаем фактор WindChild
 $_f = 35.74+(0.6215*$tF)-(35.75*pow($w,0.16))+((0.4275*$tF)*pow($w,0.16));
}else{
 //считаем по упрощенной формуле
 $_f = 0.5*($tF+61+(($tF-68)*1.2)+($h*0.094));
}
// переводим в цельсии
 $rf = round(($_f-32)/1.8,0);
//и пропишем значение
sg("current_weather.reelFeel",$rf);
// внесем дни недели прогноза
$numD=gg("ow_setting.forecast_interval");
$day[0]="ВСК";
$day[1]="ПНД";
$day[2]="ВТР";
$day[3]="СРД";
$day[4]="ЧТВ";
$day[5]="ПТН";
$day[6]="СБТ";
for ($i = 1; $i < $numD; $i++) {
 $dayNum = date("w", strtotime($date .' +'.$i.' day'));
 $name = $day[date("w", strtotime($date .' +'.$i.' day'))];
 sg("ow_forecast".$i.".name", $name);
 }
```

### CalcSunSetRise
скрипт, вычисляющего рассвет, закат, сумерки и т.д. на основе географических координат

``` php
$lat=gg('Sun.latitude');   // широта 59.9489
$long=gg('Sun.longitude'); // долгота 30.4821

$sun_info = date_sun_info(time(), $lat, $long);

foreach ($sun_info as $key => $val) {

if ($key == 'sunrise') {

$sunrise = $val;
//echo 'Восход: '.date("H:i", $sunrise).'<br>';
setGlobal('Sun.SunRiseTime',date("H:i", $sunrise));
}

if ($key == 'sunset') {

$sunset = $val;
$day_length = $sunset - $sunrise;

//echo 'Заход: '.date("H:i", $sunset).'<br>';
//echo 'Долгота дня: '.gmdate("H:i", $day_length).'<br>';
setGlobal('Sun.SunSetTime',date("H:i", $sunset));
setGlobal('Sun.LongTag',gmdate("H:i", $day_length));
}

if ($key == 'transit') {
//echo 'В зените: '.date("H:i", $val).'<br>';
setGlobal('Sun.Transit',date("H:i", $val));
}

if ($key == 'civil_twilight_begin') {
//echo 'Начало утренних сумерек: '.date("H:i", $val).'<br>';
setGlobal('Sun.civil_begin',date("H:i:s", $val));
}

if ($key == 'civil_twilight_end') {
//echo 'Конец вечерних сумерек: '.date("H:i", $val).'<br>';
setGlobal('Sun.civil_end',date("H:i", $val));
}

}
```
