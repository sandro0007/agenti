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
				$sql = "SELECT * FROM Offerte WHERE TipologiaId = '".$_POST['TipologiaId']."' and OffertaDestinazione = 'Privato'";
				$res = mysql_query($sql);
				echo"<form action=\"addcontratti.php\" method=\"post\">
				<fieldset>
					<label>Offerte Privato</label>
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
			if ($_POST['ClienteTipologia'] == 'Azienda'){
				$sql = "SELECT * FROM Offerte WHERE TipologiaId = '".$_POST['TipologiaId']."' and OffertaDestinazione = 'Azienda'";
				$res = mysql_query($sql);
				echo"<form action=\"addcontratti.php\" method=\"post\">
				<fieldset>
					<label>Offerte Azienda</label>
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
			$cliente = "SELECT * FROM Clienti where idCliente=".$_POST['IdCliente']."";
			$resCliente = mysql_query($cliente);
			$rsCliente = mysql_fetch_assoc($resCliente);
			echo "
				<form action=\"addcontratti.php\" method=\"post\">
					<fieldset>
						<h3>Dati Installazione</h3>
						<input id=\"ClienteIndirizzo1\" name=\"ClienteIndirizzo1\" type=\"text\" placeholder=\"Indirizzo\" value=\"".$rsCliente['ClienteIndirizzo']."\" required>
						<input id=\"ClienteNumero1\" name=\"ClienteNumero1\" type=\"text\" placeholder=\"Numero Civico\" value=\"".$rsCliente['ClienteNumero']."\" required>
						<input id=\"ClienteCap1\" name=\"ClienteCap1\" type=\"text\" placeholder=\"C.A.P.\" value=\"".$rsCliente['ClienteCap']."\" required>
						<input id=\"ClienteCitta1\" name=\"ClienteCitta1\" type=\"text\" placeholder=\"Citt&agrave\" value=\"".$rsCliente['ClienteCitta']."\" required>
						<h3>Dati Fatturazione</h3>
						<input id=\"ClienteIndirizzo2\" name=\"ClienteIndirizzo2\" type=\"text\" placeholder=\"Indirizzo\" value=\"".$rsCliente['ClienteIndirizzo']."\" required>
						<input id=\"ClienteNumero2\" name=\"ClienteNumero2\" type=\"text\" placeholder=\"Numero Civico\" value=\"".$rsCliente['ClienteNumero']."\" required>
						<input id=\"ClienteCap2\" name=\"ClienteCap2\" type=\"text\" placeholder=\"C.A.P.\" value=\"".$rsCliente['ClienteCap']."\" required>
						<input id=\"ClienteCitta2\" name=\"ClienteCitta2\" type=\"text\" placeholder=\"Citt&agrave\" value=\"".$rsCliente['ClienteCitta']."\" required>
						<h3>Dati Pagamento</h3>
						<label>Pagamento Canone ".$rsOfferte['OffertaPagamento']."</label>
						<select name=\"ContrattoPagamento\">
							<option value=\"Bollettino\">Bollettino Postale</option>
							<option value=\"RID\">RID</option>
							<option value=\"MAV\">MAV</option>
						</select><br />
						<label>U.T. Attivazione</label>
						<select name=\"ContrattoAttivazione\">
							<option value=\"Contanti\">Contanti al collaudo</option>
							<option value=\"Addebito\">Addebito prima fattura</option>
							<option>MAV</option>
						</select><br />
						<label>Ricezione Fattura</label>
						<select name=\"ContrattoFattura\">
							<option value=\"Digitale\">Fattura su e-mail</option>
							<option value=\"Cartaceo\">Fattura cartacea con addebito</option>
						</select><br />
						<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$_POST['IdCliente']."\" >
						<input id=\"ClienteTipologia\" name=\"ClienteTipologia\" type=\"hidden\" value=\"".$_POST['ClienteTipologia']."\" >
						<input id=\"step\" name=\"step\" type=\"hidden\" value=\"4\" >
					</fieldset>
					<fieldset id=\"actions\">
						<input type=\"submit\" id=\"submit\" value=\"avanti\">
					</fieldset>
				</form>
			";
			print_r($_POST);
			break;
		case 4:
			print_r($_POST);
			break;
	}
}	else {
		// se viene chiamata direttamente la pagina
		echo '<script language=javascript>document.location.href="clienti.php"</script>';
}             

    


?>
