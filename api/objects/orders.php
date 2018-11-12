<?php
class Order{
 
    // database connection and table name
    private $conn;
    private $table_name = "orders";
 
    // object properties
    public $id;
    public $owner;
    public $ducks=[];
    public $created;
 
    public function __construct($db){
        $this->conn = $db;
    }
 
    // create order
    function create(&$reason){
     
        // sanitize
        $this->owner=htmlspecialchars(strip_tags($this->owner));
        $this->created=htmlspecialchars(strip_tags($this->created));
        
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    owner=:owner, created=:created";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
                  
        // bind values
        $stmt->bindParam(":owner", $this->owner);
        $stmt->bindParam(":created", $this->created);
     
        // execute query
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        
        return false;
         
    }
    
    // delete the order
    function delete(){
     
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
     
        // bind id of record to delete
        $stmt->bindParam(1, $this->id);
     
        // execute query
        if($stmt->execute()){
            return true;
        }
        
        return false;
         
    }
    
    // used for sum all duck prices
    function countPrice(){
        
        // select query    
        $query = "SELECT
                    price,color
                FROM
                    `ducks` 
                    WHERE owner = ?";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
     
        // bind id of record to select
        $stmt->bindParam(1, $this->id);
        
        // execute query
        $stmt->execute();
        $count_price=0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
               extract($row);
               $count_price+=$price;
        }
        
        return $count_price;
    }
    
    // used for calculate result sum with discount
    //if ducks have more two colors - 10% discount needed
    function countSum(){
        // select query    
        $query = "SELECT
                    price,color
                FROM
                    `ducks` 
                    WHERE owner = ?";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
     
        // bind id of record 
        $stmt->bindParam(1, $this->id);
        
        // execute query
        $stmt->execute();
        $clr=[];
        $sum=0;
        //create array with order colors
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $clr[$color] = 'yep';
            $sum+=$price;
        }
        //count order colors
         if (count($clr)>1)
        {
            $sum=$sum*0.9;
        } 
        
        return $sum ;
    }
    
    //used for add Duck to Order
    //check logic of only black or green duck can be in order
    function addDuck($c){
         // select query    
        $query = "SELECT
                    color
                FROM
                    `ducks` 
                    WHERE owner = ?";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
     
        // bind id of record 
        $stmt->bindParam(1, $this->id);
        
        // execute query
        $stmt->execute();
        $clr=[];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $clr[$color] = $color;
        }
             
        // check duck colors
        if($c=='black'&&isset($clr['green'])){
            return -1;
        }
        if($c=='green'&&isset($clr['black']))
        {
            return -1;
        } 
                    
       // initialize object
        $duck = new Ducks($this->conn);
         
        // set ID property of record to read
        $duck->owner = $this->id;
        $duck->color = $c;
        // booking duck
        $duck->getDuck();
         
        if($duck->id!=null){
            // create array
            $duck_arr = array(
                "id" =>  $duck->id,
             );
         
           return $duck_arr;
        }
         
        else
        {
           return -2;
        }
  
    }
    
    //Used for get current ducks in order
    function getOrderInfo(){
        // select query     
        $query = "SELECT
                    color
                FROM
                    `ducks` 
                    WHERE owner = ?";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
     
        // bind id of record 
        $stmt->bindParam(1, $this->id);
        
        // execute query
        $stmt->execute();
        $clr=[];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            if (!isset($clr[$color]))
            $clr[$color] = 1;
            else $clr[$color] = $clr[$color]+1;
       } 
          $this->ducks=$clr;
          
    }
    
    // used when client wants to remove a duck from order
    function removeDuck($c){
         $result = 0;
         
         try {  
             
              $this->conn->beginTransaction();
              // query to read single record
                $query = "SELECT
                            id
                        FROM
                            `ducks`
                            WHERE color =  :color
                            AND owner =:owner
                            LIMIT 1";
             
                // prepare query statement
                $stmt = $this->conn->prepare( $query );
                
                // bind color of duck 
                $stmt->bindParam(":color", $c);
             // bind owner of duck to be updated
                $stmt->bindParam(":owner", $this->ownerId);
                // execute query
                $stmt->execute();
                $reason=$stmt->errorInfo();
                // get retrieved row
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
             
                // set values to object properties
                $this->id = $row['id'];
                
                // update query
                $query = "UPDATE
                            `ducks`
                        SET
                            owner = 0
                            
                        WHERE
                            id = :id";
             
                // prepare query statement
                $stmt = $this->conn->prepare($query);
             
                // sanitize
                $this->id=htmlspecialchars(strip_tags($this->id));
                
                //bind params
                $stmt->bindParam(':id', $this->id);
             
                // execute the query
                if($stmt->execute()){
                    $result= 1;
                }
                else 
                { 
                    $result= -1;
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