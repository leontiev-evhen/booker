<?php
namespace libs\controllers;
use libs\core\Controller;
use libs\models\EventsModel;
use libs\models\UsersModel;
use \Exception;

/**
* EventsController
* extends Controller 
* @author Leontiev Yevhen <leontevevgenii@gmail.com>  
*/

class EventsController extends Controller
{
	protected $model;
	private $headers;
	private $authHeader;

    /**
     * array of the allowed params in the HTTP method
     */
	protected $rules = [
        'time_start'     => 'integer',
		'time_end'       => 'integer',
        'id_user'        => 'integer',
        'id_room'        => 'integer',
        'description'    => 'string',
        'recurring'      => 'integer',
        'time_recurring' => 'string',
        'repeat'         => 'integer',
        'parent_id'      => 'integer'
	];
    /**
     * array of the allowed URL parameters
     */
    protected $params = ['id', 'month', 'year', 'room'];

    public function __construct ($params)
    {
        $this->model = new EventsModel();
        $this->model_user = new UsersModel();
        $this->headers = getallheaders();
        

        if (!empty($params))
        {
            $this->validateParams($params);
        }
    }

    
    /**
     * HTTP GET method
     *
     * @return array
     */
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

    
    /**
     * get one event
     *
     * @return array
     */
    public function getEventById ()
    {
        $data = $this->model->getOne($this->dataParam['id']);
        $user = $this->getUserInfo();

        if (!empty($data) && ($data['id_user'] == $user['id'] || $user['id_role'] == ROLE))
        {
            return $this->getServerAnswer(200, true, 'event successfully received', $data);
        }
        else
        {
            return $this->getServerAnswer(403, false, 'Forbbiden');
        }
    }

    /**
     * HTTP POST method
     *
     * @return array
     */
  	public function postEvents ()
	{
		if ($this->validate()) 
		{
            $checkData = $this->validator($this->data);
            
            if ($checkData['result'])
            {
                $data = $this->model->createEvent($this->data);

                if ($data['result'])
                {
                    return $this->getServerAnswer(200, $data['result'], $data['message']);
                }
                else
                {
                    return $this->getServerAnswer(200, $data['result'], $data['message']);
                }
            }
            else
            {
                return $this->getServerAnswer(200, $checkData['result'], $checkData['message']);
            }
           
		}

		return $this->getServerAnswer(400, false, 'Bad Request');  
	}

     /**
     * HTTP PUT method
     *
     * @return array
     */
	public function putEvents ()
	{
		if ($this->validate()) 
		{
            $checkData = $this->validator($this->data);
            
            if ($checkData['result'])
            {
                $data = $this->model->updateEvent($this->data, $this->dataParam['id']);
    			
                if ($data['result'])
    			{
    				return $this->getServerAnswer(200, $data['result'], $data['message']);
    			}
    			else
    			{
    				return $this->getServerAnswer(200, $data['result'], $data['message']);
    			}
            }
            else
            {
                return $this->getServerAnswer(200, $checkData['result'], $checkData['message']);
            }
		}
		return $this->getServerAnswer(400, false, 'Bad Request');  
	}

    /**
     * HTTP DELETE method
     *
     * @return array
     */
    public function deleteEvents ()
    {
        if ($this->dataParam['id']) 
        {
            if ($this->model->deleteEvent ($this->dataParam['id'], $this->headers['Recurring']))
            {
                return $this->getServerAnswer(200, true, 'The event has been deleted');
            }
            else
            {
                return $this->getServerAnswer(200, false, 'error');
            }
        }

        return $this->getServerAnswer(400, false, 'Bad Request');
    }

    /**
     * validate the incoming parameters
     *
     * @param object $data
     * @return array
     */
    private function validator ($data) 
    {
        
        $current_time = strtotime("now");
        
        if ($data->recurring && !empty($data->time_recurring)) 
        {
            switch ($data->time_recurring) 
            {
                case 'weekly':
                    if ($data->repeat > 4 || $data->repeat <= 0) {
                        return ['result' => false, 'message' => 'recurring event weekly in not valid, should be max 4'];
                    }
                    break;
                case 'bi-weekly':
                    if ($data->repeat > 2 || $data->repeat <= 0) {
                        return ['result' => false, 'message' => 'recurring event bi-weekly in not valid, should be max 2'];
                    }
                    break;
                case 'monthly':
                    if ($data->repeat > 1 || $data->repeat <= 0) {
                        return ['result' => false, 'message' => 'recurring event monthly in not valid, should be max 1'];
                    }
                    break;
                default:
                    return ['result' => false, 'message' => 'recurring event do not exist'];
                    break;
            }
        }

        if ($data->time_start >= $data->time_end) 
        {
            return ['result' => false, 'message' => 'Time is not valid'];
        }

        if ($data->time_start < $current_time) 
        {
            return ['result' => false, 'message' => 'Time params are not valid'];
        }

        return ['result' => true, 'message' => 'data is valid'];
    }

    /**
     * get info of the user
     * @return array|boolean
     */
    private function getUserInfo ()
    {
        $authHeader = isset($this->headers['Authorization']) ? $this->headers['Authorization'] : false;
        if ($authHeader)
        {
            return $this->model_user->checkAuth($authHeader);
        }
        return false;
       
    }

}