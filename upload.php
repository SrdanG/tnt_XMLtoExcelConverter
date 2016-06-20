<?php

//Class: upload.php
//Info: Upload user file on the server under the name data.csv

    //include main.php code
    require_once 'main.php';
    //define upload class. 
    class Upload extends Main 
    {
        //declare attributes for userdata
        private $path;
        private $target;
        //declare constructor
        public function __construct()
        {
            $this->path = "/inetpub/wwwroot/Aplikacije/carina/upload/";
            $this->target = $this->path . basename( "data.xml");
            $upload = $this->uploadfile();
			$delete = $this->deletefile($this->path);
        }
        //Declare uploadfile function. The temporary file is moved to $this->target
        public function uploadfile()
        {
            if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $this->target)) {
            ?>
                <p>* Datoteka <?php print $_FILES['uploadedfile']['name'];?> je bila uspesno prenesena.</p>
            <?php
            } else{
            ?>
                <p>* Napaka pri uvozu! Prosimo poskusite ponovno!</p>
            <?php
            }
        }
		public function deletefile($path){/*
            if (!@unlink($path)) { 
            ?>
                <p>* Ni mozno izbristati datoteke. Cudno ne?</p>
            <?php  
            } else { 
            ?>
                <p>Datoteka je bila uspesno izbrisana.</p>
            <?php  
            }
		 * 
		 */      
        }
        
    }
    //initialize class upload
    $upload = New Upload();
    
?>