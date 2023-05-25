<?php

include_once DIR_MODULES . 'plans/plan_component.class.php';

class button_dopcount extends plan_component {

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
            'DEFAULT' => '0'
        );
        $properties[] = array(
            'NAME' => 'textcolor',
            'TITLE' => LANG_COLOR.' текста',
            'TYPE' => 'select',
            'DATA' => '#ff0000=Красный|#00ff00=Зеленый|#0000ff=Синий|#000=Черный|#ccc=Серый|#fff=Белый',
            'DEFAULT' => '#ccc'
        );
        $properties[] = array(
            'NAME' => 'textsize',
            'TITLE' => 'Размер текста',
            'TYPE' => 'text',
            'DEFAULT' => '14'
        );
//        $properties[] = array(
//            'NAME' => 'classtext',
//            'TITLE' => 'Задать класс тексту',
//            'TYPE' => 'text',
//            'DEFAULT' => 'updatedText'
//        );
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

        $this->processProperties($properties);
        
        return $properties;
    }
    
    function getSVG($attributes)
    {

        $x=(int)$attributes['x'];
        $y=(int)$attributes['y'];

        $data=$this->getData();
        
        $cur_val=$data['value']['VALUE'];
        
        $current_value=(int)$data['value']['VALUE'];
        if ($data['value']['VALUE']) {
            $data['value_proc']['VALUE']=0;
        } else {
            $data['value_proc']['VALUE']=100;
        }

        $bgcolor=$data['bgcolor']['VALUE'];
        $color=$data['color']['VALUE'];
        $textcolor=$data['textcolor']['VALUE'];
        $text=$data['text']['VALUE'];
        $textsize=$data['textsize']['VALUE'];
        $rx_ry=$data['rx_ry']['VALUE'];
        $fillop=$data['fill_opacity']['VALUE'];
        $fillop2=$data['fill_opacity2']['VALUE'];
        $svgdop=$data['svg_dop']['VALUE'];
        $classtext=$data['classtext']['VALUE'];
        
      //  if ($text == '0') {$classtext='updatedText';}

        $width=(int)$attributes['width'];
        if (!$width) $width=200;
        $height=(int)$attributes['height'];
        if (!$height) $height=20;

        $svg.="<svg width='$width' height='$height' x='$x' y='$y' $svgdop>";
        $svg.="<rect x='0' y='0' width='100%' height='100%' rx='$rx_ry' ry='$rx_ry' fill='$bgcolor' fill-opacity='$fillop' stroke='#ccc' stroke-width='1' stroke-opacity='.5'/>";
        $svg.="<rect x='0' y='0' width='%value_proc%%' id='procElem{$this->component_id}' height='$height' fill='$color' fill-opacity='$fillop2' stroke='#ccc' stroke-width='2' stroke-opacity='.5'/>";
        $svg.="<text id='updatedText{$this->component_id}' x='50%' y='60%' fill='$textcolor' font-size='$textsize' font-weight='600' text-anchor='middle'>$cur_val%Tuya_relay03.status%$text</text>";
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
         var dont=$('#updatedText{$this->component_id}');
         $(dont).text(property_value);                        //$('#updatedText').text(property_value);
            var ustav = '1800';

         //   if (property_value^'100') {ustav=='100'}
        //    if (property_value^'1000') {ustav=='1000'}

          var money = property_value; // у нас есть деньги;
          var tall = money/ustav*100;

        if (property_name.toLowerCase()=='$prop_name') {
        
        var elem=$('#procElem{$this->component_id}');
        
        if (property_value=='1') {
        elem.attr('widthNum','100');
         elem.animate(
         {'widthNum':tall},
            {
            step: function(widthNum){
                 $(this).attr('width', widthNum+'%');
            },
            duration: 200
            }
            );
    
        } else {
         elem.attr('widthNum','5');
         elem.animate(
            {'widthNum':tall},
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
