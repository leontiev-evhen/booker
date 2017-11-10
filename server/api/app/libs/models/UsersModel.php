<?php
namespace libs\models;
use libs\core\Model;
use \PDO;

/**
* UsersModel is the base class for work with the table of users 
* extends class Model
* @author Leontiev Yevhen <leontevevgenii@gmail.com>  
*/

class UsersModel extends Model
{
	private $table = 'users';


    /**
     * get all users
     * @return array|boolean
     * */
    public function getAllUsers ()
    {
        $sql = $this->select([
                'id',
                'name',
                'email',
                'id_role',
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

    /**
     * get one user
     * @param integer $id
     * @return array|boolean
     * */
    public function getOneUser ($id)
    {
        $sql = $this->select([
                'id',
                'name',
                'email',
                'id_role',
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

    /**
     * create user
     * @param object $data
     * @return array
     * */
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
                'id_role'   => '<?>',
                'create_at' => '<?>'
            ])
           ->execute();
	        $sql = str_replace(["'<", ">'"], '', $sql);
	        
	        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

	        $STH->bindParam(1, $data->name);
	        $STH->bindParam(2, $data->email);
	        $STH->bindParam(3, $data->password);
            $STH->bindParam(4, $data->id_role);
	        $STH->bindParam(5, $this->date);
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

     /**
     * upadte user
     * @param object $data
     * @param integer $id
     * @return array
     * */
    public function updateUser ($data, $id)
    {
		if (!$this->checkUniqueEmailUpdate($data->email, $id))
    	{
			$sql = $this->update()
				->from(DB_PREFIX.$this->table)
				->set([
					'name' => '<?>',
					'email' => '<?>',
					'id_role' => '<?>'])
				->where(['id' => '<?>'])
				->limit(1)
				->execute();
			$sql = str_replace(["'<", ">'"], '', $sql);
			
			$STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			
			$STH->bindParam(1, $data->name);
			$STH->bindParam(2, $data->email);
			$STH->bindParam(3, $data->id_role);
			$STH->bindParam(4, $id);

			if ($STH->execute())
			{
				return ['result' => true, 'message' => 'user update successful'];
			}  
			return ['result' => false, 'message' => 'error'];
		}
		else
    	{
    		return ['result' => false, 'message' => 'Email already exist'];
    	}
    }

    /**
     * delete user
     * @param integer $id
     * @return boolean
     * */
    public function deleteUser ($id)
    {
        $sql = $this->select([
                'id'])
            ->from(DB_PREFIX.$this->table)
            ->where(['id_role' => '<?>'])
            ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);
        
        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $id_role = ROLE;
        $STH->bindParam(1, $id_role);
        $STH->execute();
        if ($STH->rowCount() >= 1)
        {
            $sql = $this->delete()
                ->from(DB_PREFIX.$this->table)
                ->where(['id' => '<?>'])
                ->where(['id_role' => '<?>'], 'and', '!=')
                ->limit(1)
                ->execute();
            $sql = str_replace(["'<", ">'"], '', $sql);

            $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $id_role = ROLE;
            $STH->bindParam(1, $id);
            $STH->bindParam(2, $id_role);

            if ($STH->execute())
            {
                return true;
            }  
            return false;
        }  
        return false;
    }

    /**
     * check user from authorization
     * @param object $data
     * @return array|boolean
     * */
    public function checkUser ($data)
    {
    	$sql = $this->select([
    			'id',
    			'name',
    			'email',
                'id_role',
    			'password'])
            ->from(DB_PREFIX.$this->table)
            ->where(['email' => "<:email>"])
           ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);
       
        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        if ($STH->execute([':email' => $data->email]))
        {
            return $STH->fetch();
        }  
        return false;
    }

    /**
     * check equal name email when create user
     * @param string $email
     * @return boolean
     * */
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

    /**
     * check equal name email when update user
     * @param string $email
     * @param integer $id
     * @return boolean
     * */
    private function checkUniqueEmailUpdate ($email, $id)
    {
        $sql = $this->select([
                'email'])
            ->from(DB_PREFIX.$this->table)
            ->where(['email' => "<?>"])
            ->where(['id' => "<?>"], 'and', '!=')
            ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);
        
        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $STH->bindParam(1, $email);
        $STH->bindParam(2, $id);
        $STH->execute();
       
        if ($STH->rowCount() > 0)
        {
            return true;
        }  
        return false;
    }

     /**
     * update user token
     * @param array $data
     * @return boolean
     * */
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

    /**
     * check authorization user
     * @param string $token
     * @return array|boolean
     * */
    public function checkAuth ($token)
    {
    	$sql = $this->select([
    			'id',
                'id_role'])
            ->from(DB_PREFIX.$this->table)
            ->where(['token' => "<:token>"])
            ->where(['token_create_at' => "<:token_create_at>"], "and", ">=")
           	->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);
        
        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $date = strtotime('now');

        $STH->execute([':token' => $token, ':token_create_at' => $date]);
       
        if ($STH->rowCount() > 0)
        {
            return $STH->fetch(PDO::FETCH_ASSOC);
        }  
        return false;
    }
}
