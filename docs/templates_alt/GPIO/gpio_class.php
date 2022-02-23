<?php
Class GPIO{
	function PinMode($pin, $mode){
		return shell_exec("/usr/local/bin/gpio mode $pin $mode");
	}
	function DigitalWrite($pin, $value){
		return shell_exec("/usr/local/bin/gpio write $pin $value");
	}
	function DigitalRead($pin, $value){
		return shell_exec("/usr/local/bin/gpio -g read $pin");
	}
	function pwmWrite($pin, $value){
		return shell_exec("/usr/local/bin/gpio pwm $pin $value");
	}
}
?>
