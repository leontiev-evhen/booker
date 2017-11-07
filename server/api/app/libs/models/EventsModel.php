<?php
namespace libs\models;
use libs\core\Model;
use \PDO;
use \DateTime;

class EventsModel extends Model
{
    private $table = 'events';
    private $time_start;
    private $time_end;

    public function getAll ($id_room, $month, $year) 
    {
		if (!function_exists('cal_days_in_month')) 
		{ 
			function cal_days_in_month($calendar, $month, $year) 
			{ 
				return date('t', mktime(0, 0, 0, $month, 1, $year)); 
			} 
		} 
		
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $time_start = strtotime($year.'/'.($month + 1).'/01');
        $time_end = strtotime($year.'/'.($month + 1).'/'.$days);

        $sql = $this->select([
                'id',
                'id_user',
                'id_room',
                'description',
                'time_start',
                'time_end',
                'parent_id',
                'create_at'])
            ->from(DB_PREFIX.$this->table)
            ->where(['time_start' => '<?>'], null, '>=')
            ->where(['time_end' => '<?>'], 'and', '<=')
            ->where(['id_room' => '<?>'], 'and')
            ->orderBy('time_start')
            ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);

        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
       
        $STH->bindParam(1, $time_start);
        $STH->bindParam(2, $time_end);
        $STH->bindParam(3, $id_room);

