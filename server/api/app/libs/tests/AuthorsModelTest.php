<?php
namespace libs\tests;
use libs\models\AuthorsModel;
use PDO;
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class AuthorsModelTest extends TestCase
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
        return $this->createMySQLXMLDataSet(dirname(__FILE__). '/dump/dump_authors.xml');
    }

    public static function setUpBeforeClass()
    {
        self::$model = new AuthorsModel();
    }

    /*
    *    getAllAuthors()
    *    return array
    */

    public function testGetAllAuthors ()
    {
        $this->assertNotEmpty(self::$model->getAllAuthors());
    }

    /**
    *   @dataProvider additionGetOneProvider
    *   getOneAuthor()
    *   retun array
    */

    public function testGetOneAuthor ($data)
    {   
        $id = (int)$data["id"];
        $this->assertNotEmpty(self::$model->getOneAuthor($id));
    }

    public function additionGetOneProvider ()
    {
        return [
            'data'  => [['id' => 20]]
        ];
    }

    /**
    *   @dataProvider additionGetOneProviderFalse
    *   getOneAuthor()
    *   retun false
    */
    public function testGetOneAuthorFalse ($data)
    {   
        $id = (int)$data["id"];
        $this->assertFalse(self::$model->getOneAuthor($id));
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
    *   createAuthor()
    *   retun true
    */
    public function testCreateAuthor ($data)
    {
        $this->assertTrue(self::$model->createAuthor((object)$data));
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
    *   createAuthor()
    *   retun false
    */
    public function testCreateAuthorFalse ($data)
    {
        $this->assertFalse(self::$model->createAuthor((object)$data));
    }

    public function additionCreateProviderFalse ()
    {
        return [
            'data'  => [['name_undefined_field' => 'test']]
        ];
    }

    /**
    *   @dataProvider additionUpdateProvider
    *   updateAuthor()
    *   retun true
    */
    public function testUpdateAuthor ($data)
    {
        
        $id = (int)$data["id"];

        $this->assertTrue(self::$model->updateAuthor((object)$data, $id));
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
    *   deleteAuthor()
    *   return true
    */
    public function testDeleteAuthor ($data)
    {
        
        $id = (int)$data["id"];

        $this->assertTrue(self::$model->deleteAuthor($id));
    }

    public function additionDeleteProvider ()
    {
        return [
           // 'data'                  => [['id' => 24]],
            'data_negative_number'  => [['id' => -1]],
            'data_string'           => [['id' => 'test']],
            'data_array'            => [['id' => []]],
            'data_float_number'     => [['id' => 1.5]],
            'data_empty'            => [['id' => '']],
        ];
    }

}
