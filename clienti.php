<?php
session_start();
include ('include/header.php');
require ('include/config.php');
//se non c'è la sessione registrata
if (!session_is_registered('autorizzato')) {
  echo "<h1>Area riservata, accesso negato.</h1>";
  echo "Per effettuare il login clicca <a href='index.php'><font color='blue'>qui</font></a>";
  die;
}
 
//Altrimenti Prelevo il codice identificatico dell'utente loggato
session_start();
$conn=mysql_connect($dbHost,$dbUser,$dbPassword);
mysql_select_db($dbName);
$cod = $_SESSION['cod']; //id cod recuperato nel file di verifica


echo $menu;

echo "
 <div class=\"box\" \">
            <a href=\"javascript:slideonlyone('newboxes1');\" >Cerca Cliente</a>
         </div>
         <div class=\"newboxes2\" id=\"newboxes1\" style=\" display: none;\">
         <form action=\"clienti.php\" method=\"post\">
        <fieldset id=\"inputs\">
            <input id=\"ClienteNome\" name=\"ClienteNome\" type=\"text\" placeholder=\"Nome\" autofocus>
            <input id=\"ClienteCognome\" name=\"ClienteCognome\" type=\"text\" placeholder=\"Cognome\">
            <input id=\"ClienteRagione\" name=\"ClienteRagione\" type=\"text\" placeholder=\"Ragione Sociale\">
            <input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"search\" >
			</fieldset> 
            <fieldset id=\"actions\">
            <input type=\"submit\" id=\"submit\" value=\"Cerca\">
			</fieldset>
           </form>
         </div>";

