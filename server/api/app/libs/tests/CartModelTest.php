<?php
namespace libs\tests;
use libs\models\CartModel;
use PDO;
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class CartModelTest extends TestCase
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
        return $this->createMySQLXMLDataSet(dirname(__FILE__). '/dump/dump_cart.xml');
    }

    public static function setUpBeforeClass()
    {
        self::$model = new CartModel();
    }

    /**
    *   @dataProvider additionGetAllProvider
    *   getAll()
    *   retun array
    */

    public function testGetAll ($data)
    {   
        $id = (int)$data["id"];
        $this->assertNotEmpty(self::$model->getAll($id));
    }

    public function additionGetAllProvider ()
    {
        return [
            'data'  => [['id' => 1]]
        ];
    }

    /**
    *   @dataProvider additionCreateProvider
    *   createCart()
    *   retun true
    */
    public function testCreateCart ($data)
    {
        $this->assertTrue(self::$model->createCart((object)$data));
    }

    public function additionCreateProvider ()
    {
        return [
            'data' => [['id_book' => 2, 'id_customer' => 1, 'count' => 1]],
        ];
    }

    /**
    *   @dataProvider additionCreateProviderFalse
    *   createCart()
    *   retun false
    */
    public function testCreateCartFalse ($data)
    {
        $this->assertFalse(self::$model->createCart((object)$data));
    }

    public function additionCreateProviderFalse ()
    {
        return [
            'data'  => [['name_undefined_field' => 'test']],
            'data2'  => [['id_book' => 2, 'id_customer' => 100, 'count' => 1]],
            'data3'  => [['id_book' => 200, 'id_customer' => 1, 'count' => 1]],
        ];
    }

    /**
    *   @dataProvider additionUpdateProvider
    *   updateCart()
    *   retun true
    */
    public function testUpdateCart($data)
    {
      	$this->assertTrue(self::$model->updateCart((object)$data));
    }

    public function additionUpdateProvider ()
    {
        return [
            'data' => [['id_book' => 2, 'id_customer' => 1, 'count' => 5]],
        ];
    }

    /**
    *   @dataProvider additionDeleteProvider
    *   deleteCart()
    *   retun true
    */
    public function testDeleteCart($data)
    {
    	$id_book = $data['id_book'];
    	$id_customer = $data['id_customer'];
      	$this->assertTrue(self::$model->deleteCart($id_book, $id_customer));
    }

    public function additionDeleteProvider ()
    {
        return [
            'data' => [['id_book' => 2, 'id_customer' => 1]],
        ];
    }

    /**
    *   @dataProvider additionDeleteCartCustomerProvider
    *   deleteCartCustomer()
    *   retun true
    */
    public function testDeleteCartCustomer($data)
    {
    	$id_customer = $data['id_customer'];
      	$this->assertTrue(self::$model->deleteCartCustomer($id_customer));
    }

    public function additionDeleteCartCustomerProvider ()
    {
        return [
            'data' => [['id_customer' => 1]],
        ];
    }

}
