<!DOCTYPE HTML>
<html>
 <head>
  <meta charset="utf-8">
  <title>GPIO</title>
  <style type="text/css">
  	a.knopka {
  color: #fff; /* цвет текста */
  text-decoration: none; /* убирать подчёркивание у ссылок */
  user-select: none; /* убирать выделение текста */
  background: rgb(212,75,56); /* фон кнопки */
  padding: .7em 1.5em; /* отступ от текста */
  outline: none; /* убирать контур в Mozilla */
}
a.knopka:hover { background: rgb(232,95,76); } /* при наведении курсора мышки */
a.knopka:active { background: rgb(152,15,0); } /* при нажатии */
  </style>
 </head>
 <body>

<?php
#require './lib/GPIO.php';
require 'gpio_class.php';
$gpio = new GPIO;
$gpio->PinMode(18, "out");
if($_GET){
	if($_GET['type'] == "on"){
	 $gpio->pwmWrite(18, 1);
	}
	if($_GET['type'] == "off"){
		$gpio->pwmWrite(18, 0);
	}
}
?>

<input value="ON" onclick="location.href='?type=on'" type="button" />
<input value="OFF" onclick="location.href='?type=off'" type="button" />


 </body>
</html>
