<?php
namespace libs\controllers;
use libs\core\Controller;
use libs\models\UsersModel;

class UsersController extends Controller
{

    protected $model;
    protected $orders_model;
    private $headers;

    protected $rules = [
        'name'      => 'string',
        'email'     => 'string',
        'password'  => 'string',
        'id_role'   => 'integer',
    ];

    public function __construct ($params)
    {
        $this->model = new UsersModel();

        $this->headers = getallheaders();

        if ($params)
        {
            $param = explode('/', $params);
            $this->id = (int)$param[0];
        }
    }

    public function getUsers ()
    {
        if (!empty($this->id))
        {       
            return $this->getUserById();
        }

        $data = $this->model->getAllUsers();
        if (!empty($data))
        {
            return $this->getServerAnswer(200, true, 'users successfully received', $data);
        }
        return $this->getServerAnswer(500, false, 'Internal Server Error');
    }

    public function getUserById ()
    {
        $data = $this->model->getOneUser($this->id);
        if (!empty($data))
        {
            return $this->getServerAnswer(200, true, 'user successfully received', $data);
        }
        else
        {
            return $this->getServerAnswer(500, false, 'Internal Server Error');
        }
    }

    public function postUsers () 
    {
        if ($this->validate()) 
        {
            $password =  base64_decode($this->data->password);
            $this->data->password = password_hash($password, PASSWORD_DEFAULT);
   
            $aData = $this->model->createUser($this->data);
            if ($aData['result'])
            {
                return $this->getServerAnswer(200, $aData['result'], $aData['message']);
            }
            else
            {
                return $this->getServerAnswer(200, $aData['result'], $aData['message']);
            }
        }

        return $this->getServerAnswer(400, false, 'Bad Request');
    }

    public function putUsers ()
    {
        if ($this->validate() && $this->id) 
        {
            if ($this->model->updateUser($this->data, $this->id))
            {
                return $this->getServerAnswer(200, true, 'user update successful');
            }
            else
            {
                return $this->getServerAnswer(200, false, 'some error');
            }
        }

        return $this->getServerAnswer(400, false, 'Bad Request');
    }

}