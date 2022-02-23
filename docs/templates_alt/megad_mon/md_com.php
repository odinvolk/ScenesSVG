<?php
if(!preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/', $_GET['ip']) || $_GET['sec']=="")
{
	die("Неправильный ввод.");
}
else
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://".$_GET['ip']."/".$_GET['sec']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_VERBOSE, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_exec($ch);
	$http_code=curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	if($http_code!=200)
		die ("Нет связи с контроллером ( ".$_GET['ip']."/".$_GET['sec'].", код ответа: ".$http_code." ).");

	echo file_get_contents("http://".$_GET['ip']."/".$_GET['sec']."/?".$_GET['cmd']);
}
?>
