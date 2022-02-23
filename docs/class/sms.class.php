<?php

/*
 * @version 0.1 (auto-set)
 */

 /**
  * Summary of sms
  * @access public
  * @param mixed $phone   Phone number
  * @param mixed $message Message
  * @return void
  */

function sms($phone, $message) {
	$token = 'C2CEC586-61C2-D5FD-C6CF-BE5DF4052C1C';   //Получить тут - https://sms.ru/?panel=my (Внизу страницы)
	if(strlen($phone) == '11' && substr($phone, 0, 2) == '79') {
		$send = json_decode(file_get_contents('https://sms.ru/sms/send?api_id='.$token.'&to='.$phone.'&msg='.urlencode($message).'&json=1'));

		if($send->status == 'OK') {
			if($send->sms->$phone->status == 'OK') {
				DebMes('Done! Message send for number - '.$phone.'. Message: '.$message.'. Balance: '.$send->balance, 'sms_send');
			} else {
				DebMes('Error! Error sending SMS!', 'sms_send');
			}
		} else {
			DebMes('Error! Error authorization! Incorrect token!', 'sms_send');
		}
	} else {
		DebMes('Error! Incorrect phone number - '.$phone.'. Message: '.$message, 'sms_send');
	}
}
