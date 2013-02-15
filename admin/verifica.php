<?php
session_start(); //inizio la sessione
//includo i file necessari a collegarmi al db con relativo script di accesso
include("include/config.php"); 
 
//mi collego
$conn=mysql_connect($dbHost,$dbUser,$dbPassword);
mysql_select_db($dbName);
 
//variabili POST con anti sql Injection
$username=mysql_real_escape_string($_POST['username']); //faccio l'escape dei caratteri dannosi
$password=mysql_real_escape_string($_POST['password']); //sha1 cifra la password anche qui in questo modo corrisponde con quella del db
 
 $query = "SELECT * FROM Admin WHERE AdminUser = '$username' AND AdminPass = '$password' ";
 $ris = mysql_query($query) or die (mysql_error());
 $riga=mysql_fetch_array($ris);  
 
/*Prelevo l'identificativo dell'utente */
$codadmin=$riga['AdminId'];
 
/* Effettuo il controllo */
if ($codadmin == NULL) $trovato = 0 ;
else $trovato = 1;  
 
/* Username e password corrette */
if($trovato === 1) {
 
 /*Registro la sessione*/
  session_register('autorizzato');
 
  $_SESSION["autorizzato"] = 1;
 
  /*Registro il codice dell'amministratore*/
  $_SESSION['admmin'] = $codadmin;
 
 /*Redirect alla pagina riservata*/
   echo '<script language=javascript>document.location.href="main.php"</script>'; 
 
} else {
 
/*Username e password errati, redirect alla pagina di login*/
 echo '<script language=javascript>document.location.href="index.php"</script>';
 
}
?>
