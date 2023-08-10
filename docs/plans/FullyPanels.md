FullyPanels

 Общее
Название: (*) Нет
Изображение: (*) 2.svg
Управление масштабом: Нет
Авто-масштабирование: Да

 Состояния
001	    button_dop01_01
002	    button_dop01_02
003	    button_dop01_03
444444	buttonSet_text	   show_it / blink_it   	Openclose01.status <> 1	 
auto	  buttonSet_auto	   hide_it / blink_it   	Relay04.autoMode <> 1	 

 Компоненты
cir1recognizer	toggle_one_step	cir1	 
cir2sendValue	toggle_one_step	cir2	 
cir3playSound	toggle_one_step	cir3	 
cir4reload	toggle_one_step	cir4	 
cir5	toggle_one_step	cir5	 
cir6	toggle_one_step	cir6	 
cir7	toggle_one_step	cir7	 
cir8rs	toggle_one_step	cir8	 
fullyBright10	button_dop	button_ldop015_1	 
fullyBright150	button_dop	button_ldop015_2	 
fullyBright250	button_dop	button_ldop015_3	 
fullyMotionStart	button_dop	button_ldop13_2	 
fullyMotionStop	button_dop	button_ldop13_1	 
fullyMusic10	button_dop	button_ldop014_1	 
fullyMusic100	button_dop	button_ldop014_3	 
fullyMusic60	button_dop	button_ldop014_2	 
fullyplaySound	button_dop	button_ldop10	 
fullyplaySound_Старое_радио	button_dop	button_ldop08	 
fullyScanQRCode	button_dop	button_ldop12	 
fullyshowNotification	button_dop	button_ldop11	 
fullystopSound	button_dop	button_ldop09	 
testdop1	button_dop	button_dop1	 
testdop11	button_dop	button_dop11	 
testdop12	button_dop	button_dop12	 
testdop13	button_dopcount	button_dop11_1	 
testdop2	button_dop	button_dop2	 
testdop3	button_dop	button_dop3	 
testdop4	button_dop	button_dop4	 
testdop5	button_dop	button_dop5	 
testdop6	button_dop	button_dop6	 
testdop7	button_dopcount	button_dop7	 
testdopcount8	button_dopcount	button_dop8	 
testsc1	steper_step	button_sc1	 
testsc2	button_sc	button_sc2	 
testsc3	steper_step	button_sc3	 
testsc4	button_sc	button_sc4	 
testsc5	button_sc	button_sc5	 
testsc6	button_sc	button_sc6	 
testsc7	button_sc	button_sc7	 
testsc8	button_sc	button_sc8	 
testsc9	button_batt	button_sc9	 
toggleGreen	toggle_one_step	fon_weather	 
toggleOrandg	toggle_one_step	ther	 
toggleRed	toggle_one_step	her	 
voiceBtn	button_recognizer	voiceBtn

CUSTOM_CSS

#buttonLamp_fon2
{
 fill: url('#LGBattonMOrange');
 stroke: #ffd300;
}
svg 
{
 font-family: FreeSans;!important
}
.button_dop {
  font-family: FreeSans;!important
  cursor: pointer;
}
.button_sc {
  font-family: FreeSans;!important
  cursor: pointer;
}
.text_ten {
    text-shadow: #000 0 0 3px;
}
.noty_effects_open {
  opacity: 0;
  transform: translate(50%);
  animation: noty_anim_in 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}
.noty_effects_close {
  animation: noty_anim_out 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}
@keyframes noty_anim_in {
  100% {
    transform: translate(0);
    opacity: 1;
  }
}
@keyframes noty_anim_out {
  100% {
    transform: translate(50%);
    opacity: 0;
  }
}

CUSTOM_JAVASCRIPT

