<?php
$arr=(json_decode($_POST['data'], true));
if (json_last_error()!= JSON_ERROR_NONE)
	die("Ошибка декодирования json: ".json_last_error_msg());

if ($arr['flags']=="")
	$stat=file_put_contents($arr["filename"],$arr["content"]);
else
	$stat=file_put_contents($arr["filename"],$arr["content"],$arr['flags']);

if (!$stat)
	die ("Ошибка записи в файл.");
?>
