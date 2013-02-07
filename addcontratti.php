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



if(isset($_POST['step'])){
	switch ($_POST['step']) {
		case 1:
			echo "<h3>Tipologia Contratto</h3>";
			echo "<p>Seleziona la tipologia di contratto che si desidera attivare;</p>";
			$sql = "SELECT * FROM Tipologie";
			$res = mysql_query($sql);
			
			echo"<form action=\"addcontratti.php\" method=\"post\">
			<fieldset>
				<label>Tipologia</label>
				<select name=\"TipologiaId\">
				<option>...</option>
				";
				while($rsTipologie = mysql_fetch_assoc($res)){
					echo "<option value=\"".$rsTipologie['TipologiaId']."\">".$rsTipologie['TipologiaNome']."</option>";
					}
			echo"</select>
			<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$_POST['IdCliente']."\" >
			<input id=\"ClienteTipologia\" name=\"ClienteTipologia\" type=\"hidden\" value=\"".$_POST['ClienteTipologia']."\" >
			<input id=\"step\" name=\"step\" type=\"hidden\" value=\"2\" >
			</fieldset>
			<fieldset id=\"actions\">
            <input type=\"submit\" id=\"submit\" value=\"avanti\">
            </fieldset>
			</form>";
			break;
		case 2:
			echo "<h3>Offerta</h3>";
			echo "<p>Seleziona l'offerta da associare al contratto;</p>";
			if ($_POST['ClienteTipologia'] == 'Privato'){
				echo "Profilo Privato";
				}
			if ($_POST['ClienteTipologia'] == 'Azienda'){
				$sql = "SELECT * FROM Offerte WHERE TipologiaId = '".$_POST['TipologiaId']."' and OffertaDestinazione = 'Azienda'";
				$res = mysql_query($sql);
				echo"<form action=\"addcontratti.php\" method=\"post\">
				<fieldset>
					<label>Offerte</label>
					<select name=\"OffertaId\">
					<option>...</option>
					";
					while($rsOfferte = mysql_fetch_assoc($res)){
						echo "<option value=\"".$rsOfferte['OffertaId']."\">".$rsOfferte['OffertaNome']."</option>";
						}
				echo"</select>
				<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$_POST['IdCliente']."\" >
				<input id=\"ClienteTipologia\" name=\"ClienteTipologia\" type=\"hidden\" value=\"".$_POST['ClienteTipologia']."\" >
				<input id=\"step\" name=\"step\" type=\"hidden\" value=\"3\" >
				</fieldset>
				<fieldset id=\"actions\">
				<input type=\"submit\" id=\"submit\" value=\"avanti\">
				</fieldset>
				</form>";
				}
			break;
		case 3:
			echo "<h3>Completa Informazioni</h3>";
			echo "<p>Completa il contratto in ogni sua parte;</p>";
			$sql = "SELECT * FROM Offerte WHERE OffertaId = '".$_POST['OffertaId']."'";
			$res = mysql_query($sql);
			$rsOfferte = mysql_fetch_assoc($res);
			echo "<table border=\"1\">
					<tr>
						<td>Nome</td>
						<td>Canone</td>
						<td>Fatturazione</td>
					</tr>
					<tr>
						<td>".$rsOfferte['OffertaNome']."</td>
						<td>".$rsOfferte['OffertaCanone']."</td>
						<td>".$rsOfferte['OffertaPagamento']."</td>
					</tr>
					<tr>
						<td colspan=\"3\">Descrizione</td>
					</tr>
					<tr>
						<td colspan=\"3\">".$rsOfferte['OffertaDescrizione']."</td>
					</tr>
			</table>";
			print_r($_POST);
			break;
	}
}	else {
		// se viene chiamata direttamente la pagina
		echo '<script language=javascript>document.location.href="clienti.php"</script>';
}             

    


?>
