<?php
    include "credentials.php";
    
    // Make a database connection
    $connection = new mysqli('localhost', $user, $password, $db);
    
    // Select all records from our database
    $record = $connection -> prepare("select * from scp_data ORDER BY id ASC");
    $record -> execute();
    $result = $record->get_result();
?>