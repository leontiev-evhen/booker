<?php
namespace libs\tests;
use libs\models\EventsModel;
use PDO;
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class EventsModelTest extends TestCase
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
        return $this->createMySQLXMLDataSet(dirname(__FILE__). '/dump/dump_events.xml');
    }

    public static function setUpBeforeClass()
    {
        self::$model = new EventsModel();
    }
    
    /**
    *	@dataProvider additionGetAllProvider
    *   getAll()
    *   retun array
    */
    public function testGetAll ($data)
    {   
    	$id_room = $data['id_room'];
    	$month = $data['month'];
    	$year = $data['year'];

        $this->assertNotEmpty(self::$model->getAll($id_room, $month, $year));
    }

    public function additionGetAllProvider ()
    {
        return [
            'data' => [['id_room' => 1, 'month' => 10, 'year' => 2017]],
        ];
    }

    /**
    *	@dataProvider additionGetAllProviderFalse
    *   getAll()
    *   retun array
    */
    public function testGetAllFalse ($data)
    {   
    	$id_room = $data['id_room'];
    	$month = $data['month'];
    	$year = $data['year'];

        $this->assertEmpty(self::$model->getAll($id_room, $month, $year));
    }

    public function additionGetAllProviderFalse ()
    {
        return [
            'data' => [['id_room' => 1, 'month' => 1, 'year' => 2016]],
            'data2' => [['id_room' => 10, 'month' => 1, 'year' => 2016]],
            'data3' => [['id_room' => 10, 'month' => 0, 'year' => 2017]],
            'data4' => [['id_room' => 'test', 'month' => 0, 'year' => 2017]],
        ];
    }

     /**
    *	@dataProvider additionGetOneProvider
    *   getAll()
    *   retun array
    */
    public function testGetOne ($data)
    {   
    	$id = $data['id'];

        $this->assertNotEmpty(self::$model->getOne($id));
    }

    public function additionGetOneProvider ()
    {
        return [
            'data' => [['id' => 244]],
        ];
    }

    /**
    *	@dataProvider additionGetOneProviderFalse
    *   getOne()
    *   retun boolean
    */
    public function testGetOneFalse ($data)
    {   
    	$id = $data['id'];

        $this->assertFalse(self::$model->getOne($id));
    }

    public function additionGetOneProviderFalse ()
    {
        return [
            'data' => [['id' => 2440]],
            'data2' => [['id' => 'test']],
        ];
    }

     /**
    *	@dataProvider additionDeleteUserEventProvider
    *   deleteUserEvent()
    *   retun boolean
    */
    public function testDeleteUserEvent($data)
    {   
    	$id = $data['id'];

        $this->assertTrue(self::$model->deleteUserEvent($id));
    }

    public function additionDeleteUserEventProvider ()
    {
        return [
            'data' => [['id' => 8]],
        ];
    }

    /**
    *	@dataProvider additionCreateEventProvider
    *   createEvent()
    *   retun boolean
    */
    public function testCreateEvent($data)
    {   
    	$result = self::$model->createEvent((object)$data);
        $this->assertTrue($result['result']);
    }

    public function additionCreateEventProvider ()
    {
        return [
            'data' => [['id_user' => 8, 'id_room' => 3, 'description' => 'test', 'time_start' => 1510214400,'time_end' => 1510218000, 'parent_id' => 0, 'recurring' => 0]],
            'data2' => [['id_user' => 8, 'id_room' => 3, 'description' => 'test', 'time_start' => 1510214400,'time_end' => 1510218000, 'parent_id' => 0, 'recurring' => 1, 'time_recurring' => 'weekly', 'repeat' => 4]],
            'data3' => [['id_user' => 8, 'id_room' => 3, 'description' => 'test', 'time_start' => 1510214400,'time_end' => 1510218000, 'parent_id' => 0, 'recurring' => 1, 'time_recurring' => 'bi-weekly', 'repeat' => 2]],
            'data4' => [['id_user' => 8, 'id_room' => 3, 'description' => 'test', 'time_start' => 1510214400,'time_end' => 1510218000, 'parent_id' => 0, 'recurring' => 1, 'time_recurring' => 'monthly', 'repeat' => 1]],
        ];
    }



    /**
    *	@dataProvider additionCreateEventProviderFalse
    *   createEvent()
    *   retun boolean
    */
    public function testCreateEventFalse($data)
    {   
    	$result = self::$model->createEvent((object)$data);
        $this->assertFalse($result['result']);
    }

    public function additionCreateEventProviderFalse ()
    {
        return [
            'data' => [['id_user' => 8, 'id_room' => 1, 'description' => 'test', 'time_start' => 1512720000,'time_end' => 1512723600, 'parent_id' => 0, 'recurring' => 0]],
            'data2' => [['id_user' => 8, 'id_room' => 1, 'description' => 'test', 'time_start' => 1512720000,'time_end' => 1512723600, 'parent_id' => 0, 'recurring' => 1]]
        ];
    }

    /**
    *	@dataProvider additionUpdateEventProvider
    *   updateEvent()
    *   retun boolean
    */
    public function testUpdateEvent($data)
    {   
    	$id = $data['id'];
    	$result = self::$model->updateEvent((object)$data, $id);

        $this->assertTrue($result['result']);
    }

    public function additionUpdateEventProvider ()
    {
        return [
            'data' => [['id_user' => 8, 'id_room' => 1, 'description' => 'test', 'time_start' => 1510214400,'time_end' => 1510218000, 'parent_id' => 0, 'recurring' => 0, 'id' => 245]]
        ];
    }

    /**
    *	@dataProvider additionUpdateEventProviderFalse
    *   updateEvent()
    *   retun boolean
    */
    public function testUpdateEventFalse($data)
    {   
    	$id = $data['id'];
    	$result = self::$model->updateEvent((object)$data, $id);

        $this->assertFalse($result['result']);
    }

    public function additionUpdateEventProviderFalse ()
    {
        return [
            'data' => [['id_user' => 8, 'id_room' => 1, 'description' => 'test', 'time_start' => 1511334000,'time_end' => 1511335800, 'parent_id' => 0, 'recurring' => 1, 'id' => 245]]
        ];
    }

    /**
    *	@dataProvider additionDeleteEventProvider
    *   deleteEvent()
    *   retun boolean
    */
    public function testDeleteEventFalse($data)
    {   
    	$id = $data['id'];
    	$recurring = $data['recurring'];
        $this->assertTrue(self::$model->deleteEvent($id, $recurring));
    }

    public function additionDeleteEventProvider ()
    {
        return [
            'data' => [['id' => 252, 'recurring' => 0]],
            'data2' => [['id' => 252, 'recurring' => 1]],
        ]; 
    }

}