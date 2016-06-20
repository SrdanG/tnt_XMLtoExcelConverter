<?php

//Class: Help.php
//Info: Show some help...

  //include main.php code
    require_once 'main.php';
    //define upload class. 
    class Help extends Main 
    {
        //declare attributes for userdata
       
        //declare constructor
        public function __construct()
        {   
            $help = $this->displayhelp();
        }       
        private function displayhelp(){
        ?>
            <div class="margin">
                <p>Pozdravljeni!</p>
                <p class="line">    1 - Najprej je treba prenašati datoteko na naš strežnik. <br />
                                    Datoteko dobiš na Petrolovi strani. Preden jo prenašaš, jo moraš <br />
                                    spremeniti v CSV (Comma delimited file) obliki. To narediš tako <br />
                                    da v Milanovim Excelom, odpreš datoteko iz Petrola (XLS) in poženeš <br />
                                    macro. Če ne veš kako, preveri točka 5. Nato klikneš na <br />
                                    Upload, brskaš do lokacije, kjer si datoteko shranil, in potrdiš. 
                </p>
                <p class="line">    2 - Potem ko si uspešno prenesel datoteko, klikneš na "import".<br />
                                    Sistem bo javil ali je bilo uspešno uvoženo. Če pride do napak, <br />
                                    preverite točko 5.<br />
                                    Če piše "Can't get stat of '/home/sek/www/FFC/upload/data.csv' (Errcode: 2)" <br />
                                    pomeni da datoteke ni. Tako da vrni se na prvo točko.  
                </p>
                <p class="line">    3 - Potem je potrebno validirati podatke. Sistem bo javil ali so podatki<br />
                                    uspšeno validirani. Če se najde nov produkt ali kartico, jo je potrebno<br />
                                    vnesti v sistem. Sledite navodila na zaslonu. Če pride do napak, <br />
                                    preverite točko 5.
                </p>
                <p class="line">    4 - Ko klikneš na Export, bo program obdelal podatke in pokazal rezultat.<br />
                                    Če klikneš na "tukaj" boš lahko prenesel datoteko na svoj računalnik.<br />
                                    Nato, jo shraniš na y:\podatki\FuelPurchase\ in čakaš do 22:00, da jo<br />
                                    angleški robot obdela. Če pride do napak, preverite točko 5.                                   
                </p>
                <p class="line">    5 - Pokliči dežurnega računalnika na 9180 ali počakajte do naslednjega delovnega dne.
                                 
                </p>
                <p class="error">    -<= za slovnične napake ne odgovarjam. =>-
                                 
                </p>
            </div>
        <?php
        }
    }
    //initialize class upload
    $upload = New Help();
    
?>