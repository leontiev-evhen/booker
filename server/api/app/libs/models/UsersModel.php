<?php
namespace libs\models;
use libs\core\Model;
use \PDO;

class UsersModel extends Model
{
	private $table = 'users';

    public function getAll ()
    {
        $sql = $this->select([
                'id',
                'name',
                'create_at'])
            ->from(DB_PREFIX.$this->table)
            ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);
        
        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
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
                'name',
                'email',
                'discaunt',
                'status',
                'token',
                'create_at'])
            ->from(DB_PREFIX.$this->table)
            ->where(['id' => "<:id>"])
			->limit(1)
            ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);
        
        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        if ($STH->execute([':id' => $id]))
        {
            return $STH->fetch(PDO::FETCH_ASSOC);
        }  
        return false;
    }
	
	public function getToken ($token)
    {
        $sql = $this->select([
                'id',
                'discaunt'])
            ->from(DB_PREFIX.$this->table)
            ->where(['token' => "<:token>"])
			->limit(1)
            ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);
        
        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        if ($STH->execute([':token' => $token]))
        {
            return $STH->fetch(PDO::FETCH_ASSOC);
        }  
        return false;
    }

	public function createUser ($data)
    {
    	if (!$this->checkUniqueEmail($data->email))
    	{
    		$sql = $this->insert()
            ->from(DB_PREFIX.$this->table)
            ->values([
                'name' 		 => '<?>',
                'email' 	 => '<?>',
                'password' 	 => '<?>',
                'discaunt' 	 => '<?>',
                'status'     => '<?>',
                'id_role'   => '<?>',
                'create_at' => '<?>'
            ])
           ->execute();
	        $sql = str_replace(["'<", ">'"], '', $sql);
	        
	        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	        $role = ROLE;
	        $STH->bindParam(1, $data->name);
	        $STH->bindParam(2, $data->email);
	        $STH->bindParam(3, $data->password);
	        $STH->bindParam(4, $data->discaunt);
            $STH->bindParam(5, $data->status);
            $STH->bindParam(6, $role);
	        $STH->bindParam(7, $this->date);
	        if ($STH->execute())
	        {
	            return ['result' => true, 'message' => 'user was added successful'];
	        }  
	        return ['result' => false, 'message' => 'Operation is failed'];
    	}
    	else
    	{
    		return ['result' => false, 'message' => 'Email already exist'];
    	}
    }

    public function updateUser ($data, $id)
    {
        $sql = $this->update()
            ->from(DB_PREFIX.$this->table)
            ->set([
                'name' => '<?>',
                'email' => '<?>',
                'discaunt' => '<?>',
                'status' => '<?>'])
            ->where(['id' => '<?>'])
			->limit(1)
            ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);
        
        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        
        $STH->bindParam(1, $data->name);
        $STH->bindParam(2, $data->email);
        $STH->bindParam(3, $data->discaunt);
        $STH->bindParam(4, $data->status);
        $STH->bindParam(5, $id);

        if ($STH->execute())
        {
            return true;
        }  
        return false;
    }

    public function checkUser ($data)
    {
    	$sql = $this->select([
    			'id',
    			'name',
    			'email',
                'discaunt',
    			'password'])
            ->from(DB_PREFIX.$this->table)
            ->where(['email' => "<:email>"])
            ->where(['status' => 1], 'and')
           ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);
       
        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        if ($STH->execute([':email' => $data->email]))
        {
            return $STH->fetch();
        }  
        return false;
    }

    public function checkAdmin ($data)
    {
        $sql = $this->select([
                'id',
                'name',
                'email',
                'discaunt',
                'password'])
            ->from(DB_PREFIX.$this->table)
            ->where(['email' => "<:email>"])
            ->where(['status' => 1], 'and')
            ->where(['id_role' => 1], 'and')
           ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);
       
        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        if ($STH->execute([':email' => $data->email]))
        {
            return $STH->fetch();
        }  
        return false;
    }

    private function checkUniqueEmail ($email)
    {
    	$sql = $this->select([
    			'email'])
            ->from(DB_PREFIX.$this->table)
            ->where(['email' => "<:email>"])
           	->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);
        
        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $STH->execute([':email' => $email]);
       
        if ($STH->rowCount() > 0)
        {
            return true;
        }  
        return false;
    }

    public function updateToken ($data)
    {
		$sql = $this->update()
	        ->from(DB_PREFIX.$this->table)
	        ->set([
	        	'token' => '<?>',
	         	'token_create_at' => '<?>'])
	        ->where(['id' => '<?>'])
			->limit(1)
	       	->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);
        
        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        
        $date = date("Y-m-d H:i:s", strtotime('1 hour'));
        $date = strtotime($date);
        $STH->bindParam(1, $data['access_token']);
        $STH->bindParam(2, $date);
        $STH->bindParam(3, $data['id']);

        if ($STH->execute())
        {
            return true;
        }  
        return false;
    }

    public function checkAuth ($token)
    {
    	$sql = $this->select([
    			'id'])
            ->from($DB_PREFIX.this->table)
            ->where(['token' => "<:token>"])
            ->where(['token_create_at' => "<:token_create_at>"], "and", ">=")
           	->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);
        
        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $date = strtotime('now');

        $STH->execute([':token' => $token, ':token_create_at' => $date]);
       
        if ($STH->rowCount() > 0)
        {
            return true;
        }  
        return false;
    }
}