//-------------------------------------------- Часы и дата------------------------------------------------------------------------
$(document).ready(function() {
 var today = new Date();
 // создаем текущее время
 today.setDate(today.getDate()); 
 var dayNames = ["воскр", "понед", "вторник", "среда", "четверг", "пятница", "суббота"];
 var monthNames = ["Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря"]; 
 var day = dayNames[today.getDay()];
 var date = today.getDate();
 var month = monthNames[today.getMonth()]; 
 $("#date").html(day+","+'&nbsp'+'&nbsp'+date+" "+month);
 $('#Date').html(day + " " + date + ' ' + month + ' ' + today.getFullYear());

 setInterval( function() {
// Создаем новый объект newDate() и извлекаем секунды of the current time on the visitor's
var seconds = new Date().getSeconds();
var minutes = new Date().getMinutes();
var hours = new Date().getHours();
if (hours < 10) hours = "0" + hours;
if (minutes < 10) minutes = "0" + minutes;
if (seconds < 10) seconds = "0" + seconds; 
 $("#time").html(hours + ":" + minutes + ":" + seconds);
 $("#time2").html(hours + ":" + minutes);
 $("#min").html(minutes);
 $("#hours").html(hours);
/*
  // Создаем новый объект newDate() для SVG часов
function r(el, deg) {
el.setAttribute('transform', 'rotate('+ deg +' 50 50)')
}
var d = new Date();
r(secS, 6*d.getSeconds());
r(minS, 6*d.getMinutes());
r(hourS, 30*(d.getHours()%12) + d.getMinutes()/2);*/
}, 1000) 
}
);
//----------------------------------------------------------------------------------------------------
function sendCode(code) {
            $.ajax({
                url: '/popup/app_qrcodes.html?qr=' + code, //fully.scanQrCode('Prompt text','http://192.168.10.26/popup/app_qrcodes.html?qr=$code');
                success: function(responce) {
                    showNoty('done', 'Для Алисы передан код ' + code);
                },
                error: function(responce) {
                    showNoty('error', 'Не удалось записать настройки :(');
                }
            });

        }
//--------------------------------------------------------------------------------------------------------------------------
/*
* Вот методы для различных вариантов вставки: https://learn.javascript.ru/modifying-document
* Вставить элементы в любое место файла на js без jquery  (document.head.append(scriptNoti); document.body.append(scriptNoti);)
* node.append(...nodes or strings) – добавляет узлы или строки в конец node,
* node.prepend(...nodes or strings) – вставляет узлы или строки в начало node,
* node.before(...nodes or strings) –- вставляет узлы или строки до node,
* node.after(...nodes or strings) –- вставляет узлы или строки после node,appendChild
* node.replaceWith(...nodes or strings) –- заменяет node заданными узлами или строками.
*
*    -------------- Пример вставки дополнительного скрипта в head --------------------------------------------------------------------------------------
*    let scriptForm = document.createElement('script');               // в переменной scriptForm создаем тег (элемент) document.createElement('script');
*    scriptForm.setAttribute('language', 'javascript');               // добовляем атрибуты
*    scriptForm.setAttribute('type', 'text/javascript');              // добовляем атрибуты
*    scriptForm.setAttribute('src', '/3rdparty/form/jquery.form.js'); // добовляем атрибуты
*    document.head.append(scriptForm);                                // вставляем элемент script в конец (append) элемента (head)
*    ----------------------------------------------------------------------------------------------------------------------------------------------------
*/
//------------------------------noty/noty.js-----------------------------------
    let scriptNoti = document.createElement('script');
    scriptNoti.setAttribute('language', 'javascript');
    scriptNoti.setAttribute('type', 'text/javascript');
    scriptNoti.setAttribute('src', '/3rdparty/noty/noty.min.js');
    document.head.append(scriptNoti);
//----------------------------noty/noty.css-------------------------------------
    let cssNoti = document.createElement('link');
    cssNoti.setAttribute('rel', 'stylesheet');
    cssNoti.setAttribute('type', 'text/css');
    cssNoti.setAttribute('href', '/3rdparty/noty/noty.css');
    document.head.append(cssNoti);
//---------------------------noty/themes/relax.css--------------------------------------
    let cssNotiThemes = document.createElement('link');
    cssNotiThemes.setAttribute('rel', 'stylesheet');
    cssNotiThemes.setAttribute('type', 'text/css');
    cssNotiThemes.setAttribute('href', '/3rdparty/noty/themes/bootstrap-v4.css');
    document.head.append(cssNotiThemes);
//---------------------------wsStatus - статус сокета-------------------------------------
    let elemWS = document.createElement('div');
    elemWS.setAttribute('id', 'wsStatus');
        document.body.append(elemWS);
//---------------------------animate/animate.css--------------------------------------
//    let cssAnimate = document.createElement('link');
//    cssAnimate.setAttribute('rel', 'stylesheet');
//    cssAnimate.setAttribute('type', 'text/css');
//    cssAnimate.setAttribute('href', '/css/animate/animate.css');
//    document.head.append(cssAnimate);

