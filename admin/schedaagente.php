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
			$contratti = "SELECT * FROM Clienti where Agenti_idAgenti = ".$_POST['idAgenti']."";
				$res = mysql_query($contratti);
				$numrows=mysql_num_rows($res);
					
				if ($numrows == 0) {
						//NESSUN Cliente associato - POSSIBILE cancellare l' Agente
						$sql="DELETE FROM Agenti WHERE idAgenti = ".$_POST['idAgenti']."";
						if (!mysql_query($sql))
						  {
							  $msg = 'Error Cancellazione Agente: ' . mysql_error();
							  echo '<script language=javascript>document.location.href="adminagenti.php?id=kodel&msg='.$msg.'"</script>';
						  }
						 $msg = 'Elimininazione Completa';
						echo '<script language=javascript>document.location.href="adminagenti.php?id=okdel&msg='.$msg.'"</script>';
					} 
					else //SONO PRESENTI CLIENTI 
					{
						$msg = 'Sono presenti dei Clienti';
						echo '<script language=javascript>document.location.href="adminagenti.php?id=kodel&msg='.$msg.'"</script>';
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
								<h2>Accesso Web</h2>
								<select id=\"AgenteAbilitato\" name=\"AgenteAbilitato\">";
									if ( $rsAgente['AgenteAbilitato'] == '1')
											{
												echo "<option value=\"1\">Agente Abilitato</option>";
												echo "<option value=\"0\">Agente Non Abilitato</option>";
											}
									else 
									{
											echo "<option value=\"0\">Agente Non Abilitato</option>";
											echo "<option value=\"1\">Agente Abilitato</option>";
										}
								echo"	</select>
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
					<table >
						<tr>
							<td colspan = \"2\" bgcolor = \"#1E90FF\" ><center><b>Dettagli Agente</b></center></td>
						</tr>
						<tr>
							<td><b>Cognome</b></td>
							<td bgcolor = \"#E5E5E5\"><i>".$rsAgente['AgenteCognome']."</i></td>
						</tr>
						<tr>
							<td><b>Nome</b></td>
							<td bgcolor = \"#E5E5E5\"><i>".$rsAgente['AgenteNome']."</i></td>
						</tr>
						<tr>
							<td><b>Telefono</b></td>
							<td bgcolor = \"#E5E5E5\"><i>".$rsAgente['AgenteTelefono']."</i></td>
						</tr>
						<tr>
							<td><b>Fax</b></td>
							<td bgcolor = \"#E5E5E5\"><i>".$rsAgente['AgenteFax']."</i></td>
						</tr>
						<tr>
							<td><b>Cellulare</b></td>
							<td bgcolor = \"#E5E5E5\"><i>".$rsAgente['AgenteCellulare']."</i></td>
						</tr>
						<tr>
							<td><b>E-Mail</b></td>
							<td bgcolor = \"#E5E5E5\"><i>".$rsAgente['AgenteMail']."</i></td>
						</tr>
						<tr>
							<td><b>Indirizzo</b></td>
							<td bgcolor = \"#E5E5E5\"><i>".$rsAgente['AgenteIndirizzo']."</i></td>
						</tr>
						<tr>
							<td><b>Civico</b></td>
							<td bgcolor = \"#E5E5E5\"><i>".$rsAgente['AgenteNumero']."</i></td>
						</tr>
						<tr>
							<td><b>C.A.P.</b></td>
							<td bgcolor = \"#E5E5E5\"><i>".$rsAgente['AgenteCap']."</i></td>
						</tr>
						<tr>
							<td><b>Citt&agrave</b></td>
							<td bgcolor = \"#E5E5E5\"><i>".$rsAgente['AgenteCitta']."</i></td>
						</tr>
						<tr>
							<td><b>Username</b></td>
							<td bgcolor = \"#E5E5E5\"><i>".$rsAgente['AgenteUser']."</i></td>
						</tr>
						<tr>
							<td><b>Password</b></td>
							<td bgcolor = \"#E5E5E5\"><i>".$rsAgente['AgentePass']."</i></td>
						</tr>
						<tr>
							<td><b>Accesso Web</b></td>";
							if ($rsAgente['AgenteAbilitato'] == '1') {
								echo "<td bgcolor = \"#90EE90\"><i>Utente Abilitato</i></td>";
								}
								else {
									echo "<td bgcolor = \"#FF0000\"><i>Utente Non Abilitato</i></td>";
									}
					echo"	</tr>
					</table>";		 
				// VISUALIZZO TUTTI I PUNTEGGI
				
				$anno = date("Y");
				$mese = date("m");
				$mese2 = date("n");
				// Punteggio Potenziale Mese Corrente
				$sqlMeseCorrente = "SELECT Sum(ContrattoPunti) as PuntiMese FROM `Contratti` as C
										JOIN Agenti_Clienti_Contratti as A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$_POST['idAgenti']."' 
											AND YEAR(C.ContrattoData) = '".$anno."' 
												AND MONTH(C.ContrattoData) = '".$mese."'";
				$resMeseCorrente = mysql_query($sqlMeseCorrente);
				$rsMeseCorrente = mysql_fetch_assoc($resMeseCorrente);
				// Punteggio Reale Mese Corrente
				$sqlMeseCorrente2 = "SELECT Sum(ContrattoPunti) as PuntiMese FROM `Contratti` as C
										JOIN Agenti_Clienti_Contratti as A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$_POST['idAgenti']."' 
										AND YEAR(C.ContrattoData) = '".$anno."' 
											AND MONTH(C.ContrattoData) = '".$mese."' 
												AND MONTH(C.ContrattoDataAttivazione) = '".$mese."'
													AND C.ContrattoStato = 'Attivato'";
				$resMeseCorrente2 = mysql_query($sqlMeseCorrente2);
				$rsMeseCorrente2 = mysql_fetch_assoc($resMeseCorrente2);

				// Calcolo Trimestre Corrente
				// $trimestre = 1 (primo trimestre);
				// $trimestre = 4 (secondo trimestre);
				// $trimestre = 7 (terzo trimestre);
				// $trimestre = 10 (quarto trimestre);

				$trimestre = ($mese2<=3?1:($mese2<=6?4:($mese2<=9?7:10)));

				switch ($trimestre){
					case 1:	// primo trimestre
							$meseda = "01";
							$mesea	= "03";
						break;
					case 4: // secondo trimestre
							$meseda = "04";
							$mesea	= "06";
						break;
					case 7:	// terzo trimestre
							$meseda = "07";
							$mesea	= "09";
						break;
					case 10: // quarto trimestre
							$meseda = "10";
							$mesea	= "12";
						break;
					}

				// Calcolo Semestre Corrente
				// $semestre = 1 (primo semestre);
				// $semestre = 2 (secondo semestre);

				$semestre = ($mese2<=6?1:2);
				switch ($semestre){
					case 1:	// primo semestre
							$meseda2 = "01";
							$mesea2	= "06";
						break;
					case 2: // secondo semestre
							$meseda2 = "07";
							$mesea2	= "12";
						break;
					}
				// Punteggio Potenziale Trimestre Corrente
				$sqlTrimestreCorrente = "SELECT Sum(ContrattoPunti) as PuntiTrimestre FROM `Contratti` as C
										JOIN Agenti_Clienti_Contratti as A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$_POST['idAgenti']."' 
											AND YEAR(C.ContrattoData) = '".$anno."' 
											AND MONTH(C.ContrattoData) >= '".$meseda."' 
												AND MONTH(C.ContrattoData) <= '".$mesea."'";
				$resTrimestreCorrente = mysql_query($sqlTrimestreCorrente);
				$rsTrimestreCorrente = mysql_fetch_assoc($resTrimestreCorrente);
				// Punteggio Reale Trimestre Corrente
				$sqlTrimestreCorrente2 = "SELECT Sum(ContrattoPunti) as PuntiTrimestre FROM `Contratti` as C
										JOIN Agenti_Clienti_Contratti as A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$_POST['idAgenti']."' 
											AND  YEAR(C.ContrattoData) = '".$anno."' 
												AND MONTH(C.ContrattoData) >= '".$meseda."' AND MONTH(C.ContrattoData) <= '".$mesea."' 
													AND MONTH(C.ContrattoDataAttivazione) >= '".$meseda."' AND MONTH(C.ContrattoDataAttivazione) <= '".$mesea."'
														AND C.ContrattoStato = 'Attivato'";
				$resTrimestreCorrente2 = mysql_query($sqlTrimestreCorrente2);
				$rsTrimestreCorrente2 = mysql_fetch_assoc($resTrimestreCorrente2);

				// Punteggio Potenziale Semetre Corrente
				$sqlSemetreCorrente = "SELECT Sum(ContrattoPunti) as PuntiSemestre FROM `Contratti` as C
										JOIN Agenti_Clienti_Contratti as A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$_POST['idAgenti']."' 
											AND  YEAR(C.ContrattoData) = '".$anno."' 
										AND MONTH(C.ContrattoData) >= '".$meseda2."' 
												AND MONTH(C.ContrattoData) <= '".$mesea2."'";
				$resSemetreCorrente = mysql_query($sqlSemetreCorrente);
				$rsSemetreCorrente = mysql_fetch_assoc($resMeseCorrente);
				// Punteggio Reale Semestre Corrente
				$sqlSemetreCorrente2 = "SELECT Sum(ContrattoPunti) as PuntiSemestre FROM `Contratti`as C
										JOIN Agenti_Clienti_Contratti as A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$_POST['idAgenti']."' 
											AND YEAR(C.ContrattoData) = '".$anno."' 
											AND MONTH(C.ContrattoData) >= '".$meseda2."' AND MONTH(C.ContrattoData) <= '".$mesea2."' 
													AND MONTH(C.ContrattoDataAttivazione) >= '".$meseda2."' AND MONTH(C.ContrattoDataAttivazione) <= '".$mesea2."' 
														AND C.ContrattoStato = 'Attivato'";
				$resSemetreCorrente2 = mysql_query($sqlSemetreCorrente2);
				$rsSemetreCorrente2 = mysql_fetch_assoc($resSemetreCorrente2);


				// Punteggio Potenziale Anno Corrente
				$sqlAnnoCorrente = "SELECT Sum(ContrattoPunti) as PuntiAnno FROM `Contratti` as C
										JOIN Agenti_Clienti_Contratti as A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$_POST['idAgenti']."' 
											AND  YEAR(C.ContrattoData) = '".$anno."'";
				$resAnnoCorrente = mysql_query($sqlAnnoCorrente);
				$rsAnnoCorrente = mysql_fetch_assoc($resAnnoCorrente);
				// Punteggio Reale Anno Corrente
				$sqlAnnoCorrente2 = "SELECT Sum(ContrattoPunti) as PuntiAnno FROM `Contratti` as C
										JOIN Agenti_Clienti_Contratti as A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$_POST['idAgenti']."' 
											AND  YEAR(C.ContrattoData) = '".$anno."' AND YEAR(C.ContrattoDataAttivazione) = '".$anno."' AND C.ContrattoStato = 'Attivato'";
				$resAnnoCorrente2 = mysql_query($sqlAnnoCorrente2);
				$rsAnnoCorrente2 = mysql_fetch_assoc($resAnnoCorrente2);
				echo "
					<table >
							<tr>
								<td colspan = \"3\" bgcolor = \"#1E90FF\" ><center><b>Dettagli Punti</b></center></td>
							</tr>
							<tr>
								<td><b>Periodo</b></td>
								<td><b>Punti Potenziali</b></td>
								<td><b>Punti Reali</b></td>
								
							</tr>
							<tr>
								<td>Mese Corrente</td>
								<td bgcolor = \"#E5E5E5\">".$rsMeseCorrente['PuntiMese']."</td>
								<td bgcolor = \"#E5E5E5\">".$rsMeseCorrente2['PuntiMese']."</td>
							</tr>
							<tr>
								<td>Trimestre Corrente</td>
								<td bgcolor = \"#E5E5E5\">".$rsTrimestreCorrente['PuntiTrimestre']."</td>
								<td bgcolor = \"#E5E5E5\">".$rsTrimestreCorrente2['PuntiTrimestre']."</td>
							</tr>
							<tr>
								<td>Semetre Corrente</td>
								<td bgcolor = \"#E5E5E5\">".$rsSemetreCorrente['PuntiSemestre']."</td>
								<td bgcolor = \"#E5E5E5\">".$rsSemetreCorrente2['PuntiSemestre']."</td>
							</tr>
							<tr>
								<td>Anno Corrente</td>
								<td bgcolor = \"#E5E5E5\">".$rsAnnoCorrente['PuntiAnno']."</td>
								<td bgcolor = \"#E5E5E5\">".$rsAnnoCorrente2['PuntiAnno']."</td>
							</tr>
						</table>
				";
						 
				// VISUALIZZO TUTTI I CONTRATTI ATTIVI PER L'AGETE
				
				$contratti = "SELECT Contratti.* FROM Contratti 
								JOIN Agenti_Clienti_Contratti 
									ON Contratti.ContrattoId = Agenti_Clienti_Contratti.ContrattoId 
										WHERE Agenti_Clienti_Contratti.AgenteId ='".$_POST['idAgenti']."'";
				$res = mysql_query($contratti);
				$numrows=mysql_num_rows($res);
					
				if ($numrows == 0) {
						//NESSUN CONTRATTO ATTIVO
					echo "<div class=\"warning\">Non risultano contratti emessi per l'agente</div>";
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
