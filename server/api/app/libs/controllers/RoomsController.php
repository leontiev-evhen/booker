<?php
namespace libs\controllers;
use libs\core\Controller;
use libs\models\RoomsModel;


class RoomsController extends Controller
{

    protected $model;
    private $id;

    public function __construct ($params)
    {
        $this->model = new RoomsModel();
        
        if ($params)
        {
        	$param = explode('/', $params);
    		$this->id = (int)$param[0];
        }
    }

    public function getRooms ()
    {
    	$data = $this->model->getAllRooms();
    	if (!empty($data))
    	{
    		return $this->getServerAnswer(200, true, 'rooms successfully received', $data);
    	}
    	return $this->getServerAnswer(500, false, 'Internal Server Error');
    }



    public function postRooms ()
    {
    	if ($this->validate()) 
        {
            $data = $this->model->createAuthor($this->data);
            if ($data)
            {
                return $this->getServerAnswer(200, true, 'room create successful');
            }
            else
            {
                return $this->getServerAnswer(200, false, 'some error');
            }
        }

        return $this->getServerAnswer(400, false, 'Bad Request');
    }

    public function putRooms ()
    { 
    	if ($this->validate() && $this->id) 
        {
            if ($this->model->updateAuthor($this->data, $this->id))
            {
                return $this->getServerAnswer(200, true, 'room upadate successful');
            }
            else
            {
                return $this->getServerAnswer(200, false, 'some error');
            }
        }

        return $this->getServerAnswer(400, false, 'Bad Request');
    }

    public function deleteRooms ()
    {
    	if ($this->id)
    	{
    		if ($this->model->deleteAuthor($this->id))
	        {
	            return $this->getServerAnswer(200, true, 'room delete successful');
	        }
	        else
	        {
	            return $this->getServerAnswer(200, false, 'some error');
	        }
    	}

        return $this->getServerAnswer(400, false, 'Bad Request');
    }
}