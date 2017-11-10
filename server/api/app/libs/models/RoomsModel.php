<?php
namespace libs\models;
use libs\core\Model;
use \PDO;

/**
* RoomsModel is the base class for work with the table of rooms 
* extends class Model
* @author Leontiev Yevhen <leontevevgenii@gmail.com>  
*/

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