<?php
namespace libs\helpers;
use \SimpleXMLElement;

/**
* ConverterHelper is the helper for type of data conversion
* @author Leontiev Yevhen <leontevevgenii@gmail.com>  
*/

class ConverterHelper
{
    private $type = '';

    public static function chooseTypeOutput ($data, $type)
    {
        switch ($type)
        {
                case '.json':
                $data = self::convertJSON($data);
                break;

            case '.xml':
                $data = self::convertXML($data);
                break;

            case '.txt':
                $data = self::convertTXT($data);
                break;

            case '.html':
                $data = self::convertHTML($data);
                break;
        }
        return $data;
    }

    /**
     * convert data into JSON format
     * 
     */
    private function convertJSON ($data)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    /**
     * convert data into XML format
     * 
     */
    private function convertXML ($data)
    {
        header('Content-Type: application/xml; charset=utf-8');
        $xml = new SimpleXMLElement('<xml/>');

        if (!empty($data['data']))
        {
            foreach ($data['data'] as $items) 
            {
                if (is_array($items)) 
                {
                    $element = $xml->addChild('item');
                    foreach (array_unique($items) as $key=>$item)
                    {
                        $element->addChild($key, $item);
                    }   
                }  
                else 
                {
                    $arr[] = $items;
                } 
            }
            if (!empty($arr))
            {
                $element = $xml->addChild('item');
                foreach (array_unique($arr) as $key=>$item)
                {
                    $element->addChild('element', $item);
                }   
            } 
        }
        else 
        {
            $element = $xml->addChild('item');
            $element->addChild('status', $data['status']);
            $element->addChild('message', $data['message']);
        }
        echo $xml->asXML();
    }

     /**
     * convert data into TEXT format
     * 
     */
    private function convertTXT ($data)
    {
        header('Content-Type: text/javascript; charset=utf-8');

        print_r($data);
    }

    /**
     * convert data into HTML format
     * 
     */
    private function convertHTML ($data)
    {
        header('Content-Type: text/html; charset=utf-8');

        $html = '<table border="1" cellspacing="0" cellpadding="5"><tbody>';
        if (!empty($data['data']))
        {
            foreach ($data['data'] as $items) 
            {
                if (is_array($items)) 
                { 
                    $html .='<tr>';
                    foreach (array_unique($items) as $item)
                    {
                        $html .='<td>'.$item.'</td>';
                    }   

                    $html .='</tr>';
                }  
                else 
                {
                    $arr[] = $items;
                } 
            }
            if (!empty($arr))
            {
                $html .='<tr>';
                foreach (array_unique($arr) as $key=>$item)
                {
                    $html .='<td>'.$item.'</td>';
                }   
                $html .='</tr>';
            }
                 
            
        }
        else
        {
            $html .= '<tr>';
                $html .= '<td>'.$data['status'].'</td>';
                $html .= '<td>'.$data['message'].'</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        echo $html;
    }
}
