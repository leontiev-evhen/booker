<?php
namespace libs\tests;
use libs\models\RoomsModel;
use PDO;
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class RoomsModelTest extends TestCase
{
    use TestCaseTrait;

	private $_conn = null;
    static $model;

    public function getConnection()
    {
        if ($this->_conn === null) {
            $pdo = new \PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
            $this->_conn = $this->createDefaultDBConnection($pdo, $GLOBALS['DB_DBNAME']);
        }
        return $this->_conn;
    }

    public function getDataSet()
    {
        return $this->createMySQLXMLDataSet(dirname(__FILE__). '/dump/dump_users.xml');
    }

    public static function setUpBeforeClass()
    {
        self::$model = new RoomsModel();
    }
    
    /**
    *   getAllRooms()
    *   retun array
    */
    public function testGetAllRooms ()
    {   
        $this->assertNotEmpty(self::$model->getAllRooms());
    }

}