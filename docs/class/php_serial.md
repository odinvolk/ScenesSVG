PHP Serial
==========

PHP Serial был написан в то время, когда я не знал другого языка, кроме PHP, и я начал серьезно скучать по его возможностям. 

Я каким-то образом завладел торговым дисплеем «Citizen C2202-PD», и я хотел поиграть с ним. Мне также удалось получить документацию по нему, и я создал удобный класс для доступа к последовательному порту через файл Linux. 

Впоследствии я разместил его в [PHP Classes](http://www.phpclasses.org/package/3679-PHP-Communicate-with-a-serial-port.html), и это, вероятно, и принесло ему какую-то заметность.

Пример
-------

```php
<?php
include 'PhpSerial.php';

// Let's start the class
$serial = new PhpSerial;

// First we must specify the device. This works on both linux and windows (if
// your linux serial device is /dev/ttyS0 for COM1, etc)
$serial->deviceSet("COM1");

// We can change the baud rate, parity, length, stop bits, flow control
$serial->confBaudRate(2400);
$serial->confParity("none");
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->confFlowControl("none");

// Then we need to open it
$serial->deviceOpen();

// To write into
$serial->sendMessage("Hello !");
```

Состояние проекта
--------------------

Интересно, что этот фрагмент кода, который широко не тестировался, создал
много, если интерес с тех пор, как он был создан, и особенно в настоящее время с
все играют с Arduinos и Raspberry Pis. Я получаю около 1 письма
каждый месяц просить помощи с кодом или отправлять патчи / предложения.

Я думаю, что пришло время для меня, чтобы удалить пыль с этого проекта и дать
это полная видимость на современных инструментах, также известный как GitHub.

### Ошибки

Есть ** много ** ошибок. Я знаю, что есть. Я просто не знаю, кто они.

### Поддерживаемые платформы

* **Linux**: изначально поддерживаемая платформа, которую я использовал. Вероятно, менее глючный.
* **MacOS**: хотя я никогда не пробовал его на MacOS, он похож на Linux и некоторые патчи были отправлены мне, так что я думаю, что все в порядке
* **Windows**: кажется, что он работает для некоторых людей, а не для других. Теоретически должен быть способ сделать это.

### Concerns

I have a few concerns regarding the behaviour of this code.

* Inter-platform consistency. I seriously doubt that all operations go the same
  way across all platforms.
* Read operations. Reading was never needed in my project, so all the tests I
  did on that matter were theoretic. I was also quite naive, so the API is
  probably not optimal. What we need is to re-think reading from scratch.
* Configuration done by calling functions. This is so Java. It would be much
  better to be able to pass a configuration array once and for all. Furthermore,
  I suspect that the order of call matters, which is bad.
* Auto-closing the device. There is an auto-close function that is registered
  at PHP shutdown. This sounds quite ridiculous, something has to be done about
  that.
* Use exceptions. Currently there is an heavy use of the errors system to report
  errors (2007 baby), but this is seriously lame. They have to be replaced by
  actual exceptions.

Call for contribution
---------------------

I have about 0 time to code or test this project. However, there is clearly a
need for it.

As in all open-source projects, I need people to fit this to their needs and to
contribute back their code.

What is needed, IMHO:

* Address the concerns listed above, and find new ones.
* Create a reproducible test environment for each OS, and prove that each
  feature works (basically, unit-testing).
* Report of use cases, bugs, missing features, etc.

If you feel like doing any of those, do not hesitate to create an issue or a
pull-request, I'll gladly consider consider it :)

Licence
-------

PHP Serial
Copyright (C) 2007-2014 PHP Serial's contributors (see CONTRIBUTORS file)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
