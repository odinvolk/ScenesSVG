# Сценарии


## Служебные

### MegaD_time

```
//http://192.168.0.14/sec/?cf=7&stime=10:57:06:4
//$time3 = date('H:i:s:w', time()); // 14:50:29:0
$time4 = strftime("%H:%M:%S:%u<br>"); // 14:50:29:0
file_get_contents("http://192.168.10.100/sec/?cf=7&stime=".$time4);
file_get_contents("http://192.168.10.101/sec/?cf=7&stime=".$time4);
//print $time3.'<br>';
//echo strftime("%H:%M:%S:%u<br>");
//http://192.168.10.101/sec/?pt=34&ext=13&epwm=4095  levelSaved levelWork
//http://192.168.10.101/sec/?pt=34&ext=1&epwm=4
//file_get_contents("http://".$ipAddress."/".$Password."/?pt=".$Port."&ext=".$Ext."&epwm=".$value);
//file_get_contents("http://".$ipAddress."/".$Password."/?cmd=".$Port."e".$Ext.":".$value);


//$d = getdate(); // использовано текущее время
//foreach ( $d as $key => $val ) echo "$key = $val<br>";
//echo "<hr>Сегодня: $d[mday].$d[mon].$d[year]";
```

### updateTodayText

```
$days=array('воскресенье','понедельник','вторник','среда','четверг','пятница','суббота');
$months=array(1=>'январь','февраль','март','апрель','май','июнь','июль','август','сентябрь','октябрь','ноябрь','декабрь');
$months2=array(1=>'января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
$daymonth=array(1=>'первое','второе','третье','четвертое','пятое','шестое','седьмое','восьмое','девятое','десятое','одинадцатое','двенадцатое','тринадцатое','четырнадцатое','пятнадцатое','шестнадцатое','семнадцатое','восемнадцатое','девятнадцатое','двадцатое','двадцать первое','двадцать второе','двадцать третье','двадцать четвертое','двадцать пятое','двадцать шестое','двадцать седьмое','двадцать восьмое','двадцать девятое','тридцатое','тридцать первое');
$day_num=(int)date('w');
$month_num=(int)date('m');
$day_month=(int)date('j');
$day_of_month=$daymonth[$day_month];
$day_of_week=$days[$day_num];
$month_txt1=$months[$month_num];
$month_txt2=$months2[$month_num];
setGlobal('Time.MonthNum',$month_num);
setGlobal('Time.MonthTXT',$month_txt1);
setGlobal('Time.MonthTXTalt',$month_txt2);
setGlobal('Time.DayOfMonthNum',$day_month);
setGlobal('Time.DayOfMonthTXT',$day_of_month);
setGlobal('Time.DayOfWeekTXT',$day_of_week);
setGlobal('Time.DayOfWeekNum',$day_num);

// ================= раскидываем дату и время если изменилось в Windows =========================================
$h=(int)date('G',time());

if (gg('Time.DateD') != date("d", time())) {

   $day = array(
       'воскресенье', 'понедельник', 'вторник', 'среда',
       'четверг', 'пятница', 'суббота'
   );

   $month = array(
       1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля',
       5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа',
       9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря'
   );

   $monthAlt = array(
       1 => 'ЯНВАРЬ', 2 => 'ФЕВРАЛЬ', 3 => 'МАРТ', 4 => 'АПРЕЛЬ',
       5 => 'МАЙ', 6 => 'ИЮНЬ', 7 => 'ИЮЛЬ', 8 => 'АВГУСТ',
       9 => 'СЕНТЯБРЬ', 10 => 'ОКТЯБРЬ', 11 => 'НОЯБРЬ', 12 => 'ДЕКАБРЬ'
   );
   // раскидываем дату по свойствам  
   sg("Time.DateD",date( "d", time()));
   sg("Time.DateM",date( "m", time()));
   sg("Time.DateY",date( "Y", time()));
   sg("Time.Date",date( "d.m.Y", time()));
   sg("Time.Day",$day[date("w")]);
   sg("Time.Month",$month[date("n")]);
   sg("Time.MonthAlt",$monthAlt[date("n")]);

}
```
