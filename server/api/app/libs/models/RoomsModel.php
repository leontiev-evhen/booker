<?php
namespace libs\models;
use libs\core\Model;
use \PDO;

class RoomsModel extends Model
{
    private $table = 'rooms';

    /**
     * get all rooms
     * @return array|boolean
     * */
    public function getAllRooms ()
    {
        $sql = $this->select([
                'id',
                'name',])
            ->from(DB_PREFIX.$this->table)
            ->limit(NUM_SHOW_ROOM)
           	->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);
        
        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        if ($STH->execute())
        {
            return $STH->fetchAll();
        }  
        return false;
    }
}