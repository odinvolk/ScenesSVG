<?php
if(!preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/', $_GET['ip']) || $_GET['sec']=="" || $_GET['read-conf']=="" )
{
	die("Неправильный ввод: ( url:<b>".$_GET['ip']."/".$_GET['sec']."</b>, файл: <b>".$_GET['read-conf']."</b>).");
}
else
{
	$options['ip']=$_GET['ip'];
	$options['p']=$_GET['sec'];
	$options['read-conf']='data/'.$_GET['read-conf'];

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://".$options['ip']."/".$options['p']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_VERBOSE, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_exec($ch);
	$http_code=curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	if($http_code!=200)
		die ("Нет связи с контроллером ( ".$_GET['ip']."/".$_GET['sec'].", код ответа: ".$http_code." ).");

//echo "Reading configuration... ";

		$pages = array("cf=1", "cf=2", "cf=7", "cf=8",);

		for ( $i = 0; $i < 10; $i++ )
		$pages[] = "cf=10&prn=$i";

		//$pages = array();
		$page = file_get_contents("http://".$options['ip']."/".$options['p']);

		if ( preg_match("/IN\/OUT/", $page) )
		$ports = 37;
		else
		$ports = preg_replace("/.*\?pt=(\d+).*/", "$1", $page);

		for ( $i = 0; $i <= $ports; $i++ )
		$pages[] = "pt=$i";

		$fh = fopen($options['read-conf'], "w");
		$dom = new DOMDocument();
		$preset_flag = 0;

		for ( $i = 0; $i < count($pages); $i++ )
		{
			if ( $preset_flag == 1 )
			{
				//echo "Setting preset 0\n";
				$page = file_get_contents("http://".$options['ip']."/".$options['p']."/?cf=1&pr=0");
				sleep(1);
				$preset_flag = 2;
			}

			$page = file_get_contents("http://".$options['ip']."/".$options['p']."/?".$pages[$i]);
			$page = str_replace("<<", "<", $page);

			@$dom->loadHTML($page);
			//$url = "http://".$options['ip']."/".$options['p']."/?";
			$url = "";

			$els=$dom->getelementsbytagname('input');
			foreach($els as $inp)
			{
				if ( $inp->getAttribute('type') != "submit" )
				{
					$name=$inp->getAttribute('name');
					//$value=urlencode($inp->getAttribute('value'));
					if ( $inp->getAttribute('type') == "checkbox" )
					{
						if ( $inp->hasAttribute('checked') )
						$value=1;
						else
						$value='';
					}
					else
					$value=$inp->getAttribute('value');

					$value = str_replace("&", "%26", $value); // &

					if ( $name != "pt" )
					{
						if ( $name == "sl" && empty($value));
						else
						$url .= "$name=$value&";
					}
				}
			}

			$select = $dom->getelementsbytagname('select');

			foreach($select as $elem)
			{
				$name=$elem->getAttribute('name');
				$els=$elem->getelementsbytagname('option');

				$sel_flag = 0;
				foreach($els as $inp)
				{
					if ( $inp->hasAttribute('selected') )
					{
						//$name=$inp->getAttribute('name');
						$value=urlencode($inp->getAttribute('value'));
						$value=$inp->getAttribute('value');
						$url .= "$name=$value&";
						$sel_flag = 1;

						if ( $pages[$i] == "cf=1" && $name == "pr" && !empty($value) )
						{
							$preset_flag = 1;
							$stored_preset = $value;
						}

					}
				}
				// Хак ввиду того, что PHP DOM почему-то не может распарсить значение <> поля "Mode"
				if ( $sel_flag == 0 && $name == "m" )
				$url .= "m=3&";

			}

			$url = preg_replace("/&$/", "", $url);
			if ( !preg_match("/^cf=1&/", $url) && $i < count($pages) - 1 )
			//if ( !preg_match("/^cf=1&/", $url) )
			$url .= "&nr=1";
			fwrite($fh, "$url\n");
		}

		fclose($fh);

		if ( $preset_flag == 2 )
		{
			//echo "Setting preset 1\n";
			$page = file_get_contents("http://".$options['ip']."/".$options['p']."/?cf=1&pr=$stored_preset");
			sleep(1);
		}


		echo "OK";
}
?>
