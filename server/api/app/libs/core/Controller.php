<?php
namespace libs\core;
use \Exception;

/**
* Controller is the base class for all controller classes
* @author Leontiev Yevhen <leontevevgenii@gmail.com>  
*/

class Controller
{
    protected $model;
    protected $rules;
    protected $params;
    protected $dataParam;
    protected $data;

    public function __construct ()
    {
       
    }

    protected function validateParams ($data)
    {
        $param = explode('/', $data);
        $arrData = array_chunk($param , 2);

        foreach ($arrData as $item) 
        {
            if (in_array($item[0], $this->params) && !empty($item[1])) 
            {
                $this->dataParam[$item[0]] = trim((int)$item[1]);
            } 
            else
            {
                throw new Exception('Invalid data');
            }
        }
        return $this->dataParam;
    }

    protected function validate ()
    {
    	$this->data = $this->getData();

        if ($this->data)
        {
            foreach ($this->data as $key=>$item) 
            {
                if (!isset($this->rules[$key]) || gettype($item) != $this->rules[$key]) 
                {
                    return false;
                } 

                $this->data->$key = trim(strip_tags($item));
            }
         
            return true;
        }
        throw new Exception('Invalid data');
    }

    protected function getData ()
    {
    	$json = file_get_contents('php://input');
    	return json_decode($json);
    }

    protected function getServerAnswer ($code, $success, $message, $data = [])
    {

    	header('HTTP/1.0 '.$code, $message);
        
    	return ['status' => $code, 'success' => $success, 'message' => $message, 'data' => $data];
    }


}
