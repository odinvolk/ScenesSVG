<?php

include_once DIR_MODULES . 'plans/plan_component.class.php';

class toggle_two_step extends plan_component {

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
            $data['value_proc']['VALUE']='url(#LGToggleGreen)';
            $data['value_cx']['VALUE']=204;
        } else {
            $data['value_proc']['VALUE']='url(#LGToggleGrey)';
            $data['value_cx']['VALUE']=75;
        }

        $bgcolor=$data['bgcolor']['VALUE'];
        $dopcolor=$data['dopcolor']['VALUE'];
        $svgdop=$data['svg_dop']['VALUE'];

        
        if ($text == '0') {$classtext='updatedText';}

        $width=(int)$attributes['width'];
        if (!$width) $width=200;
        $height=(int)$attributes['height'];
        if (!$height) $height=20;

/*
 <rect width="278" height="148" x="1" y="1" rx="74" ry="73" stroke="#000" stroke-miterlimit="10" stroke-width="1" fill="#0f0" fill-opacity=".6" stroke-opacity=".4"/>
 <rect width="270" height="140" x="5" y="5" rx="70" ry="73" fill='%EconomMode.active|"url(#LGToggleGrey);url(#LGToggleGreen);"%' fill-opacity=".8" stroke-width="1"/>
 <rect width="140" height="148" x="0" y="0" rx="74" ry="73" fill="#ccc" fill-opacity=".01" onclick="callMethod('EconomMode.deactivate');"/>
 <rect width="140" height="148" x="140" y="0" rx="74" ry="73" fill="#000" fill-opacity=".01" onclick="callMethod('EconomMode.activate');"/>
 <ellipse rx="68" ry="68" cy="75" cx='%EconomMode.active|"75;204;"%' stroke="#000" stroke-width="1.2" stroke-opacity=".4" fill="#f0f0f0" fill-opacity=".6" onclick="callMethod('EconomMode.switch');"> 
  <title id="tEconomMode">%EconomMode.active|"Режим экономии выключен;Режим экономии включен;"%</title>
 </ellipse> 

*/
        $svg.="<svg width='$width' height='$height' x='$x' y='$y' viewBox='0 0 280 150' $svgdop>";
        $svg.="<rect x='1' y='1' width='278' height='148' rx='74' ry='73' fill='#0f0' fill-opacity='.6' stroke='#000' stroke-miterlimit='10' stroke-width='1'/>";
        $svg.="<rect id='procElem{$this->component_id}' x='5' y='5' width='270' height='140' rx='70' ry='73' fill='%value_proc%' fill-opacity='.6' stroke-width='1'/>";        
        $svg.="<rect x='0' y='0' width='140' height='148' rx='74' ry='73' fill='#ccc' fill-opacity='.1'/>";
        $svg.="<rect x='140' y='0' width='140' height='148' rx='74' ry='73' fill='#ccc' fill-opacity='.1'/>";
        $svg.="<ellipse id='cx{$this->component_id}' rx='68' ry='68' cx='%value_cx%' cy='75' stroke='#000' stroke-width='1.2' stroke-opacity='.4' fill='#f0f0f0' fill-opacity='.6'/>";
//        $svg.="<use xlink:href='#sEconomMode' x='5' y='5' width='140' height='140'/>";
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
        var cxp=$('#cx{$this->component_id}');
        
        if (property_value=='1') {
        elem.attr('fill','url(#LGToggleGreen)');
        cxp.attr('cx', '204');
        } else {
        elem.attr('fill','url(#LGToggleGrey)');
        cxp.attr('cx', '75');
        }
            }
    }
EOD;
        return $code;
    }
}
