<?php
namespace libs\tests;
use libs\models\CustomersModel;
use PDO;
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class CustomersModelTest extends TestCase
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
        return $this->createMySQLXMLDataSet(dirname(__FILE__). '/dump/dump_customers.xml');
    }

    public static function setUpBeforeClass()
    {
        self::$model = new CustomersModel();
    }
 
    public function testGetAllCustomers ()
    {   
        $this->assertNotEmpty(self::$model->getAllCustomers());
    }


    /**
    *   @dataProvider additionOneCustomerProvider
    *   getOneCustomer()
    *   retun array
    */
    public function testGetOneCustomer ($data)
    {
    	$id = (int)$data["id"];
        $this->assertNotEmpty(self::$model->getOneCustomer($id));
    }

    public function additionOneCustomerProvider ()
    {
        return [
            'data' => [['id' => 1]],
        ];
    }

    /**
    *   @dataProvider additionOneCustomerProviderFalse
    *   getOneCustomer()
    *   retun array
    */
    public function testGetOneCustomerFalse ($data)
    {
    	$id = (int)$data["id"];
        $this->assertFalse(self::$model->getOneCustomer($id));
    }

    public function additionOneCustomerProviderFalse ()
    {
        return [
            'data' => [['id' => 100]],
        ];
    }

    /**
    *   @dataProvider additionCustomerTokenProvider
    *   getCustomerToken()
    *   retun array
    */
    public function testGetCustomerToken ($data)
    {
    	$token = $data["token"];
        $this->assertNotEmpty(self::$model->getCustomerToken($token));
    }

    public function additionCustomerTokenProvider ()
    {
        return [
            'data' => [['token' => 'zzwGLM28U8dcY1viApTogVVt8CyhLm6A']],
        ];
    }

    /**
    *   @dataProvider additionCustomerTokenProviderFalse
    *   getCustomerToken()
    *   retun array
    */
    public function testGetCustomerTokenFalse ($data)
    {
    	$token = $data["token"];
        $this->assertFalse(self::$model->getCustomerToken($token));
    }

    public function additionCustomerTokenProviderFalse ()
    {
        return [
            'data' => [['token' => 'token']],
        ];
    }

    /**
    *   @dataProvider additionCreateCustomerProvider
    *   createCustomer()
    *   retun array
    */
    public function testCreateCustomer ($data)
    {
    	$result = self::$model->createCustomer((object)$data);
        $this->assertTrue($result['result']);
    }

    public function additionCreateCustomerProvider ()
    {
        return [
            'data' => [['name' => 'test', 'email' => 'test@test.com', 'password' => '123', 'discaunt' => 10, 'status' => 1]],
        ];
    }

    /**
    *   @dataProvider additionRegistrationCustomerProvider
    *   registrationCustomer()
    *   retun array
    */
    public function testRegistrationCustomer ($data)
    {
    	$result = self::$model->registrationCustomer((object)$data);
        $this->assertTrue($result['result']);
    }

    public function additionRegistrationCustomerProvider ()
    {
        return [
            'data' => [['name' => 'test', 'email' => 'test@test.com', 'password' => '123', 'discaunt' => 10, 'status' => 1]],
        ];
    }

    /**
    *   @dataProvider additionUpdateCustomerProvider
    *   updateCustomer()
    *   retun true
    */
    public function testUpdateCustomer ($data)
    {
    	$id = (int)$data["id"];
        $this->assertTrue(self::$model->updateCustomer((object)$data, $id));
    }

    public function additionUpdateCustomerProvider ()
    {
        return [
            'data' => [['name' => 'test', 'email' => 'test@test.com', 'discaunt' => 10, 'status' => 1, 'id' => 1]],
        ];
    }


    /**
    *   @dataProvider additionCheckCustomerProvider
    *   checkCustomer()
    *   retun array
    */
    public function testCheckCustomer ($data)
    {
        $this->assertNotEmpty(self::$model->checkCustomer((object)$data));
    }

    public function additionCheckCustomerProvider ()
    {
        return [
            'data' => [['email' => 'leo@mail.cz']],
        ];
    }

    /**
    *   @dataProvider additionCheckCustomerProviderFalse
    *   checkCustomer()
    *   retun false
    */
    public function testCheckCustomerFalse ($data)
    {
        $this->assertFalse(self::$model->checkCustomer((object)$data));
    }

    public function additionCheckCustomerProviderFalse ()
    {
        return [
            'data' => [['email' => 'test@mail.cz']],
        ];
    }
   
    /**
    *   @dataProvider additionCheckAdminProvider
    *   checkAdmin()
    *   retun array
    */
    public function testCheckAdmin ($data)
    {
        $this->assertNotEmpty(self::$model->checkAdmin((object)$data));
    }

    public function additionCheckAdminProvider ()
    {
        return [
            'data' => [['email' => 'leo@mail.cz']],
        ];
    }

    /**
    *   @dataProvider additionCheckAdminProviderFalse
    *   checkAdmin()
    *   retun false
    */
    public function testCheckAdminFalse ($data)
    {
        $this->assertFalse(self::$model->checkAdmin((object)$data));
    }

    public function additionCheckAdminProviderFalse ()
    {
        return [
            'data' => [['email' => 'test@mail.cz']],
        ];
    }

    /**
    *   @dataProvider additionUpdateTokenProvider
    *   updateToken()
    *   retun true
    */
    public function testUpdateToken ($data)
    {
        $this->assertTrue(self::$model->updateToken($data));
    }

    public function additionUpdateTokenProvider ()
    {
        return [
            'data' => [['access_token' => 'test', 'id' => 1]],
        ];
    }

    /**
    *   @dataProvider additionCheckAuthProviderFalse
    *   checkAuth()
    *   retun false
    */
    public function testCheckAuthFalse ($data)
    {
    	$token = $data["token"];
        $this->assertFalse(self::$model->checkAuth($token));
    }

    public function additionCheckAuthProviderFalse ()
    {
        return [
            'data' => [['token' => 'test']],
        ];
    }
}