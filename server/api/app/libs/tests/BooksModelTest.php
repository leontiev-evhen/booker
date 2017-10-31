<?php
namespace libs\tests;
use libs\models\BooksModel;
use PDO;
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class BooksModelTest extends TestCase
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
        return $this->createMySQLXMLDataSet(dirname(__FILE__). '/dump/dump_books.xml');
    }

    public static function setUpBeforeClass()
    {
        self::$model = new BooksModel();
    }

    /*
    *    getAllBooks()
    *    return array
    */

    public function testGetAllBooks ()
    {
        $this->assertNotEmpty(self::$model->getAllBooks());
    }

    /**
    *   @dataProvider additionGetOneProvider
    *   getOneBook()
    *   retun array
    */

    public function testGetOneBook ($data)
    {   
        $id = (int)$data["id"];
        $this->assertNotEmpty(self::$model->getOneBook($id));
    }

    public function additionGetOneProvider ()
    {
        return [
            'data'  => [['id' => 2]]
        ];
    }

    /**
    *   @dataProvider additionGetOneProviderFalse
    *   getOneBook()
    *   retun false
    */
    public function testGetOneBookFalse ($data)
    {   
        $id = (int)$data["id"];
        $this->assertFalse(self::$model->getOneBook($id));
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
    *   createBook()
    *   retun true
    */
    public function testCreateBook ($data)
    {
        $this->assertTrue(self::$model->createBook((object)$data));
    }

    public function additionCreateProvider ()
    {
        return [
            'data' => [['name' => 'test', 'description' => 'test', 'price' => 100, 'discaunt' => 10]],
        ];
    }

    /**
    *   @dataProvider additionCreateProviderFalse
    *   createBook()
    *   retun false
    */
    public function testCreateBookFalse ($data)
    {
        $this->assertFalse(self::$model->createBook((object)$data));
    }

    public function additionCreateProviderFalse ()
    {
        return [
            'data'  => [['name_undefined_field' => 'test']]
        ];
    }

    /**
    *   @dataProvider additionUpdateProvider
    *   updateBook()
    *   retun true
    */
    public function testUpdateBook ($data)
    {
        
        $id = (int)$data["id"];

        $this->assertTrue(self::$model->updateBook((object)$data, $id));
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
    *   deleteBook()
    *   return true
    */
    public function testDeleteBook ($data)
    {
        
        $id = (int)$data["id"];

        $this->assertTrue(self::$model->deleteBook($id));
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

    /**
    *   @dataProvider additionCreateBook2RelationProvider
    *   createBook2Relation()
    *   return true
    */
    public function testCreateBook2Relation ($data)
    {
        
        $id = (int)$data["id"];
        $relation = (string)$data["relation"];
        $data = json_decode (json_encode ($data), FALSE);

        $this->assertTrue(self::$model->createBook2Relation($data, $relation, $id));
    }

    public function additionCreateBook2RelationProvider ()
    {
        return [
           'data' => [['ids' => [['id' => 20], ['id' => 21]], 'relation' => 'author', 'id' => 2]],
        ];
    }

    /**
    *   @dataProvider additionCreateBook2RelationProviderFalse
    *   createBook2Relation()
    *   return true
    */
    public function testCreateBook2RelationFalse ($data)
    {
        
        $id = (int)$data["id"];
        $relation = (string)$data["relation"];
        $data = json_decode (json_encode ($data), FALSE);

        $this->assertFalse(self::$model->createBook2Relation($data, $relation, $id));
    }

    public function additionCreateBook2RelationProviderFalse ()
    {
        return [
           'data' => [['ids' => [['id' => 20], ['id' => 21]], 'relation' => 'test', 'id' => 2]],
           'data2' => [['ids' => [['id' => 2], ['id' => 3]], 'relation' => 'author', 'id' => 2]],
           'data3' => [['ids' => [['id' => 20], ['id' => 21]], 'relation' => 'author', 'id' => 20]],
        ];
    }

    /**
    *   @dataProvider additionBooksCategoryProvider
    *   BooksCategory()
    *   return true
    */
    public function testBooksCategory ($data)
    {
        
        $id = (int)$data["id"];
        $relation = (string)$data["relation"];

        $this->assertNotEmpty(self::$model->BooksCategory($relation, $id));
    }

    public function additionBooksCategoryProvider ()
    {
        return [
           'data' => [['relation' => 'author', 'id' => 20]],
           'data2' => [['relation' => 'genre', 'id' => 2]],
        ];
    }

    /**
    *   @dataProvider additionBooksCategoryProviderFalse
    *   BooksCategory()
    *   return true
    */
    public function testBooksCategoryFalse ($data)
    {
        
        $id = (int)$data["id"];
        $relation = (string)$data["relation"];

        $this->assertEmpty(self::$model->BooksCategory($relation, $id));
    }

    public function additionBooksCategoryProviderFalse ()
    {
        return [
           'data' => [['relation' => 'test', 'id' => 20]],
           'data2' => [['relation' => 'genre', 'id' => 200]],
        ];
    }

}
