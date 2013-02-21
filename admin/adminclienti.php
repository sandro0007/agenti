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
$codadmin = $_SESSION['admin']; //id cod recuperato nel file di verifica
// Recupero il numero di pagina corrente.
// Generalmente si utilizza una querystring
$pag = $_GET['pag'];

// Controllo se $pag è valorizzato...
// ...in caso contrario gli assegno valore 1
if (!$pag) $pag = 1; 

echo $menu;

echo "
 <div class=\"box\" \">
            <a href=\"javascript:slideonlyone('newboxes1');\" >Cerca Cliente</a>
         </div>
         <div class=\"newboxes2\" id=\"newboxes1\" style=\" display: none;\">
         <form action=\"adminclienti.php\" method=\"post\">
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

// Controllo Resultato
switch($_GET['id']){
		case okupdate:
				echo "<h2>Aggiornamento Effettuato Correttamente</h2>";
			break;
		
		case koupdate:
				echo "<h2>Aggiornamento Non Effettuato</h2><br />".$_POST['msg'];
			break;
			
		case okserch:
				echo "<h2>Ricerca Effettuata Correttamente</h2>";
			break;
		
		case kosearch:
				echo "<h2>Ricerca Non Effettuata</h2><br />".$_POST['msg'];
			break;
		
		}
// Fine controllo Resultato

