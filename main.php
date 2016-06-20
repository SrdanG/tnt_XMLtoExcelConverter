<?php

//Class: main.php
//Info:

  class Main
  {
      //database setup
      private $hostname_logon = 'localhost';		    //Database server LOCATION
      private $username_logon = 'root';	    	//Database USERNAME
      private $password_logon = 'ljubljana';		    //Database PASSWORD 
      //declare attributes for userdata
 
      //declare constructor
      public function __construct()
      {
        
      }
      //declare __set function
      public function __set($name, $value)
      {
          $this->$name = $value;
      }

      //connect to database
      public function dbconnect($db)
      {
          $connections = mysql_connect($this->hostname_logon, $this->username_logon, $this->password_logon)
              or die ('Unable to connect to the database');
          mysql_select_db($db)
              or die();
          return;
      }
    //minimum cleaning of data received from form
      public function CleanData($data)
      {
          //delete black from the string
          $data = trim($data);
          //convert html special chars
          $data = htmlspecialchars($data);
          //return clean data
          return $data;
      }
      //mysqlclean function - clean sql query to prevent SQL injection attacks
        public function mysqlclean($query)
        {
            $args  = func_get_args();
            $query = array_shift($args);
            $query = str_replace("?", "%s", $query);
            $args  = array_map('mysql_real_escape_string', $args);
            array_unshift($args,$query);
            $query = call_user_func_array('sprintf',$args);
            $result = mysql_query($query) or die(mysql_error());
            if($result){
                return $result;
            } else {
                $error = "Error";
                return $result;
            }
        }       
  }
?>