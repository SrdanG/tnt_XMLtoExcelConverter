<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
    <head>
        <title>Carina XML to Excel Converter</title>
        <link rel="stylesheet" type="text/css" href="common.css">
        <script type="text/javascript" src="sources/jquery.js"></script>        
    </head>    
    <body>
        <div id="wrapper">
            <div id="header">
                <div class="logo left">
                    <img src="images/tnt1.png" width="94" height="62" alt="tnt1">
                </div>
                <div class="title left">
                    Carina XML to Excel Converter
                </div>
            </div>
            <div id="main">
                <div id="menu" class="left">
                    <ul>
                        <li id="upload">
                            <a href="#">
                                1 - Upload
                            </a>                            
                        </li>
                        <li id="import">
                            <a href="#">
                                2 - Import
                            </a>                            
                        </li>
                        <li id="export">
                            <a href="#">
                                3 - Export
                            </a>                            
                        </li>
                        <li id="help">
                            <a href="#">
                                0 - Help
                            </a>                            
                        </li>
                    </ul>

                </div>
                <div id="content" class="right">
                    
                </div>
            </div>
            <div id="footer">
                Copyright <?php print date('Y');?> &copy; IT Oddelek
            </div>
        </div>
         <script type="text/javascript">                                         
            $(document).ready(function() {
                //Handle event for ==UPLOAD== button
                $('#upload').click(function(event){
                    event.preventDefault();
                    $("#content").empty();
                    $.ajax({
                        url: "uploadform.php",
                        cache: false,
                        success: function(html){
                            $("#content").append(html);
                        }
                    });
                });
                //Handle event for ==IMPORT== button
                $('#import').click(function(event){
                    event.preventDefault();
                    $("#content").empty();
                    $.ajax({
                        url: "import.php",
                        cache: false,
                        success: function(html){
                            $("#content").append(html);
                        }
                    });
                });
                //Handle event for ==EXPORT== button
                $('#export').click(function(event){
                    event.preventDefault();
                    $("#content").empty();
                    $.ajax({
                        url: "export.php",
                        cache: false,
                        success: function(html){
                            $("#content").append(html);
                        }
                    });
                });
                //Handle event for ==EXPORT== button
                $('#help').click(function(event){
                    event.preventDefault();
                    $("#content").empty();
                    $.ajax({
                        url: "help.php",
                        cache: false,
                        success: function(html){
                            $("#content").append(html);
                        }
                    });
                });
             });
         </script>  
    </body>
</html>