        if ($STH->execute())
        {
            return $STH->fetchAll();
        }  
        return false;
    }

    public function getOne ($id)
    {
        $sql = $this->select([
                'id',
                'id_user',
                'id_room',
                'description',
                'time_start',
                'time_end',
                'parent_id',
                'create_at'])
            ->from(DB_PREFIX.$this->table)
            ->where(['id' => '<?>'])
            ->limit(1)
            ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);

        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $STH->bindParam(1, $id);

        if ($STH->execute())
        {
            $data = $STH->fetch(PDO::FETCH_ASSOC);
          
            $sql = $this->select([
                    'id'])
                ->from(DB_PREFIX.$this->table)
                ->where(['parent_id' => '<?>'])
                ->execute();
            $sql = str_replace(["'<", ">'"], '', $sql);

            $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

            if ($data['parent_id'] == 0) 
            {
                $STH->bindParam(1, $id);
            }
            else
            {
                $STH->bindParam(1, $data['parent_id']);
            }
            
            $STH->execute();
            if ($STH->rowCount() > 0)
            {
                $data['child'] = $STH->rowCount();
            }
            
            
            return $data;
        }  
        return false;
    }

    public function deleteUserEvent ($id)
    {
        $sql = $this->delete()
            ->from(DB_PREFIX.$this->table)
            ->where(['id_user' => '<?>'])
            ->where(['time_start' => '<?>'], 'and', '>=')
            ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);

        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        
        $time = strtotime("now");
        $STH->bindParam(1, $id);
        $STH->bindParam(2, $time);

        if ($STH->execute())
        {
            return true;
        }  
        return false;
    }

    public function createEvent ($data)
    {
        $arrEvents = $this->getEventsDay($data);
        $result = $this->checkTimeEvents($data, $arrEvents);

        if ($result)
        {
            $arrResult = [];
            $arrResult[] = $this->inserEvent($data);
            $lastId = $this->connect->lastInsertId();
            
            if ($data->recurring)
            {
                $this->time_start = $data->time_start;
                $this->time_end = $data->time_end;
                $data->parent_id = $lastId;

                switch ($data->time_recurring) 
                {
                    case 'weekly':
                        for ($i = 1; $i <= $data->repeat; $i++) 
                        { 
                            $arrResult[] = $this->insertRecurringEvent($data, 'week', $i);
                        }
                        break;
                    
                    case 'bi-weekly':
                        for ($i = 1, $k = 2; $i <= $data->repeat; $i++, $k+=2) 
                        { 
                            $arrResult[] = $this->insertRecurringEvent($data, 'week', $k);
                        }
                        break;

                    case 'monthly':
                        $arrResult[] = $this->insertRecurringEvent($data, 'month');
                        break;
                }
            }
        }
        else
        {
            $arrResult = ['result' => false, 'message' => date("Y-m-d H:i", $data->time_start).' - '.date("H:i", $data->time_end).' the time has already been booked'];
        }

     
        if (count($arrResult) == 2)
        {
            return $arrResult;
        }
        else
        {   
            $resultMessage = '';
            foreach ($arrResult as $key => $value) {
                $resultMessage .= $value['message']."<br>";
            }
            return ['result' => true, 'message' => $resultMessage];
        }
    }

    private function insertRecurringEvent ($data, $time = 'week', $num = 1)
    {
        $period = "+ ".$num." ".$time;
        $data->time_start = strtotime($period, $this->time_start);
        $data->time_end = strtotime($period, $this->time_end);

        $arrEvents = $this->getEventsDay($data);
        if ($this->checkTimeEvents($data, $arrEvents))
        {
            return $this->inserEvent($data);
        }
        else
        {
           return ['result' => false, 'message' => date("Y-m-d H:i", $data->time_start).' - '.date("H:i", $data->time_end).' the time has already been booked'];
        }
    }

    private function inserEvent ($data)
    {
        $sql = $this->insert()
                ->from(DB_PREFIX.$this->table)
                ->values([
                'id_user'      => '<?>',
                'id_room'      => '<?>',
                'description'  => '<?>',
                'time_start'   => '<?>',
                'time_end'     => '<?>',
                'parent_id'    => '<?>',
                'create_at'    => '<?>'])
           ->execute();
            $sql = str_replace(["'<", ">'"], '', $sql);
            
            $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

            $STH->bindParam(1, $data->id_user);
            $STH->bindParam(2, $data->id_room);
            $STH->bindParam(3, $data->description);
            $STH->bindParam(4, $data->time_start);
            $STH->bindParam(5, $data->time_end);
            $STH->bindParam(6, $data->parent_id);
            $STH->bindParam(7, $this->date);
            if ($STH->execute())
            {
                return ['result' => true, 'message' => 'The event '.date("Y-m-d H:i", $data->time_start).' - '.date("H:i", $data->time_end).' has been added'];
            }  
            return ['result' => false, 'message' => 'Operation is failed'];
    }

    private function checkTimeEvents ($data, $arrEvents)
    {
        $result = false;
        $count = count($arrEvents);
        if ($count == 0) 
        {
            return true;
        }

        if ($count == 1) 
        {
            if ($data->time_end <= $arrEvents[0]['time_start'] && $data->time_start >= $arrEvents[0]['time_end']) 
            {
                return true;
            }
        }

        if ($data->time_end <= $arrEvents[0]['time_start'] || $data->time_start >= $arrEvents[$count - 1]['time_end']) 
        {
            return true;
        }

      
        for ($i = 0; $i < $count; $i++) 
        {
            if (!empty($arrEvents[$i+1]['time_start']))
            {
                if ($arrEvents[$i]['time_end'] <= $data->time_start && $arrEvents[$i+1]['time_start'] >= $data->time_end) 
                {
                    $result = true;
                }
            } 
            else
            {
                if ($arrEvents[$i]['time_end'] <= $data->time_start) 
                {
                    $result = true;
                }
            }
        }

        return $result;
    }

    private function getEventsDay ($data) 
    {
        $year = date("Y", $data->time_start);
        $month = date("m", $data->time_start);
        $day = date("d", $data->time_start);
        $id_room = $data->id_room;

        $time_start = strtotime($day.'-'.$month.'-'.$year.'08:00:00');
        $time_end = strtotime($day.'-'.$month.'-'.$year.'20:00:00');

        $sql = $this->select([
                'time_start',
                'time_end'])
            ->from(DB_PREFIX.$this->table)
            ->where(['time_start' => '<?>'], null, '>=')
            ->where(['time_end' => '<?>'], 'and', '<=')
            ->where(['id_room' => '<?>'], 'and')
            ->orderBy('time_start')
            ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);

        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
       
        $STH->bindParam(1, $time_start);
        $STH->bindParam(2, $time_end);
        $STH->bindParam(3, $id_room);

        if ($STH->execute())
        {
            return $STH->fetchAll();
        }  
        return false;
    }

    public function updateEvent ($data, $id) 
    {
        $arrEvents = $this->getEventsDay($data);
        if ($this->checkTimeEvents($data, $arrEvents))
        {
            $result = false;
            $date = new DateTime();
            $sql = $this->update()
                ->from(DB_PREFIX.$this->table)
                ->set([
                    'id_user' => '<?>',
                    'description' => '<?>',
                    'time_start' => '<?>',
                    'time_end' => '<?>'])
                ->where(['id' => '<?>'])
                ->limit(1)
                ->execute();
            $sql = str_replace(["'<", ">'"], '', $sql);
            
            $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            
            $STH->bindParam(1, $data->id_user);
            $STH->bindParam(2, $data->description);
            $STH->bindParam(3, $data->time_start);
            $STH->bindParam(4, $data->time_end);
            $STH->bindParam(5, $id);

            if ($STH->execute())
            {
                $result = true;
            }  

            if ($data->recurring)
            {
                if ($data->parent_id == 0)
                {
                    $parent_id = $id;
                }
                else
                {
                    $parent_id = $data->parent_id;
                }

                $arrData = $this->getRecurringEvents($data->time_end, $data->id_room, $parent_id);
                if ($arrData)
                {
                    $newHourStart = (int)date('H', $data->time_start);
                    $newMinStart = (int)date('i', $data->time_start);

                    $newHourEnd = (int)date('H', $data->time_end);
                    $newMinEnd = (int)date('i', $data->time_end);
                    $data->recurring = 0;
                    foreach ($arrData  as $key => $value) 
                    {
                        $date->setTimestamp($value['time_start']);
                        $date->setTime($newHourStart, $newMinStart);
                        $data->time_start = $date->getTimestamp();

                        $date->setTimestamp($value['time_end']);
                        $date->setTime($newHourEnd, $newMinEnd);
                        $data->time_end = $date->getTimestamp();

                        $this->updateEvent($data, $value['id']);
                    }
                }
            }
            return $result;
        }
        else
        {
            $arrResult = ['result' => false, 'message' => date("Y-m-d H:i", $data->time_start).' - '.date("H:i", $data->time_end).' the time has already been booked'];
        }


        
    }

    private function getRecurringEvents ($time, $id_room, $parent_id)
    {
        $sql = $this->select([
                'id',
                'id_user',
                'description',
                'time_start',
                'time_end'])
            ->from(DB_PREFIX.$this->table)
            ->where(['time_start' => '<?>'], null, '>=')
            ->where(['id_room' => '<?>'], 'and')
            ->where(['parent_id' => '<?>'], 'and')
            ->orderBy('time_start')
            ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);

        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
       
        $STH->bindParam(1, $time);
        $STH->bindParam(2, $id_room);
        $STH->bindParam(3, $parent_id);

        if ($STH->execute())
        {
            return $STH->fetchAll();
        } 
        return false; 
        
    }

    public function deleteEvents ($id)
    {
        $result = false;
        $sql = $this->delete()
            ->from(DB_PREFIX.$this->table)
            ->where(['id' => '<?>'])
            ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);

        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        
        $STH->bindParam(1, $id);

        if ($STH->execute())
        {
            $result = true;
        }  

        if ($data->recurring)
        {
            $result = true;
        }
        return $result;
    }

}