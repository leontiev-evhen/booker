<?php
namespace libs\controllers;
use libs\core\Controller;
use libs\models\RoomsModel;


class RoomsController extends Controller
{

    protected $model;

    public function __construct ($params)
    {
        $this->model = new RoomsModel();
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

}