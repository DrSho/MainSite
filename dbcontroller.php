<?php

//session_start();

//set and check for session timeout
include ("session_timeout.php");

class DBController {
    private $host = "mpwebservicesnet.ipagemysql.com";
    private $user = "drsho";
    private $password = "metcs633";
    private $database = "drsho";

//    private $host = "localhost";
//    private $user = "root";
//    private $password = "root";
//    private $database = "drsho";
//    private $conn;

    function __construct() {

        $this->connectDB();

    }

    function closeDB(){
        $this->conn->close();
    }

    function connectDB() {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die('Connect Error (' . $this->conn->connect_errno . ') '
                . $$this->conn->connect_error);
        }

        if (mysqli_connect_error()) {
            die('Connect Error (' . mysqli_connect_errno() . ') '
                . mysqli_connect_error());
        }

        $_SESSION['mysqli'] = $this->conn;
    }


    function runQuery($query) {
        $result = $this->conn->query($query);

        if(!empty($result))
            return $result;
    }

    function lastInsert(){
        return $this->insert_id;
    }

    function numRows($query) {
        $result  = $this->conn->query($query);
        $rowcount = mysqli_num_rows($result);
        return $rowcount;
    }

    function updateQuery($query) {
        $result = $this->conn->query($query);
        if (!$result) {
            die('Invalid query: ' . mysqli_error());
        } else {
            return $result;
        }
    }

    function insertQuery($query) {
        $result = $this->conn->query($query);
        if (!$result) {
            die('Invalid query: ' . mysqli_error());
        } else {
            return $result;
        }
    }

    function deleteQuery($query) {
        $result = $this->conn->query($query);
        if (!$result) {
            die('Invalid query: ' . mysqli_error());
        } else {
            return $result;
        }
    }


}
