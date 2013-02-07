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
$cod = $_SESSION['cod']; //id cod recuperato nel file di verifica
echo $menu;

print_r($_POST);

$stato = $_POST['stato'];

if(isset($stato)){
	switch($stato){
		case del:
			echo "Richiesta Cancellazione  Contratti numero ".$_POST['ContrattoId']."";
			break;
			
		case edit:
			echo "Richiesta Modifica  Contratti numero ".$_POST['ContrattoId']."";
			break;
			
		case moreall:
			echo "Richiesta Dettaglio Contratti per il cliente ".$_POST['IdCliente']."";
			break;
			
		case more:
			echo "Richiesta Dettaglio Contratti numero ".$_POST['ContrattoId']."";
			break;
		}
	}
	else
	{
		// se viene chiamata direttamente la pagina
		echo '<script language=javascript>document.location.href="contratti.php"</script>';
		}
?>