//Обертка для модуля Noty https://ned.im/noty
        function showNoty(status, showtext) {
            new Noty({
                type: status,          //alert, success, error, warning, info
                layout: 'bottomRight', //top, topLeft, topCenter, topRight, center, centerLeft, centerRight, bottom, bottomLeft, bottomCenter, bottomRight
                theme: 'bootstrap-v4',        //mint, sunset, relax, nest, metroui, semanticui, light, bootstrap-v3, bootstrap-v4
                text: showtext,
                progressBar: true,     //true false
                timeout: 5000,
                animation: {
                    open: 'noty_effects_open', // Animate.css class names \animated bounceInRight \noty_effects_open
                    close: 'noty_effects_close', // Animate.css class names \animated bounceOutRight \noty_effects_close
                }
            }).show();
        }
/* 
  let div = document.createElement('div');
  div.className = "alert";
  div.innerHTML = "<strong>Всем привет!</strong> Вы прочитали важное сообщение.";
  document.body.prepend(div);
  setTimeout(() => div.remove(), 10000);
 */

//--------------------------------------------------------------------------------
/*
// Данные для визуализации в пикселях
var data = [20, 100, -60, 40, -70]
// Ширина столбика гистограммы
var barWidth = 10

// Аналог document.querySelector('svg') или $('svg')
d3.select("svg")
    .append("svg") // добавляем элемент svg
    .attr("x", 16) // установка у rect ширины1016
    .attr("y", 600) // установка у rect высоты691
    .attr("width", 226) // установка у rect ширины1016
    .attr("height", 226) // установка у rect высоты691
    .style("fill", "green") // установка у rect стиля
  // Самая сложная для понимания часть.  
  // D3 связывает еще не созданные элементы с данными.
  .selectAll("rect")
  .data(data)
  .enter()   
  // Код ниже выполнится 5 раз. Ровно столько у нас данных.

  // Добавляем прямоугольник тегом rect с нужной шириной, 
  // высотой и координатами. Код похож на jQuery.
  .append("rect")
  .attr("width", barWidth)
  .attr("height", d => d)
  // Изначально все прямоугольники спозиционированы  
  // абсолютно и находятся в координате 0,0  
  // Сдвигаем прямоугольники по оси x, на [barWidth * i]
  .attr("x", (d, i) => barWidth * i)
*/
//----------------------------------------------------- часы поверх слоев----------------------------------------------------------------
/*d3.select("svg")
    .append("text")
    .attr("id", "time2") // установка у rect ширины1016
    .attr("class", "time") // установка у rect ширины1016
    .attr("x", 200) // установка у rect ширины1016
    .attr("y", 328) // установка у rect высоты691
    .attr("width", 50) // установка у rect ширины
    .attr("height", 50) // установка у rect высоты
    .attr("rx", 20) // установка у rect высоты
    .attr("ry", 20) // установка у rect высоты
    .attr("fill", "#000") // установка у rect стиля
    .attr("font-size", 46) // установка у rect ширины
    .attr("font-weight", 600) // установка у rect высоты
    .attr("text-anchor", "middle") // установка у rect высоты
    .style("opacity", 1) // установка у rect стиля
*/
//------------------------------ элемент дополнительный --------------------------------
d3.select("svg") // получаем элемент body
    .append("svg") // добавляем элемент svg
    .attr("x", 16) // установка у rect ширины1016
    .attr("y", 683) // установка у rect высоты691
    .attr("width", 40) // установка у rect ширины1016
    .attr("height", 40) // установка у rect высоты691
   // .style("fill", "green") // установка у rect стиля
    .append("rect") // добавляем в svg элемент rect
    .attr("width", 40) // установка у rect ширины
    .attr("height", 40) // установка у rect высоты
    .attr("x", 0) // установка у rect высоты
    .attr("y", 0) // установка у rect высоты
    .attr("fill", "red") // установка у rect стиля
    .style("opacity", "0.5") // установка у rect стиля
    .attr("onclick", "callMethod('Motion13.blockSensor');") // установка у rect стиля
//    .attr("onclick", "callMethod('Tuya_relay03.switch');") // установка у rect стиля
//    .attr("ontouchstart", "timer = setTimeout(function(){callMethod('Motion13.blockSensor');},500);") // установка у rect стиля
//    .attr("ontouchend", "clearTimeout(timer);") // установка у rect стиля
//    .attr("onmousedown", "timer = setTimeout(function(){callMethod('Motion13.blockSensor');},500);") // установка у rect стиля
//    .attr("onmouseup", "clearTimeout(timer);") // установка у rect стиля
    .attr("onmouseover", "this.style.opacity = '1'") // установка у rect стиля
    .attr("onmouseout", "this.style.opacity = '0.5'"); // установка у rect стиля

