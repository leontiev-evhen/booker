<?php
namespace libs\tests;
use libs\models\GenresModel;
use PDO;
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class GenresModelTest extends TestCase
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
        return $this->createMySQLXMLDataSet(dirname(__FILE__). '/dump/dump_genres.xml');
    }

    public static function setUpBeforeClass()
    {
        self::$model = new GenresModel();
    }

    /*
    *    getAllGenres()
    *    return array
    */

    public function testGetAllGenres ()
    {
        $this->assertNotEmpty(self::$model->getAllGenres());
    }

    /**
    *   @dataProvider additionGetOneProvider
    *   getOneGenre()
    *   retun array
    */

    public function testGetOneGenre ($data)
    {   
        $id = (int)$data["id"];
        $this->assertNotEmpty(self::$model->getOneGenre($id));
    }

    public function additionGetOneProvider ()
    {
        return [
            'data'  => [['id' => 2]]
        ];
    }

    /**
    *   @dataProvider additionGetOneProviderFalse
    *   getOneGenre()
    *   retun false
    */
    public function testGetOneGenreFalse ($data)
    {   
        $id = (int)$data["id"];
        $this->assertFalse(self::$model->getOneGenre($id));
    }

    public function additionGetOneProviderFalse ()
    {
        return [
            'data_negative_number'  => [['id' => -1]],
            'data_float_number'     => [['id' => 1.5]], 
            'data_string'           => [['id' => 'test']],
            'data_array'            => [['id' => []]],
            'data_empty'            => [['id' => '']]
        ];
    }

    /**
    *   @dataProvider additionCreateProvider
    *   createGenre()
    *   retun true
    */
    public function testCreateGenre ($data)
    {
        $this->assertTrue(self::$model->createGenre((object)$data));
    }

    public function additionCreateProvider ()
    {
        return [
            'data'                  => [['name' => 'test']],
            'data_empty'            => [['name' => '']],
            'data_number'           => [['name' => 1]],
            'data_negative_number'  => [['name' => -1]],
            'data_float_number'     => [['name' => 1.5]],
        ];
    }

    /**
    *   @dataProvider additionCreateProviderFalse
    *   createGenre()
    *   retun false
    */
    public function testCreateGenreFalse ($data)
    {
        $this->assertFalse(self::$model->createGenre((object)$data));
    }

    public function additionCreateProviderFalse ()
    {
        return [
            'data'  => [['name_undefined_field' => 'test']]
        ];
    }

    /**
    *   @dataProvider additionUpdateProvider
    *   updateGenre()
    *   retun true
    */
    public function testUpdateGenre ($data)
    {
        
        $id = (int)$data["id"];

        $this->assertTrue(self::$model->updateGenre((object)$data, $id));
    }

    public function additionUpdateProvider ()
    {
        return [
            'data'                  => [['name' => 'test', 'id' => 25]],
            'data_negative_number'  => [['name' => 'test', 'id' => -25]],
            'data_string'           => [['name' => 'test', 'id' => 'test']],
            'data_array'            => [['name' => 'test', 'id' => []]],
            'data_float_number'     => [['name' => 'test', 'id' => 1.5]],
            'data_empty'            => [['name' => 'test', 'id' => '']],
        ];
    }

    /**
    *   @dataProvider additionDeleteProvider
    *   deleteGenre()
    *   return true
    */
    public function testDeleteGenre ($data)
    {
        
        $id = (int)$data["id"];

        $this->assertTrue(self::$model->deleteGenre($id));
    }

    public function additionDeleteProvider ()
    {
        return [
            'data'                  => [['id' => 200]],
            'data_negative_number'  => [['id' => -1]],
            'data_string'           => [['id' => 'test']],
            'data_array'            => [['id' => []]],
            'data_float_number'     => [['id' => 1.5]],
            'data_empty'            => [['id' => '']],
        ];
    }

}
