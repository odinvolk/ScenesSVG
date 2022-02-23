Вот у меня в датчике движения разные варианты действий в зависимости от режима его работы:

``` php
$ot = $this->object_title;
//Если выставлен АВТО-режим выключения света, включаем свет
if ($this->getProperty('LightOffAutoMode') == 1) {
cm("Lamp_0440.turnOn");
//Выключаем свет через 3 минуты
setTimeOut($ot.'_TurnOff_Lighting','cm("Lamp_0440.turnOff");',60*3);
return;
}

//Выключим свет в помещении через 5 минут, включаем АВТО-режим выключения света
setTimeOut($ot.'_TurnOff_Lighting','cm("Lamp_0440.turnOff");getProperty("SensorMotion_0320.LightOffAutoMode",1);',60*5);

```

``` php
$this->setProperty('alive',1); // присвоить свойству alive этого же объекта значение 1
$this->setProperty('value',$params['value']); // присвоить свойству value  этого же объекта переданное вместе с вызовом значение (см пример callmethod)
setGlobal('moscow.temp', 15); // присвоение значения глобального свойства (с указанием конкретного объекта, свойства и значения)
$this->setProperty("updated",time()); // присвоить свойству updated значение текущего времени в формате nix
$this->setProperty("updatedTime",date("d/m/y H:i")); // присвоить свойству updatedTime значение текущего времени в формате d/m/y H:m

$value=$this->getProperty("value"); //получить значение value этого же объекта
$ot=$this->object_title; //получить имя объекта
$temp=getGlobal(moscow.temp); // получение значения глобального свойства (с указанием конкретного объекта и свойства)

callMethod('Arduino.statusChanged',array("value"=>0)); // вызвать метод и отправить ему значение 0

say("всем привет", 2); Сказать Всем привет с уровнем важности 2 (может проговариваться либо просто появиться в истории сообщений)

getRandomLine('privet'); // получение случайной строки из текстового файла. privet - это имя файла  ./texts/privet.txt  в короткой форме без пути и расширения !!!Файл только в кодировке UTF8  (say(getRandomLine('privet'));)

if (ping(gg('Arduino.IP'))) {} else {} // PING адреса, заданного в свойстве IP объекта Arduino и выполнение кода в зависимости от результата.

$command="Сколько время";
callMethod("ThisComputer.commandReceived",array("command"=>$command));

```