function autoClick() {
  let auSVG = document.getElementById("component66"); 
  auSVG.setAttribute("onclick", "callMethod('Tuya_relay04.switch');"); // callMethod("Tuya_relay04.pressed");
//  auSVG.setAttribute("ontouchstart", "timer=setTimeout(function(){callMethod('Relay04.switchAutoMode');},500);");
//  auSVG.setAttribute("ontouchend", "clearTimeout(timer);");
  auSVG.setAttribute("onmousedown", "timer=setTimeout(function(){callMethod('Relay04.switchAutoMode');},500);");
  auSVG.setAttribute("onmouseup", "clearTimeout(timer);");
  auSVG.setAttribute("onmouseover", "this.style.opacity = '1'");
  auSVG.setAttribute("onmouseout", "this.style.opacity = '0.5'");
}
/*
// Upr1
d3.select("svg")
    .append("rect")
    .attr("x", 20) // установка у rect ширины1016
    .attr("y", 328) // установка у rect высоты691
    .attr("width", 50) // установка у rect ширины
    .attr("height", 50) // установка у rect высоты
    .attr("rx", 20) // установка у rect высоты
    .attr("ry", 20) // установка у rect высоты
    .attr("fill", "#ccc") // установка у rect стиля
    .style("opacity", "0.1") // установка у rect стиля
    .attr("onclick", "openUpr1();")
function openUpr1() {
  var mySVG = document.getElementById("menuUpr1");
  mySVG.setAttribute("display", "inline");
}
function closeUpr1() {
  var mySVG = document.getElementById("menuUpr1");
  mySVG.setAttribute("display", "none");
}
function openUpr2() {
  var mySVG = document.getElementById("menuUpr2");
  mySVG.setAttribute("display", "inline");
}
function closeUpr2() {
  var mySVG = document.getElementById("menuUpr2");
  mySVG.setAttribute("display", "none");
}
           $('#myForm').ajaxForm(function() { 
                alert("Thank you for your comment!"); 
           });

// prepare the form when the DOM is ready
$(document).ready(function() {
    // bind form using ajaxForm
    $('#jsonForm').ajaxForm({
        // dataType identifies the expected content type of the server response
        dataType:  'json',

        // success identifies the function to invoke when the server response
        // has been received
        success:   processJson
    });
});
function processJson(data) {
    // 'data' is the json object returned from the server
    alert(data.message);
}    //panelDopMenu001 panelDopMenul01 panelScenes01
*/

function openNav4() {
  let mySVG = document.getElementById("panelScenes");
  mySVG.setAttribute("display", "inline");
}
function closeNav4() {
  let mySVG = document.getElementById("panelScenes");
  mySVG.setAttribute("display", "none");
}
function openNav5() {
  let pdm = document.getElementById("panelDopMenu");
  let pdm001 = document.getElementById("panelDopMenu001");
  pdm.setAttribute("display", "inline");
  pdm001.setAttribute("display", "inline");
}
function closeNav5() {
  let pdm = document.getElementById("panelDopMenu");
  let pdm001 = document.getElementById("panelDopMenu001");
  pdm.setAttribute("display", "none");
  pdm001.setAttribute("display", "none");
}
function openNav6() {
  let pdm = document.getElementById("panelDopMenul01");
  let pdm001 = document.getElementById("panelDopMenur01");
  pdm.setAttribute("display", "inline");
  pdm001.setAttribute("display", "inline");
}
function closeNav6() {
  let pdm = document.getElementById("panelDopMenul01");
  let pdm001 = document.getElementById("panelDopMenur01");
  pdm.setAttribute("display", "none");
  pdm001.setAttribute("display", "none");
}
function openNav7() {
  let pdm = document.getElementById("panelScenes01");
  pdm.setAttribute("display", "inline");
}
function closeNav7() {
  let pdm = document.getElementById("panelScenes01");
  pdm.setAttribute("display", "none");
}


function changedValue() {
url='/objects/?object=Megad2wm_relay01&op=m&m=switch'
AJAXRequest(url);
}
function changedValue1() {
callMethod("Megad2wm_relay01.switch");
}
//----------------------------------------------------------------------------------------
function sendValue(object, property, value) {
            $.ajax({
                url: '/objects/?op=set&object=' + object + '&p=' + property + '&v=' + value,
                success: function(responce) { showNoty('success', 'Для ' + object + '.' + property + ' установлено значение ' + value); }, //если ошибок не возникло
                error: function(responce) { showNoty('error', 'Не удалось передать'); }, //если произошла ошибка
//                complete: function(responce) { showNoty('info', 'Конец передачи'); }, //срабатывает по окончанию запроса
//                beforeSend: function(responce) { showNoty('warning', 'Начало передачи'); }, //срабатывает перед отправкой запроса             
            });
        }
