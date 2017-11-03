<?php
namespace libs\controllers;
use libs\core\Controller;
use libs\models\EventsModel;
use \Exception;

class EventsController extends Controller
{
	protected $model;
	private $headers;
	private $time_start;
	private $time_end;

	protected $rules = [
        'time_start' => 'integer',
		'time_end'   => 'integer',
	];

    protected $params = ['id', 'month', 'year', 'room'];

    public function __construct ($params)
    {
        $this->model = new EventsModel();
        $this->headers = getallheaders();

        if (!empty($params))
        {
            $this->validateParams($params);
        }
    }

    public function getEvents ()
    {
    	if (!empty($this->dataParam['id']))
        {       
            return $this->getEventById();
        }

        $data = $this->model->getAll($this->dataParam['room'], $this->dataParam['month'], $this->dataParam['year']);
        if (!empty($data))
        {
            return $this->getServerAnswer(200, true, 'events successfully received', $data);
        }
        return $this->getServerAnswer(200, false, 'events not found');
    }

    public function getEventById ()
    {
        $data = $this->model->getOne($this->dataParam['id']);
        $data['status_orders'] = $this->status_model->getAll();

        if (!empty($data))
        {
            return $this->getServerAnswer(200, true, 'order successfully received', $data);
        }
        else
        {
            return $this->getServerAnswer(500, false, 'Internal Server Error');
        }
    }

    public function getEventsByCustomer ()
    {
    	$data = $this->model->getOrdersByCustomer($this->headers['Authorization']);
    	if ($data)
    	{
    		return $this->getServerAnswer(200, true, 'orders successfully received', $data);
    	}
    	else
    	{
    		return $this->getServerAnswer(500, false, 'Internal Server Error');
    	}
    }

  	public function postEvents ()
	{

		if ($this->validate()) 
		{
            $customer = $this->model_customer->getCustomerToken($this->headers['Authorization']);
            $this->data->id_customer = $customer['id'];
            
			if ($this->model->createEvent($this->data))
			{
				return $this->getServerAnswer(200, true, 'order was added successful');
			}
			else
			{
				return $this->getServerAnswer(500, false, 'Internal Server Error');
			}
		}

		return $this->getServerAnswer(400, false, 'Bad Request');  
	}

	public function putEvents ()
	{
		if ($this->validate()) 
		{
			if ($this->model->updateEvent($this->data, $this->dataParam['id']))
			{
				return $this->getServerAnswer(200, true, 'order status change successful');
			}
			else
			{
				return $this->getServerAnswer(500, false, 'Internal Server Error');
			}
		}
		return $this->getServerAnswer(400, false, 'Bad Request');  
	}
}