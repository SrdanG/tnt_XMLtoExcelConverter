<?php

//Class: validate.php
//Info: Validate that every card ID is registered on the database and linked to a functional area

/////////////////////// TO - DO //////////////////
//
// * Inprove validation process. Check if data is correct before import on the database.
//
/////////////////////////////////////////////////
    //include main.php code
    require_once 'main.php';
    //define upload class. 
    class Validate extends Main 
    {
        //declare attributes for userdata
        private $db = "ops";
        //declare constructor
        public function __construct()
        {
            //Connect to the database when the object is initialized
            $link = parent::dbconnect($this->db);
            if ($_GET['register'] == 'card'){
                //if form has been submited already, then update table1 with card data.
                $updatecard = $this->updatecard();
            } elseif ($_GET['register'] == 'product'){
                //if form has been submited already, then update table2 with product data.
                $updatecard = $this->updateproduct();
            } else {                
                $cardcheck = $this->checkcard();
            }   
        }
        //Function checkcard checks that every card number was imported on Petrol is present on table1
        public function checkcard()
        {
            //Query that check if every card number on petrol is registered on table1
            $query = "SELECT St_kartice
                      FROM Petrol
                      LEFT JOIN table1
                      ON St_kartice = St_kartice1
		      WHERE St_kartice1 IS NULL";
            //execute query
            $result = mysql_query($query);
            //save result on cardid as array
            $cardid = mysql_fetch_array($result);
            //Check the results of the exection
            if ($result) {
            ?>
                <p>* Testiranje kartic je uspelo</p>
            <?php    
            } else {
            ?>
                <p>* Testiranje ni mozno: <?php print mysql_error();?></p>
            <?php
            }
            //Check the results of the exection
            if (empty($cardid)) {
            ?>
                <p>* Vse kartice so registrirane v podatkovni bazi.</p>
            <?php    
                $productcheck = $this->checkproduct();
            } else {
            ?>
                <p>* Najdel sem neregistrirane kartice, in sicer: <?php print (sizeof($cardid)-1);?>.<br />
                prosim da jih registrirate v spodnjem obrazcu.</p>
                
                <form id="regitercard" action="validate.php?register=card" method="post" class="left">
                    <label for="cardid">Stevilka kartice</label>
                    <input id="cardid" type="text" name="cardid" value="<?php print $cardid['0'];?>"/><br />
                    <label for="costcenter">Cost center:</label>
                    <input id="costcenter" type="text" name="costcenter" /><br />
                    <label for="location">Lokacija koda:</label>
                    <input id="location" type="text" name="location" /><br />
                    <label for="funcarea">Functional area:</label>
                    <input id="funcarea" type="text" name="funcarea" /><br />
                    <label for="register" class="warning">POZOR: preveri podatke preden potrdis</label>
                    <input id="register" type="submit" value="Registriraj"/>
                </form>
                <script type="text/javascript">                                         
                $(document).ready(function() {
                    //Handle event for ==UPLOAD== button
                    $('form').submit(function(event){
                        var carddata = $(this).serialize();
                        carddata = "validate.php?register=card&" + carddata;
                        event.preventDefault();
                        $("#content").empty();
                        $.ajax({
                            url: carddata,
                            cache: false,
                            success: function(html){
                                $("#content").append(html);
                            }
                        });
                    })
                 });
                </script>     
            <?php
            }
        }
        //Function update card update table1 with data comming from the registercard form
        public function updatecard(){
            //retreive and clean data from the register card form
            $cardid = parent::CleanData($_GET['cardid']);
            $costcenter = parent::CleanData($_GET['costcenter']);   
            $location = parent::CleanData($_GET['location']);
            $funcarea = parent::CleanData($_GET['funcarea']);
            //check for empty fields
            if (empty($cardid) || empty($costcenter) || empty($location) || empty($funcarea))
            {
            ?>
                <p>* Nekatera polja so prazna, prosim da pozkusite ponovno.</p>
            <?php 
            } else {
                //create query with form data
                $query = "INSERT INTO table1 (St_kartice1, Cost_center, Lokacija_koda, Func_area)
                      VALUES('$cardid','$costcenter','$location','$funcarea')";
                //execute query
                $result = mysql_query($query);
                //Check result
                if ($result) {
                ?>
                    <p>* Kartica je bila registrirana, prosim da validirate ponovno</p>
                <?php    
                } else {
                ?>
                    <p>* Registriranje ni mozno: <?php print mysql_error();?></p>
                <?php
                }
            }
        }
        //Function checkproduct checks that every product was imported on Petrol is present on table2 (BL_ST)
        public function checkproduct()
        {
            //Query that check if every product on petrol is registered on table2 (BL_ST)
            $query = "SELECT SifraBL_ST
                        FROM Petrol
                        LEFT JOIN table2
                        ON SifraBL_ST = Sifra_BL_ST
                        WHERE Sifra_BL_ST IS NULL";
            //execute query
            $result = mysql_query($query);
            //save result on cardid as array
            $productid = mysql_fetch_array($result);
            //Check the results of the exection
            if ($result) {
            ?>
                <p>* Testiranje produktov je uspelo</p>
            <?php    
            } else {
            ?>
                <p>* Testiranje ni mozno: <?php print mysql_error();?></p>
            <?php
            }
            //Check the results of the exection
            if (empty($productid)) {
            ?>
                <p>* Vsi produkti so registrirani v podatkovni bazi.</p>
            <?php    
            } else {
            ?>
                <p>* Najdel sem neregistrirani produkti, in sicer: <?php print (sizeof($productid)-1);?>.<br />
                prosim da jih registrirate v spodnjem obrazcu.</p>
                                
                <form id="regiterproduct" action="validate.php?register=product" method="post" class="left">
                    <label for="pruductid">Sifra izdelka:</label>
                    <input id="pruductid" type="text" name="pruductid" value="<?php print $productid['0'];?>"/><br />
                    <label for="matgroup">Material Group:</label>
                    <input id="matgroup" type="text" name="matgroup" /><br />
                    <label for="glaccount">GL Account:</label>
                    <input id="glaccount" type="text" name="glaccount" /><br />
                    <label for="funcarea">Functional area:</label>
                    <input id="funcarea2" type="text" name="funcarea" /><br />
                    <label for="shortdesc">Kratek opis:</label>
                    <input id="shortdesc" type="text" name="shortdesc" /><br />
                    <label for="register" class="warning">POZOR: preveri podatke preden potrdiš</label>
                    <input id="register2" type="submit" value="Registriraj"/>
                </form>
                <script type="text/javascript">                                         
                $(document).ready(function() {
                    //Handle event for ==UPLOAD== button
                    $('form').submit(function(event){
                        var carddata = $(this).serialize();
                        carddata = "validate.php?register=product&" + carddata;
                        event.preventDefault();
                        $("#content").empty();
                        $.ajax({
                            url: carddata,
                            cache: false,
                            success: function(html){
                                $("#content").append(html);
                            }
                        });
                    })
                 });
                </script>     
            <?php
            }
        }
        //Function update card update table1 with data comming from the registercard form
        public function updateproduct(){
            //retreive and clean data from the register card form
            $pruductid = parent::CleanData($_GET['pruductid']);
            $matgroup = parent::CleanData($_GET['matgroup']);   
            $glaccount = parent::CleanData($_GET['glaccount']);
            $funcarea = parent::CleanData($_GET['funcarea']);
            $shortdesc = parent::CleanData($_GET['shortdesc']);
            //check for empty fields
            if (empty($pruductid) || empty($matgroup) || empty($glaccount) || empty($funcarea) || empty($shortdesc))
            {
            ?>
                <p>* Nekatera polja so prazna, prosim da pozkusite ponovno.</p>
            <?php 
            } else {
                //create query with form data
                $query = "INSERT INTO table2 (Func_area_test, Sifra_BL_ST, Material_group, GLAccount_override, Short_desc)
                          VALUES('$funcarea','$pruductid','$matgroup','$glaccount','$shortdesc')";
                //execute query
                $result = mysql_query($query);
                //Check result
                if ($result) {
                ?>
                    <p>* Produkt je bil registriran, prosim da validirate ponovno</p>
                <?php    
                } else {
                ?>
                    <p>* Registriranje ni mozno: <?php print mysql_error();?></p>
                <?php
                }
            }
        }
    }
    //initialize class upload
    $upload = New Validate();
    
?>