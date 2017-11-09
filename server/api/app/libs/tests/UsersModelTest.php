<?php
namespace libs\tests;
use libs\models\UsersModel;
use PDO;
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class UsersModelTest extends TestCase
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
        self::$model = new UsersModel();
    }
    
    /**
    *   getAllUsers()
    *   retun array
    */
    public function testGetAllCustomers ()
    {   
        $this->assertNotEmpty(self::$model->getAllUsers());
    }


    /**
    *   @dataProvider additionOneUserProvider
    *   getOneUser()
    *   retun array
    */
    public function testGetOneUser($data)
    {
    	$id = (int)$data["id"];
        $this->assertNotEmpty(self::$model->getOneUser($id));
    }

    public function additionOneUserProvider ()
    {
        return [
            'data' => [['id' => 7]],
        ];
    }

    /**
    *   @dataProvider additionOneUserProviderFalse
    *   getOneUser()
    *   retun boolean
    */
    public function testGetOneUserrFalse ($data)
    {
    	$id = (int)$data["id"];
        $this->assertFalse(self::$model->getOneUser($id));
    }

    public function additionOneUserProviderFalse ()
    {
        return [
            'data' => [['id' => 100]],
        ];
    }

    /**
    *   @dataProvider additionCreateUserProvider
    *   createUser()
    *   retun boolean
    */
    public function testCreateUser ($data)
    {
    	$result = self::$model->createUser((object)$data);
        $this->assertTrue($result['result']);
    }

    public function additionCreateUserProvider ()
    {
        return [
            'data' => [['name' => 'test', 'email' => 'new@test.com', 'password' => '123', 'id_role' => 2]],
        ];
    }

    /**
    *   @dataProvider additionCreateUserProviderFalse
    *   createUser()
    *   retun boolean
    */
    public function testCreateUserFalse ($data)
    {
        $result = self::$model->createUser((object)$data);
        $this->assertFalse($result['result']);
    }

    public function additionCreateUserProviderFalse ()
    {
        return [
            'data' => [['name' => 'test', 'email' => 'test@test.com', 'password' => '123', 'id_role' => 2]],
        ];
    }

    /**
    *   @dataProvider additionUpdateUserProvider
    *   updateUser()
    *   retun boolean
    */
    public function testUpdateUser ($data)
    {
    	$id = (int)$data["id"];
        $result = self::$model->updateUser((object)$data, $id);
        $this->assertTrue($result['result']);
    }

    public function additionUpdateUserProvider ()
    {
        return [
            'data' => [['name' => 'test', 'email' => 'new@test.com', 'id_role' => 2, 'id' => 7]],
        ];
    }

     /**
    *   @dataProvider additionUpdateUserProviderFalse
    *   updateUser()
    *   retun boolean
    */
    public function testUpdateUserFalse ($data)
    {
        $id = (int)$data["id"];
        $result = self::$model->updateUser((object)$data, $id);
        $this->assertFalse($result['result']);
    }

    public function additionUpdateUserProviderFalse ()
    {
        return [
            'data' => [['name' => 'test', 'email' => 'test@test.com', 'id_role' => 2, 'id' => 7]],
        ];
    }

    /**
    *   @dataProvider additionDeleteUserProvider
    *   deleteUser()
    *   retun boolean
    */
    public function testDeleteUser ($id)
    {
        $this->assertTrue(self::$model->deleteUser($id));
    }

    public function additionDeleteUserProvider ()
    {
        return [
            [9]
        ];
    }


    /**
    *   @dataProvider additionCheckUserProvider
    *   checkUser()
    *   retun array
    */
    public function testCheckCustomer ($data)
    {
        $this->assertNotEmpty(self::$model->checkUser((object)$data));
    }

    public function additionCheckUserProvider ()
    {
        return [
            'data' => [['email' => 'leo@mail.cz']],
        ];
    }

    /**
    *   @dataProvider additionCheckUserProviderFalse
    *   checkUser()
    *   retun boolean
    */
    public function testCheckUserFalse ($data)
    {
        $this->assertFalse(self::$model->checkUser((object)$data));
    }

    public function additionCheckUserProviderFalse ()
    {
        return [
            'data' => [['email' => 'test@mail.cz']],
        ];
    }
   
    /**
    *   @dataProvider additionUpdateTokenProvider
    *   updateToken()
    *   retun boolean
    */
    public function testUpdateToken ($data)
    {
        $this->assertTrue(self::$model->updateToken($data));
    }

    public function additionUpdateTokenProvider ()
    {
        return [
            'data' => [['access_token' => 'test', 'id' => 7]],
        ];
    }

    /**
    *   @dataProvider additionCheckAuthProviderFalse
    *   checkAuth()
    *   retun boolean
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