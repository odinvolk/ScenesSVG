<?php

include_once DIR_MODULES . 'plans/plan_component.class.php';

class toggle_one_step extends plan_component {

    function __construct($id)
    {
        $this->name=str_replace('.class.php','',basename(__FILE__));
        parent::__construct($id);
    }

    function getProperties()
    {
        $properties = parent::getProperties();
        $properties[] = array(
            'NAME' => 'dopcolor',
            'TITLE' => LANG_COLOR.' текста',
            'TYPE' => 'select',
            'DATA' => 'green=Зеленый|red=Красный|orange=Оранжевый|#ccc=Серый',
            'DEFAULT' => '#ccc'
        );
        $properties[] = array(
            'NAME' => 'bgcolor',
            'TITLE' => LANG_COLOR.' фона',
            'TYPE' => 'select',
            'DATA' => 'url(#LGToggleGreen)=Зелёный градиент|url(#LGToggleRed)=Красный градиент|url(#LGToggleOrange)=Оранжевый градиент|url(#LGToggleGrey)=Серый',
            'DEFAULT' => '#ff0000'
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
        
        $current_value=(int)$data['value']['VALUE'];
        if ($data['value']['VALUE']) {
            $data['value_proc']['VALUE']='blink_it';
            $data['value_fill']['VALUE']='url(#LGToggleGreen)';
        } else {
            $data['value_proc']['VALUE']='none';
            $data['value_fill']['VALUE']='url(#LGToggleGrey)';
        }

        $bgcolor=$data['bgcolor']['VALUE'];
        $dopcolor=$data['dopcolor']['VALUE'];
        $svgdop=$data['svg_dop']['VALUE'];

        $width=(int)$attributes['width'];
        if (!$width) $width=200;
        $height=(int)$attributes['height'];
        if (!$height) $height=20;

/*
 %EconomMode.active|"#ccc;red;"%
 <rect width="140" height="140" y="5" x="5" rx="70" ry="73" fill='%EconomMode.active|"url(#LGToggleGrey);url(#LGToggleRed);"%' fill-opacity=".8" stroke='%EconomMode.active|"#ccc;red;"%' stroke-width="0.1" stroke-opacity=".8"/>
 <ellipse rx="68" ry="68" cy="75" cx="75" stroke="#000" stroke-width="1.2" stroke-opacity=".4" fill="#f0f0f0" fill-opacity=".6" onclick="callMethod('EconomMode.switch');">
  onclick="callMethod('EconomMode.switch');"
  <title>%EconomMode.active|"Режим 0;Режим 1;"%</title>
 </ellipse>

*/
       $svg.="<svg width='$width' height='$height' x='$x' y='$y' viewBox='0 0 150 150' $svgdop>";
       $svg.="<rect x='1' y='1' width='148' height='148' rx='74' ry='74' fill='$dopcolor' fill-opacity='.6' stroke='#000' stroke-width='.1' stroke-opacity='.1'/>";
       $svg.="<rect x='5' y='5' width='140' height='140' rx='73' ry='73' fill='$bgcolor' fill-opacity='.8' stroke='$dopcolor' stroke-width='.1' stroke-opacity='.8'/>";
       $svg.="<ellipse id='procElem{$this->component_id}' rx='68' ry='68' cy='75' cx='75' stroke='#000' stroke-width='1.2' stroke-opacity='.4' fill='#f0f0f0' fill-opacity='.6' class='%value_proc%'/>";
       $svg.="<use xlink:href='#sEconomMode' x='5' y='5' width='140' height='140'/>";
//        $svg.="<rect x='0' y='0' width='%value_proc%%' id='procElem{$this->component_id}' height='$height' fill='$color' fill-opacity='$fillop2' stroke='#ccc' stroke-width='2' stroke-opacity='.5'/>";
//        $svg.="<text id='updatedText{$this->component_id}' x='50%' y='60%' fill='$textcolor' font-size='$textsize' font-weight='600' text-anchor='middle'>$text</text>";
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
         $(dont).text(property_value);
         
        if (property_name.toLowerCase()=='$prop_name') {
        
        var elem=$('#procElem{$this->component_id}');
        var fillp=$('#fill{$this->component_id}');
        
        if (property_value=='1') { 
          elem.attr('class','blink_it');
//        fillp.attr('fill','url(#LGToggleGreen)');
        } else {
          elem.attr('class','none');
//        fillp.attr('fill','url(#LGToggleGrey)');
        }
            }
    }
EOD;
        return $code;
    }
}
