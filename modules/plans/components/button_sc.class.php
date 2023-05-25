<?php

include_once DIR_MODULES . 'plans/plan_component.class.php';

class button_sc extends plan_component {

    function __construct($id)
    {
        $this->name=str_replace('.class.php','',basename(__FILE__));
        parent::__construct($id);
    }

    function getProperties()
    {
        $properties = parent::getProperties();
        $properties[] = array(
            'NAME' => 'color',
            'TITLE' => LANG_COLOR.' действия',
            'TYPE' => 'rgb',
            'DEFAULT' => '#ff0000'
        );
        $properties[] = array(
            'NAME' => 'fill_opacity2',
            'TITLE' => 'Прозрачность действия',
            'TYPE' => 'select',
            'DATA' => '0|0.1|0.2|0.3|0.4|0.5|0.6|0.7|0.8|0.9|1',
            'DEFAULT' => '1'
        );
        $properties[] = array(
            'NAME' => 'text',
            'TITLE' => 'Текст',
            'TYPE' => 'text',
            'DEFAULT' => 'Текст'
        );
        $properties[] = array(
            'NAME' => 'textcolor',
            'TITLE' => LANG_COLOR.' текста',
            'TYPE' => 'select',
            'DATA' => '#ff0000=Красный|#ad4=Зелёненький|#00ff00=Зелёный|#0000ff=Синий|#000=Чёрный|#ccc=Серый|#fff=Белый',
            'DEFAULT' => '#ad4'
        );
        $properties[] = array(
            'NAME' => 'textsize',
            'TITLE' => 'Размер текста',
            'TYPE' => 'text',
            'DEFAULT' => '10'
        );
        $properties[] = array(
            'NAME' => 'text1',
            'TITLE' => 'Текст1',
            'TYPE' => 'text',
            'DEFAULT' => 'Текст1'
        );
        $properties[] = array(
            'NAME' => 'textcolor1',
            'TITLE' => LANG_COLOR.' текста1',
            'TYPE' => 'select',
            'DATA' => '#ff0000=Красный|#ad4=Зелёненький|#00ff00=Зеленый|#0000ff=Синий|#000=Черный|#ccc=Серый|#fff=Белый',
            'DEFAULT' => '#ccc'
        );
        $properties[] = array(
            'NAME' => 'textsize1',
            'TITLE' => 'Размер текста1',
            'TYPE' => 'text',
            'DEFAULT' => '10'
        );
        $properties[] = array(
            'NAME' => 'rx_ry',
            'TITLE' => 'Закругления',
            'TYPE' => 'int',
            'DEFAULT' => '2'
        );
        $properties[] = array(
            'NAME' => 'bgcolor',
            'TITLE' => LANG_COLOR.' фона',
            'TYPE' => 'select',
            'DATA' => 'url(#LGBattonMGreen)=Зелёный градиент|#ff0000=Красный|#00ff00=Зеленый|#0000ff=Синий|#000=Черный|#ccc=Серый|#fff=Белый',
            'DEFAULT' => '#ff0000'
        );
        $properties[] = array(
            'NAME' => 'fill_opacity',
            'TITLE' => 'Прозрачность',
            'TYPE' => 'select',
            'DATA' => '0|0.1|0.2|0.3|0.4|0.5|0.6|0.7|0.8|0.9|1',
            'DEFAULT' => '1'
        );
        $properties[] = array(
            'NAME' => 'svg_dop',
            'TITLE' => 'Добавить в конце тега SVG',
            'TYPE' => 'text',
            'DEFAULT' => ''
        );
        $properties[] = array(
            'NAME' => 'value',
            'TITLE' => LANG_VALUE,
            'TYPE' => 'linked_property'
        );
        /*
        $properties[] = array(
            'NAME' => 'value_min',
            'TITLE' => 'Min',
            'TYPE' => 'int',
            'DEFAULT' => '0'
        );
        $properties[] = array(
            'NAME' => 'value_max',
            'TITLE' => 'Max',
            'TYPE' => 'int',
            'DEFAULT' => '100'
        );
        */
        
        $this->processProperties($properties);
        
        return $properties;
    }
    
    function getSVG($attributes)
    {

        $x=(int)$attributes['x'];
        $y=(int)$attributes['y'];

        $data=$this->getData();

        $current_value=(int)$data['value']['VALUE'];
        if ($data['value']['VALUE']) {
            $data['value_proc']['VALUE']=100;
        } else {
            $data['value_proc']['VALUE']=0;
        }

        $bgcolor=$data['bgcolor']['VALUE'];
        $color=$data['color']['VALUE'];
        $textcolor=$data['textcolor']['VALUE'];
        $text=$data['text']['VALUE'];
        $textsize=$data['textsize']['VALUE'];
        $textcolor1=$data['textcolor1']['VALUE'];
        $text1=$data['text1']['VALUE'];
        $textsize1=$data['textsize1']['VALUE'];
        $rx_ry=$data['rx_ry']['VALUE'];
        $fillop=$data['fill_opacity']['VALUE'];
        $fillop2=$data['fill_opacity2']['VALUE'];
        $svgdop=$data['svg_dop']['VALUE'];

        $width=(int)$attributes['width'];
        if (!$width) $width=200;
        $height=(int)$attributes['height'];
        if (!$height) $height=20;

        $svg.="<svg width='$width' height='$height' x='$x' y='$y' $svgdop>";
        $svg.="<rect x='0' y='0' width='100%' height='100%' rx='$rx_ry' ry='$rx_ry' fill='$bgcolor' fill-opacity='$fillop' stroke='#ccc' stroke-width='2' stroke-opacity='.5'/>";
        $svg.="<rect x='0' y='0' width='%value_proc%%' id='procElem{$this->component_id}' height='$height' fill='$color' fill-opacity='$fillop2' stroke='#ccc' stroke-width='2' stroke-opacity='.5'/>";
        $svg.="<text x='50%' y='35%' fill='$textcolor' font-size='$textsize' font-weight='600' text-anchor='middle'>$text</text>";
        $svg.="<text x='50%' y='70%' fill='$textcolor1' font-size='$textsize1' font-weight='600' text-anchor='middle'>$text1</text>";
        $svg.="</svg>";
        foreach($data as $k=>$v) {
            $svg=str_replace('%'.$k.'%',$v['VALUE'],$svg);
        }
        return $svg;
    }
    
    function getJavascript($attributes)
    {
        $data=$this->getData();
        $prop_name=strtolower($data['value']['LINKED_OBJECT'].'.'.$data['value']['LINKED_PROPERTY']);
        $code = <<<EOD
    function componentUpdated{$this->component_id}(property_name,property_value) {
        if (property_name.toLowerCase()=='$prop_name') {
        var elem=$('#procElem{$this->component_id}');
        if (property_value=='1') {
        elem.attr('widthNum','5');
         elem.animate(
         {'widthNum':100},
            {
            step: function(widthNum){
                 $(this).attr('width', widthNum+'%');
            },
            duration: 200
            }
            );
    
        } else {
         elem.attr('widthNum','100');
         elem.animate(
            {'widthNum':0},
            {
            step: function(widthNum){
                 $(this).attr('width', widthNum+'%');
            },
            duration: 200
            }
            );
        }
        }
    }
EOD;
        return $code;
    }
}
