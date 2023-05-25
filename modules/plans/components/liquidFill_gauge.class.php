<?php
//
// класс не доработан
//
include_once DIR_MODULES . 'plans/plan_component.class.php';

class liquidFill_gauge extends plan_component {

    function __construct($id)
    {
        $this->name=str_replace('.class.php','',basename(__FILE__));
        parent::__construct($id);
    }

    function getProperties() {

        $properties = parent::getProperties();
        $properties[] = array(
            'NAME' => 'value',
            'TITLE' => LANG_VALUE,
            'TYPE' => 'linked_property'
        );

        $properties[] = array(
            'NAME' => 'title',
            'TITLE' => LANG_TITLE,
            'TYPE' => 'text',
            'DEFAULT' => 'Data'
        );

        $properties[] = array(
            'NAME' => 'units',
            'TITLE' => 'Units',
            'TYPE' => 'text',
            'DEFAULT' => '\u00B0C'
        );
 // The gauge minimum value. Минимальное значение датчика
        $properties[] = array(
            'NAME' => 'min_Value',
            'TITLE' => 'Минимальное значение датчика',
            'TYPE' => 'float',
            'DEFAULT' => '0'
        );
 // The gauge maximum value. Максимальное значение датчика
        $properties[] = array(
            'NAME' => 'max_Value',
            'TITLE' => 'Максимальное значение датчика',
            'TYPE' => 'float',
            'DEFAULT' => '100'
        );
// The outer circle thickness as a percentage of it's radius.
// Толщина внешнего круга в процентах от его радиуса
        $properties[] = array(
            'NAME' => 'circleThickness',
            'TITLE' => 'Толщина внешнего круга в процентах от его радиуса',
            'TYPE' => 'float',
            'DEFAULT' => '0.05'
        );
// The size of the gap between the outer circle and wave circle as a percentage of the outer circles radius.
// Размер зазора между внешним кругом и волновым кругом в процентах от радиуса внешних кругов.
        $properties[] = array(
            'NAME' => 'circleFillGap',
            'TITLE' => 'Размер зазора между внешним кругом и волновым кругом в процентах от радиуса внешних кругов.',
            'TYPE' => 'float',
            'DEFAULT' => '0.05'
        );
// The color of the outer circle. Цвет внешнего круга
        $properties[] = array(
            'NAME' => 'circleColor',
            'TITLE' => 'Цвет внешнего круга',
            'TYPE' => 'rgb',
            'DEFAULT' => '#178BCA'
        );
// The wave height as a percentage of the radius of the wave circle.
// Высота волны в процентах от радиуса волнового круга.
        $properties[] = array(
            'NAME' => 'waveHeight',
            'TITLE' => 'Высота волны в процентах от радиуса волнового круга.',
            'TYPE' => 'float',
            'DEFAULT' => '0.05'
        );
// The number of full waves per width of the wave circle.
// Количество полных волн на ширину волнового круга.
        $properties[] = array(
            'NAME' => 'waveCount',
            'TITLE' => 'Количество полных волн на ширину волнового круга.',
            'TYPE' => 'int',
            'DEFAULT' => '1'
        );
// The amount of time in milliseconds for the wave to rise from 0 to it's final height.
// Время в миллисекундах, за которое волна поднимется от 0 до своей конечной высоты.
        $properties[] = array(
            'NAME' => 'waveRiseTime',
            'TITLE' => 'Время в миллисекундах, за которое волна поднимется от 0 до своей конечной высоты.',
            'TYPE' => 'int',
            'DEFAULT' => '1000'
        );
// The amount of time in milliseconds for a full wave to enter the wave circle.
// Время в миллисекундах, в течение которого полная волна входит в волновой круг.
        $properties[] = array(
            'NAME' => 'waveAnimateTime',
            'TITLE' => 'Время в миллисекундах, в течение которого полная волна входит в волновой круг.',
            'TYPE' => 'int',
            'DEFAULT' => '18000'
        );
// Control if the wave should rise from 0 to it's full height, or start at it's full height.
// Контролируйте, должна ли волна подниматься от 0 до полной высоты или начинаться с полной высоты.
        $properties[] = array(
            'NAME' => 'waveRise',
            'TITLE' => 'Контролируйте, должна ли волна подниматься от 0 до полной высоты или начинаться с полной высоты.',
            'TYPE' => 'float',
            'DEFAULT' => 'true'
        );
// Controls wave size scaling at low and high fill percentages. When true, wave height reaches it's maximum at 50% fill, and minimum at 0% and 100% fill. This helps to prevent the wave from making the wave circle from appear totally full or empty when near it's minimum or maximum fill.
// Управляет масштабированием размера волны при низком и высоком проценте заполнения. Когда true, высота волны достигает максимума при заполнении 50% и минимума при заполнении 0% и 100%. Это помогает предотвратить появление волны из-за того, что волновой круг будет казаться полностью заполненным или пустым, когда его заполнение близко к минимуму или максимуму.
        $properties[] = array(
            'NAME' => 'waveHeightScaling',
            'TITLE' => 'Когда true, высота волны достигает максимума при заполнении 50% и минимума при заполнении 0% и 100%.',
            'TYPE' => 'float',
            'DEFAULT' => 'true'
        );
// Controls if the wave scrolls or is static. Управляет прокруткой волны или статичностью.
        $properties[] = array(
            'NAME' => 'waveAnimate',
            'TITLE' => 'Управляет прокруткой волны или статичностью.',
            'TYPE' => 'float',
            'DEFAULT' => 'true'
        );
// The color of the fill wave. Цвет волны заливки
        $properties[] = array(
            'NAME' => 'waveColor',
            'TITLE' => 'Цвет волны заливки',
            'TYPE' => 'rgb',
            'DEFAULT' => '#178BCA'
        );
// The amount to initially offset the wave. 0 = no offset. 1 = offset of one full wave. Сумма для первоначального смещения волны. 0 = без смещения. 1 = смещение одной полной волны.
        $properties[] = array(
            'NAME' => 'waveOffset',
            'TITLE' => 'Сумма для первоначального смещения волны. 0 = без смещения. 1 = смещение одной полной волны.',
            'TYPE' => 'select',
            'DATA' => '0=без смещения|1=смещение одной полной волны',
            'DEFAULT' => '0'
        );
// The height at which to display the percentage text withing the wave circle. 0 = bottom, 1 = top. Высота, на которой отображается процентный текст внутри волнового круга. 0 = низ, 1 = верх.
        $properties[] = array(
            'NAME' => 'textVertPosition',
            'TITLE' => 'Высота, на которой отображается процентный текст внутри волнового круга. 0 = низ, 1 = верх.',
            'TYPE' => 'select',
            'DATA' => '0=низ|1=верх',
            'DEFAULT' => '0'
        );
// The relative height of the text to display in the wave circle. 1 = 50% Относительная высота текста, отображаемого в волновом круге. 1 = 50%
        $properties[] = array(
            'NAME' => 'textSize',
            'TITLE' => 'Относительная высота текста, отображаемого в волновом круге. 1 = 50%',
            'TYPE' => 'int',
            'DEFAULT' => '1'
        );
// If true, the displayed value counts up from 0 to it's final value upon loading. If false, the final value is displayed.
// Если true, отображаемое значение отсчитывается от 0 до окончательного значения при загрузке. Если false, отображается окончательное значение.
        $properties[] = array(
            'NAME' => 'valueCountUp',
            'TITLE' => 'Если true, отображаемое значение отсчитывается от 0 до окончательного значения при загрузке. Если false, отображается окончательное значение.',
            'TYPE' => 'select',
            'DATA' => 'true=true|false=false',
            'DEFAULT' => 'true'
        );
// If true, a % symbol is displayed after the value. Если true, после значения отображается символ %.
        $properties[] = array(
            'NAME' => 'displayPercent',
            'TITLE' => 'Если true, после значения отображается символ %.',
            'TYPE' => 'select',
            'DATA' => 'true=true|false=false',
            'DEFAULT' => 'true'
        );
// The color of the value text when the wave does not overlap it. Цвет текста значения, когда волна не перекрывает его
        $properties[] = array(
            'NAME' => 'textColor',
            'TITLE' => 'Цвет текста значения, когда волна не перекрывает его',
            'TYPE' => 'rgb',
            'DEFAULT' => '#045681'
        );
// The color of the value text when the wave overlaps it. Цвет текста значения, когда волна перекрывает его.
        $properties[] = array(
            'NAME' => 'waveTextColor',
            'TITLE' => 'Цвет текста значения, когда волна перекрывает его.',
            'TYPE' => 'rgb',
            'DEFAULT' => '#A4DBf8'
        );

        $this->processProperties($properties);
        
        return $properties;
    }

