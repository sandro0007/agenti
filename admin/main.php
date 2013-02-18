<?php
session_start();
//se non c'è la sessione registrata
if (!session_is_registered('autorizzato')) {
  echo "<h1>Area riservata, accesso negato.</h1>";
  echo "Per effettuare il login clicca <a href='index.php'><font color='blue'>qui</font></a>";
  die;
}
 
//Altrimenti Prelevo il codice identificatico dell'utente loggato
session_start();
include ('include/header.php');
require ('include/config.php');
$conn=mysql_connect($dbHost,$dbUser,$dbPassword);
mysql_select_db($dbName);
$codadmin = $_SESSION['admin']; //id cod recuperato nel file di verifica


echo $menu;
                        
echo "INFORMAZIONE Amministratore <br />";

$agente = "SELECT * FROM Admin where AdminId=".$codadmin."";
$res = mysql_query($agente);
$rsAgente = mysql_fetch_assoc($res);
 echo "User: ".$rsAgente['AdminUser']."<br/ >";
 echo "Pass: ".$rsAgente['AdminPass']."<br/ >";
 echo "e-mail: ".$rsAgente['AdminMail']."<br/ >"; 
?>
