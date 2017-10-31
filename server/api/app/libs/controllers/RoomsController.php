<?php
namespace libs\controllers;
use libs\core\Controller;
use libs\models\AuthorsModel;


class AuthorsController extends Controller
{

    protected $model;
    private $id;
    protected $rules = [
        'name'      => 'string'
    ];

    public function __construct ($params)
    {
        $this->model = new AuthorsModel();
        
        if ($params)
        {
        	$param = explode('/', $params);
    		$this->id = (int)$param[0];
        }
    }

    public function getAuthors ($params = false)
    {
    	if (!empty($this->id))
    	{    	
    		return $this->getAuthorById();
    	}

    	$data = $this->model->getAllAuthors();
    	if (!empty($data))
    	{
    		return $this->getServerAnswer(200, true, 'authors successfully received', $data);
    	}
    	return $this->getServerAnswer(500, false, 'Internal Server Error');
    }

    public function getAuthorById ()
    {
 		$data = $this->model->getOneAuthor($this->id);
		if (!empty($data))
		{
			return $this->getServerAnswer(200, true, 'author successfully received', $data);
		}
		else
		{
			return $this->getServerAnswer(500, false, 'Internal Server Error');
		}
    }

    public function postAuthors ()
    {
    	if ($this->validate()) 
        {
            $data = $this->model->createAuthor($this->data);
            if ($data)
            {
                return $this->getServerAnswer(200, true, 'author create successful');
            }
            else
            {
                return $this->getServerAnswer(200, false, 'some error');
            }
        }

        return $this->getServerAnswer(400, false, 'Bad Request');
    }

    public function putAuthors ()
    { 
    	if ($this->validate() && $this->id) 
        {
            if ($this->model->updateAuthor($this->data, $this->id))
            {
                return $this->getServerAnswer(200, true, 'author upadate successful');
            }
            else
            {
                return $this->getServerAnswer(200, false, 'some error');
            }
        }

        return $this->getServerAnswer(400, false, 'Bad Request');
    }

    public function deleteAuthors ()
    {
    	if ($this->id)
    	{
    		if ($this->model->deleteAuthor($this->id))
	        {
	            return $this->getServerAnswer(200, true, 'author delete successful');
	        }
	        else
	        {
	            return $this->getServerAnswer(200, false, 'some error');
	        }
    	}

        return $this->getServerAnswer(400, false, 'Bad Request');
    }
}