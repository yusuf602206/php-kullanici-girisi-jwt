<?php 

class StudentGateWay{

    private PDO $conn;
    public function __construct(Database $db){
        $this->conn = $db->getConnection();
    }
    public function getAllStudents(){
        $query="SELECT * FROM students";
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }
    public function getStudentById($id){
        $querry="SELECT * FROM students WHERE id=:id LIMIT 1";
        $stmt=$this->conn->prepare($querry);
        $stmt->bindParam(":id",$id,\pdo::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    public function AddStudent($name,$age,$gender)
    {
        $sql="INSERT INTO students(name,age,gender)VALUES(:name,:age,:gender)";
        $stmt=$this->conn->prepare($sql);
        $stmt->bindParam(":name",$name);
        $stmt->bindParam(":age",$age,\PDO::PARAM_INT);
        $stmt->bindPAram(":gender",$gender);
        $stmt->execute();

        return $this->getStudentById($this->conn->lastInsertId());


    }
    public function UpdateStudents($id,$name,$age,$gender){
        $sql = "UPDATE students SET name = :name, age = :age, gender = :gender WHERE id = :id";
        $stmt=$this->conn->prepare($sql);
        $stmt->bindParam(":id",$id);
        $stmt->bindParam("name",$name);
        $stmt->bindParam(":age",$age,\PDO::PARAM_INT);
        $stmt->bindPAram(":gender", $gender,\PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();

    }
    public function deleteStudent($id){
        $query="DELETE FROM students WHERE id=:id";
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(":id",$id,PDO::PARAM_INT);
        return $stmt->execute();
    }
}
 ?>   