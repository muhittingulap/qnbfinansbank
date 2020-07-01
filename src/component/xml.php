<?php

namespace EFINANS\Component;

class xml
{
    private $params = array();
    private $xprms = array();
    private $xml = array();

    public function __construct($element)
    {
        $data = '<?xml version="1.0" encoding="UTF-8"?> <' . $element . '   />';
        $this->xml = new \SimpleXMLElement($data);
    }

    public function setParams($data = array(), $xprms = array())
    {
        $this->params = $data;
        $this->xprms = $xprms;

        return $this;
    }

    private function array_to_xml($data, &$xml_data, $ustkey = "",$enustkey="",$enustkey2="")
    {
        foreach ($data as $key => $value) {
            $prmval = "";
            if (is_array($value)) {
                if (is_numeric($key)) {

                    $subnode = $xml_data->addChild($ustkey,'','mglp2');
                    $this->array_to_xml($value, $subnode, $ustkey);
                } else {
                    if (is_numeric(key($value))) {
                        if(!is_array($value[key($value)])){
                            foreach ($value as $k => $v){
                                $xml_data->addChild("$key", htmlspecialchars("$v"),'mglp1');
                            }
                        }else{

                            $this->array_to_xml($value, $xml_data, $key);
                        }
                    } else {
                        $subnode = $xml_data->addChild($key,'','mglp2');
                        $this->array_to_xml($value, $subnode, $key,$ustkey,$enustkey,$enustkey2);
                    }
                }
            } else {
                $child = $xml_data->addChild("$key", htmlspecialchars("$value"),'mglp1');
                $prmval="";

                if($enustkey && $this->xprms[$enustkey2][$enustkey]){
                        if ($this->xprms[$enustkey2][$enustkey][$ustkey][$key]["name"]) {
                            $prmval = $this->xprms[$enustkey2][$enustkey][$ustkey][$key];
                        }
                }else{
                      if ($ustkey) {
                          if ($this->xprms[$ustkey][$key]["name"]) {
                              $prmval = $this->xprms[$ustkey][$key];
                          } elseif ($this->xprms[$key]["name"]) {
                              $prmval = $this->xprms[$key]["name"];
                          }
                      }else{
                          if ($this->xprms[$key]["name"]) {
                              $prmval = $this->xprms[$key]["name"];
                          }
                      }
                }


                if ($prmval) {
                    $child->addAttribute($prmval["name"], $prmval["value"]);
                }
            }
        }
    }

    public function getFaturaSablonXml()
    {

        $this->array_to_xml($this->params, $this->xml);

        $this->return = $this->xml->asXML();
        $this->return = $xml = str_replace(array(' xmlns:cbc="mglp1"', ' xmlns:cac="mglp2"',' xmlns:cbc="mglp2"'), '',  $this->return);

        return $this->return;
    }
}
