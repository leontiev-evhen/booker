<?php
namespace libs\models;
use libs\core\Model;
use \PDO;

class EventsModel extends Model
{
    private $table = 'events';

    public function getAll ($room, $month, $year) 
    {
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
            ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);

        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
       
        $STH->bindParam(1, $time_start);
        $STH->bindParam(2, $time_end);
        $STH->bindParam(3, $room);

        if ($STH->execute())
        {
            return $STH->fetchAll();
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

}