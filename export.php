<?php

//Class: export.php
//Info: Upload user file on the server under the name data.csv

  //include main.php code
    require_once 'main.php';
    //define upload class. 
    class Export extends Main 
    {
        //declare attributes for userdata
        private $db = "xmlimport";
        private $pathcsv = "upload/";          //path for csv file to be saved?
        private $namecsv = "FuelPurchase.csv";   //File name
        private $txtoutput = "";     //TXT output file to be saved on FuelPurchase.csv
        private $htmloutput = "";    //HTML output file to be presented on site
        //declare functional areas for every sector
        private $ops = array('000','010','111','112','210','240');  //OPS functional areas to be defined here
        private $sm = array('301','303','304');  //Sales & Marketing functional areas to be defined here
        private $it = array('500');  //IT functional areas to be defined here
        private $fa = array('600');  //Finance functional areas to be defined here
        //private $gm = array('700');  //General Manager functional areas to be defined here
        private $hr = array('800');  //HumanResources functional areas to be defined here
        private $cc = array('400');  //CallCenter functional areas to be defined here
        //declare head id for every sector
        private $opshead = '1';  //OPS header ID for tablel
        private $smhead = '2';  //SM header ID for tablel
        private $ithead = '3';  //IT header ID for tablel
        private $fahead = '4';  //FA header ID for tablel
        //private $gmhead = '5';  //GM header ID for tablel
        private $hrhead = '6';  //HR header ID for tablel
        private $cchead = '7';  //CC header ID for tablel
        ////declare VAT indicator
        private $vat = array ("010" => "true",  //VAT for LineHauls
                              "111" => "true",  //VAT for Vans  
                              "112" => "true",  //VAT for Trucks
                              "240" => "true",  //VAT for Forklift
                             );
       
        //declare constructor
        public function __construct()
        {   
            //Connect to the database when the object is initialized
            $link = parent::dbconnect($this->db);
            //call the exportdata function after DB connection
            $export = $this->exportdata();
            //Save output to CSV file
            //$save = $this->savetocsv();
            //Empty tables
            $empty = $this->emptytable('tempexcelexport');
            $empty = $this->emptytable('xmlimport');
			$empty = $this->emptytable('excelexport');
			$empty = $this->emptytable('footer');
			
        } 
        //Export data from mysql and create a CSV file named FuelPurchase.csv on the server
        public function exportdata()
        {
           // $setquantity = $this->setquantity();
           // print "<p>* Kolicina nastavljena</p>";
           	  $header = $this->fillheader();
              $filltablel = $this->filltable1();
              $fillbody = $this->fillbody();
			  $filltable2 = $this->filltable2();
			  $changedate = $this->changedateformat();
			  $save = $this->savetocsv();
			  
			  //PO PRENOSU JE DATOTEKO POTREBO SE IZBRISATI !!!
			  
          //  $updateheader = $this->updateheader();
          //  print "<p>* Headeri posodobljeni</p>";
          //  $poref = $this->updateporef();
          //  print "<p>* Reference posodobljene</p>";
          //  $createoutput = $this->createoutput();
          //  print "<p>$this->htmloutput</p>";
        ?>
            <p>Datoteka je uspesno kreirana in pripravljena za uvoz v SAP.
                <a href="http://<?php print $_SERVER['SERVER_NAME'] ;?>/FFC/<?php print $this->pathcsv.$this->namecsv ;?>" target="_blank">
                    <span class="download"></span>
                </a>
           </p> 
        <?php    
        }
        //Setquantity on Petrol record kolicina to value 1
        public function setquantity()
        {/*
            //As far as the quantity is not neeeded on the PO we set quantity to 0 on every record on petrol
            $query = "UPDATE Petrol SET Kolicina = 1" ;
            $result = mysql_query($query) or
                die ("<p>Ni mozno nastaviti kolicina v petrol na 1</p>");*/
        }
        //Filltable1 fills table1 with data from petrol in order to allow export.
        
        public function fillheader()
        {
            $query = "INSERT INTO tempexcelexport
                      SELECT *
                      FROM header";
            $result = mysql_query($query) or die ("<p>Ne morem zapisati header v tabelo excelexport</p>");
            
        }
        
        public function filltable1()
        {
            $query = "INSERT INTO excelexport (Documentdate, CrossReferencedocument, 
                                          HeaderText,AmountinDocumentCurrency, Assignmentnumber)
                      SELECT DatumRegistracije, MRN,MRN, SkupniZnesekFakture, OpravilnaStevilka
                      FROM xmlimport";
            $result = mysql_query($query) or die ("<p>Ne morem zapisovati v tabelo excelexport</p>");
            
        }
		
		public function fillbody()
        {
            $query = "INSERT INTO tempexcelexport (Documentdate,CrossReferencedocument,HeaderText,AmountinDocumentCurrency,Assignmentnumber)
                      SELECT Documentdate,CrossReferencedocument,HeaderText,AmountinDocumentCurrency,Assignmentnumber
                      FROM excelexport";
            $result = mysql_query($query) or die ("<p>Ne morem zapisati header v tabelo excelexport</p>");
            
        }
		
		public function filltable2()
        {
        	$query1 = "INSERT INTO footer (CrossReferencedocument,HeaderText,DebitCredit,EBOGLAccount,EBOProfitCentre) 
                      VALUES ('OBV DO CARINE 01/2013','OBV DO CARINE 01/2013','c','345400','9908LJ1')";
            $result1 = mysql_query($query1) or die ("<p>Ne morem zapisovati v tabelo excelexport</p>");	
        	
            $query2 = "UPDATE footer SET AmountinDocumentCurrency = (SELECT SUM(SkupniZnesekFakture) FROM xmlimport)";
            $result2 = mysql_query($query2) or die ("<p>Ne morem zapisovati v tabelo excelexport</p>");
            
            $query3 = "UPDATE xmlimport p, footer pp 
						SET pp.Documentdate = p.DatumRegistracije
						WHERE pp.DebitCredit = 'c'";
            $result3 = mysql_query($query3) or die ("<p>Ne morem zapisovati v tabelo excelexport</p>");
            
			$query4 = "INSERT INTO tempexcelexport (Documentdate,CrossReferencedocument,HeaderText,DebitCredit,EBOGLAccount,EBOProfitCentre,AmountinDocumentCurrency)
                      SELECT Documentdate,CrossReferencedocument,HeaderText,DebitCredit,EBOGLAccount,EBOProfitCentre,AmountinDocumentCurrency
                      FROM footer";
            $result4 = mysql_query($query4) or die ("<p>Ne morem zapisati header v tabelo excelexport</p>");
            
        }
		
		public function changedateformat()
        {
            $query1 = "UPDATE tempexcelexport SET Documentdate = DATE_FORMAT(Documentdate, '%Y%m%d') WHERE DebitCredit = 'c' OR DebitCredit = 'd'  ";
            $result1 = mysql_query($query1) or die ("<p>Ne morem spremeniti formata datuma Documentdate</p>");		
            		
            	
            $query2 = "UPDATE tempexcelexport SET Postingdate =  NOW() WHERE DebitCredit = 'c' OR DebitCredit = 'd'";
            $result2 = mysql_query($query2) or die ("<p>Ne morem doddati tekoci datum v Postingdate</p>");
			
			
			$query3 = "UPDATE tempexcelexport SET Postingdate = DATE_FORMAT(Postingdate, '%Y%m%d') WHERE DebitCredit = 'c' OR DebitCredit = 'd'";
            $result3 = mysql_query($query3) or die ("<p>Ne morem spremeniti formata datuma Postingdate</p>");
            
        }
        //updateheader fills tableh field count with the sum of func_area on tablel by sector.
        public function updateheader()
        {/*
            $ops = $this->countfuncarea($this->ops, $this->opshead);
            $sm = $this->countfuncarea($this->sm, $this->smhead);
            $fa = $this->countfuncarea($this->fa, $this->fahead);
            $it = $this->countfuncarea($this->it, $this->ithead);
            $hr = $this->countfuncarea($this->hr, $this->hrhead);
            //$gm = $this->countfuncarea($this->gm, $this->gmhead);
            $cc = $this->countfuncarea($this->cc, $this->cchead);
		 */
		 
        }
        //countfuncarea count distinct func_area on tablel. Argument is array with functional areas by sector
        public function countfuncarea($array, $header)
        {/*
            $queryA = "UPDATE tableh SET LineCnt =(SELECT COUNT(DISTINCT Func_area)
                      FROM tablel ";
            $endofloop = sizeof($array);
            for ($i=0; $i< $endofloop;$i++)
            {
                if ($i == 0){
                    if ($i == ($endofloop-1)){
                        $queryB = "WHERE Func_area = $array[$i]) ";
                    } else {
                        $queryB = "WHERE Func_area = $array[$i] ";
                    }
                } elseif (($i != 0) && ($i == ($endofloop-1))){
                    $queryB .= "OR Func_area = $array[$i]) ";
                } else {
                    $queryB .= "OR Func_area = $array[$i] ";
                }
            }     
            $queryC = "WHERE idtableh = $header";
            $query = $queryA.$queryB.$queryC;
            $result = mysql_query($query) or 
                die('<p>count func area failed</p>');
            return $result;
		 */
        }
        //countfuncarea count distinct func_area on tablel. Argument is array with functional areas by sector
        public function updateporef()
        {/*
            $query = "UPDATE tableh SET `PORef` = `PORef` + 10" ;
            $result = mysql_query($query) or 
                die ("Ne morem posodobiti POref polje");
		 
		 */
        }
        //Createoutput create a file with all the headers and lines from tablel and tableh
        public function createoutput(){/*
            //insert the ops header
            $opsheader = $this->insertheader($this->opshead);
            //insert lines for each func area
            $opsbody = $this->createbody($this->ops);
            //insert the Sales & Marketing header
            $smheader = $this->insertheader($this->smhead);
            //insert lines for each func area
            $smbody = $this->createbody($this->sm);
            //insert the IT header
            $itheader = $this->insertheader($this->ithead);
            //insert lines for each func areaa 
            $itbody = $this->createbody($this->it);
            //insert the Finance header
            $faheader = $this->insertheader($this->fahead);
            //insert lines for each func area
            $fabody = $this->createbody($this->fa);
            //insert the HR header
            $hrheader = $this->insertheader($this->hrhead);
            //insert lines for each func area
            $hrbody = $this->createbody($this->hr);
            //insert the CGM header
            //$gmheader = $this->insertheader($this->gmhead);
            //insert lines for each func area
            //$gmbody = $this->createbody($this->gm);
            //insert the CGM header
            $ccheader = $this->insertheader($this->cchead);
            //insert lines for each func area
            $ccbody = $this->createbody($this->cc);
		 * 
		 */
        }
        //Insertheader retreive the header by ID and return it
        public function insertheader($headid){/*
            //query that retreive the header by ID from tableh
            $query = "SELECT `RecType`, `SourceSys`, `MessageType`, `MessageGrp`, `CompCode`, `PORef`, 
                             `Vendor`, `LineCnt`, `Currency`, `DocDate`, `RecDate`, `Purchorg`, `Purchgrp`
                      FROM `tableh`
                      WHERE idtableh = $headid";
            //Execute query
            $result = mysql_query($query) or 
                die ("Ne morem brati header");
            //Save result to array
            $array = mysql_fetch_assoc($result);
            //Format every retreived field to be saved as txt and presented as HTML
            foreach ($array as $key => $value)
            {
                //If last record then, create a new line on the output
                if ($key == "Purchgrp") {
                    $this->txtoutput .= $value."\r\n";
                    $this->htmloutput .= $value."<br>";
                //for others fields just put in there
                } else {
                    $this->txtoutput .= $value.",";
                    $this->htmloutput .= $value.",";
                }
            }
            unset($array);
		 * 
		 */
        }
        //Insertline retreive the line by func_area and return it
        public function insertline($funcarea){/*
            //query that retreive the line by ID from tablel
            $query = "SELECT `RecType`, `Lokacija_koda`, `Material_group`, `Cost_center`, 
                     `Func_area`, `TaxCode`, `ShortText`, SUM(Cena) as Cena, `Kolicina`, `UnitOfMeas`, 
                     `GLAccount_override`, `Short_desc`
                      FROM `tablel`
                      WHERE Func_area = '$funcarea'
                      GROUP BY Func_area";      
            //Execute query
            $result = mysql_query($query) or 
                die ("Ne morem brati vsebine tablel");
            //Save result to array
            $array = mysql_fetch_assoc($result);      
            //format every retreived field to be saved as txt and presented as HTML
            if (!empty($array)){
                foreach ($array as $key => $value)
                {
                    //If last record then, create a new line on the output
                    if ($key == "Short_desc") {
                        $this->txtoutput .= $value."\r\n";
                        $this->htmloutput .= $value."<br>";
                    //If we are testing the price, then round and format number
                    } elseif ($key == "Cena") {
                        $price = $value; 
                        //test for VAT deviation. If true then rest 20%
                        if ($this->vat[$funcarea]){
                            $price = $price / 1.20 ;
                        }   
                        //round price to 2 decimals
                        $price = round($price,2);
                        //Split on puntuation
                        $pricearray= explode('.',$price);
                        //If decimal number is 1, then full with 0
                        if (strlen($pricearray[1])== 1 ) {
                             $pricearray[1].= "0";
                        }
                        //If decimal number is 0, then full with 00
                        else if (empty($pricearray[1]))  {
                             $pricearray[1].= "00";
                        }
                        //Join number to be saved on the output var
                        $price = $pricearray[0].$pricearray[1];
                        $this->txtoutput .= $price.",";
                        $this->htmloutput .= $price.",";
                    //for others fields just put in there
                    } else {
                        $this->txtoutput .= $value.",";
                        $this->htmloutput .= $value.",";
                    }
                }
            }
            unset($array);
		 * 
		 */
        }
        //Create body insert all the lines for a given sector to be inserted after header
        private function createbody($sector) {/*
            //loop through the array and create lines by functional area
            foreach ($sector as $key => $funcarea)
            {
                $opsbody = $this->insertline($funcarea);
            }    
            return $opsbody;
		 * 
		 */
        }
        //Empty tables truncate the content of the givven table
        private function emptytable($table){
            $query = "TRUNCATE TABLE $table";
            $result = mysql_query($query) or 
                die ("Ne morem izprazniti tabelo");
				
        }
        //Empty tables truncate the content of the givven table
        private function savetocsv(){
            //generate complete path for csv file
           /* $csv = $this->pathcsv.$this->namecsv;
            //open csv file for writting
            $file = fopen($csv, 'w');
            fwrite($file, $this->txtoutput);
            fclose($file);*/
			$query="SELECT * FROM tempexcelexport ORDER BY  `tempexcelexport`.`CompanyCode` ASC INTO OUTFILE '/inetpub/wwwroot/Aplikacije/carina/upload/dataexport10.txt' FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n'";
 			$result= mysql_query($query) or die ("<p>Ta datoteka je že uvožena v SAP. Prosim izbriši datoteko in poženi samo EXPORT še enkrat.</p>");
			
		
        }
    }
    //initialize class upload
    $export = New Export();

?>