if(isset($_POST['stato'])){

		if(isset($_POST['ClienteNome']) && $_POST['ClienteNome'] != ''){
			$sql = "SELECT * FROM Clienti WHERE ClienteNome like '%".$_POST['ClienteNome']."%' order by ClienteNome ASC";
			}
		if(isset($_POST['ClienteCognome']) && $_POST['ClienteCognome'] != ''){
			$sql = "SELECT * FROM Clienti WHERE  
			ClienteCognome like '%".$_POST['ClienteCognome']."%' order by ClienteCognome ASC";
			}
		if(isset($_POST['ClienteRagione']) && $_POST['ClienteRagione'] != ''){
			$sql = "SELECT * FROM Clienti WHERE ClienteRagione like '%".$_POST['ClienteRagione']."%' order by ClienteRagione ASC";
			}
	
	//print_r($sql);
	$res = mysql_query($sql);
	$numrows=mysql_num_rows($res);
	
	if ($numrows == 0) {
	echo "<div class=\"warning\">Nessuna Corrispondenza Trovata</div>";
	echo "<h2>Lista Clienti</h2>";
	$cliente = "SELECT * FROM Clienti where Agenti_idAgenti=".$cod." order by ClienteCognome ASC";
	$res = mysql_query($cliente);
	echo "
			<div class=\"tabella\" >
				<table>
					<tr>
					<td>Cognome</td>
					<td>Nome</td>
					<td>Ragione Sociale</td>
					<td></td>
					</tr>";
	while ($rsCliente = mysql_fetch_assoc($res)){
		echo "<tr>
				<td>".$rsCliente['ClienteCognome']."</td>
				<td>".$rsCliente['ClienteNome']."</td>
				<td>".$rsCliente['ClienteRagione']."</td>
				<td style=\"float:right\" >
				<form action=\"schedaclienti.php\" method=\"post\" style=\"float: right;\">
						<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
						<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"more\" >
						<input name=\"Dettaglio Cliente\" type=\"image\" src=\"image\search.gif\" alt=\"Dettaglio\" title=\"Dettaglio Cliente\"> 
					</fieldset>
				</form>
				<form action=\"schedaclienti.php\" method=\"post\" style=\"float: right;\">
						<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
						<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"edit\" >
						<input name=\"Edita Cliente\" type=\"image\" src=\"image\edit.gif\" alt=\"Edita Cliente\" title=\"Edita Cliente\"> 
					</fieldset>
				</form>
				<form action=\"schedaclienti.php\" method=\"post\" style=\"float: right;\">
						<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
						<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"del\" >
						<input name=\"Cancella Cliente\" type=\"image\" src=\"image\delete.gif\" alt=\"Cancella Cliente\" title=\"Cancella Cliente\"> 
					</fieldset>
				</form>
				<form action=\"schedacontratti.php\" method=\"post\" style=\"float: right;\">
						<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
						<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"moreall\" >
						<input name=\"Dettaglio Contratti\" type=\"image\" src=\"image\contract.gif\" alt=\"Dettaglio Contratti\" title=\"Dettaglio Contratti\"> 
					</fieldset>
				</form>
				<form action=\"addcontratti.php\" method=\"post\" style=\"float: right;\">
						<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
						<input id=\"ClienteTipologia\" name=\"ClienteTipologia\" type=\"hidden\" value=\"".$rsCliente['ClienteTipologia']."\" >
						<input id=\"stato\" name=\"step\" type=\"hidden\" value=\"1\" >
						<input name=\"Aggiungi Contratto\" type=\"image\" src=\"image\addcontract.gif\" alt=\"Aggiungi Contratto\" title=\"Aggiungi Contratto\"> 
					</fieldset>
				</form>
				</td>
			</tr>";
	}
		echo "</table>
		</div>";
	}
 else {
	 
	 echo "
			<div class=\"tabella\" >
				<table>
					<tr>
					<td>Cognome</td>
					<td>Nome</td>
					<td>Ragione Sociale</td>
					<td ></td>
					</tr>";
	while ($rsCliente = mysql_fetch_assoc($res)){
		/**
		 * 
		 * $rsCliente['idCliente']
		 * $rsCliente['ClienteNome']
		 * $rsCliente['ClienteCognome']
		 * $rsCliente['ClienteRagione']
		 * $rsCliente['ClienteCF']
		 * $rsCliente['ClientePI']
		 * $rsCliente['ClienteMail']
		 * $rsCliente['ClienteIndirizzo']
		 * $rsCliente['ClienteNumero']
		 * $rsCliente['ClienteCap']
		 * $rsCliente['ClienteCitta']
		 * $rsCliente['ClienteTipologia']
		 * 
		 * */

		echo "<tr>
				<td>".$rsCliente['ClienteCognome']."</td>
				<td>".$rsCliente['ClienteNome']."</td>
				<td>".$rsCliente['ClienteRagione']."</td>
				<td style=\"float:right\" >
				<form action=\"schedaclienti.php\" method=\"post\" style=\"float: right;\">
						<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
						<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"more\" >
						<input name=\"Dettaglio Cliente\" type=\"image\" src=\"image\search.gif\" alt=\"Dettaglio\" title=\"Dettaglio Cliente\"> 
					</fieldset>
				</form>
				<form action=\"schedaclienti.php\" method=\"post\" style=\"float: right;\">
						<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
						<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"edit\" >
						<input name=\"Edita Cliente\" type=\"image\" src=\"image\edit.gif\" alt=\"Edita Cliente\" title=\"Edita Cliente\"> 
					</fieldset>
				</form>
				<form action=\"schedaclienti.php\" method=\"post\" style=\"float: right;\">
						<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
						<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"del\" >
						<input name=\"Cancella Cliente\" type=\"image\" src=\"image\delete.gif\" alt=\"Cancella Cliente\" title=\"Cancella Cliente\"> 
					</fieldset>
				</form>
				<form action=\"schedacontratti.php\" method=\"post\" style=\"float: right;\">
						<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
						<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"moreall\" >
						<input name=\"Dettaglio Contratti\" type=\"image\" src=\"image\contract.gif\" alt=\"Dettaglio Contratti\" title=\"Dettaglio Contratti\"> 
					</fieldset>
				</form>
				<form action=\"addcontratti.php\" method=\"post\" style=\"float: right;\">
						<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
						<input id=\"ClienteTipologia\" name=\"ClienteTipologia\" type=\"hidden\" value=\"".$rsCliente['ClienteTipologia']."\" >
						<input id=\"stato\" name=\"step\" type=\"hidden\" value=\"1\" >
						<input name=\"Aggiungi Contratto\" type=\"image\" src=\"image\addcontract.gif\" alt=\"Aggiungi Contratto\" title=\"Aggiungi Contratto\"> 
					</fieldset>
				</form>
				</td>
			</tr>";
	}
		echo "</table>
		</div>";
	
		
		}
}
else {
	switch($_GET['id']){
		case 1:
			echo "<div class=\"success\">Il cliente &egrave stato aggiornato : ".$_GET['msg']."</div>";
			break;
		case kocontratto:
			echo "<div class=\"error\">Il Contratto non &egrave stato inserito : ".$_GET['msg']."</div>";
			break;
		case ok:
			echo "<div class=\"success\">Il Cliente &egrave stato aggiornato : ".$_GET['msg']."</div>";
			break;
		case ko:
			echo "<div class=\"error\">Il Cliente non &egrave stato aggiornato : ".$_GET['msg']."</div>";
			break;
		case kodel:
			echo "<div class=\"error\">Il Cliente non &egrave stato cancellato : ".$_GET['msg']."</div>";
			break;
		case kodelfile:
			echo "<div class=\"error\">Il File non &egrave stato cancellato : ".$_GET['msg']."</div>";
			break;
		case okdel:
			echo "<div class=\"success\">Il Cliente &egrave stato cancellato : ".$_GET['msg']."</div>";
			break;
		case okdelfile:
			echo "<div class=\"success\">Il File &egrave stato cancellato : ".$_GET['msg']."</div>";
			break;
		
		}
	echo "<h2>Lista Clienti</h2>";
	$cliente = "SELECT * FROM Clienti where Agenti_idAgenti=".$cod." order by ClienteCognome ASC" ;
	$res = mysql_query($cliente);
	echo "
			<div class=\"tabella\" >
				<table>
					<tr>
					<td>Cognome</td>
					<td>Nome</td>
					<td>Ragione Sociale</td>
					<td ></td>
					</tr>";
	while ($rsCliente = mysql_fetch_assoc($res)){
		echo "<tr>
				<td>".$rsCliente['ClienteCognome']."</td>
				<td>".$rsCliente['ClienteNome']."</td>
				<td>".$rsCliente['ClienteRagione']."</td>
				<td style=\"float:right\" >
				<form action=\"schedaclienti.php\" method=\"post\" style=\"float: right;\">
						<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
						<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"more\" >
						<input name=\"Dettaglio Cliente\" type=\"image\" src=\"image\search.gif\" alt=\"Dettaglio\" title=\"Dettaglio Cliente\"> 
					</fieldset>
				</form>
				<form action=\"schedaclienti.php\" method=\"post\" style=\"float: right;\">
						<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
						<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"edit\" >
						<input name=\"Edita Cliente\" type=\"image\" src=\"image\edit.gif\" alt=\"Edita Cliente\" title=\"Edita Cliente\"> 
					</fieldset>
				</form>
				<form action=\"schedaclienti.php\" method=\"post\" style=\"float: right;\">
						<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
						<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"del\" >
						<input name=\"Cancella Cliente\" type=\"image\" src=\"image\delete.gif\" alt=\"Cancella Cliente\" title=\"Cancella Cliente\"> 
					</fieldset>
				</form>
				<form action=\"schedacontratti.php\" method=\"post\" style=\"float: right;\">
						<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
						<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"moreall\" >
						<input name=\"Dettaglio Contratti\" type=\"image\" src=\"image\contract.gif\" alt=\"Dettaglio Contratti\" title=\"Dettaglio Contratti\"> 
					</fieldset>
				</form>
				<form action=\"addcontratti.php\" method=\"post\" style=\"float: right;\">
						<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
						<input id=\"ClienteTipologia\" name=\"ClienteTipologia\" type=\"hidden\" value=\"".$rsCliente['ClienteTipologia']."\" >
						<input id=\"stato\" name=\"step\" type=\"hidden\" value=\"1\" >
						<input name=\"Aggiungi Contratto\" type=\"image\" src=\"image\addcontract.gif\" alt=\"Aggiungi Contratto\" title=\"Aggiungi Contratto\"> 
					</fieldset>
				</form>
				</td>
			</tr>";
	}
		echo "</table>
		</div>";
	
	}
?>
