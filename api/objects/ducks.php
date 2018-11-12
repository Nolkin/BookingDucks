<?php
class Ducks{
 
    // database connection and table name
    private $conn;
    private $table_name = "ducks";
 
    // object properties
    public $id;
    public $price;
    public $color;
    public $owner;
    public $created;
 
    public function __construct($db){
        $this->conn = $db;
    }
 
    // used by select all ducks
    public function read(){
     
        //select all data
        $query = "SELECT
                    id, price, color, owner
                FROM
                    " . $this->table_name . "
                ORDER BY
                    id";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
     
        return $stmt;
    }
    
    // used for get all ducks without owner
    function getAllEmpty(){
     
       //select all data
        $query = "SELECT color, COUNT(color) as sum FROM
                    " . $this->table_name . " where owner=0
                GROUP BY color";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
     
        return $stmt;
    }
    
    // used when client wants to get a duck and booking it
    function getDuck(){
     $result = -1;
     
     try {  
         //Using transaction for properly booking ducks
          $this->conn->beginTransaction();
          // query to read single record
            $query = "SELECT
                        id
                    FROM
                        " . $this->table_name . " 
                        WHERE color =  ?
                        AND owner =0
                        LIMIT 0, 1";
         
            // prepare query statement
            $stmt = $this->conn->prepare( $query );
         
            // bind id of duck to be selected
            $stmt->bindParam(1, $this->color);
         
            // execute query
            $stmt->execute();
         
            // get retrieved row
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
         
            // set values to object properties
            $this->id = $row['id'];
            
            // update query
            $query = "UPDATE
                        " . $this->table_name . "
                    SET
                        owner = :owner
                        
                    WHERE
                        id = :id";
         
            // prepare query statement
            $stmt = $this->conn->prepare($query);
         
            // sanitize
            $this->id=htmlspecialchars(strip_tags($this->id));
            $this->owner=htmlspecialchars(strip_tags($this->owner));
            
            //bind params     
            $stmt->bindParam(':owner', $this->owner);
            $stmt->bindParam(':id', $this->id);
         
            // execute the query
            if($stmt->execute()){
                $result= $this->id;
            }
              $this->conn->commit();
              
            } catch (Exception $e) {
              $this->conn->rollBack();
              echo "Ошибка: " . $e->getMessage();
            }

        return $result;
    }
}
?>