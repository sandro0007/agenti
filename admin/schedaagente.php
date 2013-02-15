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
				  echo $sql."<br />";
			  die('Error Aggiornamento Cliente: ' . mysql_error());
			  }
			echo '<script language=javascript>document.location.href="clienti.php?id=1"</script>';
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
						 
						echo "<h2>Modifica Cliente</h2>";
						if ($rsCliente['ClienteTipologia'] == 'Privato') {
						echo "<form action=\"schedaclienti.php\" method=\"post\">
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
							<form action=\"schedaclienti.php\" method=\"post\">
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
						 
				} else {
						// CONTRATTI PRESENTI - NON E POSSIBILE EDITARE IL CLIENTE 
					echo "Non posso editare";
				}
			break;
			// FINE EDIT CLIENTE
			
			// INIZIO DETTAGLIO CLIENTE
		case more:
				echo "<h2>Richiesta Dettaglio Agente</h2>";
				$agente = "SELECT * FROM Agenti where idAgenti =".$_POST['idAgenti']."";
				$res = mysql_query($agente);
				$rsAgente = mysql_fetch_assoc($res);
					/* *
					 * 
						 * $rsCliente['idAgenti']
						 * $rsCliente['AgenteNome']
						 * $rsCliente['AgenteCognome']
						 * $rsCliente['AgenteTelefono']
						 * $rsCliente['AgenteFax']
						 * $rsCliente['AgenteCellulare']
						 * $rsCliente['AgenteMail']
						 * $rsCliente['AgenteIndirizzo']
						 * $rsCliente['AgenteNumero']
						 * $rsCliente['AgenteCap']
						 * $rsCliente['AgenteCitta']
						 * $rsCliente['AgenteUser']
						 * $rsCliente['AgentePass']
						 * $rsCliente['AgenteAbilitato']
					 * 
					 * */
				echo "
					<table border=\"1\">
						<tr>
							<td>Cognome</td>
							<td>".$rsAgente['AgenteCognome']."</td>
						</tr>
						<tr>
							<td>Nome</td>
							<td>".$rsAgente['AgenteNome']."</td>
						</tr>
						<tr>
							<td>Telefono</td>
							<td>".$rsAgente['AgenteTelefono']."</td>
						</tr>
						<tr>
							<td>Fax</td>
							<td>".$rsAgente['AgenteFax']."</td>
						</tr>
						<tr>
							<td>Cellulare</td>
							<td>".$rsAgente['AgenteCellulare']."</td>
						</tr>
						<tr>
							<td>E-Mail</td>
							<td>".$rsAgente['AgenteMail']."</td>
						</tr>
						<tr>
							<td>Indirizzo</td>
							<td>".$rsAgente['AgenteIndirizzo']."</td>
						</tr>
						<tr>
							<td>Civico</td>
							<td>".$rsAgente['AgenteNumero']."</td>
						</tr>
						<tr>
							<td>C.A.P.</td>
							<td>".$rsAgente['AgenteCap']."</td>
						</tr>
						<tr>
							<td>Citt&agrave</td>
							<td>".$rsAgente['AgenteCitta']."</td>
						</tr>
						<tr>
							<td>Username</td>
							<td>".$rsAgente['AgenteUser']."</td>
						</tr>
						<tr>
							<td>Password</td>
							<td>".$rsAgente['AgentePass']."</td>
						</tr>
						<tr>
							<td>Accesso Web</td>";
							if ($rsAgente['AgenteAbilitato'] == '1') {
								echo "<td>Utente Abilitato</td>";
								}
								else {
									echo "<td>Utente Non Abilitato</td>";
									}
					echo"	</tr>
					</table>";		 
					
						 
				// VISUALIZZO TUTTI I CONTRATTI ATTIVI PER IL CLIENTE
				
				$contratti = "SELECT Contratti.* FROM Contratti 
								JOIN Agenti_Clienti_Contratti 
									ON Contratti.ContrattoId = Agenti_Clienti_Contratti.ContrattoId 
										WHERE Agenti_Clienti_Contratti.AgenteId ='".$_POST['idAgenti']."'";
				$res = mysql_query($contratti);
				$numrows=mysql_num_rows($res);
					
				if ($numrows == 0) {
						//NESSUN CONTRATTO ATTIVO
				echo "<h2>Nessun Contratto ATTIVO</h2>";
				} else {
						// VI SONO CONTRATTI ATTIVI
						echo "<h2>Tutti i Contratti dell'agente</h2>";
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
		echo '<script language=javascript>document.location.href="adminagenti.php"</script>';
		}


?>
