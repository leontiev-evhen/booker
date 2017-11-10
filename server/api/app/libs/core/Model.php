<?php
namespace libs\core;
use libs\core\db\Sql;
use \PDO;

/**
* Model is the base class for all model classes
* @author Leontiev Yevhen <leontevevgenii@gmail.com>  
*/


class Model extends Sql
{
    protected $connect;
    protected $date;

    public function __construct ()
    {
        if (!$this->connect = new PDO('mysql:host='.HOST.';dbname='.DB, USER, PASSWORD))
        {
            throw new Exception('Could not connect');
        }
        date_default_timezone_set('Europe/Kiev');
        $date = date("Y-m-d H:i:s");
        $this->date = $date;
    }

    protected function formattingDate ()
    {
    	$date = date("Y-m-d H:i:s");
	    $this->date = strtotime($date);
    }
}