if(isset($_POST['stato'])){ // INIZIO CONTROLLO VARIABILE
	switch($_POST['stato']) {
			//INIZIO DEL CLIENTE
		case del:
			echo "Richiesta Cancellazione Cliente ".$_POST['IdCliente']."";
			break;
			// FINE DEL CLIENTE
			
			//INIZIO UPDATE CLIENTE
		case update:
			$sql = "UPDATE  `Clienti` SET  
					`ClienteNome` =  '".$_POST['ClienteNome']."',
					`ClienteCognome` =  '".$_POST['ClienteCognome']."',
					`ClienteRagione` =  '".$_POST['ClienteRagione']."',
					`ClienteCF` =  '".$_POST['ClienteCF']."' ,
					`ClientePI` =  '".$_POST['ClientePI']."',
					`ClienteDataNascita` =  '".$_POST['ClienteDataNascita']."',
					`ClienteLuogoNascita` =  '".$_POST['ClienteLuogoNascita']."',
					`ClienteProvinciaNascita` =  '".$_POST['ClienteProvinciaNascita']."',
					`ClienteTelefono` =  '".$_POST['ClienteTelefono']."',
					`ClienteFax` =  '".$_POST['ClienteFax']."',
					`ClienteCellulare` =  '".$_POST['ClienteCellulare']."',
					`ClienteMail` =  '".$_POST['ClienteMail']."',
					`ClienteSesso` =  '".$_POST['ClienteSesso']."',
					`ClienteTipoDocumento` =  '".$_POST['ClienteTipoDocumento']."',
					`ClienteNumeroDocumento` =  '".$_POST['ClienteNumeroDocumento']."',
					`ClienteEnteDocumento` =  '".$_POST['ClienteEnteDocumento']."',
					`ClienteRilascioDocumento` =  '".$_POST['ClienteRilascioDocumento']."',				
					`ClienteIndirizzo` =  '".$_POST['ClienteIndirizzo']."',
					`ClienteNumero` =  '".$_POST['ClienteNumero']."',
					`ClienteCap` =  '".$_POST['ClienteCap']."',
					`ClienteCitta` =  '".$_POST['ClienteCitta']."'
						WHERE  `idCliente` = '".$_POST['idCliente']."'";
			if (!mysql_query($sql))
			  {				  
				$msg = 'Error Aggiornamento Cliente: ' . mysql_error();
				echo '<script language=javascript>document.location.href="adminclienti.php?id=koupdate&msg='.$msg.'"</script>';
			  }
			echo '<script language=javascript>document.location.href="adminclienti.php?id=okupdate"</script>';
			break;
			// FINE UPDATE CLIENTE
			
			// INIZIO EDIT CLIENTE
		case edit:
			echo "<h2>Richiesta Modifica Cliente</h2>";
					
					$cliente = "SELECT * FROM Clienti where idCliente=".$_POST['IdCliente']."";
					$resCliente = mysql_query($cliente);
					$rsCliente = mysql_fetch_assoc($resCliente);
					/**
						 * 
						 * $rsCliente['idCliente']
						 * $rsCliente['ClienteNome']
						 * $rsCliente['ClienteCognome']
						 * $rsCliente['ClienteRagione']
						 * $rsCliente['ClienteCF']
						 * $rsCliente['ClientePI']
						 * $rsCliente['ClienteDataNascita']
						 * $rsCliente['ClienteLuogoNascita']
						 * $rsCliente['ClienteProvinciaNascita']
						 * $rsCliente['ClienteTelefono']
						 * $rsCliente['ClienteFax']
						 * $rsCliente['ClienteCellulare']
						 * $rsCliente['ClienteMail']
						 * $rsCliente['ClienteSesso']
						 * $rsCliente['ClienteTipoDocumento']
						 * $rsCliente['ClienteNumeroDocumento']
						 * $rsCliente['ClienteEnteDocumento']
						 * $rsCliente['ClienteRilascioDocumento']
						 * $rsCliente['ClienteIndirizzo']
						 * $rsCliente['ClienteNumero']
						 * $rsCliente['ClienteCap']
						 * $rsCliente['ClienteCitta']
						 * $rsCliente['ClienteTipologia']
						 * 
						 * */
						 
						echo "<h3>Modifica Cliente</h3>";
						if ($rsCliente['ClienteTipologia'] == 'Privato') {
						echo "<form action=\"adminclienti.php\" method=\"post\">
							<fieldset id=\"inputs\">
								<input id=\"ClienteCognome\" name=\"ClienteCognome\" type=\"text\" placeholder=\"Cognome\" value=\"".$rsCliente['ClienteCognome']."\" autofocus required>
								<input id=\"ClienteNome\" name=\"ClienteNome\" type=\"text\" placeholder=\"Nome\"  value=\"".$rsCliente['ClienteNome']."\" required>
								<input id=\"ClienteCF\" name=\"ClienteCF\" type=\"text\" placeholder=\"Codice Fiscale\" value=\"".$rsCliente['ClienteCF']."\" required><br />
								<input id=\"ClienteSesso\" name=\"ClienteSesso\" type=\"text\" placeholder=\"Sesso\" value=\"".$rsCliente['ClienteSesso']."\" required>
								<input id=\"ClienteDataNascita\" name=\"ClienteDataNascita\" type=\"text\" placeholder=\"Data di Nascita\" value=\"".$rsCliente['ClienteDataNascita']."\" required>
								<input id=\"ClienteLuogoNascita\" name=\"ClienteLuogoNascita\" type=\"text\" placeholder=\"Luogo di Nascita\" value=\"".$rsCliente['ClienteLuogoNascita']."\" required>
								<input id=\"ClienteProvinciaNascita\" name=\"ClienteProvinciaNascita\" type=\"text\" placeholder=\"Provincia di Nascita\" value=\"".$rsCliente['ClienteProvinciaNascita']."\" required><br />
								<h2>Documenti</h2>
								<input id=\"ClienteTipoDocumento\" name=\"ClienteTipoDocumento\" type=\"text\" placeholder=\"Tipo Documento\" value=\"".$rsCliente['ClienteTipoDocumento']."\" >
								<input id=\"ClienteNumeroDocumento\" name=\"ClienteNumeroDocumento\" type=\"text\" placeholder=\"Numero Documetno\" value=\"".$rsCliente['ClienteNumeroDocumento']."\" required>
								<input id=\"ClienteEnteDocumento\" name=\"ClienteEnteDocumento\" type=\"text\" placeholder=\"Ente Documento\" value=\"".$rsCliente['ClienteEnteDocumento']."\" required>
								<input id=\"ClienteRilascioDocumento\" name=\"ClienteRilascioDocumento\" type=\"text\" placeholder=\"Data Rilascio Documento\" value=\"".$rsCliente['ClienteRilascioDocumento']."\" required>
								<h2>Recapiti</h2>
								<input id=\"ClienteTelefono\" name=\"ClienteTelefono\" type=\"text\" placeholder=\"Telefono\" value=\"".$rsCliente['ClienteTelefono']."\" >
								<input id=\"ClienteFax\" name=\"ClienteFax\" type=\"text\" placeholder=\"Fax\" value=\"".$rsCliente['ClienteFax']."\" >
								<input id=\"ClienteCellulare\" name=\"ClienteCellulare\" type=\"text\" placeholder=\"Cellulare\" value=\"".$rsCliente['ClienteCellulare']."\" >
								<input id=\"ClienteMail\" name=\"ClienteMail\" type=\"text\" placeholder=\"E-Mail\" value=\"".$rsCliente['ClienteMail']."\" required>
								<h2>Dati Fatturazione</h2>
								<input id=\"ClienteIndirizzo\" name=\"ClienteIndirizzo\" type=\"text\" placeholder=\"Indirizzo\" value=\"".$rsCliente['ClienteIndirizzo']."\" required>
								<input id=\"ClienteNumero\" name=\"ClienteNumero\" type=\"text\" placeholder=\"Numero Civico\" value=\"".$rsCliente['ClienteNumero']."\" required>
								<input id=\"ClienteCap\" name=\"ClienteCap\" type=\"text\" placeholder=\"C.A.P.\" value=\"".$rsCliente['ClienteCap']."\" required>
								<input id=\"ClienteCitta\" name=\"ClienteCitta\" type=\"text\" placeholder=\"Citt&agrave\" value=\"".$rsCliente['ClienteCitta']."\" required>
								<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"update\" >
								<input id=\"ClienteTipologia\" name=\"ClienteTipologia\" type=\"hidden\" value=\"Privato\" >
								<input id=\"idCliente\" name=\"idCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
							</fieldset>
							<fieldset id=\"actions\">
								<input type=\"submit\" id=\"submit\" value=\"Aggiorna\">
							</fieldset>
						</form>";
					 }
					 if ($rsCliente['ClienteTipologia'] == 'Azienda') {
						 echo "
							<form action=\"adminclienti.php\" method=\"post\">
							<fieldset id=\"inputs\">
								<input id=\"ClienteRagione\" name=\"ClienteRagione\" type=\"text\" placeholder=\"Ragione Sociale\" value=\"".$rsCliente['ClienteRagione']."\" autofocus required>
								<input id=\"ClientePI\" name=\"ClientePI\" type=\"text\" placeholder=\"Partita Iva\" value=\"".$rsCliente['ClientePI']."\" required><br />
								<h2>Legale Rappresentante</h2>
								<input id=\"ClienteCognome\" name=\"ClienteCognome\" type=\"text\" placeholder=\"Cognome\" value=\"".$rsCliente['ClienteCognome']."\" autofocus required>
								<input id=\"ClienteNome\" name=\"ClienteNome\" type=\"text\" placeholder=\"Nome\"  value=\"".$rsCliente['ClienteNome']."\" required>
								<input id=\"ClienteCF\" name=\"ClienteCF\" type=\"text\" placeholder=\"Codice Fiscale\" value=\"".$rsCliente['ClienteCF']."\" required><br />
								<input id=\"ClienteSesso\" name=\"ClienteSesso\" type=\"text\" placeholder=\"Sesso\" value=\"".$rsCliente['ClienteSesso']."\" required>
								<input id=\"ClienteDataNascita\" name=\"ClienteDataNascita\" type=\"text\" placeholder=\"Data di Nascita\" value=\"".$rsCliente['ClienteDataNascita']."\" required>
								<input id=\"ClienteLuogoNascita\" name=\"ClienteLuogoNascita\" type=\"text\" placeholder=\"Luogo di Nascita\" value=\"".$rsCliente['ClienteLuogoNascita']."\" required>
								<input id=\"ClienteProvinciaNascita\" name=\"ClienteProvinciaNascita\" type=\"text\" placeholder=\"Provincia di Nascita\" value=\"".$rsCliente['ClienteProvinciaNascita']."\" required><br />
								<h2>Documenti</h2>
								<input id=\"ClienteTipoDocumento\" name=\"ClienteTipoDocumento\" type=\"text\" placeholder=\"Tipo Documento\" value=\"".$rsCliente['ClienteTipoDocumento']."\" >
								<input id=\"ClienteNumeroDocumento\" name=\"ClienteNumeroDocumento\" type=\"text\" placeholder=\"Numero Documetno\" value=\"".$rsCliente['ClienteNumeroDocumento']."\" required>
								<input id=\"ClienteEnteDocumento\" name=\"ClienteEnteDocumento\" type=\"text\" placeholder=\"Ente Documento\" value=\"".$rsCliente['ClienteEnteDocumento']."\" required>
								<input id=\"ClienteRilascioDocumento\" name=\"ClienteRilascioDocumento\" type=\"text\" placeholder=\"Data Rilascio Documento\" value=\"".$rsCliente['ClienteRilascioDocumento']."\" required>
								<h2>Recapiti</h2>
								<input id=\"ClienteTelefono\" name=\"ClienteTelefono\" type=\"text\" placeholder=\"Telefono\" value=\"".$rsCliente['ClienteTelefono']."\" >
								<input id=\"ClienteFax\" name=\"ClienteFax\" type=\"text\" placeholder=\"Fax\" value=\"".$rsCliente['ClienteFax']."\" >
								<input id=\"ClienteCellulare\" name=\"ClienteCellulare\" type=\"text\" placeholder=\"Cellulare\" value=\"".$rsCliente['ClienteCellulare']."\" >
								<input id=\"ClienteMail\" name=\"ClienteMail\" type=\"text\" placeholder=\"E-Mail\" value=\"".$rsCliente['ClienteMail']."\" required>
								<h2>Dati Fatturazione</h2>
								<input id=\"ClienteIndirizzo\" name=\"ClienteIndirizzo\" type=\"text\" placeholder=\"Indirizzo\" value=\"".$rsCliente['ClienteIndirizzo']."\"required>
								<input id=\"ClienteNumero\" name=\"ClienteNumero\" type=\"text\" placeholder=\"Numero Civico\" value=\"".$rsCliente['ClienteNumero']."\"required>
								<input id=\"ClienteCap\" name=\"ClienteCap\" type=\"text\" placeholder=\"C.A.P.\" value=\"".$rsCliente['ClienteCap']."\"required>
								<input id=\"ClienteCitta\" name=\"ClienteCitta\" type=\"text\" placeholder=\"Citt&agrave\" value=\"".$rsCliente['ClienteCitta']."\"required>
								<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"update\" >
								<input id=\"ClienteTipologia\" name=\"ClienteTipologia\" type=\"hidden\" value=\"Azienda\" >
								<input id=\"idCliente\" name=\"idCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
							</fieldset>
							<fieldset id=\"actions\">
								<input type=\"submit\" id=\"submit\" value=\"Aggiorna\">
							</fieldset>
						</form>";
						 }
						break; // FINE EDIT CLIENTE
			
			// INIZIO DETTAGLIO CLIENTE
		case more:
				echo "<h2>Richiesta Dettaglio Cliente</h2>";
				$cliente = "SELECT * FROM Clienti where idCliente=".$_POST['IdCliente']."";
				//echo $cliente;
				$res = mysql_query($cliente);
				$rsCliente = mysql_fetch_assoc($res);
					/* *
					 * 
						 * $rsCliente['idCliente']
						 * $rsCliente['ClienteNome']
						 * $rsCliente['ClienteCognome']
						 * $rsCliente['ClienteRagione']
						 * $rsCliente['ClienteCF']
						 * $rsCliente['ClientePI']
						 * $rsCliente['ClienteDataNascita']
						 * $rsCliente['ClienteLuogoNascita']
						 * $rsCliente['ClienteProvinciaNascita']
						 * $rsCliente['ClienteTelefono']
						 * $rsCliente['ClienteFax']
						 * $rsCliente['ClienteCellulare']
						 * $rsCliente['ClienteMail']
						 * $rsCliente['ClienteSesso']
						 * $rsCliente['ClienteTipoDocumento']
						 * $rsCliente['ClienteNumeroDocumento']
						 * $rsCliente['ClienteEnteDocumento']
						 * $rsCliente['ClienteRilascioDocumento']
						 * $rsCliente['ClienteIndirizzo']
						 * $rsCliente['ClienteNumero']
						 * $rsCliente['ClienteCap']
						 * $rsCliente['ClienteCitta']
						 * $rsCliente['ClienteTipologia']
					 * 
					 * */
				if ($rsCliente['ClienteTipologia'] == 'Privato'){
					echo "
					<table border=\"1\">
						<tr>
							<td>Cognome</td>
							<td>".$rsCliente['ClienteCognome']."</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>Nome</td>
							<td>".$rsCliente['ClienteNome']."</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>Codice Fiscale</td>
							<td>".$rsCliente['ClienteCF']."</td>
							<td>Sesso</td>
							<td>".$rsCliente['ClienteSesso']."</td>
						</tr>
						<tr>
							<td>Data di Nascita</td>
							<td>".$rsCliente['ClienteDataNascita']."</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>Luogo di Nascita</td>
							<td>".$rsCliente['ClienteLuogoNascita']."</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>Provincia di Nascita</td>
							<td>".$rsCliente['ClienteProvinciaNascita']."</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
									<td colspan=\"4\">Documenti</td>
								</tr>
								<tr>
									<td>Documento</td>
									<td>Numero</td>
									<td>Ente</td>
									<td>DataRilascio</td>
								</tr>
								<tr>
									<td>".$rsCliente['ClienteTipoDocumento']."</td>
									<td>".$rsCliente['ClienteNumeroDocumento']."</td>
									<td>".$rsCliente['ClienteEnteTipoDocumento']."</td>
									<td>".$rsCliente['ClienteRilascioTipoDocumento']."</td>
								</tr>
								<tr>
									<td colspan=\"4\">Dati Fatturazione</td>
								</tr>
								<tr>
									<td>Indirizzo</td>
									<td>Numero</td>
									<td>CAP</td>
									<td>Citta</td>
								</tr>
								<tr>
									<td>".$rsCliente['ClienteIndirizzo']."</td>
									<td>".$rsCliente['ClienteNumero']."</td>
									<td>".$rsCliente['ClienteCap']."</td>
									<td>".$rsCliente['ClienteCitta']."</td>
								</tr></table>
					
				";		 
					}
				if ($rsCliente['ClienteTipologia'] == 'Azienda'){
					echo "
					<table border=\"1\">
						<tr>
							<td>Ragione Sociale</td>
							<td>".$rsCliente['ClienteRagione']."</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>Partita Iva</td>
							<td>".$rsCliente['ClientePI']."</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td colspan=\"4\">Legale Rappresentante</td>
						</tr>
						<tr>
							<td>Cognome</td>
							<td>".$rsCliente['ClienteCognome']."</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>Nome</td>
							<td>".$rsCliente['ClienteNome']."</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>Codice Fiscale</td>
							<td>".$rsCliente['ClienteCF']."</td>
							<td>Sesso</td>
							<td>".$rsCliente['ClienteSesso']."</td>
						</tr>
						<tr>
							<td>Data di Nascita</td>
							<td>".$rsCliente['ClienteDataNascita']."</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>Luogo di Nascita</td>
							<td>".$rsCliente['ClienteLuogoNascita']."</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>Provincia di Nascita</td>
							<td>".$rsCliente['ClienteProvinciaNascita']."</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
									<td colspan=\"4\">Documenti</td>
								</tr>
								<tr>
									<td>Documento</td>
									<td>Numero</td>
									<td>Ente</td>
									<td>DataRilascio</td>
								</tr>
								<tr>
									<td>".$rsCliente['ClienteTipoDocumento']."</td>
									<td>".$rsCliente['ClienteNumeroDocumento']."</td>
									<td>".$rsCliente['ClienteEnteTipoDocumento']."</td>
									<td>".$rsCliente['ClienteRilascioTipoDocumento']."</td>
								</tr><tr>
									<td colspan=\"4\">Dati Fatturazione</td>
								</tr>
								<tr>
									<td>Indirizzo</td>
									<td>Numero</td>
									<td>CAP</td>
									<td>Citta</td>
								</tr>
								<tr>
									<td>".$rsCliente['ClienteIndirizzo']."</td>
									<td>".$rsCliente['ClienteNumero']."</td>
									<td>".$rsCliente['ClienteCap']."</td>
									<td>".$rsCliente['ClienteCitta']."</td>
								</tr>
							</table>";
				}
						 
				// VISUALIZZO TUTTI I CONTRATTI ATTIVI PER IL CLIENTE
				
				$contratti = "SELECT * FROM Contratti where Clienti_idCliente = ".$_POST['IdCliente']."";
				$res = mysql_query($contratti);
				$numrows=mysql_num_rows($res);
					
				if ($numrows == 0) {
						//NESSUN CONTRATTO ATTIVO
				echo "<h2>Nessun Contratto ATTIVO</h2>";
				} else {
						// VI SONO CONTRATTI ATTIVI
						echo "<h2>Tutti i Contratti del Cliente</h2>";
						echo "	<div class=\"tabella\" >
								<table>
									<tr>
									<td>Contratto Numero</td>
									<td>Nome Contratto</td>
									<td>Tipologia</td>
									<td>Stato</td>
									<td></td>
									</tr>";
				while ($rsContratti = mysql_fetch_assoc($res)){
						/**
						 *  
						 * $rsContratti['ContrattoId']
						 * $rsContratti['ContrattoNome']
						 * $rsContratti['ContrattoTipo']
						 * $rsContratti['ContrattoStato']
						 * 
						 * */
						echo "<tr>
								<td>".$rsContratti['ContrattoId']."</td>
								<td>".$rsContratti['ContrattoNome']."</td>
								<td>".$rsContratti['ContrattoTipo']."</td>
								<td>".$rsContratti['ContrattoStato']."</td>
								<td style=\"float:right\" >
								<form action=\"admincontratti.php\" method=\"post\" style=\"float: right;\">
										<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"edit\" >
										<input id=\"ContrattoId\" name=\"ContrattoId\" type=\"hidden\" value=\"".$rsContratti['ContrattoId']."\" >
										<input name=\"Edita Contratto\" type=\"image\" src=\"image\edit.gif\" alt=\"Edita Contratto\" title=\"Edita Contratto\"> 
									</fieldset>
								</form>
								<form action=\"admincontratti.php\" method=\"post\" style=\"float: right;\">
										<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"del\" >
										<input id=\"ContrattoId\" name=\"ContrattoId\" type=\"hidden\" value=\"".$rsContratti['ContrattoId']."\" >
										<input name=\"Cancella Contratto\" type=\"image\" src=\"image\delete.gif\" alt=\"Cancella Contratto\" title=\"Cancella Contratto\"> 
									</fieldset>
								</form>
								<form action=\"admincontratti.php\" method=\"post\" style=\"float: right;\">
										<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"more\" >
										<input id=\"ContrattoId\" name=\"ContrattoId\" type=\"hidden\" value=\"".$rsContratti['ContrattoId']."\" >
										<input name=\"Dettaglio Contratti\" type=\"image\" src=\"image\contract.gif\" alt=\"Dettaglio Contratti\" title=\"Dettaglio Contratti\"> 
									</fieldset>
								</form>
								</td>
							</tr>";
					}
						echo "</table>
				</div>";
			}
			break;
			// FINE DETTAGLIO CLIENTE
			
			case search: // Inizio Risultato Ricerca
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
					 // nessun risultato trovato
					if ($numrows == 0) {
							$msg = 'Nessuna corrispondenza con la Ricerca';
							echo '<script language=javascript>document.location.href="adminclienti.php?id=kosearch&msg='.$msg.'"</script>';
						}
						else // risultato trovato 
						{
							echo "<h3>Risultato Ricerca</h3>
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
										<form action=\"adminclienti.php\" method=\"post\" style=\"float: right;\">
												<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
												<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"more\" >
												<input name=\"Dettaglio Cliente\" type=\"image\" src=\"image\search.gif\" alt=\"Dettaglio\" title=\"Dettaglio Cliente\"> 
											</fieldset>
										</form>
										<form action=\"adminclienti.php\" method=\"post\" style=\"float: right;\">
												<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
												<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"edit\" >
												<input name=\"Edita Cliente\" type=\"image\" src=\"image\edit.gif\" alt=\"Edita Cliente\" title=\"Edita Cliente\"> 
											</fieldset>
										</form>
										<form action=\"adminclienti.php\" method=\"post\" style=\"float: right;\">
												<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
												<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"del\" >
												<input name=\"Cancella Cliente\" type=\"image\" src=\"image\delete.gif\" alt=\"Cancella Cliente\" title=\"Cancella Cliente\"> 
											</fieldset>
										</form>
										<form action=\"adminclienti.php\" method=\"post\" style=\"float: right;\">
												<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
												<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"moreall\" >
												<input name=\"Dettaglio Contratti\" type=\"image\" src=\"image\contract.gif\" alt=\"Dettaglio Contratti\" title=\"Dettaglio Contratti\"> 
											</fieldset>
										</form>
										<form action=\"admincontratti.php\" method=\"post\" style=\"float: right;\">
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
							} // Fine Risultato
				break; // FINE SEARCH
				
		
		} // FINE SWITCH
} // Fine Controllo Variabili

