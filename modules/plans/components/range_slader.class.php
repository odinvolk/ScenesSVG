<?php
//
// нужно доработать
//
include_once DIR_MODULES . 'plans/plan_component.class.php';

class range_slader extends plan_component
{

    function __construct($id)
    {
        $this->name = str_replace('.class.php', '', basename(__FILE__));
        parent::__construct($id);
    }

    function getProperties()
    {
        $properties = parent::getProperties();
        $properties[] = array(
            'NAME' => 'valuelabel',
            'TITLE' => 'Подпись',
            'TYPE' => 'text',
            'DEFAULT' => 'Прихожая'
        );
        $properties[] = array(
            'NAME' => 'value',
            'TITLE' => LANG_VALUE,
            'TYPE' => 'linked_property'
        );
        $properties[] = array(
            'NAME' => 'name',
            'TITLE' => 'Name',
            'TYPE' => 'text',
            'DEFAULT' => 'slider1'
        );
        $properties[] = array(
            'NAME' => 'id',
            'TITLE' => 'ID',
            'TYPE' => 'text',
            'DEFAULT' => 'slider1'
        );
        $properties[] = array(
            'NAME' => 'oninput',
            'TITLE' => 'Oninput',
            'TYPE' => 'text',
            'DEFAULT' => 'ajaxSetGlobal(\"Dimmer01.level\",+ document.getElementById(\"slider-1\").value);'
        );
        $properties[] = array(
            'NAME' => 'min',
            'TITLE' => 'Min',
            'TYPE' => 'int',
            'DEFAULT' => '0'
        );
        $properties[] = array(
            'NAME' => 'max',
            'TITLE' => 'Max',
            'TYPE' => 'int',
            'DEFAULT' => '100'
        );
        $properties[] = array(
            'NAME' => 'step',
            'TITLE' => 'Step',
            'TYPE' => 'int',
            'DEFAULT' => '1'
        );

        $properties[] = array(
            'NAME' => 'scale',
            'TITLE' => LANG_APPEAR_SCALE.' (%)',
            'TYPE' => 'text',
            'DEFAULT' => '100'
        );

        $properties[] = array(
            'NAME' => 'bgcolor',
            'TITLE' => LANG_COLOR.' (background)',
            'TYPE' => 'rgb',
            'DEFAULT' => 'white'
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

        $svg = "<svg width='$width' height='$height' x='$x' y='$y' xmlns='http://www.w3.org/2000/svg' version='1.0' xmlns:xlink='http://www.w3.org/1999/xlink'>";
        $svg .= "<g transform=\"scale($scale)\">";
//        $svg .= "<foreignObject x='$x' y='$y' width='$width' height='$height' transform=\"scale($scale)\">";
        $svg .= "<foreignObject x=\"0\" y=\"0\" width=\"$scaleProc%\" height=\"$scaleProc%\" style='text-align: center; padding: 0'>";
        $svg .= "<label for='$id'>$valuelabel</label>";
        $svg .= "<input type='range' class='slider' id='$id' name='$name' value='$value' min='$min' max='$max' step='$step' oninput='$oninput'/>";
        $svg .= "</foreignObject>";
        $svg .= "</g>";
        $svg .= "</svg>";
        return $svg;
    }

    function getJavascript($attributes)
    {
        $code = '';
        return $code;
    }
}
