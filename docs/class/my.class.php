<?php
/*
* Свои дополнительные функции
*/
/**
 * Секунды в строку
 * Эта функция возвращает кол-во секунд преобразованное в дни, часы, минуты, секунды
 * используется в коде OHM_data
 * 
 * @param integer $seconds всего время в секундах.
 * @return string "1234567" возвращает "14 дн., 6 час., 56 мин., 7 сек.".
 */
function secondsToString($seconds) {
    $result = '';
    if ($seconds >= 86400) {
        $days = floor($seconds / 86400);
        $seconds = $seconds % 86400;
        $result = $days.' д., ';
    }
    if ($seconds >= 3600) {
        $hours = floor($seconds / 3600);
        $seconds = $seconds % 3600;
        $result .= $hours.' ч, '; //час.
    }
    if ($seconds >= 60) {
        $minutes = floor($seconds / 60);
        $seconds = $seconds % 60;
        $result .= $minutes.' м, '; // мин.
    }
    $result .= $seconds.' с'; // сек.
    return $result;
}

// Возвращает оставшееся время в секундах работы таймера по его имени.
// Если таймера нет, вернет 0
function timeOutResidue($title) {
  $timerId=timeOutExists($title);
  if ($timerId) {
   $timer_job=SQLSelectOne("SELECT UNIX_TIMESTAMP(RUNTIME) as TM FROM jobs WHERE ID='".$timerId."'");
   $diff=(int)$timer_job['TM']-time(); // получаем время в секундах, оставшееся до запланированного срабатывания таймера
    return $diff;
   } else {
    return 0;
   }
 }

// Получить имя класса по имени объекта
function getClassNameByObject($title) {
 $obj=getObject($title);
 $class=SQLSelectOne("SELECT * FROM classes WHERE ID='".(int)$obj->class_id."'");
 if (is_array ($class )) {
  return $class['TITLE'];
 } else {
  return 'error';
 }
}

// зададим константу в которой будут все наименования и краткие описания функций
define('VOVIX_SCRIPTS','
secondsToString($seconds)~ Секунды в строку. Эта функция возвращает кол-во секунд преобразованное в дни, часы, минуты, секунды, используется в коде OHM_data;
timeOutResidue($title)~ Возвращает оставшееся время в секундах работы таймера по его имени. Если таймера нет, вернет 0;
getClassNameByObject($title)~Получить имя класса по имени объекта;
dsCryptV($input,$decrypt=false)~Обратимое шифрование методом "Двойного квадрата";
padezhV($num, $p1, $p2, $p5)~Функция склонения значений чисел по падежам;
dateDiffV($d, $now = null)~Функция получения - сколько прошло времени в текстовом виде;
check_for_number($str)~Функция проверки на наличие цифры (числа) в строке;
text_to_number($str)~Функция преобразования текстового написания числа в цифру (ограничена до 100);
Timer::start() ...  echo (Timer::finish()." сек.\n\r")~Функции класса для измерения времени выполнения скрипта или операций;
');
?>
