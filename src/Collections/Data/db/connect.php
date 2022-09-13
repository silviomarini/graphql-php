<?php
    $host= 'localhost';
    $db = 'local';
    $user = 'postgres';
    $password = 'Codicesegreto1'; // change to your password

    //if(strpos($_SERVER['HTTP_HOST'], 'localhost') === false){
    if(1){
        $host= 'db.rupvyjmpxzoqjajuhhyy.supabase.co';
        $db = 'postgres';
        $user = 'postgres';
        $password = 'Codicesegreto1'; 
    }

    try {
        $dsn = "pgsql:host=$host;port=6543;dbname=$db;";
        
        // make a database connection
        $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        /*if ($pdo) {
            echo "Connected to the $db database successfully!";
        }*/
    } catch (PDOException $e) {
        die($e->getMessage());
    }
?>