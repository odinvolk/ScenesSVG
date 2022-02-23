<?php
function weekDay($date)
    {
        $this_year = (int) date('Y', $date);
        $url = "http://xmlcalendar.ru/data/ru/".$this_year."/calendar.xml";
        $url_local = "calendar.xml";

        // Отключение ошибок libxml и передача полномочий по выборке и обработке информации об ошибках
        libxml_use_internal_errors(true);

        $res = @simplexml_load_file($url); //загружаем его и раскладываем на массив

        if ($res === false)
        {   //Не удалось получить данные
            $res = @simplexml_load_file($url_local); // Возьмем ранее загруженный календарь

            foreach(libxml_get_errors() as $error)
            {   //регистрируем ошибку
                registerError('Не удалось получить данные от сервиса xmlcalendar<br>Скрипт сработал используя бэкап данных', $error -> message);
            }
            libxml_clear_errors();
        }
        else
        {   //Если прочитали без ошибок
            $res->asXML($url_local); //запишем в файл на всякий случай
		}

		//Проверяем сегодняшний день по производственному календарю (рабочий или нет)
		$weekDay = -1;

		$this_date = date('m.d', $date);

		foreach($res -> days -> day as $day)
			{
				$type = $day -> attributes() -> t; //тип дня: 1-выходной день, 2-короткий день, 3-рабочий день (суббота/воскресен)
				$CalDate = $day -> attributes() -> d; //дата

				if ($this_date == $CalDate)
				{
					if ($type == 1)
					{ //Выходной
						$weekDay = 0;
					}
					elseif($type == 2)
					{
						//Короткий день, запишим как рабочий
						$weekDay = 1;
					}
					elseif($type == 3)
					{ //Перенесенный рабочий
						$weekDay = 1;
					}
				}
			}

		//Если в производственном календаре ничего не нашлось
		if ($weekDay == -1)
		{
			if (date('w', $date) == 0 || date('w', $date) == 6)
			{
				$weekDay = 0;
			}
			else
			{
				$weekDay = 1;
			}
		}

		return $weekDay;
	}

//  Потом в сценариях можно использовать следующие:
// 0 - выходной, 1 - рабочий день
//**********************************************
//weekDay(strtotime("+1 day")); //завтра
//weekDay(time()); //сегодня
//echo weekDay(mktime(0, 0, 0, 2, 26)); //месяц, день

?>
