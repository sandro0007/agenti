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

//print_r($_POST);

if(isset($_POST['stato'])){
	switch($_POST['stato']){
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
					`ClienteMail` =  '".$_POST['ClienteMail']."',
					`ClienteIndirizzo1` =  '".$_POST['ClienteIndirizzo1']."',
					`ClienteNumero1` =  '".$_POST['ClienteNumero1']."',
					`ClienteCap1` =  '".$_POST['ClienteCap1']."',
					`ClienteCitta1` =  '".$_POST['ClienteCitta1']."',
					`ClienteIndirizzo2` =  '".$_POST['ClienteIndirizzo2']."',
					`ClienteNumero2` =  '".$_POST['ClienteNumero2']."',
					`ClienteCap2` =  '".$_POST['ClienteCap2']."',
					`ClienteCitta2` =  '".$_POST['ClienteCitta2']."',
					`ClienteIndirizzo3` =  '".$_POST['ClienteIndirizzo3']."',
					`ClienteNumero3` =  '".$_POST['ClienteNumero3']."',
					`ClienteCap3` =  '".$_POST['ClienteCap3']."',
					`ClienteCitta3` =  '".$_POST['ClienteCitta3']."'
						WHERE  `idCliente` =".$_POST['idCliente']."";
			if (!mysql_query($sql))
			  {
			  die('Error Aggiornamento Cliente: ' . mysql_error());
			  }
			echo "<h2>Aggiornamento Effettuato Correttamente</h2>";
			
			break;
			// FINE UPDATE CLIENTE
			
			// INIZIO EDIT CLIENTE
		case edit:
			echo "<h2>Richiesta Modifica Cliente</h2>";
				$contratti = "SELECT * FROM Contratti where Clienti_idCliente = ".$_POST['IdCliente']."";
				$res = mysql_query($contratti);
				$numrows=mysql_num_rows($res);
					
				if ($numrows == 0) {
						//NESSUN CONTRATTO ATTIVO - POSSIBILE EDITARE IL CLIENTE
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
						 * $rsCliente['ClienteMail']
						 * $rsCliente['ClienteIndirizzo1']
						 * $rsCliente['ClienteNumero1']
						 * $rsCliente['ClienteCap1']
						 * $rsCliente['ClienteCitta1']
						 * $rsCliente['ClienteIndirizzo2']
						 * $rsCliente['ClienteNumero2']
						 * $rsCliente['ClienteCap2']
						 * $rsCliente['ClienteCitta2']
						 * $rsCliente['ClienteIndirizzo3']
						 * $rsCliente['ClienteNumero3']
						 * $rsCliente['ClienteCap3']
						 * $rsCliente['ClienteCitta3']
						 * $rsCliente['ClienteTipologia']
						 * 
						 * */
						 
						echo "<h2>Modifica Cliente</h2>";
						if ($rsCliente['ClienteTipologia'] == 'Privato') {
						echo "<form action=\"schedaclienti.php\" method=\"post\">
							<fieldset id=\"inputs\">
								<input id=\"ClienteNome\" name=\"ClienteNome\" type=\"text\" placeholder=\"Nome\" autofocus value=\"".$rsCliente['ClienteNome']."\" required>
								<input id=\"ClienteCognome\" name=\"ClienteCognome\" type=\"text\" placeholder=\"Cognome\" value=\"".$rsCliente['ClienteCognome']."\"required>
								<input id=\"ClienteCF\" name=\"ClienteCF\" type=\"text\" placeholder=\"Codice Fiscale\" value=\"".$rsCliente['ClienteCF']."\"required><br />
								<h2>Recapito E-Mail</h2>
								<input id=\"ClienteMail\" name=\"ClienteMail\" type=\"text\" placeholder=\"E-Mail\" value=\"".$rsCliente['ClienteMail']."\"required>
								<h2>Dati Fatturazione</h2>
								<input id=\"ClienteIndirizzo1\" name=\"ClienteIndirizzo1\" type=\"text\" placeholder=\"Indirizzo\" value=\"".$rsCliente['ClienteIndirizzo1']."\"required>
								<input id=\"ClienteNumero1\" name=\"ClienteNumero1\" type=\"text\" placeholder=\"Numero Civico\" value=\"".$rsCliente['ClienteNumero1']."\"required>
								<input id=\"ClienteCap1\" name=\"ClienteCap1\" type=\"text\" placeholder=\"C.A.P.\" value=\"".$rsCliente['ClienteCap1']."\"required>
								<input id=\"ClienteCitta1\" name=\"ClienteCitta1\" type=\"text\" placeholder=\"Citt&agrave\" value=\"".$rsCliente['ClienteCitta1']."\"required>
								<h2>Dati Installazione</h2>
								<input id=\"ClienteIndirizzo2\" name=\"ClienteIndirizzo2\" type=\"text\" placeholder=\"Indirizzo\" value=\"".$rsCliente['ClienteIndirizzo2']."\">
								<input id=\"ClienteNumero2\" name=\"ClienteNumero2\" type=\"text\" placeholder=\"Numero Civico\" value=\"".$rsCliente['ClienteNumero2']."\">
								<input id=\"ClienteCap2\" name=\"ClienteCap2\" type=\"text\" placeholder=\"C.A.P.\" value=\"".$rsCliente['ClienteCap2']."\">
								<input id=\"ClienteCitta2\" name=\"ClienteCitta2\" type=\"text\" placeholder=\"Citt&agrave\" value=\"".$rsCliente['ClienteCitta2']."\" >
								<h2>Dati Spedizione</h2>
								<input id=\"ClienteIndirizzo3\" name=\"ClienteIndirizzo3\" type=\"text\" placeholder=\"Indirizzo\" value=\"".$rsCliente['ClienteIndirizzo3']."\" >
								<input id=\"ClienteNumero3\" name=\"ClienteNumero3\" type=\"text\" placeholder=\"Numero Civico\" value=\"".$rsCliente['ClienteNumero3']."\" >
								<input id=\"ClienteCap3\" name=\"ClienteCap3\" type=\"text\" placeholder=\"C.A.P.\" value=\"".$rsCliente['ClienteCap3']."\" >
								<input id=\"ClienteCitta3\" name=\"ClienteCitta3\" type=\"text\" placeholder=\"Citt&agrave\" value=\"".$rsCliente['ClienteCitta3']."\" >
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
							<form action=\"schedaclienti.php\" method=\"post\">
							<fieldset id=\"inputs\">
								<input id=\"ClienteRagione\" name=\"ClienteRagione\" type=\"text\" placeholder=\"Ragione Sociale\" value=\"".$rsCliente['ClienteRagione']."\" autofocus required>
								<input id=\"ClientePI\" name=\"ClientePI\" type=\"text\" placeholder=\"Partita Iva\" value=\"".$rsCliente['ClientePI']."\" required><br />
								<h2>Recapito E-Mail</h2>
								<input id=\"ClienteMail\" name=\"ClienteMail\" type=\"text\" placeholder=\"E-Mail\" value=\"".$rsCliente['ClienteMail']."\"required>
								<h2>Dati Fatturazione</h2>
								<input id=\"ClienteIndirizzo1\" name=\"ClienteIndirizzo1\" type=\"text\" placeholder=\"Indirizzo\" value=\"".$rsCliente['ClienteIndirizzo1']."\"required>
								<input id=\"ClienteNumero1\" name=\"ClienteNumero1\" type=\"text\" placeholder=\"Numero Civico\" value=\"".$rsCliente['ClienteNumero1']."\"required>
								<input id=\"ClienteCap1\" name=\"ClienteCap1\" type=\"text\" placeholder=\"C.A.P.\" value=\"".$rsCliente['ClienteCap1']."\"required>
								<input id=\"ClienteCitta1\" name=\"ClienteCitta1\" type=\"text\" placeholder=\"Citt&agrave\" value=\"".$rsCliente['ClienteCitta1']."\"required>
								<h2>Dati Installazione</h2>
								<input id=\"ClienteIndirizzo2\" name=\"ClienteIndirizzo2\" type=\"text\" placeholder=\"Indirizzo\" value=\"".$rsCliente['ClienteIndirizzo2']."\">
								<input id=\"ClienteNumero2\" name=\"ClienteNumero2\" type=\"text\" placeholder=\"Numero Civico\" value=\"".$rsCliente['ClienteNumero2']."\">
								<input id=\"ClienteCap2\" name=\"ClienteCap2\" type=\"text\" placeholder=\"C.A.P.\" value=\"".$rsCliente['ClienteCap2']."\">
								<input id=\"ClienteCitta2\" name=\"ClienteCitta2\" type=\"text\" placeholder=\"Citt&agrave\" value=\"".$rsCliente['ClienteCitta2']."\" >
								<h2>Dati Spedizione</h2>
								<input id=\"ClienteIndirizzo3\" name=\"ClienteIndirizzo3\" type=\"text\" placeholder=\"Indirizzo\" value=\"".$rsCliente['ClienteIndirizzo3']."\" >
								<input id=\"ClienteNumero3\" name=\"ClienteNumero3\" type=\"text\" placeholder=\"Numero Civico\" value=\"".$rsCliente['ClienteNumero3']."\" >
								<input id=\"ClienteCap3\" name=\"ClienteCap3\" type=\"text\" placeholder=\"C.A.P.\" value=\"".$rsCliente['ClienteCap3']."\" >
								<input id=\"ClienteCitta3\" name=\"ClienteCitta3\" type=\"text\" placeholder=\"Citt&agrave\" value=\"".$rsCliente['ClienteCitta3']."\" >
								<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"update\" >
								<input id=\"ClienteTipologia\" name=\"ClienteTipologia\" type=\"hidden\" value=\"Azienda\" >
								<input id=\"idCliente\" name=\"idCliente\" type=\"hidden\" value=\"".$rsCliente['idCliente']."\" >
							</fieldset>
							<fieldset id=\"actions\">
								<input type=\"submit\" id=\"submit\" value=\"Aggiorna\">
							</fieldset>
						</form>";
						 }
						 
				} else {
						// CONTRATTI PRESENTI - NON E POSSIBILE EDITARE IL CLIENTE 
					echo "Non posso editare";
				}
			break;
			// FINE EDIT CLIENTE
			
			// INIZIO DETTAGLIO CLIENTE
		case more:
				echo "<h2>Richiesta Dettaglio Cliente</h2>";
				$cliente = "SELECT * FROM Clienti where idCliente=".$_POST['IdCliente']."";
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
					 * $rsCliente['ClienteMail']
					 * $rsCliente['ClienteIndirizzo1']
					 * $rsCliente['ClienteNumero1']
					 * $rsCliente['ClienteCap1']
					 * $rsCliente['ClienteCitta1']
					 * $rsCliente['ClienteIndirizzo2']
					 * $rsCliente['ClienteNumero2']
					 * $rsCliente['ClienteCap2']
					 * $rsCliente['ClienteCitta2']
					 * $rsCliente['ClienteIndirizzo3']
					 * $rsCliente['ClienteNumero3']
					 * $rsCliente['ClienteCap3']
					 * $rsCliente['ClienteCitta3']
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
							<td></td>
							<td></td>
						</tr>";
						if (isset($rsCliente['ClienteIndirizzo1']) and isset($rsCliente['ClienteNumero1']) and isset($rsCliente['ClienteCap1']) and isset($rsCliente['ClienteCitta1'])) {
								echo "<tr>
									<td colspan=\"4\">Dati Fatturazione</td>
								</tr>
								<tr>
									<td>Indirizzo</td>
									<td>Numero</td>
									<td>CAP</td>
									<td>Citta</td>
								</tr>
								<tr>
									<td>".$rsCliente['ClienteIndirizzo1']."</td>
									<td>".$rsCliente['ClienteNumero1']."</td>
									<td>".$rsCliente['ClienteCap1']."</td>
									<td>".$rsCliente['ClienteCitta1']."</td>
								</tr>
								<tr>
									<td colspan=\"4\"></td>
								</tr>";
							}
						if (isset($rsCliente['ClienteIndirizzo2']) and isset($rsCliente['ClienteNumero2']) and isset($rsCliente['ClienteCap2']) and isset($rsCliente['ClienteCitta2'])) {
						
						echo "<tr>
								<td colspan=\"4\">Dati Installazione</td>
							</tr>
							<tr>
								<td>Indirizzo</td>
								<td>Numero</td>
								<td>CAP</td>
								<td>Citta</td>
							</tr>
							<tr>
								<td>".$rsCliente['ClienteIndirizzo2']."</td>
								<td>".$rsCliente['ClienteNumero2']."</td>
								<td>".$rsCliente['ClienteCap2']."</td>
								<td>".$rsCliente['ClienteCitta2']."</td>
							</tr>
							<tr>
								<td colspan=\"4\"></td>
							</tr>";
						}
						if (isset($rsCliente['ClienteIndirizzo3']) and isset($rsCliente['ClienteNumero3']) and isset($rsCliente['ClienteCap3']) and isset($rsCliente['ClienteCitta3'])) {
								echo "
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
									<td>".$rsCliente['ClienteIndirizzo3']."</td>
									<td>".$rsCliente['ClienteNumero3']."</td>
									<td>".$rsCliente['ClienteCap3']."</td>
									<td>".$rsCliente['ClienteCitta3']."</td>
								</tr>";
						}
					echo "</table>
					
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
						</tr>";
						if (isset($rsCliente['ClienteIndirizzo1']) and isset($rsCliente['ClienteNumero1']) and isset($rsCliente['ClienteCap1']) and isset($rsCliente['ClienteCitta1'])) {
								echo "<tr>
									<td colspan=\"4\">Dati Fatturazione</td>
								</tr>
								<tr>
									<td>Indirizzo</td>
									<td>Numero</td>
									<td>CAP</td>
									<td>Citta</td>
								</tr>
								<tr>
									<td>".$rsCliente['ClienteIndirizzo1']."</td>
									<td>".$rsCliente['ClienteNumero1']."</td>
									<td>".$rsCliente['ClienteCap1']."</td>
									<td>".$rsCliente['ClienteCitta1']."</td>
								</tr>
								<tr>
									<td colspan=\"4\"></td>
								</tr>";
							}
						if (isset($rsCliente['ClienteIndirizzo2']) and isset($rsCliente['ClienteNumero2']) and isset($rsCliente['ClienteCap2']) and isset($rsCliente['ClienteCitta2'])) {
						
						echo "<tr>
								<td colspan=\"4\">Dati Installazione</td>
							</tr>
							<tr>
								<td>Indirizzo</td>
								<td>Numero</td>
								<td>CAP</td>
								<td>Citta</td>
							</tr>
							<tr>
								<td>".$rsCliente['ClienteIndirizzo2']."</td>
								<td>".$rsCliente['ClienteNumero2']."</td>
								<td>".$rsCliente['ClienteCap2']."</td>
								<td>".$rsCliente['ClienteCitta2']."</td>
							</tr>
							<tr>
								<td colspan=\"4\"></td>
							</tr>";
						}
						if (isset($rsCliente['ClienteIndirizzo3']) and isset($rsCliente['ClienteNumero3']) and isset($rsCliente['ClienteCap3']) and isset($rsCliente['ClienteCitta3'])) {
								echo "
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
									<td>".$rsCliente['ClienteIndirizzo3']."</td>
									<td>".$rsCliente['ClienteNumero3']."</td>
									<td>".$rsCliente['ClienteCap3']."</td>
									<td>".$rsCliente['ClienteCitta3']."</td>
								</tr>";
						}
					echo "</table>
					
				";
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
								<form action=\"schedacontratti.php\" method=\"post\" style=\"float: right;\">
										<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"edit\" >
										<input id=\"ContrattoId\" name=\"ContrattoId\" type=\"hidden\" value=\"".$rsContratti['ContrattoId']."\" >
										<input name=\"Edita Contratto\" type=\"image\" src=\"image\edit.gif\" alt=\"Edita Contratto\" title=\"Edita Contratto\"> 
									</fieldset>
								</form>
								<form action=\"schedacontratti.php\" method=\"post\" style=\"float: right;\">
										<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"del\" >
										<input id=\"ContrattoId\" name=\"ContrattoId\" type=\"hidden\" value=\"".$rsContratti['ContrattoId']."\" >
										<input name=\"Cancella Contratto\" type=\"image\" src=\"image\delete.gif\" alt=\"Cancella Contratto\" title=\"Cancella Contratto\"> 
									</fieldset>
								</form>
								<form action=\"schedacontratti.php\" method=\"post\" style=\"float: right;\">
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
		}
	}
	// FINE CONTROLLO VARIABILE SESSIONE
	else
	{
		// se viene chiamata direttamente la pagina
		echo '<script language=javascript>document.location.href="clienti.php"</script>';
		}


?>
