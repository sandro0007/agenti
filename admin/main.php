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
                        
echo "<h2>INFORMAZIONE Amministratore</h2>";

$admin = "SELECT * FROM Admin WHERE AdminId = ".$codadmin."";
$res = mysql_query($admin);
$rsAdmin = mysql_fetch_assoc($res);
echo "
		<table >
			<tr>
				<td colspan = \"2\" bgcolor = \"#1E90FF\" ><center><b>Dettagli Amministratore</b></center></td>
			</tr>
			<tr>
				<td><b>Cognome</b></td>
				<td bgcolor = \"#E5E5E5\"><i>".$rsAdmin['AdminCognome']."</i></td>
			</tr>
			<tr>
				<td><b>Nome</b></td>
				<td bgcolor = \"#E5E5E5\"><i>".$rsAdmin['AdminNome']."</i></td>
			</tr>
			<tr>
				<td><b>E-Mail</b></td>
				<td bgcolor = \"#E5E5E5\"><i>".$rsAdmin['AdminMail']."</i></td>
			</tr>
		</table>";	
?>
