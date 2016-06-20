<?php

//Class: import.php
//Info: Upload user file on the server under the name data.csv

  //include main.php code
    require_once 'main.php';
    //define upload class. 
    class Import extends Main 
    {
        //declare attributes for userdata
        private $path = '/inetpub/wwwroot/Aplikacije/carina/upload/data.xml';
        private $db = "xmlimport";
        //declare constructor
        public function __construct()
        {   
            //Connect to the database when the object is initialized
            $link = parent::dbconnect($this->db);
            //Call the import funciton after the database is connected
            $import = $this->importdata();
            //Delete file after import
            $delete = $this->deletefile($this->path);
        }       
        //declare function importdata(). Import data from data.csv into the mysql db.
        public function importdata()
        {
            //Load data into the Petrol table
            $query = "LOAD XML LOCAL INFILE '$this->path' 
            INTO TABLE xmlimport ROWS IDENTIFIED BY '<Imenovanje>'";
			
			/*LOAD DATA INFILE '$this->path'
                      INTO TABLE xmlimport
                      FIELDS TERMINATED BY ';'
                      ENCLOSED BY '\"'
                      LINES TERMINATED by  '\r\n'
                     (SkupniZnesekFakture, MRN, @DatumRegistracije, OpravilnaStevilka)
                     SET DatumRegistracije = str_to_date(@DatumRegistracije,'%Y%m%d')*/

            //Execute query with mysqlclean function from Main class
            $result = mysql_query($query);
						
			//Check the results
            if ($result) {
            ?>
                <p>* Uvoz v bazo je bil uspesen</p>
            <?php    
            } else {
            ?>
                <p>* Uvoz ni uspelo: <?php print mysql_error();?></p>
            <?php
            }			
		
        }
        //Function that delete the data file from the upload dir.
        public function deletefile($path){
            if (!@unlink($path)) { 
            ?>
                <p>* Ni mozno izbristati datoteke. Cudno ne?</p>
            <?php  
            } else { 
            ?>
                <p>Datoteka je bila uspesno izbrisana.</p>
            <?php  
            }      
        }
    }
    //initialize class upload
    $upload = New Import();
    
?>