<?php
namespace libs\controllers;
use libs\core\Controller;
use libs\models\UsersModel;
use libs\models\EventsModel;

class UsersController extends Controller
{

    protected $model;
    protected $model_event;
    protected $orders_model;
    private $authHeader;

    protected $rules = [
        'name'      => 'string',
        'email'     => 'string',
        'password'  => 'string',
        'id_role'   => 'integer',
    ];

    protected $params = ['id'];

    public function __construct ($params)
    {
        $this->model = new UsersModel();
        $this->model_event = new EventsModel();

        $headers = getallheaders();
        $this->authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : false;

        if ($params)
        {
            $this->validateParams($params);
        }
    }

    public function getUsers ()
    {
        if ($this->checkAuth($this->authHeader))
        {
            if (!empty($this->dataParam['id']))
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
        else
        {
            return $this->getServerAnswer(401, false, 'Unauthorized');
        }
    }

    private function getUserById ()
    {
        $data = $this->model->getOneUser($this->dataParam['id']);
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
        if ($this->checkAuth($this->authHeader))
        {
            if ($this->validate()) 
            {
                $password =  base64_decode($this->data->password);
                $this->data->password = password_hash($password, PASSWORD_DEFAULT);
       
                $aData = $this->model->createUser($this->data);
                if ($aData['result'])
                {
                    return $this->getServerAnswer(201, $aData['result'], $aData['message']);
                }
                else
                {
                    return $this->getServerAnswer(200, $aData['result'], $aData['message']);
                }
            }

            return $this->getServerAnswer(400, false, 'Bad Request');
        }
        else
        {
            return $this->getServerAnswer(401, false, 'Unauthorized');
        }
    }

    public function putUsers ()
    {
        if ($this->checkAuth($this->authHeader))
        {
            if ($this->validate() && $this->dataParam['id']) 
            {
                if ($this->model->updateUser($this->data, $this->dataParam['id']))
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
        else
        {
            return $this->getServerAnswer(401, false, 'Unauthorized');
        }
    }

    public function deleteUsers ()
    {
        if ($this->checkAuth($this->authHeader))
        {
            if ($this->dataParam['id']) 
            {
                if ($this->model->deleteUser($this->dataParam['id']) && $this->model_event->deleteUserEvent($this->dataParam['id']))
                {
                    return $this->getServerAnswer(200, true, 'user delete successful');
                }
                else
                {
                    return $this->getServerAnswer(200, false, 'error');
                }
            }

            return $this->getServerAnswer(400, false, 'Bad Request');
        }
        else
        {
            return $this->getServerAnswer(401, false, 'Unauthorized');
        }
    }

    private function checkAuth ($access_token)
    {
        return $this->model->checkAuth($access_token);
    }

}