//------------работает---------------------------------------------------------------------------------------------------------
//http://192.168.10.26/objects/?script=getFully&cmd=playSound&url=http://192.168.10.26/cms/sounds/10h.mp3&stream=3&loop=false
// для вставки onclick="playSoundS('http://192.168.10.26/cms/sounds/10h.mp3','2');"
 function playSoundS(playurl, stream) {
//  var url='http://192.168.10.26';
//  url+='/objects/?script=getFully&cmd=playSound&url='+playurl+'&stream='+stream;
  $.ajax({
 //  url: url,
   url: '/objects/?script=getFully&cmd=playSound&url='+playurl+'&stream='+stream,
          success: function(responce) { showNoty('success', 'Для Алисы передано сообщение'); }, //если ошибок не возникло                          
          error: function(responce) { showNoty('error', 'Не удалось передать сообщение'); }, //alert('Ошибка отправки AJAX'); //если произошла ошибка  
//        complete: function(responce) { showNoty('info', 'Конец передачи'); }, //срабатывает по окончанию запроса
//        beforeSend: function(responce) { showNoty('warning', 'Начало передачи'); }, //срабатывает перед отправкой запроса   
  }).done(function(data) { 
   var obj=jQuery.parseJSON(data);
    if (obj.DATA) {
      //
    }
   });
  return false;
 }
//--------------------------------------------------------------------------------------------------------------------
function loadScreensaver() {
//    $('#imgScreenTab').attr('src', 'http://192.168.8.129:2323/?cmd=getScreenshot&password=197994'); //, 320, 280
 openTWindow('tcm1321855241', 'tinyCam Monitor Pro', 'http://192.168.8.129:2323/?cmd=getScreenshot&password=197994', 720, 500)
 //   $('#loadScreenDIV').hide();
 //   $('#imgScreenTab').show();
}
function loadCamshot() {
//    $('#imgCamShot').attr('src', 'http://192.168.8.129:2323/?cmd=getCamshot&password=197994');
 openTWindow('tcm1321855241', 'tinyCam Monitor Pro', 'http://192.168.8.129:2323/?cmd=getCamshot&password=197994', 340, 290)
 //openTWindow('tcm1321855242', 'tinyCam Monitor Pro', 'http://192.168.8.129:2323/?cmd=getScreenshot&password=197994', 720, 500)
 //   $('#loadScreenDIVCam').hide();
 //   $('#imgCamShot').show();
}

//---------------------------------------------------------------------------------------
/*
* window: в JavaScript представляет собой открытое окно в браузере.
* location: в JavaScript хранится информация о текущем URL.
* location Объект подобен фрагменту window объекта и вызывается через window.location свойство.
* location объект имеет три метода:
*     assign(): используется для загрузки нового документа
*     reload(): используется для перезагрузки текущего документа
*     replace(): используется для замены текущего документа на новый
*
* Что бы получить страницу непосредственно с сервера, а не из кэша, вы можете передать true параметр в location.reload()
* Этот метод совместим со всеми основными браузерами, включая IE, Chrome, Firefox, Safari, Opera.
*      onclick='window.location.replace("%link.s6%", true);'
*      onclick='window.location.reload(true);'
*                      location.reload();
*               window.location.reload();
*      
*      onclick='window.location.href="%link.s6%"; callMethod("Tuya_relay03.switch");'
*      onClick="return openTWindow('workshopLightTWindow', 'Кабинет', '/popup/plans.html?id=6', 1200, 600);"
*      onClick="return openTWindow('kinderRoomTWindow', 'Детская', '/menu.html?parent=123', 750, 350);"
*      ontouchstart='timer = setTimeout(function(){callMethod("Relay04.switchAutoMode");},500); $(this).addClass("pressed");'
*      ontouchend='clearTimeout(timer); $(this).removeClass("pressed");'
*      onmousedown='timer = setTimeout(function(){callMethod("Relay04.switchAutoMode");},500); $(this).addClass("pressed");'
*      onmouseup='clearTimeout(timer); $(this).removeClass("pressed");'>
*/
