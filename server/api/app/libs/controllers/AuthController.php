<?php
namespace libs\controllers;
use libs\core\Controller;
use libs\models\UsersModel;

/**
* AuthController 
* extends Controller
* @author Leontiev Yevhen <leontevevgenii@gmail.com>  
*/

class AuthController extends Controller
{

    protected $model;
    protected $orders_model;
    private $headers;

    /**
     * array of the allowed params in the HTTP method
     */
    protected $rules = [
        'name'      => 'string',
        'email'     => 'string',
        'password'  => 'string',
    ];

    public function __construct ($params)
    {
        $this->model = new UsersModel();
        $this->headers = getallheaders();
    }

    /**
     * HTTP GET method
     *
     * @return array
     */
    public function getAuth () 
    {
        $data = $this->checkAuth($this->headers['Authorization']);
        if ($data)
        {
            return $this->getServerAnswer(200, true, 'authentication true', $data);
        }
        else
        {
            return $this->getServerAnswer(200, false, 'authentication false');
        }
    }

     /**
     * HTTP PUT method
     *
     * @return array
     */
    public function putAuth () 
    {
        if ($this->validate()) 
        {
            $password =  base64_decode($this->data->password);

            $aData = $this->model->checkUser ($this->data);
            
            if ($aData)
            {
                if (password_verify($password, $aData['password'])) {

                    //$aData = array_unique($aData);
                    unset($aData['password']);

                    $aData['access_token'] = $this->createToken($aData['id']);

                    return $this->getServerAnswer(200, true, 'user authentication successful', $aData);
                } 
                return $this->getServerAnswer(200, false, 'invalid email or password');
            }
            else
            {
                return $this->getServerAnswer(200, false, 'invalid email or password');
            }
        }

        return $this->getServerAnswer(400, false, 'Bad Request');
    }

    /**
     * HTTP POST method
     *
     * @return array
     */
    public function postAuth () 
    {
        if ($this->validate()) 
        {
            $password =  base64_decode($this->data->password);
            $this->data->password = password_hash($password, PASSWORD_DEFAULT);
   
            $aData = $this->model->registrationCustomer ($this->data);
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

    /**
     * check authorization user
     *
     * @return array
     */
    private function checkAuth ($access_token)
    {
        return $this->model->checkAuth($access_token);
    }

     /**
     * create token for new session
     *
     * @return string
     */
    private function createToken ($id) 
    {
        $data['access_token'] = $this->getToken();
        $data['id'] = $id;
        $this->model->updateToken($data);
        return $data['access_token'];
    }

     /**
     * generate new token
     *
     * @return string
     */
    private function getToken()
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet);

        for ($i = 0; $i < 32; $i++) 
        {
            $token .= $codeAlphabet[rand(0, $max-1)];
        }

        return $token;
    }
}