else // Visualizzazione di default 
{
		// Seleziono Tutti i Contratti
		$query = "SELECT * FROM Clienti";
		// conto il numero di righe totali
		$all_rows = mysql_num_rows(mysql_query($query));
		// definisco il numero totale di pagine
		$all_pages = ceil($all_rows / $ClientiPagina);
		// Calcolo da quale record iniziare
		$first = ($pag - 1) * $ClientiPagina;
		// Recupero i record per la pagina corrente...
		// utilizzando LIMIT per partire da $first e contare fino a $ContrattiPagine
		
		echo "<h2>Lista Clienti</h2>";
		$cliente = "SELECT * FROM Clienti LIMIT ".$first.", ".$ClientiPagina."";
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
					<form action=\"adminclienti.php\" method=\"post\" style=\"float: right;\">
							<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
							<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"more\" >
							<input name=\"Dettaglio Cliente\" type=\"image\" src=\"image\search.gif\" alt=\"Dettaglio\" title=\"Dettaglio Cliente\"> 
						</fieldset>
					</form>
					<form action=\"adminclienti.php\" method=\"post\" style=\"float: right;\">
							<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
							<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"edit\" >
							<input name=\"Edita Cliente\" type=\"image\" src=\"image\edit.gif\" alt=\"Edita Cliente\" title=\"Edita Cliente\"> 
						</fieldset>
					</form>
					<form action=\"adminclienti.php\" method=\"post\" style=\"float: right;\">
							<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
							<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"del\" >
							<input name=\"Cancella Cliente\" type=\"image\" src=\"image\delete.gif\" alt=\"Cancella Cliente\" title=\"Cancella Cliente\"> 
						</fieldset>
					</form>
					<form action=\"admincontratti.php\" method=\"post\" style=\"float: right;\">
							<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
							<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"more\" >
							<input name=\"Dettaglio Contratti\" type=\"image\" src=\"image\contract.gif\" alt=\"Dettaglio Contratti\" title=\"Dettaglio Contratti\"> 
						</fieldset>
					</form>
					</td>
				</tr>";
		}
			echo "</table>
			</div>";
}	// Fine Visualizzazione di default 
// Se le pagine totali sono più di 1...
			// stampo i link per andare avanti e indietro tra le diverse pagine!
			if ($all_pages > 1){
			  if ($pag > 1){
				echo "<a href=\"" . $_SERVER['PHP_SELF'] . "?pag=" . ($pag - 1) . "\">";
				echo "Pagina Indietro</a>&nbsp;";
			  } 
			  if ($all_pages > $pag){
				echo "<a href=\"" . $_SERVER['PHP_SELF'] . "?pag=" . ($pag + 1) . "\">";
				echo "Pagina Avanti</a>";
			  } 
			}

?>
