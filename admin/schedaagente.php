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
			//INIZIO DELLLA CANCELLAZIONE AGENTE
		case del:
			echo "Richiesta Cancellazione Agente ID: ".$_POST['idAgenti']."<br />";
			$contratti = "SELECT * FROM Agenti_Clienti_Contratti where AgenteId = ".$_POST['idAgenti']."";
				$res = mysql_query($contratti);
				$numrows=mysql_num_rows($res);
					
				if ($numrows == 0) {
						//NESSUN CONTRATTO ATTIVO - POSSIBILE EDITARE IL Agente
						$sql="DELETE FROM Agenti WHERE idAgenti = ".$_POST['idAgenti']."";
						if (!mysql_query($sql))
						  {
							  echo $sql."<br />";
						  die('Error Cancellazione Agente: ' . mysql_error());
						  }
						echo '<script language=javascript>document.location.href="adminagenti.php?id=okdel"</script>';
					} 
					else //SONO PRESENTI CONTRATTI 
					{
						// Ritorno il messaggio
						echo '<script language=javascript>document.location.href="adminagenti.php?id=nodel"</script>';
						}
			break;
			// FINE CANCELLAZIONE AGENTE
			
			//INIZIO UPDATE AGENTE
		case update:
			$sql = "UPDATE  `Agenti` SET  
					`AgenteNome` =  '".$_POST['AgenteNome']."',
					`AgenteCognome` =  '".$_POST['AgenteCognome']."',
					`AgenteTelefono` =  '".$_POST['AgenteTelefono']."',
					`AgenteFax` =  '".$_POST['AgenteFax']."',
					`AgenteCellulare` =  '".$_POST['AgenteCellulare']."',
					`AgenteMail` =  '".$_POST['AgenteMail']."',	
					`AgenteIndirizzo` =  '".$_POST['AgenteIndirizzo']."',
					`AgenteNumero` =  '".$_POST['AgenteNumero']."',
					`AgenteCap` =  '".$_POST['AgenteCap']."',
					`AgenteCitta` =  '".$_POST['AgenteCitta']."',
					`AgenteUser` =  '".$_POST['AgenteUser']."',
					`AgentePass` =  '".$_POST['AgentePass']."',
					`AgenteAbilitato` =  '".$_POST['AgenteAbilitato']."'
						WHERE  `idAgenti` = '".$_POST['idAgente']."'";
				
			if (!mysql_query($sql))
			  {
				  echo $sql."<br />";
			  die('Error Aggiornamento Agente: ' . mysql_error());
			  }
			echo '<script language=javascript>document.location.href="adminagenti.php?id=ok"</script>';
			break;
			// FINE UPDATE AGENTE
			
			// INIZIO EDIT AGENTE
		case edit:
			echo "<h2>Richiesta Modifica Agente</h2>";
				
					$Agente = "SELECT * FROM Agenti where idAgenti=".$_POST['idAgenti']."";
					$resAgente = mysql_query($Agente);
					$rsAgente = mysql_fetch_assoc($resAgente);
					/**
						 * 
						 * $rsAgente['idAgente']
						 * $rsAgente['AgenteNome']
						 * $rsAgente['AgenteCognome']
						 * $rsAgente['AgenteRagione']				
						 * $rsAgente['AgenteTelefono']
						 * $rsAgente['AgenteFax']
						 * $rsAgente['AgenteCellulare']
						 * $rsAgente['AgenteMail']
						 * $rsAgente['AgenteIndirizzo']
						 * $rsAgente['AgenteNumero']
						 * $rsAgente['AgenteCap']
						 * $rsAgente['AgenteCitta']
						 * $rsAgente['AgenteUser']
						 * $rsAgente['AgentePass']
						 * $rsAgente['AgenteAbilitato']
						 * 
						 * */
						 
						 echo "
							<form action=\"schedaagente.php\" method=\"post\">
							<fieldset id=\"inputs\">
								<h2>Agente</h2>
								<input id=\"AgenteCognome\" name=\"AgenteCognome\" type=\"text\" placeholder=\"Cognome\" value=\"".$rsAgente['AgenteCognome']."\" autofocus required>
								<input id=\"AgenteNome\" name=\"AgenteNome\" type=\"text\" placeholder=\"Nome\"  value=\"".$rsAgente['AgenteNome']."\" required>
								<h2>Recapiti</h2>
								<input id=\"AgenteTelefono\" name=\"AgenteTelefono\" type=\"text\" placeholder=\"Telefono\" value=\"".$rsAgente['AgenteTelefono']."\" >
								<input id=\"AgenteFax\" name=\"AgenteFax\" type=\"text\" placeholder=\"Fax\" value=\"".$rsAgente['AgenteFax']."\" >
								<input id=\"AgenteCellulare\" name=\"AgenteCellulare\" type=\"text\" placeholder=\"Cellulare\" value=\"".$rsAgente['AgenteCellulare']."\" >
								<input id=\"AgenteMail\" name=\"AgenteMail\" type=\"text\" placeholder=\"E-Mail\" value=\"".$rsAgente['AgenteMail']."\" required>
								<h2>Dati Fatturazione</h2>
								<input id=\"AgenteIndirizzo\" name=\"AgenteIndirizzo\" type=\"text\" placeholder=\"Indirizzo\" value=\"".$rsAgente['AgenteIndirizzo']."\"required>
								<input id=\"AgenteNumero\" name=\"AgenteNumero\" type=\"text\" placeholder=\"Numero Civico\" value=\"".$rsAgente['AgenteNumero']."\"required>
								<input id=\"AgenteCap\" name=\"AgenteCap\" type=\"text\" placeholder=\"C.A.P.\" value=\"".$rsAgente['AgenteCap']."\"required>
								<input id=\"AgenteCitta\" name=\"AgenteCitta\" type=\"text\" placeholder=\"Citt&agrave\" value=\"".$rsAgente['AgenteCitta']."\"required>
								<h2>Dettagli Accesso WEB</h2>
								<input id=\"AgenteUser\" name=\"AgenteUser\" type=\"text\" placeholder=\"Username\" value=\"".$rsAgente['AgenteUser']."\"required>
								<input id=\"AgentePass\" name=\"AgentePass\" type=\"text\" placeholder=\"Password\" value=\"".$rsAgente['AgentePass']."\"required>
								<input id=\"AgenteAbilitato\" name=\"AgenteAbilitato\" type=\"text\" placeholder=\"Accesso Web\" value=\"".$rsAgente['AgenteAbilitato']."\"required>
								<input id=\"idAgente\" name=\"idAgente\" type=\"hidden\" value=\"".$rsAgente['idAgenti']."\" >
								<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"update\" >
							</fieldset>
							<fieldset id=\"actions\">
								<input type=\"submit\" id=\"submit\" value=\"Aggiorna\">
							</fieldset>
						</form>";
			break;
			// FINE EDIT AGENTE
			
			// INIZIO DETTAGLIO AGENTE
		case more:
				echo "<h2>Richiesta Dettaglio Agente</h2>";
				$agente = "SELECT * FROM Agenti where idAgenti =".$_POST['idAgenti']."";
				$res = mysql_query($agente);
				$rsAgente = mysql_fetch_assoc($res);
					/* *
					 * 
						 * $rsAgente['idAgenti']
						 * $rsAgente['AgenteNome']
						 * $rsAgente['AgenteCognome']
						 * $rsAgente['AgenteTelefono']
						 * $rsAgente['AgenteFax']
						 * $rsAgente['AgenteCellulare']
						 * $rsAgente['AgenteMail']
						 * $rsAgente['AgenteIndirizzo']
						 * $rsAgente['AgenteNumero']
						 * $rsAgente['AgenteCap']
						 * $rsAgente['AgenteCitta']
						 * $rsAgente['AgenteUser']
						 * $rsAgente['AgentePass']
						 * $rsAgente['AgenteAbilitato']
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
					
						 
				// VISUALIZZO TUTTI I CONTRATTI ATTIVI PER L'AGETE
				
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
			// FINE DETTAGLIO AGENTE
		}
	}
	// FINE CONTROLLO VARIABILE SESSIONE
	else
	{
		// se viene chiamata direttamente la pagina
		echo '<script language=javascript>document.location.href="adminagenti.php"</script>';
		}


?>
