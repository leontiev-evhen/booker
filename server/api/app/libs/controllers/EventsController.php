<?php
namespace libs\controllers;
use libs\core\Controller;
use libs\models\EventsModel;
use \Exception;

class EventsController extends Controller
{
	protected $model;
	private $headers;
	private $customer;
	private $customer_id;

	protected $rules = [
		'user_id' 		=> 'integer',
		'payment_id'	=> 'integer',
		'id_status'		=> 'integer',
		'id'			=> 'integer',
        'books'         => 'array',
        'sum'           => 'integer'
	];


    public function __construct ($params)
    {
        $this->model = new EventsModel();
        $this->headers = getallheaders();

        if (!empty($params))
        {
            $param = explode('/', $params);
            $this->id = isset($param[0]) ? (int)$param[0] : null;
            $this->customer = isset($param[1]) ? (string)$param[1] : null;
        }
    }

    public function getEvents ()
    {
    	if (!empty($this->id))
        {       
            return $this->getEventById();
        }

        if (!empty($this->customer))
        {       
            return $this->getEventsByCustomer();
        }
        
        $data = $this->model->getAll();
        if (!empty($data))
        {
            return $this->getServerAnswer(200, true, 'orders successfully received', $data);
        }
        return $this->getServerAnswer(500, false, 'Internal Server Error');
    }

    public function getEventById ()
    {
        $data = $this->model->getOne($this->id);
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
			if ($this->model->updateEvent($this->data, $this->id))
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