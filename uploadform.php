<?php

//script: uploadform.php
//Info: show upload form on index.php

?>
    <?php //Present the user with the upload form ?>    

    <form id="uploadform" enctype="multipart/form-data" action="upload.php" target="result" method="POST">
        <label for="file">Izberite datoteko:</label>
        <input id="file" name="uploadedfile" type="file" />
        <input id="button" type="submit" value="Potrdi" />
    </form>
    <iframe id="result" name="result" src="" frameborder="0"></iframe>



