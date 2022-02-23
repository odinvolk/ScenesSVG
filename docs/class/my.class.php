<?php
/*
* Свои дополнительные функции
*/

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

?>
