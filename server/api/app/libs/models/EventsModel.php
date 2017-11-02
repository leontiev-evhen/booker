<?php
namespace libs\models;
use libs\core\Model;
use \PDO;

class EventsModel extends Model
{
    private $table = 'events';

    public function getAll ($time_start, $time_end) {

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
            ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);

        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
       
        $STH->bindParam(1, $time_start);
        $STH->bindParam(2, $time_end);

        if ($STH->execute())
        {
            return $STH->fetchAll();
        }  
        return false;
    }
}