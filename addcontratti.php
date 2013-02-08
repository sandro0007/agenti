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
			echo "<h3>STEP 1 - Creazione Contratto</h3>";
			echo "<p>Seleziona la tipologia di contratto che si desidera attivare;</p>";
			$sql = "SELECT * FROM Tipologie";
			$res = mysql_query($sql);
			
			echo"<form action=\"addcontratti.php\" method=\"post\">
			<fieldset id=\"inputs\" style=\"border:1px solid #128F9A\">
				<legend>Tipologia Contratto</legend><br />
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
			echo "<h3>STEP 2 - Creazione Contratto</h3>";
			echo "<p>Seleziona l'offerta da associare al contratto;</p>";
			if ($_POST['ClienteTipologia'] == 'Privato'){
				$sql = "SELECT * FROM Offerte WHERE TipologiaId = '".$_POST['TipologiaId']."' and OffertaDestinazione = 'Privato'";
				$res = mysql_query($sql);
				echo"<form action=\"addcontratti.php\" method=\"post\">
				<fieldset id=\"inputs\" style=\"border:1px solid #128F9A\">
					<legend>Offerta</legend><br />
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
				<fieldset id=\"inputs\" style=\"border:1px solid #128F9A\">
					<legend>Offerta</legend><br />
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
			echo "<h3>STEP 3 - Creazione Contratto</h3>";
			$sql = "SELECT * FROM Offerte WHERE OffertaId = '".$_POST['OffertaId']."'";
			$res = mysql_query($sql);
			$rsOfferte = mysql_fetch_assoc($res);
			echo "
			<fieldset id=\"inputs\" style=\"border:1px solid #128F9A\">
					<legend>Dettaglio Offerta</legend><br />
						<label>Nome</label>
						<input type=\"text\" value=\"".$rsOfferte['OffertaNome']."\" readonly>
						<label>Canone</label>
						<input type=\"text\"value=\"".$rsOfferte['OffertaCanone']."\" readonly><br />
						<label>Fatturazione</label>
						<input type=\"text\" value=\"".$rsOfferte['OffertaPagamento']."\" readonly><br />
						<label>Descrizione</label>
						<textarea disabled rows=\"4\" cols=\"50\">".$rsOfferte['OffertaDescrizione']."</textarea>
						<br />
			</fieldset><br />";
			$cliente = "SELECT * FROM Clienti where idCliente=".$_POST['IdCliente']."";
			$resCliente = mysql_query($cliente);
			$rsCliente = mysql_fetch_assoc($resCliente);
			echo "
				<form action=\"addcontratti.php\" method=\"post\">
					<fieldset id=\"inputs\" style=\"border:1px solid #128F9A\">
					<legend>Dati Servizio</legend><br />
					<div>
						<a href=\"javascript:slideonlyone('boxattivazione');\" >Nuova Attivazione</a>
					 </div>
					 <div class=\"newboxes2\" id=\"boxattivazione\" style=\" display: none;\">
						<label>Tipologia linea</label>
						<select name=\"LineaDati\">
							<option value=\"isdn\">ISDN</option>
							<option value=\"pstn\">PSTN - POTS</option>
							<option value=\"naked\">Solo Dati</option>
							<option value=\"ibrida\">Dati & Fonia</option>
						</select>
						<br />
						<label>Numero Pilota</label>
						<input id=\"LineaPilota\" name=\"LineaPilota\" type=\"text\" placeholder=\"Numero Telefonico Adiacente\">
						<br />
					</div>
					<div>
						<a href=\"javascript:slideonlyone('boxmigrazione');\" >Migrazione Linea</a>
					 </div>
					 <div class=\"newboxes2\" id=\"boxmigrazione\" style=\" display: none;\">
						<label>Tipologia linea</label>
						<select name=\"LineaDati\">
							<option value=\"isdn\">ISDN</option>
							<option value=\"pstn\">PSTN - POTS</option>
							<option value=\"ibrida\">Dati & Fonia</option>
						</select>
						<br />
						<label>Numero Linea</label>
						<input id=\"LineaNumero\" name=\"LineaNumero\" type=\"text\" placeholder=\"Numero Telefonico da Migrare\"  required>
						<br />
						<label>Codice Segreto</label>
						<input id=\"LineaCodice\" name=\"LineaCodice\" type=\"text\" placeholder=\"Codice Migrazione\"  required>
						<br />
					</div>
					<br />
					</fieldset>
					<br />
					<fieldset id=\"inputs\" style=\"border:1px solid #128F9A\">
					<legend>Indirizzo Installazione</legend><br />
						<label>Indirizzo</label>
						<input id=\"ClienteIndirizzo1\" name=\"ClienteIndirizzo1\" type=\"text\" placeholder=\"Indirizzo\" value=\"".$rsCliente['ClienteIndirizzo']."\" required>
						<label>Civico</label>
						<input id=\"ClienteNumero1\" name=\"ClienteNumero1\" type=\"text\" placeholder=\"Numero Civico\" value=\"".$rsCliente['ClienteNumero']."\" required><br />
						<label>C.A.P.</label>
						<input id=\"ClienteCap1\" name=\"ClienteCap1\" type=\"text\" placeholder=\"C.A.P.\" value=\"".$rsCliente['ClienteCap']."\" required>
						<label>Citt&agrave</label>
						<input id=\"ClienteCitta1\" name=\"ClienteCitta1\" type=\"text\" placeholder=\"Citt&agrave\" value=\"".$rsCliente['ClienteCitta']."\" required>
					</fieldset><br />
					<fieldset id=\"inputs\" style=\"border:1px solid #128F9A\">
					<legend>Indirizzo Fatturazione</legend><br />
						<label>Indirizzo</label>
						<input id=\"ClienteIndirizzo1\" name=\"ClienteIndirizzo1\" type=\"text\" placeholder=\"Indirizzo\" value=\"".$rsCliente['ClienteIndirizzo']."\" required>
						<label>Civico</label>
						<input id=\"ClienteNumero1\" name=\"ClienteNumero1\" type=\"text\" placeholder=\"Numero Civico\" value=\"".$rsCliente['ClienteNumero']."\" required><br />
						<label>C.A.P.</label>
						<input id=\"ClienteCap1\" name=\"ClienteCap1\" type=\"text\" placeholder=\"C.A.P.\" value=\"".$rsCliente['ClienteCap']."\" required>
						<label>Citt&agrave</label>
						<input id=\"ClienteCitta1\" name=\"ClienteCitta1\" type=\"text\" placeholder=\"Citt&agrave\" value=\"".$rsCliente['ClienteCitta']."\" required>
					</fieldset><br />
					<fieldset id=\"inputs\" style=\"border:1px solid #128F9A\">
					<legend>Modalit&agrave Pagamento</legend>
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
					</fieldset>
					<br />
					<fieldset id=\"inputs\" style=\"border:1px solid #128F9A\">
					<legend>Opzioni</legend><br />
						<label>IP Statico</label>
						<select name=\"OpzioneIp\">
							<option value=\"0\">Nessuno</option>
							<option value=\"1\">1 Ip Statico</option>
							<option value=\"4\">4 Ip Statico</option>
							<option value=\"8\">8 Ip Statico</option>
							<option value=\"16\">16 Ip Statico</option>
							<option value=\"32\">32 Ip Statico</option>
						</select><br /><br />
						<label style=\"float: left;\">Acquisto e installazione Access Point</label><input type=\"checkbox\" name=\"OpzioneAP\" value=\"1\" style=\"float: left;\">
						<label style=\"float: rigth;\">Acquisto Switch 8 Porte</label><input type=\"checkbox\" name=\"OpzioneSwitch\" value=\"1\" style=\"float: rigth;\">
						<br />
						<br />
						<br />
						<label style=\"float: left;\">Pubblicazione Numero in Elenco Telefonico</label><input type=\"checkbox\" name=\"OpzioneElenco\" value=\"1\" style=\"float: left;\">
						<label style=\"float: rigth;\">Identificativo Chiamante (Chi &egrave)</label><input type=\"checkbox\" name=\"OpzioneChie\" value=\"1\" style=\"float: rigth;\">
						<br />
						<br />
						<br />
						<label style=\"float: left;\">Blocco Identificativo Chiamante (CLI Nascosto)</label><input type=\"checkbox\" name=\"OpzioniClinascosto\" value=\"1\" style=\"float: left;\">
						<label style=\"float: rigth;\">Trasferimento di Chiamata</label><input style=\"float: rigth;\" type=\"checkbox\" name=\"OpzioniTrasferimento\" value=\"1\">
						<br />
						<br />
						<br />
						<label style=\"float: left;\">Vuoi ricevere pubblicità?</label><input type=\"checkbox\" name=\"OpzioniPubblicita\" value=\"1\" style=\"float: left;\"><br />
					</fieldset><br />
						<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$_POST['IdCliente']."\" >
						<input id=\"ClienteTipologia\" name=\"ClienteTipologia\" type=\"hidden\" value=\"".$_POST['ClienteTipologia']."\" >
						<input id=\"OffertaId\" name=\"OffertaId\" type=\"hidden\" value=\"".$rsOfferte['OffertaId']."\" >
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
			echo "<h3>STEP 4 - Creazione Contratto</h3>";
			print_r($_POST);
			break;
	}
}	else {
		// se viene chiamata direttamente la pagina
		echo '<script language=javascript>document.location.href="clienti.php"</script>';
}             

    


?>