    function getSVG($attributes)
    {
        
        $x = (int)$attributes['x'];
        $y = (int)$attributes['y'];

        $data=$this->getData();

        $current_value=(float)$data['value']['VALUE'];

        $bgcolor = $data['bgcolor']['VALUE'];

        $name=$data['name']['VALUE'];
        $id=$data['id']['VALUE'];
        $value=$data['value']['VALUE'];
        $valuelabel=$data['valuelabel']['VALUE'];
        $min =(int)$data['min']['VALUE'];
        $max =(int)$data['max']['VALUE'];
        $step =(int)$data['step']['VALUE'];
        $oninput=$data['oninput']['VALUE'];
        $scaleProc=(int)$data['scale']['VALUE'];
        if (!$scaleProc) {
            $scaleProc=100;
        }
        $scale = round($scaleProc/100,2);
        $scaleProc=(1/$scale)*100;

        $width = (int)$attributes['width'];
        if (!$width) $width = 200;
        $height = (int)$attributes['height'];
        if (!$height) $height = 200;
        
        
        return $svg;
    }

    function getJavascript($attributes)
    {
        $data=$this->getData();
        $prop_name=strtolower($data['value']['LINKED_OBJECT'].'.'.$data['value']['LINKED_PROPERTY']);
        $code = '';
        return $code;
    }
}
