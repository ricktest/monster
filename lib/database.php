<?php

    class database{

        private $con;
        private $config;

        function __construct(){
            require_once './lib/Config/databaseConfig.php';
            $this->config=new databaseConfig();
            $this->con=new mysqli($this->config->dbhost,$this->config->acount,"",$this->config->db_name);
            if(!$this->con){
                die("連線錯誤: " . mysqli_connect_error());
            }
            $this->con->query("SET NAMES utf8");
        }

        function replacedata($data){
           return  mysqli_real_escape_string($this->con,$data);
        }

        function sqlquery($sql){

            return $this->con->query($sql);
          
        }
        function resultarray($obj_data){
            $arr_data=null;
            while($result=mysqli_fetch_assoc($obj_data)){
                $arr_data[]=$result;
            }
            return $arr_data;
        }
    }
    
?>