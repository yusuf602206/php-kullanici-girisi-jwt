<?php 

class Usergateway{

    public PDO $conn;
    public function __construct(Database $db){
        $this->conn = $db->getConnection();
    }
    public function getByUsername(string $username):array|false{
        $sql='SELECT * FROM user WHERE username= :username';
        $stmt=$this->conn->prepare($sql);
        $stmt->bindValue(":username",$username, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}



?>