<?php 
 
       include("config.php"); 
 
        //collegamento
    $col = "mysql:host=$host;dbname=$db_name";
 
        try {
                  //tentativo di connessione
          $db = new PDO($col , "$db_user", "$db_psw");
        }
                    //gestione errori
            catch(PDOException $e) {
 
              echo 'Attenzione errore: '.$e->getMessage();
            }       
 
?>
