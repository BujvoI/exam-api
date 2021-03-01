<?php

class DBOperation
{
    private $conn;

    //Constructor
    function __construct()
    {
        require_once dirname(__FILE__) . '/db.php';
        require_once dirname(__FILE__) . '/DBConnect.php';
        // opening db connection
        $db = new DBConnect();
        $this->conn = $db->connect();
    }

    public function newProduct($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO products (name, article, price, maker, category, weight, count) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssss", $data['name'], $data['article'], $data['price'], $data['maker'], $data['category'], $data['weight'], $data['count']);
        $result = $stmt->execute();
        $stmt->close();
        if ($result) {
            return json_encode(['success'=>true,'data'=>['id'=>mysqli_insert_id($this->conn)]]);
        } else {
            return json_encode(['success'=>false,'error'=>mysqli_error($this->conn)]);
        }
    }

    public function updateProduct($data,$id)
    {
        $stmt = $this->conn->prepare("UPDATE products SET name= ?, article = ?, price = ?, maker = ?, category = ?, weight = ?, count = ?,updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->bind_param("sssssssi", $data['name'], $data['article'], $data['price'], $data['maker'], $data['category'], $data['weight'], $data['count'], $id);
        $result = $stmt->execute();
        $stmt->close();
        if ($result) {
            return json_encode(['success'=>true,'data'=>[]]);
        } else {
            return json_encode(['success'=>false,'error'=>mysqli_error($this->conn)]);
        }
    }

    public function updateProductCount($id,$plusminus)
    {
        $stmt = $this->conn->prepare("UPDATE products SET count = ".($plusminus?"count + 1":"count - 1")." WHERE id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        if ($result) {
            return json_encode(['success'=>true,'data'=>[]]);
        } else {
            return json_encode(['success'=>false,'error'=>mysqli_error($this->conn)]);
        }
    }

    public function getProducts($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM products ".($id!==null?" WHERE id = ?":"")." ORDER BY name");
        if($id!==null and $id!== 0){
            $stmt->bind_param("i", $id);
        }
        $stmt->execute();
        if($id!==null)
            $result = $stmt->get_result()->fetch_assoc();
        else
            $result = $stmt->get_result()->fetch_all();
        $stmt->close();
        if(!mysqli_error($this->conn)){
            if (!empty($result)) {
                return json_encode(['success'=>true,'data'=>$result]);
            } else {
                return json_encode(['success'=>true,'data'=>$result]);
            }
        }else{
            return json_encode(['success'=>false,'error'=>mysqli_error($this->conn)]);
        }

    }

    public function deleteProduct($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        if ($result) {
            return json_encode(['success'=>true,'data'=>'the Product was successfully deleted']);
        } else {
            return json_encode(['success'=>false,'error'=>mysqli_error($this->conn)]);
        }
    }

}