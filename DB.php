<?php 
class DB {  

    public $connection;

    function __construct(){
        $servername = "";
        $username = "";
        $password = "";
        $dbname = "";
        $port = "";

        $conn = new mysqli();
        $conn->init();
        if(!$conn){
            echo "Connection failed";
        }else{
            $conn->ssl_set(NULL,NULL,NULL,'/public_html/sys_tests', NULL);

            $conn->real_connect($servername, $username, $password, $dbname, $port, NULL, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT);

            if($conn->connection_errorno){
                echo "Connection failed";
            }else{
                //echo"<p>Connected to server: " . $conn->host_info . "</p>";
                $this->connection = $conn;
                //echo "Set connection object";
            }
        }



    }

    function getUsers(){
        $sql = "SELECT * FROM `tbl_account` WHERE 1";
        $result = mysqli_query($this->connection,$sql);

        $users = [];

        while ($row = mysqli_fetch_row($result)){
           
            array_push($users,$row);
        }

        return $users;

    }
    function addQuotes($dict){
        return "'" . $dict . "'";

        
    }

    function insertUser($fname,$sname,$email,$password,$isPrem){
        
        $fname = $this->addQuotes($fname);
        $sname = $this->addQuotes($sname);
        $email = $this->addQuotes($email);
        //$password = $this->addQuotes($password);
        $isPrem = $this->addQuotes($isPrem);

        $salt = bin2hex(random_bytes(128));
        //$salt = "'dog'";


        $fullPass = $password . $salt;
        $hashPass = hash('sha256',$fullPass);
        $password = $this->addQuotes($hashPass);
        $salt = $this->addQuotes($salt);



        $sql = "INSERT INTO `tbl_account` (`account_id`, `account_fname`,`account_sname`,`account_email`,`account_password`, `account_salt`, `account_ispremium`) VALUES (NULL, $fname, $sname, $email, $password, $salt,$isPrem) ";
        echo $sql;

        mysqli_query($this->connection,$sql);
    }

    function login($email,$password){
        //$password = $this->addQuotes($password);
        //$email = $this->addQuotes($email);
        $sql = "SELECT * FROM tbl_account WHERE (account_email = '$email') ";

        $result = mysqli_query($this->connection,$sql);
        //var_dump($result);



        $userRow = mysqli_fetch_assoc($result);
        $salt = $userRow['account_salt'];

        $fullPass = $password . $salt;
        //echo $fullPass;
        $hashedPass = hash('sha256',$fullPass);

        $r = [];
    
        if ($hashedPass == $userRow['account_password']){
            $r["result"] = "true";
            $r["account"] = $userRow;
            return $r;
        }else{
            $r["result"] = "false";
            return $r;
        }
    }

    function insertMeal($mealId,$mealName){

        if($this->checkDBForMeal($mealId)["result"] == true){

        $mealId = $this->addQuotes($mealId);
        $mealName = $this->addQuotes($mealName);
        $rate =  $this->addQuotes(0);

        $sql = "INSERT INTO tbl_meal (meal_id,meal_name,meal_rate)  VALUES ($mealId,$mealName,0)";
        echo $sql;
        $result = mysqli_query($this->connection,$sql); 

        return $sql;
        }
    }

    function checkDBForMeal($mealId){
        $sql = "SELECT * FROM tbl_meal WHERE meal_id = $mealId ";
        $result = mysqli_query($this->connection,$sql);
        $r = [];

        

        $r["result"] =  (mysqli_fetch_row($result) == null)? false:true;
        return json_encode($r);

    }

    function addFav($accountId,$mealId){
        $sql = "INSERT INTO tbl_favourite (account_id,meal_id) VALUES($accountId,$mealId)";
        $result = mysqli_query($this->connection,$sql);


    }

    function getFavs($accountId){
        $sql = "SELECT meal_id FROM tbl_favourite WHERE account_id = $accountId ";
        $result = mysqli_query($this->connection,$sql);

        $favList = [];
        while($row = mysqli_fetch_row($result)){
            array_push($favList,intval( $row[0]));
        }
        echo $sql;
        return json_encode($favList);

    }

    function removeFav($accountId,$mealId){
        $sql = "DELETE FROM tbl_favourite WHERE (account_id = $accountId AND meal_id = $mealId)";
        $result = mysqli_query($this->connection,$sql);
    }
    
}
?>