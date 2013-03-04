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
			echo "<fieldset id=\"inputs\" style=\"border:1px solid #128F9A\">
					<legend>Dettaglio Offerta</legend><br />
					<label>Nome</label>
						<input type=\"text\" value=\"".$rsOfferte['OffertaNome']."\" readonly><br />
					<label>Canone</label>
						<input type=\"text\"value=\"".$rsOfferte['OffertaCanone']."\" readonly><br />
					<label>Fatturazione</label>
						<input type=\"text\" value=\"".$rsOfferte['OffertaPagamento']."\" readonly><br />
					<label>Descrizione</label><br />
						<textarea readonly rows=\"4\" cols=\"50\">".$rsOfferte['OffertaDescrizione']."</textarea>
					</fieldset>
			<br />";
			
			$cliente = "SELECT * FROM Clienti where idCliente=".$_POST['IdCliente']."";
			$resCliente = mysql_query($cliente);
			$rsCliente = mysql_fetch_assoc($resCliente);
			echo "
				<form action=\"addcontratti.php\" method=\"post\">
					<fieldset id=\"inputs\" style=\"border:1px solid #128F9A\">
					<legend>Dati Servizio</legend><br />";
					
				echo "<h3>Nuova Attivazione</h3><br />
						<label>Tipologia linea</label>
						<select name=\"LineaDatiAttivazione\">
							<option value=\"nessuna\">Nessuna</option>
							<option value=\"isdn\">ISDN</option>
							<option value=\"pstn\">PSTN - POTS</option>
							<option value=\"naked\">Solo Dati</option>
							<option value=\"ibrida\">Dati & Fonia</option>
						</select>
						<br />
						
						<label>Numero Pilota</label>
						<input id=\"LineaPilota\" name=\"LineaPilota\" type=\"text\" placeholder=\"Numero Telefonico Adiacente\">
						<br />
						<h3>Migrazione linea esistente</h3>
						<br />
						<label>Migrazione Tipologia linea</label>
						<select name=\"LineaDatiMigrazione\">
							<option value=\"nessuna\">Nessuna</option>
							<option value=\"isdn\">ISDN</option>
							<option value=\"pstn\">PSTN - POTS</option>
							<option value=\"ibrida\">Dati & Fonia</option>
						</select>
						<br />
						<label>Numero Linea</label>
						<input id=\"LineaNumero\" name=\"LineaNumero\" type=\"text\" placeholder=\"Numero Telefonico da Migrare\">
						<br />
						<label>Codice Segreto</label>
						<input id=\"LineaCodice\" name=\"LineaCodice\" type=\"text\" placeholder=\"Codice Migrazione\">
						
					<br />";
					
					echo "</fieldset>
					<br />";
					
				echo "	<fieldset id=\"inputs\" style=\"border:1px solid #128F9A\">
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
						<input id=\"ClienteIndirizzo2\" name=\"ClienteIndirizzo2\" type=\"text\" placeholder=\"Indirizzo\" value=\"".$rsCliente['ClienteIndirizzo']."\" required>
						<label>Civico</label>
						<input id=\"ClienteNumero2\" name=\"ClienteNumero2\" type=\"text\" placeholder=\"Numero Civico\" value=\"".$rsCliente['ClienteNumero']."\" required><br />
						<label>C.A.P.</label>
						<input id=\"ClienteCap2\" name=\"ClienteCap2\" type=\"text\" placeholder=\"C.A.P.\" value=\"".$rsCliente['ClienteCap']."\" required>
						<label>Citt&agrave</label>
						<input id=\"ClienteCitta2\" name=\"ClienteCitta2\" type=\"text\" placeholder=\"Citt&agrave\" value=\"".$rsCliente['ClienteCitta']."\" required>
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
					<legend>RID</legend>
						<label>Banca</label>
						<input id=\"ContrattoBanca\" name=\"ContrattoBanca\" type=\"text\" placeholder=\"Banca\"><br />
						<label>Agenzia</label>
						<input id=\"ContrattoAgenzia\" name=\"ContrattoAgenzia\" type=\"text\" placeholder=\"Agenzia\"><br />
						<label>Localit&agrave</label>
						<input id=\"ContrattoLocalita\" name=\"ContrattoLocalita\" type=\"text\" placeholder=\"Localit&agrave\"><br />
						<label>Intestazione</label>
						<input id=\"ContrattoIntestazione\" name=\"ContrattoIntestazione\" type=\"text\" placeholder=\"Intestazione\"><br />
						<label>IBAN</label>
						<input id=\"ContrattoIban\" name=\"ContrattoIban\" type=\"text\" placeholder=\"IBAN\"><br />
					</fieldset>
					<br />
					<fieldset id=\"inputs\" style=\"border:1px solid #128F9A\">
					<legend>NOTE</legend>
						<label>Informazioni Aggiuntive</label>
						<textarea id=\"ContrattoNote\" name=\"ContrattoNote\" type=\"text\" placeholder=\"Note\">
						</textarea>
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
						<label style=\"float: left;\">Vuoi ricevere pubblicità?</label><input type=\"checkbox\" name=\"OpzioniPubblicita\" value=\"1\" style=\"float: left;\">
						<label style=\"float: rigth;\">Chiamata in attesa</label><input style=\"float: rigth;\" type=\"checkbox\" name=\"OpzioniAttesa\" value=\"1\">
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
			break;
		case 4:
			echo "<h3>STEP 4 - Riepilogo Contratto</h3>";
			// Prelevo il Cliente
			$sql = "SELECT * FROM Clienti WHERE idCliente = ".$_POST['IdCliente']."";
			$res = mysql_query($sql);
			$rsCliente = mysql_fetch_assoc($res);
			// Prelevo l'offerta
			$sql2 = "SELECT * FROM Offerte WHERE OffertaId = ".$_POST['OffertaId'].""; 
			$res2 = mysql_query($sql2);
			$rsOfferta = mysql_fetch_assoc($res2);
			
			echo "
			<form action=\"addcontratti.php\" method=\"POST\" name=\"form\">
				<table>
					<tr>
						<td colspan = \"8\" bgcolor = \"#1E90FF\" ><center><b>Dati Intestatario Contratto</b></center></td>
					</tr>
					<tr>
						<td><b>Cognome</b></td><td ><input id=\"ClienteCognome\" name=\"ClienteCognome\" type=\"text\" placeholder=\"Cognome\" value=\"".$rsCliente['ClienteCognome']."\" readonly></td>
						<td><b>Nome</b></td><td><input id=\"ClienteNome\" name=\"ClienteNome\" type=\"text\" placeholder=\"Nome\" value=\"".$rsCliente['ClienteNome']."\" readonly></td>
						<td><b>Codice Fiscale</b></td><td><input id=\"ClienteCF\" name=\"ClienteCF\" type=\"text\" placeholder=\"Codice Fiscale\" value=\"".$rsCliente['ClienteCF']."\" readonly></td>
						<td><b>Sesso</b></td>
							<td>";
							 if ($rsCliente['ClienteSesso'] == 'M' ) 
									{ 
										echo "<input id=\"ClienteSesso\" name=\"ClienteSesso\" type=\"text\" placeholder=\"Sesso\" value=\"".$rsCliente['ClienteSesso']."\" readonly>";
									} 
							if ($rsCliente['ClienteSesso'] == 'F' ) 
								{
										echo "<input id=\"ClienteSesso\" name=\"ClienteSesso\" type=\"text\" placeholder=\"Sesso\" value=\"".$rsCliente['ClienteSesso']."\" readonly>";
										}
									//~ else 
									//~ {
										//~ echo "M<input type=\"checkbox\" name=\"ClienteSesso\" value=\"M\"  readonly/>
											  //~ F<input type=\"checkbox\" name=\"ClienteSesso\" value=\"F\"  readonly/>";
										//~ }
						echo " </td>
					</tr>
					<tr>
						<td><b>Ragione Sociale</b></td><td colspan = \"4\"><input id=\"ClienteRagione\" name=\"ClienteRagione\" type=\"text\" placeholder=\"Ragione Sociale\" value=\"".$rsCliente['ClienteRagione']."\" readonly></td>
						<td><b>Partita Iva</b></td><td colspan = \"2\"><input id=\"ClientePI\" name=\"ClientePI\" type=\"text\" placeholder=\"Partita Iva\" value=\"".$rsCliente['ClientePI']."\" readonly></td>
					</tr>
					<tr>
						<td><b>Data Nascita</b></td><td><input id=\"ClienteDataNascita\" name=\"ClienteDataNascita\" type=\"text\" placeholder=\"Data di Nascita\" value=\"".$rsCliente['ClienteDataNascita']."\" readonly></td>
						<td><b>Luogo di Nascita</b></td><td colspan = \"3\"><input id=\"ClienteLuogoNascita\" name=\"ClienteLuogoNascita\" type=\"text\" placeholder=\"Luogo di Nascita\" value=\"".$rsCliente['ClienteLuogoNascita']."\" readonly></td>
						<td><b>Provincia</b></td><td><input id=\"ClienteProvinciaNascita\" name=\"ClienteProvinciaNascita\" type=\"text\" placeholder=\"Provioncia di Nascita\" value=\"".$rsCliente['ClienteProvinciaNascita']."\" readonly></td>
					</tr>
					<tr>
						<td><b>Indirizzo</b></td><td><input id=\"ClienteIndirizzo\" name=\"ClienteIndirizzo\" type=\"text\" placeholder=\"Indirizzo\" value=\"".$rsCliente['ClienteIndirizzo']."\" readonly></td>
						<td><b>Numero</b></td><td><input id=\"ClienteNumero\" name=\"ClienteNumero\" type=\"text\" placeholder=\"Civico\" value=\"".$rsCliente['ClienteNumero']."\" readonly></td>
						<td><b>C.A.P.</b></td><td><input id=\"ClienteCap\" name=\"ClienteCap\" type=\"text\" placeholder=\"CAP\" value=\"".$rsCliente['ClienteCap']." \"readonly></td>
						<td><b>Citt&agrave</b></td><td><input id=\"ClienteCitta\" name=\"ClienteCitta\" type=\"text\" placeholder=\"Citta\" value=\"".$rsCliente['ClienteCitta']."\" readonly></td>
					</tr>
					<tr>
						<td><b>Indirizzo Installazione</b></td><td><input id=\"ClienteIndirizzo1\" name=\"ClienteIndirizzo1\" type=\"text\" placeholder=\"Indirizzo\" value=\"".$_POST['ClienteIndirizzo1']."\" readonly></td>
						<td><b>Numero</b></td><td><input id=\"ClienteNumero1\" name=\"ClienteNumero1\" type=\"text\" placeholder=\"Civico\" value=\"".$_POST['ClienteNumero1']."\" readonly></td>
						<td><b>C.A.P.</b></td><td><input id=\"ClienteCap1\" name=\"ClienteCap1\" type=\"text\" placeholder=\"CAP\" value=\"".$_POST['ClienteCap1']."\" readonly></td>
						<td><b>Citt&agrave</b></td><td><input id=\"ClienteCitta1\" name=\"ClienteCitta1\" type=\"text\" placeholder=\"Citta\" value=\"".$_POST['ClienteCitta1']."\" readonly></td>
					</tr>
					<tr>
						<td><b>Indirizzo Corrisponenza</b></td><td><input id=\"ClienteIndirizzo\" name=\"ClienteIndirizzo2\" type=\"text\" placeholder=\"Indirizzo\" value=\"".$_POST['ClienteIndirizzo2']."\" readonly></td>
						<td><b>Numero</b></td><td><input id=\"ClienteNumero2\" name=\"ClienteNumero2\" type=\"text\" placeholder=\"Civico\" value=\"".$_POST['ClienteNumero2']."\" readonly></td>
						<td><b>C.A.P.</b></td><td><input id=\"ClienteCap2\" name=\"ClienteCap2\" type=\"text\" placeholder=\"CAP\" value=\"".$_POST['ClienteCap2']."\" readonly></td>
						<td><b>Citt&agrave</b></td><td><input id=\"ClienteCitta2\" name=\"ClienteCitta2\" type=\"text\" placeholder=\"Citta\" value=\"".$_POST['ClienteCitta2']."\" readonly></td>
					</tr>
					<tr>
						<td><b>Documento Identit&agrave</b></td>
							<td colspan = \"3\">";
							if ($rsCliente['ClienteTipoDocumento'] == 'CartaIdentita' ) 
									{ 
										echo "<input id=\"ClienteTipoDocumento\" name=\"ClienteTipoDocumento\" type=\"text\" placeholder=\"Tipo Documento\" value=\"".$rsCliente['ClienteTipoDocumento']."\" readonly>";	  
									}
							if ($rsCliente['ClienteTipoDocumento'] == 'Patente' ) 
									{ 
										echo "<input id=\"ClienteTipoDocumento\" name=\"ClienteTipoDocumento\" type=\"text\" placeholder=\"Tipo Documento\" value=\"".$rsCliente['ClienteTipoDocumento']."\" readonly>";	  
									}
							if ($rsCliente['ClienteTipoDocumento'] == 'Passaporto' ) 
									{ 
										echo "<input id=\"ClienteTipoDocumento\" name=\"ClienteTipoDocumento\" type=\"text\" placeholder=\"Tipo Documento\" value=\"".$rsCliente['ClienteTipoDocumento']."\" readonly>";	  
									}
							
				echo "</td>
						<td><b>Numero Documento</b></td><td><input id=\"ClienteNumeroDocumento\" name=\"ClienteNumeroDocumento\" type=\"text\" placeholder=\"Numero Documento\" value=\"".$rsCliente['ClienteNumeroDocumento']."\" readonly></td>
						<td><b>Rilasciato il</b></td><td><input id=\"ClienteRilascioDocumento\" name=\"ClienteRilascioDocumento\" type=\"text\" placeholder=\"Rilascio Documento\" value=\"".$rsCliente['ClienteRilascioDocumento']."\" readonly></td>
					</tr>
					<tr>
						<td><b>Rilasciato da</b></td>
						<td colspan=\"4\">
							<input id=\"ClienteEnteDocumento\" name=\"ClienteEnteDocumento\" type=\"text\" placeholder=\"Ente di Documento\" value=\"".$rsCliente['ClienteEnteDocumento']."\" readonly>
						</td>
						<td><b>di</b></td>
						<td colspan = \"2\"><input id=\"ClienteEnteDiDocumento\" name=\"ClienteEnteDiDocumento\" type=\"text\" placeholder=\"Ente di Documento\" value=\"".$rsCliente['ClienteEnteDiDocumento']."\" readonly></td>
					</tr>
					<tr>
						<td><b>Telefono</b></td><td><input id=\"ClienteTelefono\" name=\"ClienteTelefono\" type=\"text\" placeholder=\"Telefono\" value=\"".$rsCliente['ClienteTelefono']."\" readonly></td>
						<td><b>Fax</b></td><td><input id=\"ClienteFax\" name=\"ClienteFax\" type=\"text\" placeholder=\"Fax\" value=\"".$rsCliente['ClienteFax']."\" readonly></td>
						<td><b>Cellulare</b></td><td><input id=\"ClienteCelllulare\" name=\"ClienteCelllulare\" type=\"text\" placeholder=\"Cellulare\" value=\"".$rsCliente['ClienteCelllulare']."\" readonly></td>
						<td><b>E-Mail</b></td><td><input id=\"ClienteMail\" name=\"ClienteMail\" type=\"text\" placeholder=\"E-Mail\" value=\"".$rsCliente['ClienteMail']."\" readonly></td>
					</tr>
					<tr>
						<td colspan = \"8\"bgcolor = \"#1E90FF\" ><center><b>Dati Servizio</b></center></td>
					</tr>
					<tr>
						<td><b>Codice Contratto</b></td><td><input id=\"OffertaNome\" name=\"OffertaNome\" type=\"text\" placeholder=\"Nome Offerta\" value=\"".$rsOfferta['OffertaNome']."\" readonly></td>
						<td><b>Descrizione</b></td><td colspan =\"3\"><input id=\"OffertaDescrizione\" name=\"OffertaDescrizione\" type=\"text\" placeholder=\"Descrizione\" value=\"".$rsOfferta['OffertaDescrizione']."\" readonly></td>
						<td><b>Importo Mensile</b></td><td><input id=\"OffertaCanone\" name=\"OffertaCanone\" type=\"text\" placeholder=\"Canone\" value=\"".$rsOfferta['OffertaCanone']."\" readonly></td>
					</tr>
					<tr>
						<td><b>Linea Telefonica:</b></td>
						<td><b>ESISTENTE</b></td><td><input id=\"LineaDatiMigrazione\" name=\"LineaDatiMigrazione\" type=\"text\" placeholder=\"Linea Migrazione\" value=\"".$_POST['LineaDatiMigrazione']."\" readonly></td>
						<td><b>Numero Da Migrare</b></td>
						<td colspan = \"2\"><input id=\"LineaNumero\" name=\"LineaNumero\" type=\"text\" placeholder=\"Linea Numero Migrazione\" value=\"".$_POST['LineaNumero']."\" readonly></td>
						<td><b>Codice Migrazione</b></td>
						<td><input id=\"LineaCodice\" name=\"LineaCodice\" type=\"text\" placeholder=\"Codice Migrazione\" value=\"".$_POST['LineaCodice']."\" readonly></td>
					</tr>
					<tr>
						<td></td>
						<td><b>Nuova Attivazione</b></td>
						<td colspan = \"3\"><input id=\"LineaDatiAttivazione\" name=\"LineaDatiAttivazione\" type=\"text\" placeholder=\"Linea Attivazione\" value=\"".$_POST['LineaDatiAttivazione']."\" readonly></td>
						<td><b>Numero Pilota</b></td>
						<td colspan = \"2\"><input id=\"LineaPilota\" name=\"LineaPilota\" type=\"text\" placeholder=\"Linea Pilota\" value=\"".$_POST['LineaPilota']."\" readonly></td>
					</tr>
					<tr>
						<td><b>Pagamento Canone</b></td>
						<td><input id=\"ContrattoPagamento\" name=\"ContrattoPagamento\" type=\"text\" placeholder=\"Pagamento Canone\" value=\"".$_POST['ContrattoPagamento']." \" readonly></td>
						<td><b>U.T. Attivazione</b></td>
						<td><input id=\"ContrattoAttivazione\" name=\"ContrattoAttivazione\" type=\"text\" placeholder=\"Attivazione\" value=\"".$_POST['ContrattoAttivazione']." \" readonly></td>
						<td><b>Ricezione fattura</b></td>
						<td><input id=\"ContrattoFattura\" name=\"ContrattoFattura\" type=\"text\" placeholder=\"Ricezione Fattura\" value=\"".$_POST['ContrattoFattura']." \" readonly></td>
					</tr>
					<tr>
						<td colspan = \"8\"bgcolor = \"#1E90FF\" ><center><b>Servizi Opzionali</b></center></td>
					</tr>
					<tr>
						<td><b>Ip Statito</b></td>
						<td colspan = \"7\">
						<fieldset>";
							if ($_POST['OpzioneIp'] == '0')
							{
								echo "Nessuno<input type=\"radio\" name=\"OpzioneIp\" value=\"0\"  checked=\"checked\" /> - ";
								echo "1 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"1\" /> - ";
								echo "4 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"4\" /> - ";
								echo "8 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"8\" /> - ";	  
								echo "16 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"16\" /> - ";	  
								echo "32 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"32\" />";	  
								}
							if ($_POST['OpzioneIp'] == '1')
							{
								echo "Nessuno<input type=\"radio\" name=\"OpzioneIp\" value=\"0\"  /> - ";
								echo "1 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"1\" checked=\"checked\" /> - ";
								echo "4 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"4\" /> - ";
								echo "8 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"8\" /> - ";	  
								echo "16 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"16\" /> - ";	  
								echo "32 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"32\" />";	  
								}
							if ($_POST['OpzioneIp'] == '4')
							{
								echo "Nessuno<input type=\"radio\" name=\"OpzioneIp\" value=\"0\"   /> - ";
								echo "1 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"1\" /> - ";
								echo "4 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"4\" checked=\"checked\" /> - ";
								echo "8 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"8\" /> - ";	  
								echo "16 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"16\" /> - ";	  
								echo "32 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"32\" />";	  
								}
							if ($_POST['OpzioneIp'] == '8')
							{
								echo "Nessuno<input type=\"radio\" name=\"OpzioneIp\" value=\"0\"   /> - ";
								echo "1 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"1\" /> - ";
								echo "4 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"4\" /> - ";
								echo "8 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"8\" checked=\"checked\" /> - ";	  
								echo "16 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"16\" /> - ";	  
								echo "32 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"32\" />";	  
								}
							if ($_POST['OpzioneIp'] == '16')
							{
								echo "Nessuno<input type=\"radio\" name=\"OpzioneIp\" value=\"0\"   /> - ";
								echo "1 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"1\" /> - ";
								echo "4 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"4\" /> - ";
								echo "8 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"8\" /> - ";	  
								echo "16 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"16\" checked=\"checked\" /> - ";	  
								echo "32 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"32\" />";	  
								}
							if ($_POST['OpzioneIp'] == '32')
							{
								echo "Nessuno<input type=\"radio\" name=\"OpzioneIp\" value=\"0\"   /> - ";
								echo "1 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"1\" /> - ";
								echo "4 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"4\" /> - ";
								echo "8 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"8\" /> - ";	  
								echo "16 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"16\" /> - ";	  
								echo "32 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"32\" checked=\"checked\" />";	  
								}
								
				echo "	</td>
					</tr>
					<tr>
						<td colspan = \"4\">";
							if (isset($_POST['OpzioneAP']))
							{
								echo "Acquisto e installazione Access Point<input type=\"checkbox\" name=\"OpzioneAp\" value=\"1\"  checked=\"checked\" />";
							} else 
								{
									echo "Acquisto e installazione Access Point<input type=\"checkbox\" name=\"OpzioneAp\" value=\"1\"  />";
									}
							
						echo "</td>
						<td colspan = \"4\">";
							if (isset($_POST['OpzioneElenco']))
							{
								echo "Pubblicazione Numero in Elenco Telefonico<input type=\"checkbox\" name=\"OpzioneElenco\" value=\"1\"  checked=\"checked\" />";
							} else 
								{
									echo "Pubblicazione Numero in Elenco Telefonico<input type=\"checkbox\" name=\"OpzioneElenco\" value=\"1\" />";
									}
							
						echo "</td>
					</tr>
					<tr>
						<td colspan = \"4\">";
							if (isset($_POST['OpzioneChie']))
							{
								echo "Identificaticazione Chiamante (Chi &egrave)<input type=\"checkbox\" name=\"OpzioneChie\" value=\"1\"  checked=\"checked\" />";
							} else 
								{
									echo "Identificaticazione Chiamante (Chi &egrave)<input type=\"checkbox\" name=\"OpzioneChie\" value=\"1\" />";
									}
							
						echo "</td>
						<td colspan = \"4\">
						";
							if (isset($_POST['OpzioneClinascosto']))
							{
								echo "Blocco Identificazitvo Chiamante (CLI Nascosto)<input type=\"checkbox\" name=\"OpzioneClinascosto\" value=\"1\"  checked=\"checked\" />";
							} else 
								{
									echo "Blocco Identificazitvo Chiamante (CLI Nascosto)<input type=\"checkbox\" name=\"OpzioneClinascosto\" value=\"1\" />";
									}
							
						echo "</td>
					</tr>
					<tr>
						<td colspan = \"4\">";
							if (isset($_POST['OpzioneTrasferimento']))
							{
								echo "Trasferimento di chiamata<input type=\"checkbox\" name=\"OpzioneTrasferimento\" value=\"1\"  checked=\"checked\" />";
							} else 
								{
									echo "Trasferimento di chiamata<input type=\"checkbox\" name=\"OpzioneTrasferimento\" value=\"1\" />";
									}
							
						echo "</td>
						<td colspan = \"4\">";
							if (isset($_POST['OpzionePubblicita']))
							{
								echo "Vuole ricevere la pubblicit&agrave?<input type=\"checkbox\" name=\"OpzionePubblicita\" value=\"1\"  checked=\"checked\" />";
							} else 
								{
									echo "Vuole ricevere la pubblicit&agrave?<input type=\"checkbox\" name=\"OpzionePubblicita\" value=\"1\" />";
									}
							
						echo "</td>
					</tr>
					<tr>
						<td colspan = \"4\">";
							if (isset($_POST['OpzioneAttesa']))
							{
								echo "Chiamata in attesa<input type=\"checkbox\" name=\"OpzioneAttesa\" value=\"1\"  checked=\"checked\" />";
							} else 
								{
									echo "Chiamata in attesa<input type=\"checkbox\" name=\"OpzioneAttesa\" value=\"1\" />";
									}
							
						echo "</td>
						<td colspan = \"4\">";
							if (isset($_POST['OpzioneSwitch']))
							{
								echo "Acquisto Switch 8 Porte<input type=\"checkbox\" name=\"OpzioneSwitch\" value=\"1\"  checked=\"checked\" />";
							} else 
								{
									echo "Acquisto Switch 8 Porte<input type=\"checkbox\" name=\"OpzioneSwitch\" value=\"1\" />";
									}
							
						echo "</td>
					</tr>
					<tr>
						<td colspan = \"8\" bgcolor = \"#1E90FF\" ><center><b>RID</b></center></td>
					</tr>
					<tr>
						<td><b>Banca</b></td><td colspan = \"2\"><input id=\"ContrattoBanca\" name=\"ContrattoBanca\" type=\"text\" placeholder=\"Banca\" value=\"".$_POST['ContrattoBanca']."\" readonly></td>
						<td><b>Agenzia</b></td><td colspan = \"2\"><input id=\"ContrattoAgenzia\" name=\"ContrattoAgenzia\" type=\"text\" placeholder=\"Agenzia\" value=\"".$_POST['ContrattoAgenzia']."\" readonly></td>
						<td><b>Localit&agrave</b></td><td><input id=\"ContrattoLocalita\" name=\"ContrattoLocalita\" type=\"text\" placeholder=\"Localita\" value=\"".$_POST['ContrattoLocalita']."\" readonly></td>
					</tr>
					<tr>
						<td><b>Intestazione</b></td>
						<td colspan =\"7\"><input id=\"ContrattoIntestazione\" name=\"ContrattoIntestazione\" type=\"text\" placeholder=\"Intestazione\" value=\"".$_POST['ContrattoIntestazione']."\" readonly></td>
					</tr>
					<tr>
						<td><b>IBAN</b></td><td colspan = \"5\"><input id=\"ContrattoIban\" name=\"ContrattoIban\" type=\"text\" placeholder=\"IBAN\" value=\"".$_POST['ContrattoIban']."\" readonly></td>
					</tr>
					<tr>
						<td  colspan =\"8\" bgcolor = \"#1E90FF\" ><center><b>NOTE</b></center></td>
					</tr>
					<tr>
						<td colspan = \"8\">
						<textarea id=\"ContrattoNote\" name=\"ContrattoNote\" type=\"text\" placeholder=\"Note\" readonly>".$_POST['ContrattoNote']."</textarea></td>
					</tr>
				</table>
				<input id=\"OffertaId\" name=\"OffertaId\" type=\"hidden\" value=\"".$rsOfferta['OffertaId']."\" >
				<input id=\"OffertaCanone\" name=\"OffertaCanone\" type=\"hidden\" value=\"".$rsOfferta['OffertaCanone']."\" >
				<input id=\"idCliente\" name=\"idCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
				<input id=\"ClienteTipologia\" name=\"ClienteTipologia\" type=\"hidden\" value=\"".$rsCliente['ClienteTipologia']."\" >
				<input id=\"step\" name=\"step\" type=\"hidden\" value=\"5\" >
						<input  type=\"submit\" id=\"submit\" value=\"Inserisci\">
			</form>";
			break;
		case 5:
			// Prelevo l'id dell'ultimo contratto per generare parte del nome contratto
			$result = mysql_query('SELECT MAX( ContrattoId ) FROM Contratti');
			$row = mysql_fetch_assoc($result);
			$id = $row['id']+1;
			$lunId = strlen($id);
			for ($i=0;$i<$lunId;$i++) {
				 $tmp = $tmp.'0';
				 }
				$value = $tmp."".$id;
			// prelevo parte del codice agente per generare la parte del nome contratto
			$lunCod = strlen($cod);
			for ($i=0;$i<$lunCod;$i++) {
				 $tmpCod = $tmpCod.'0';
				 }
				$agente = $tmpCod."".$cod;
			$sql = "INSERT INTO Contratti ( ContrattoNome , 
						ContrattoData,
						ContrattoTipo, 
						ContrattoStato, 
						ContrattoFatturato, 
						ContrattoPagato, 
						ContrattoPagamento, 
						ContrattoAttivazione,
						ContrattoFattura,
						ContrattoIndirizzo1,
						ContrattoNumero1,
						ContrattoCap1,
						ContrattoCitta1,
						ContrattoIndirizzo2,
						ContrattoNumero2,
						ContrattoCap2,
						ContrattoCitta2,
						ContrattoBanca,
						ContrattoAgenzia,
						ContrattoLocalita,
						ContrattoIntestazione,
						ContrattoIban,
						ContrattoNote,
						ContrattoProvvigioni,
						Clienti_idCliente ) 
						VALUES
							(
							'AG".$agente."-".$value."',
							CURDATE(),
							'".$_POST['ClienteTipologia']."',
							'Inserito',
							'0',
							'0',
							'".$_POST['ContrattoPagamento']."',
							'".$_POST['ContrattoAttivazione']."',
							'".$_POST['ContrattoFattura']."',
							'".$_POST['ClienteIndirizzo1']."',
							'".$_POST['ClienteNumero1']."',
							'".$_POST['ClienteCap1']."',
							'".$_POST['ClienteCitta1']."',
							'".$_POST['ClienteIndirizzo2']."',
							'".$_POST['ClienteNumero2']."',
							'".$_POST['ClienteCap2']."',
							'".$_POST['ClienteCitta2']."',
							'".$_POST['ContrattoBanca']."',
							'".$_POST['ContrattoAgenzia']."',
							'".$_POST['ContrattoLocalita']."',
							'".$_POST['ContrattoIntestazione']."',
							'".$_POST['ContrattoIban']."',
							'".$_POST['ContrattoNote']."',
							'".$_POST['OffertaCanone']."',
							'".$_POST['idCliente']."'
							);";
				if (!mysql_query($sql))
					  {
					 $msg = 'Error Inserimento Contratto: ' . mysql_error();
					 echo '<script language=javascript>document.location.href="clienti.php?id=kocontratto&msg='.$msg.'"</script>';
					  }
			$ContrattoId = mysql_insert_id();
			$sql2 = "INSERT INTO Linea 
						( LineaDatiAttivazione,
							LineaPilota,
								LineaDatiMigrazione,
									LineaNumero,
										LineaCodice )
							VALUES
							( '".$_POST['LineaDatiAttivazione']."',
								'".$_POST['LineaPilota']."',
									'".$_POST['LineaDatiMigrazione']."',
										'".$_POST['LineaNumero']."',
											'".$_POST['LineaCodice']."');";
			if (!mysql_query($sql2))
					  {
					 $msg = 'Errore Inserimento Dati Linea: ' . mysql_error();
					 echo '<script language=javascript>document.location.href="clienti.php?id=kocontratto&msg='.$msg.'"</script>';
					  }
			$LineaId = mysql_insert_id();
			
			// Imposto i corretti valori delle opzioni
			if(isset($_POST['OpzioneAP'])) {
				$OpzioneAP = $_POST['OpzioneAP'];
				} else {
					$OpzioneAP = '0';
					}
			if(isset($_POST['OpzioneElenco'])) {
				$OpzioneElenco = $_POST['OpzioneElenco'];
				} else {
					$OpzioneElenco = '0';
					}
			if(isset($_POST['OpzioneChie'])) {
				$OpzioneChie = $_POST['OpzioneChie'];
				} else {
					$OpzioneChie = '0';
					}
					
			if(isset($_POST['OpzioneClinascosto'])) {
				$OpzioneClinascosto = $_POST['OpzioneClinascosto'];
				} else {
					$OpzioneClinascosto = '0';
					}
			if(isset($_POST['OpzioneTrasferimento'])) {
				$OpzioneTrasferimento = $_POST['OpzioneTrasferimento'];
				} else {
					$OpzioneTrasferimento = '0';
					}
			if(isset($_POST['OpzionePubblicita'])) {
				$OpzionePubblicita = $_POST['OpzionePubblicita'];
				} else {
					$OpzionePubblicita = '0';
					}
					
			if(isset($_POST['OpzioneSwitch'])) {
				$OpzioneSwitch = $_POST['OpzioneSwitch'];
				} else {
					$OpzioneSwitch = '0';
					}
			if(isset($_POST['OpzioneAttesa'])) {
				$OpzioneAttesa = $_POST['OpzioneAttesa'];
				} else {
					$OpzioneAttesa = '0';
					}
			// FINE CHECK		
			$sql3 = "INSERT INTO  Opzioni (
							`OpzioneAP` ,
								`OpzioneElenco` ,
									`OpzioneChie` ,
										`OpzioneClinascosto` ,
											`OpzioneTrasferimento` ,
												`OpzionePubblicita` ,
													`OpzioneSwitch`,
														OpzioneAttesa)
								VALUES (
								'".$OpzioneAP."',  
									'".$OpzioneElenco."',  
										'".$OpzioneChie."',  
											'".$OpzioneClinascosto."',  
												'".$OpzioneTrasferimento."',  
													'".$OpzionePubblicita."',  
														'".$OpzioneSwitch."',
															'".$OpzioneAttesa."'
								);";
			if (!mysql_query($sql3))
					  {
					 $msg = 'Errore Inserimento Opzioni Linea: ' . mysql_error();
					 echo '<script language=javascript>document.location.href="clienti.php?id=kocontratto&msg='.$msg.'"</script>';
					  }
			$OpzioneId = mysql_insert_id();
			
			// Associazione Contratto - Linea
			$sql4 = "INSERT INTO  Contratti_Linea (
							`ContrattoId` ,
								`LineaId`)
								VALUES (
								'".$ContrattoId."',  
									'".$LineaId."');";
			if (!mysql_query($sql4))
					  {
					 $msg = 'Errore Associazione Contratto - Linea: ' . mysql_error();
					 echo '<script language=javascript>document.location.href="clienti.php?id=kocontratto&msg='.$msg.'"</script>';
					  }
			// Associazione Contratto - Opzioni
			$sql5 = "INSERT INTO  Contratti_Opzioni (
							`ContrattoId` ,
								`OpzioneId`)
								VALUES (
								'".$ContrattoId."',  
									'".$OpzioneId."');";
			if (!mysql_query($sql5))
					  {
					 $msg = 'Errore Associazione Contratto - Opzioni: ' . mysql_error();
					 echo '<script language=javascript>document.location.href="clienti.php?id=kocontratto&msg='.$msg.'"</script>';
					  }
			// Associazione Contratto - Agente
			$sql6 = "INSERT INTO  Agenti_Clienti_Contratti (
							`AgenteId` ,
								`ContrattoId`)
								VALUES (
								'".$cod."',  
									'".$ContrattoId."');";
			if (!mysql_query($sql6))
					  {
					 $msg = 'Errore Associazione Contratto - Agente: ' . mysql_error();
					 echo '<script language=javascript>document.location.href="clienti.php?id=kocontratto&msg='.$msg.'"</script>';
					  }
			// Associazione Contratto - Offerta
			$sql7 = "INSERT INTO  Contratti_Offerte (
							`OffertaId` ,
								`ContrattoId`)
								VALUES (
								'".$_POST['OffertaId']."',  
									'".$ContrattoId."');";
			if (!mysql_query($sql7))
					  {
					 $msg = 'Errore Associazione Contratto - Offerta: ' . mysql_error();
					 echo '<script language=javascript>document.location.href="clienti.php?id=kocontratto&msg='.$msg.'"</script>';
					  }
			echo "Contratto Inserito Correttamente <br />";
			mail("supporto@linkspace.it", "Inserito Contratto ".$ContrattoId."");
			$msg = 'Contratto inserito correttamente';
			echo '<script language=javascript>document.location.href="contratti.php?id=okcontratto&msg='.$msg.'"</script>';
			break;
		
	}
}	else {
		// se viene chiamata direttamente la pagina
		echo '<script language=javascript>document.location.href="clienti.php"</script>';
}             

    


?>
