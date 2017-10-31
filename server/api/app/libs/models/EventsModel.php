<?php
namespace libs\models;
use libs\core\Model;
use \PDO;

class OrdersModel extends Model
{
    private $table = 'orders';

    public function getAllOrders ()
    {
        $sql = $this->select([
                'orders.id',
                'customers.id as id_customer',
                'customers.name as CUSTOMER_NAME',
                'status_order.name as STATUS_NAME',
                'sum',
                'id_status',
                'id_payment',
                'orders.create_at',
            ])
            ->from($this->table)
            ->join('left', 'customers', 'customers.id = orders.id_customer')
            ->join('left', 'status_order', 'status_order.id = orders.id_status')
            ->orderBy('orders.create_at', 'desc')
            ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);
        
        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        if ($STH->execute())
        {
            return $STH->fetchAll();
        }
    }

    public function getOneOrder ($id)
    {
        $sql = $this->select([
                'orders.id',
                'sum',
                'id_status',
                'id_payment',
                'name as NAME_PAYMENT'])
            ->from($this->table)
            ->join('left', 'payment_system ON payment_system.id = orders.id_payment')
            ->where(['orders.id' => "<:id>"])
			->limit(1)
            ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);
        //echo $sql;
        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        if ($STH->execute([':id' => $id]))
        {
            $data =  $STH->fetch();
        }

        $sql = $this->select([
                'id',
                'name',
                'price'])
            ->from('books')
            ->join('left', 'book2order ON book2order.id_book = books.id')
            ->where(['book2order.id_order' => "<:id>"])
             ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);

        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        if ($STH->execute([':id' => $id]))
        {
            $data['books'] = $STH->fetchAll();
        }

        return $data;
    }

	public function createOrder ($data)
    {
        $res = false;
        $sql = $this->insert()
            ->from($this->table)
            ->values([
            'sum' => '<?>',
            'id_customer' => '<?>',
            'id_status' => '<?>',
            'id_payment' => '<?>',
            'create_at' => '<?>'])
           ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);
        
        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $status = 1;
        $STH->bindParam(1, $data->sum);
        $STH->bindParam(2, $data->id_customer);
        $STH->bindParam(3, $status);
        $STH->bindParam(4, $data->payment_id);
        $STH->bindParam(5, $this->date);

        $STH->execute();
        if (!empty($data->books))
        {
            $id_order = $this->connect->lastInsertId();
            foreach ($data->books as $value) {
                $sql = $this->insert()
                    ->from('book2order')
                    ->values([
                        'id_order'    => '<?>',
                        'id_book'  => '<?>',
                        'count'  => '<?>'])
                    ->execute();
                $sql = str_replace(["'<", ">'"], '', $sql);
                
                $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

                $STH->bindParam(1, $id_order);
                $STH->bindParam(2, $value->id_book);
                $STH->bindParam(3, $value->count);

                if ($STH->execute())
                {
                    $res = true;
                }
            }


        } 
        return $res;
    }

    public function updateOrderStatus ($data, $id)
    {
        $sql = $this->update()
            ->from($this->table)
            ->set(['id_status' => '<?>',])
            ->where(['id' => '<?>'])
			->limit(1)
            ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);
        
        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        
        $STH->bindParam(1, $data->id_status);
        $STH->bindParam(2, $id);

        if ($STH->execute())
        {
            return true;
        }  
        return false;
    }

    public function getOrdersByCustomer ($token)
    {
        $sql = $this->select([
                'orders.id',
                'orders.create_at',
                'orders.sum',
                'status_order.name as STATUS_NAME'])
            ->from($this->table)
            ->join('left', 'customers', 'customers.id = orders.id_customer')
            ->join('right', 'status_order', 'status_order.id = orders.id_status')
            ->where(['customers.token' => "<:token>"])
            ->orderBy('orders.create_at', 'desc')
           ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);
        
        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        if ($STH->execute([':token' => $token]))
        {
            return $STH->fetchAll();
        }  
    }

    public function getOrdersByCustomerId ($id)
    {
        $sql = $this->select([
                'orders.id',
                'orders.create_at',
                'orders.sum',
                'status_order.name as STATUS_NAME'])
            ->from($this->table)
            ->join('left', 'customers', 'customers.id = orders.id_customer')
            ->join('right', 'status_order', 'status_order.id = orders.id_status')
            ->where(['customers.token' => "<:token>"])
           ->execute();
        $sql = str_replace(["'<", ">'"], '', $sql);
        
        $STH = $this->connect->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        if ($STH->execute([':token' => $id]))
        {
            return $STH->fetchAll();
        }  
    }
}