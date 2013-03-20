<?php
session_start();
//se non c'è la sessione registrata
if (!session_is_registered('autorizzato')) {
  echo "<h1>Area riservata, accesso negato.</h1>";
  echo "Per effettuare il login clicca <a href='index.php'><font color='blue'>qui</font></a>";
  die;
}
define('EURO', chr(128));
//Altrimenti Prelevo il codice identificatico dell'utente loggato
session_start();
include ('include/header.php');
require ('include/config.php');
$conn=mysql_connect($dbHost,$dbUser,$dbPassword);
mysql_select_db($dbName);
$codadmin = $_SESSION['admin']; //id cod recuperato nel file di verifica
echo $menu;
echo "
<div class=\"box\" \">
            <a href=\"javascript:slideonlyone('newboxes1');\" >Cerca Contratti</a>
         </div>
         <div class=\"newboxes2\" id=\"newboxes1\" style=\" display: none;\">
         <form action=\"admincontratti.php\" method=\"post\">
        <fieldset id=\"inputs\">
            <input id=\"ContrattoId\" name=\"ContrattoId\" type=\"text\" placeholder=\"Id Contratto\" autofocus>
            <input id=\"ContrattoNome\" name=\"ContrattoNome\" type=\"text\" placeholder=\"Contratto\">
            <label>Tipo</label>
            <select id=\"ContrattoTipo\" name=\"ContrattoTipo\">
				<option value=\"Privato\">Privato</option>
				<option value=\"Azienda\">Azienda</option>
			</select>
            
            <label>Stato</label>
			<select id=\"ContrattoStato\" name=\"ContrattoStato\">
				<option value=\"Inserito\">Inserito</option>
				<option value=\"InLavorazione\">InLavorazione</option>
				<option value=\"Scartato\">Scartato</option>
				<option value=\"Attivato\">Attivato</option>
			</select>
            <input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"search\" >
			</fieldset> 
            <fieldset id=\"actions\">
            <input type=\"submit\" id=\"submit\" value=\"Cerca\">
			</fieldset>
           </form>
         </div>";
// Recupero il numero di pagina corrente.
// Generalmente si utilizza una querystring
$pag = $_GET['pag'];

// Controllo se $pag è valorizzato...
// ...in caso contrario gli assegno valore 1
if (!$pag) $pag = 1; 

$stato = $_POST['stato'];

// CONTROLLO GESTIONE MESSAGGI

switch($_GET['id']){

		case okedit:
			echo "<div class=\"success\">Modifica contratto effettuata: ".$_GET['msg']."</div>";
			break;
		
		case koedit:
			echo "<div class=\"error\">Impossibile modificare contratto: ".$_GET['msg']."</div>";
			break;
			
		case kodel:
			echo "<div class=\"error\">Impossibile cancellare contratto: ".$_GET['msg']."</div>";
			break;
			
		case okdel:
			echo "<div class=\"success\">Cancellazione effettuata: ".$_GET['msg']."</div>";
			break;
		
		}

if(isset($stato)) // se la variabile stato è settata
{ 
	switch($stato)
	{
		case confirmdelete: //DOPO CONFERMA CANCELLAZIONE
			
			$sqlContratto = "DELETE FROM Contratti WHERE ContrattoId = ".$_POST['ContrattoId']."";
			$sqlDelOfferta = "DELETE FROM Contratti_Offerte WHERE ContrattoId = ".$_POST['ContrattoId']."";
			$sqlOpzioniTmp = "SELECT * FROM Contratti_Opzioni WHERE ContrattoId = ".$_POST['ContrattoId']."";
				$resOpzioniTmp = mysql_query($sqlOpzioniTmp);
				$rsOpzioniTmp = mysql_fetch_assoc($resOpzioniTmp);
			$sqlOpzioni = "DELETE FROM Opzioni WHERE OpzioneId = ".$rsOpzioniTmp['OpzioneId']."";
			
			$sqlDelOpzioni = "DELETE FROM Contratti_Opzioni WHERE ContrattoId = ".$_POST['ContrattoId']."";
			
			$sqlLineaTmp = "SELECT * FROM Contratti_Linea WHERE ContrattoId = ".$_POST['ContrattoId']."";
				$resLineaTmp = mysql_query($sqlLineaTmp);
				$rsLineaTmp = mysql_fetch_assoc($resLineaTmp);
			$sqlLinea = "DELETE FROM Linea WHERE LineaId = ".$rsLineaTmp['LineaId']."";
			
			$sqlDelLinea = "DELETE FROM Contratti_Linea WHERE ContrattoId = ".$_POST['ContrattoId']."";
			
			$sqlDelContratto = "DELETE FROM Agenti_Clienti_Contratti WHERE ContrattoId = '".$_POST['ContrattoId']."'";
		
			if (!mysql_query($sqlDelContratto))
			  {
				
				$msg = 'Error Cancellazione Contratto - Agenti: ' . mysql_error();
				echo '<script language=javascript>document.location.href="admincontratti.php?id=kodel&msg='.$msg.'"</script>';
			  }
			  
			 if (!mysql_query($sqlDelLinea))
			  {
				
				$msg = 'Error Cancellazione Contratto - Linea: ' . mysql_error();
				echo '<script language=javascript>document.location.href="admincontratti.php?id=kodel&msg='.$msg.'"</script>';
			  }
			  
			  if (!mysql_query($sqlLinea))
			  {
				
				$msg = 'Error Cancellazione Linea: ' . mysql_error();
				echo '<script language=javascript>document.location.href="admincontratti.php?id=kodel&msg='.$msg.'"</script>';
			  }
			  
			 if (!mysql_query($sqlDelOpzioni))
			  {
				
				$msg = 'Error Cancellazione Contratto - Opzioni : ' . mysql_error();
				echo '<script language=javascript>document.location.href="admincontratti.php?id=kodel&msg='.$msg.'"</script>';
			  }
			
			if (!mysql_query($sqlOpzioni))
			  {
				
				$msg = 'Error Cancellazione Opzioni : ' . mysql_error();
				echo '<script language=javascript>document.location.href="admincontratti.php?id=kodel&msg='.$msg.'"</script>';
			  }
			
			if (!mysql_query($sqlDelOfferta))
			  {
				
				$msg = 'Error Cancellazione Contratto - Offerta : ' . mysql_error();
				echo '<script language=javascript>document.location.href="admincontratti.php?id=kodel&msg='.$msg.'"</script>';
			  }
			
			if (!mysql_query($sqlContratto))
			  {
				
				$msg = 'Error Cancellazione Contratto : ' . mysql_error();
				echo '<script language=javascript>document.location.href="admincontratti.php?id=kodel&msg='.$msg.'"</script>';
			  }
						  
			$msg = "ESITO POSITOVO";
			echo '<script language=javascript>document.location.href="admincontratti.php?id=okdel&msg'.$msg.'"</script>';
			//~ echo $_POST['ContrattoId']."<br />";
			//~ echo $sqlContratto."<br />".$sqlDelOfferta."<br />".$sqlOpzioni."<br />".$sqlDelOpzioni."<br />".$sqlLinea."<br />".$sqlDelLinea."<br />".$sqlDelContratto;
			break;
			
		case del://RICHIESTA CANCELLAZIONE CONTRATTO
			echo "<h3>Richiesta Cancellazione  Contratto numero ".$_POST['ContrattoId']."</h3>";
			$sqlContratto = "SELECT * FROM Contratti WHERE ContrattoId = '".$_POST['ContrattoId']."'";
				$resContratto = mysql_query($sqlContratto);
				$rsContratto = mysql_fetch_assoc($resContratto);
			if ($rsContratto['ContrattoStato'] != "Inserito") 
				{
					$msg = "Contratto nello stato -> ".$rsContratto['ContrattoStato']."";
					echo '<script language=javascript>document.location.href="admincontratti.php?id=kodel&msg='.$msg.'"</script>';
				}
				else  // Contratto MODIFICABILE
				{
					echo "<h3>Vuoi Veramente Cancellare il Contratto?</h3>
							<form action=\"admincontratti.php\" method=\"POST\" name=\"form\">
							<input id=\"stato\" name=\"stato\"  type=\"hidden\" value=\"confirmdelete\">
						<input id=\"ContrattoId\" name=\"ContrattoId\" type=\"hidden\" value=\"".$_POST['ContrattoId']."\">
						<input  type=\"submit\" id=\"submit\" value=\"DELETE\">
					</form><br /><br />";
				}
			break; // FINE CANCELLAZIONE CONTRATTO
			
		case edit:
			echo "<h3>Richiesta Modifica  Contratti numero ".$_POST['ContrattoId']."</h3>";
			$sqlContratto = "SELECT * FROM Contratti WHERE ContrattoId = '".$_POST['ContrattoId']."'";
				$resContratto = mysql_query($sqlContratto);
				$rsContratto = mysql_fetch_assoc($resContratto);
			if ($rsContratto['ContrattoStato'] != "Inserito") {
					$msg = "Contratto nello stato -> ".$rsContratto['ContrattoStato']."";
					echo '<script language=javascript>document.location.href="admincontratti.php?id=koedit&msg='.$msg.'"</script>';
				}
				else  // Contratto MODIFICABILE
				{
					$sqlOfferta = "SELECT * FROM Contratti_Offerte as C JOIN Offerte as O ON C.OffertaId = O.OffertaId WHERE C.ContrattoId = '".$_POST['ContrattoId']."'";
					$resOfferta = mysql_query($sqlOfferta);
					$rsOfferta = mysql_fetch_assoc($resOfferta);
					if ($rsOfferta['OffertaPersonalizzata'] == '1') // Offerta Personalizzata
					{
						echo "
			<form action=\"admincontratti.php\" method=\"POST\" name=\"form\">
				<table >
					
					<tr>
						<td colspan = \"8\" bgcolor = \"#1E90FF\" ><center><b>Dati Servizio</b></center></td>
					</tr>
					<tr>
						<td>Codice Contratto</td>
						<td>
							<select id=\"OffertaId\" name=\"OffertaId\">";
							
						$sqlOffertaTemp = "SELECT * FROM Offerte WHERE OffertaDestinazione = '".$rsOfferta['OffertaDestinazione']."' ";
							$resOffertaTemp = mysql_query($sqlOffertaTemp);
								echo "<option value=\"".$rsOfferta['OffertaId']."\">".$rsOfferta['OffertaNome']."</option>";
								while($rsOffertaTemp = mysql_fetch_assoc($resOffertaTemp))
								{
									if ($rsOffertaTemp['OffertaNome'] != $rsOfferta['OffertaNome']) {
									echo "<option value=\"".$rsOffertaTemp['OffertaId']."\">".$rsOffertaTemp['OffertaNome']."</option>";
									}
								}
						echo"	</select>
						
						</td>
						<td>Descrizione</td><td colspan =\"3\"><input id=\"OffertaDescrizione\" name=\"OffertaDescrizione\" type=\"text\" placeholder=\"Descrizione\" value=\"".$rsOfferta['OffertaDescrizione']."\" readonly></td>
						<td>Importo Mensile</td><td><input id=\"OffertaCanone\" name=\"OffertaCanone\" type=\"text\" placeholder=\"Canone\" value=\"".$rsOfferta['OffertaCanone']."\" readonly></td>
					</tr>
					<tr>
						<td>Pagamento Canone</td>
						<td>
						<select id=\"ContrattoPagamento\" name=\"ContrattoPagamento\">";
							
							echo "<option value=\"".$rsContratto['ContrattoPagamento']."\">".$rsContratto['ContrattoPagamento']."</option>";
							$pagamento = array("Bollettino", "RID", "MAV");
							reset($pagametno);
							foreach ($pagamento as $key3 => $value3) {
								if ( $value3 != $rsContratto['ContrattoPagamento'])
									{
										echo "<option value=\"".$value3."\">".$value3."</option>";
									}
								
							}
						echo"	</select>
						</td>
						<td>U.T. Attivazione</td>
						<td>
						<select id=\"ContrattoAttivazione\" name=\"ContrattoAttivazione\">";
							echo "<option value=\"".$rsContratto['ContrattoAttivazione']."\">".$rsContratto['ContrattoAttivazione']."</option>";
							$attivazione = array("Contanti", "Addebito");
							reset($attivazione);
							foreach ($attivazione as $key4 => $value4) {
								if ( $value4 != $rsContratto['ContrattoAttivazione'])
									{
										echo "<option value=\"".$value4."\">".$value4."</option>";
									}
								
							}
						echo"	</select>
						</td>
						<td>Ricezione fattura</td>
						<td>
						<select id=\"ContrattoFattura\" name=\"ContrattoFattura\">";
							echo "<option value=\"".$rsContratto['ContrattoFattura']."\">".$rsContratto['ContrattoFattura']."</option>";
							$fattura = array("Digitale", "Cartaceo");
							reset($fattura);
							foreach ($fattura as $key5 => $value5) {
								if ( $value5 != $rsContratto['ContrattoFattura'])
									{
										echo "<option value=\"".$value5."\">".$value5."</option>";
									}
								
							}
						echo"	</select>
					</tr>
					<tr>
						<td colspan = \"8\" bgcolor = \"#1E90FF\">RID</td>
					</tr>
					<tr>
						<td>Banca</td><td colspan = \"2\"><input id=\"ContrattoBanca\" name=\"ContrattoBanca\" type=\"text\" placeholder=\"Banca\" value=\"".$rsContratto['ContrattoBanca']."\"></td>
						<td>Agenzia</td><td colspan = \"2\"><input id=\"ContrattoAgenzia\" name=\"ContrattoAgenzia\" type=\"text\" placeholder=\"Agenzia\" value=\"".$rsContratto['ContrattoAgenzia']."\" ></td>
						<td>Localit&agrave</td><td><input id=\"ContrattoLocalita\" name=\"ContrattoLocalita\" type=\"text\" placeholder=\"Localita\" value=\"".$rsContratto['ContrattoLocalita']."\" ></td>
					</tr>
					<tr>
						<td>Intestazione</td>
						<td colspan =\"7\"><input id=\"ContrattoIntestazione\" name=\"ContrattoIntestazione\" type=\"text\" placeholder=\"Intestazione\" value=\"".$rsContratto['ContrattoIntestazione']."\" ></td>
					</tr>
					<tr>
						<td>IBAN</td><td colspan = \"5\"><input id=\"ContrattoIban\" name=\"ContrattoIban\" type=\"text\" placeholder=\"IBAN\" value=\"".$rsContratto['ContrattoIban']."\" ></td>
					</tr>
					<tr>
						<td  colspan =\"8\" bgcolor = \"#1E90FF\">NOTE</td>
					</tr>
					<tr>
						<td colspan = \"8\">
						<textarea id=\"ContrattoNote\" name=\"ContrattoNote\" type=\"text\" placeholder=\"Note\" >".$rsContratto['ContrattoNote']."</textarea></td>
					</tr>
					<tr>
						<td  colspan =\"4\" bgcolor = \"#1E90FF\"><center>Punteggio</center></td>
						<td  colspan =\"4\" bgcolor = \"#1E90FF\"><center>Provvigione</center></td>
					</tr>
					<tr>
						<td></td><td colspan = \"2\"><input id=\"ContrattoPunti\" name=\"ContrattoPunti\" type=\"text\" placeholder=\"Punti\" value=\"".$rsContratto['ContrattoPunti']."\" ></td>
						<td></td>
						<td></td><td colspan = \"2\">".EURO." i.v.a. esclusa<input id=\"ContrattoProvvigioni\" name=\"ContrattoProvvigioni\" type=\"text\" placeholder=\"CProvvigioni\" value=\"".$rsContratto['ContrattoProvvigioni']."\" ></td>
						<td></td>
					</tr>
				</table>
				<input id=\"OffertaId\" name=\"OffertaId\" type=\"hidden\" value=\"".$rsOfferta['OffertaId']."\" >
				<input id=\"ContrattoId\" name=\"ContrattoId\" type=\"hidden\" value=\"".$_POST['ContrattoId']."\" >
				<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"update2\" >
						<input  type=\"submit\" id=\"submit\" value=\"Applica\">
			</form>";
						
						}
						else // offerta non personalizzata
						{
			$sqlLinea = "SELECT * FROM Contratti_Linea as C JOIN Linea as L on C.LineaId = L.LineaId WHERE C.ContrattoId = '".$_POST['ContrattoId']."'";
				$resLinea = mysql_query($sqlLinea);
				$rsLinea = mysql_fetch_assoc($resLinea);
			$sqlOpzioni = "SELECT * FROM Contratti_Opzioni as C JOIN Opzioni as O ON C.OpzioneId = O.OpzioneId WHERE C.ContrattoId = '".$_POST['ContrattoId']."'";
				$resOpzioni = mysql_query($sqlOpzioni);
				$rsOpzioni = mysql_fetch_assoc($resOpzioni);
			// START
			echo "
			<form action=\"admincontratti.php\" method=\"POST\" name=\"form\">
				<table border=\"1\" >
					
					<tr>
						<td colspan = \"8\" bgcolor = \"#1E90FF\" ><center><b>Dati Servizio</b></center></td>
					</tr>
					<tr>
						<td>Codice Contratto</td>
						<td>
							<select id=\"OffertaId\" name=\"OffertaId\">";
							
						$sqlOffertaTemp = "SELECT * FROM Offerte WHERE OffertaDestinazione = '".$rsOfferta['OffertaDestinazione']."' ";
							$resOffertaTemp = mysql_query($sqlOffertaTemp);
								echo "<option value=\"".$rsOfferta['OffertaId']."\">".$rsOfferta['OffertaNome']."</option>";
								while($rsOffertaTemp = mysql_fetch_assoc($resOffertaTemp))
								{
									if ($rsOffertaTemp['OffertaNome'] != $rsOfferta['OffertaNome']) {
									echo "<option value=\"".$rsOffertaTemp['OffertaId']."\">".$rsOffertaTemp['OffertaNome']."</option>";
									}
								}
						echo"	</select>
						
						</td>
						<td>Descrizione</td><td colspan =\"3\"><input id=\"OffertaDescrizione\" name=\"OffertaDescrizione\" type=\"text\" placeholder=\"Descrizione\" value=\"".$rsOfferta['OffertaDescrizione']."\" readonly></td>
						<td>Importo Mensile</td><td><input id=\"OffertaCanone\" name=\"OffertaCanone\" type=\"text\" placeholder=\"Canone\" value=\"".$rsOfferta['OffertaCanone']."\" readonly></td>
					</tr>
					<tr>
						<td>Linea Telefonica:</td>
						<td>ESISTENTE</td>
						<td>
						<select id=\"LineaDatiMigrazione\" name=\"LineaDatiMigrazione\">";
							
							echo "<option value=\"".$rsLinea['LineaDatiMigrazione']."\">".$rsLinea['LineaDatiMigrazione']."</option>";
							$scelta = array("nessuna", "isdn", "pstn", "naked", "ibrida");
							reset($scelta);
							foreach ($scelta as $key => $value) {
								if ( $value != $rsLinea['LineaDatiMigrazione'])
									{
										echo "<option value=\"".$value."\">".$value."</option>";
									}
								
							}
						echo"	</select>
						</td>
						<td> Numero Da Migrare</td>
						<td colspan = \"2\"><input id=\"LineaNumero\" name=\"LineaNumero\" type=\"text\" placeholder=\"Linea Numero Migrazione\" value=\"".$rsLinea['LineaNumero']."\" ></td>
						<td>Codice Migrazione</td>
						<td><input id=\"LineaCodice\" name=\"LineaCodice\" type=\"text\" placeholder=\"Codice Migrazione\" value=\"".$rsLinea['LineaCodice']."\" ></td>
					</tr>
					<tr>
						<td></td>
						<td>Nuova Attivazione</td>
						<td colspan = \"3\">
							<select id=\"LineaDatiAttivazione\" name=\"LineaDatiAttivazione\">";
							
							echo "<option value=\"".$rsLinea['LineaDatiAttivazione']."\">".$rsLinea['LineaDatiAttivazione']."</option>";
							$scelta2 = array("nessuna", "isdn", "pstn", "naked", "ibrida");
							reset($scelta2);
							foreach ($scelta2 as $key2 => $value2) {
								if ( $value2 != $rsLinea['LineaDatiAttivazione'])
									{
										echo "<option value=\"".$value2."\">".$value2."</option>";
									}
								
							}
						echo"	</select>
						</td>
						<td>Numero Pilota</td>
						<td colspan = \"2\"><input id=\"LineaPilota\" name=\"LineaPilota\" type=\"text\" placeholder=\"Linea Pilota\" value=\"".$rsLinea['LineaPilota']."\" ></td>
					</tr>
					<tr>
						<td>Pagamento Canone</td>
						<td>
						<select id=\"ContrattoPagamento\" name=\"ContrattoPagamento\">";
							
							echo "<option value=\"".$rsContratto['ContrattoPagamento']."\">".$rsContratto['ContrattoPagamento']."</option>";
							$pagamento = array("Bollettino", "RID", "MAV");
							reset($pagametno);
							foreach ($pagamento as $key3 => $value3) {
								if ( $value3 != $rsContratto['ContrattoPagamento'])
									{
										echo "<option value=\"".$value3."\">".$value3."</option>";
									}
								
							}
						echo"	</select>
						</td>
						<td>U.T. Attivazione</td>
						<td>
						<select id=\"ContrattoAttivazione\" name=\"ContrattoAttivazione\">";
							echo "<option value=\"".$rsContratto['ContrattoAttivazione']."\">".$rsContratto['ContrattoAttivazione']."</option>";
							$attivazione = array("Contanti", "Addebito");
							reset($attivazione);
							foreach ($attivazione as $key4 => $value4) {
								if ( $value4 != $rsContratto['ContrattoAttivazione'])
									{
										echo "<option value=\"".$value4."\">".$value4."</option>";
									}
								
							}
						echo"	</select>
						</td>
						<td>Ricezione fattura</td>
						<td>
						<select id=\"ContrattoFattura\" name=\"ContrattoFattura\">";
							echo "<option value=\"".$rsContratto['ContrattoFattura']."\">".$rsContratto['ContrattoFattura']."</option>";
							$fattura = array("Digitale", "Cartaceo");
							reset($fattura);
							foreach ($fattura as $key5 => $value5) {
								if ( $value5 != $rsContratto['ContrattoFattura'])
									{
										echo "<option value=\"".$value5."\">".$value5."</option>";
									}
								
							}
						echo"	</select>
					</tr>
					<tr>
						<td colspan = \"8\"> Servizi Opzionali</td>
					</tr>
					<tr>
						<td>Ip Statico</td>
						<td colspan = \"7\">
						<fieldset>";
						
							if ($rsOpzioni['OpzioneIp'] == '0')
							{
								echo "Nessuno<input type=\"radio\" name=\"OpzioneIp\" value=\"0\"  checked=\"checked\" />";
								echo "1 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"1\" />";
								echo "4 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"4\" />";
								echo "8 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"8\" />";	  
								echo "16 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"16\" />";	  
								echo "32 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"32\" />";	  
								}
							if ($rsOpzioni['OpzioneIp'] == '1')
							{
								echo "Nessuno<input type=\"radio\" name=\"OpzioneIp\" value=\"0\"  />";
								echo "1 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"1\" checked=\"checked\" />";
								echo "4 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"4\" />";
								echo "8 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"8\" />";	  
								echo "16 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"16\" />";	  
								echo "32 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"32\" />";	  
								}
							if ($rsOpzioni['OpzioneIp'] == '4')
							{
								echo "Nessuno<input type=\"radio\" name=\"OpzioneIp\" value=\"0\"   />";
								echo "1 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"1\" />";
								echo "4 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"4\" checked=\"checked\" />";
								echo "8 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"8\" />";	  
								echo "16 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"16\" />";	  
								echo "32 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"32\" />";	  
								}
							if ($rsOpzioni['OpzioneIp'] == '8')
							{
								echo "Nessuno<input type=\"radio\" name=\"OpzioneIp\" value=\"0\"   />";
								echo "1 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"1\" />";
								echo "4 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"4\" />";
								echo "8 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"8\" checked=\"checked\" />";	  
								echo "16 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"16\" />";	  
								echo "32 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"32\" />";	  
								}
							if ($rsOpzioni['OpzioneIp'] == '16')
							{
								echo "Nessuno<input type=\"radio\" name=\"OpzioneIp\" value=\"0\"   />";
								echo "1 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"1\" />";
								echo "4 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"4\" />";
								echo "8 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"8\" />";	  
								echo "16 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"16\" checked=\"checked\" />";	  
								echo "32 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"32\" />";	  
								}
							if ($rsOpzioni['OpzioneIp'] == '32')
							{
								echo "Nessuno<input type=\"radio\" name=\"OpzioneIp\" value=\"0\"   />";
								echo "1 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"1\" />";
								echo "4 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"4\" />";
								echo "8 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"8\" />";	  
								echo "16 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"16\" />";	  
								echo "32 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"32\" checked=\"checked\" />";	  
								}
								
				echo "	</fieldset>
					</td>
					</tr>
					<tr>
						<td colspan = \"4\">";
							if ($rsOpzioni['OpzioneAP'] == '1')
							{
								echo "Acquisto e installazione Access Point<input type=\"checkbox\" name=\"OpzioneAP\" value=\"1\"  checked=\"checked\" />";
							} else 
								{
									echo "Acquisto e installazione Access Point<input type=\"checkbox\" name=\"OpzioneAP\" value=\"1\"  />";
									}
							
						echo "</td>
						<td colspan = \"4\">";
							if ($rsOpzioni['OpzioneElenco'] == '1')
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
							if ($rsOpzioni['OpzioneChie'] == '1')
							{
								echo "Identificaticazione Chiamante (Chi &egrave)<input type=\"checkbox\" name=\"OpzioneChie\" value=\"1\"  checked=\"checked\" />";
							} else 
								{
									echo "Identificaticazione Chiamante (Chi &egrave)<input type=\"checkbox\" name=\"OpzioneChie\" value=\"1\" />";
									}
							
						echo "</td>
						<td colspan = \"4\">
						";
							if ($rsOpzioni['OpzioneClinascosto']  == '1')
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
							if ($rsOpzioni['OpzioneTrasferimento']  == '1')
							{
								echo "Trasferimento di chiamata<input type=\"checkbox\" name=\"OpzioneTrasferimento\" value=\"1\"  checked=\"checked\" />";
							} else 
								{
									echo "Trasferimento di chiamata<input type=\"checkbox\" name=\"OpzioneTrasferimento\" value=\"1\" />";
									}
							
						echo "</td>
						<td colspan = \"4\">";
							if ($rsOpzioni['OpzionePubblicita']  == '1')
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
							if ($rsOpzioni['OpzioneAttesa']  == '1')
							{
								echo "Chiamata in attesa<input type=\"checkbox\" name=\"OpzioneAttesa\" value=\"1\"  checked=\"checked\" />";
							} else 
								{
									echo "Chiamata in attesa<input type=\"checkbox\" name=\"OpzioneAttesa\" value=\"1\" />";
									}
							
						echo "</td>
						<td colspan = \"4\">";
							if ($rsOpzioni['OpzioneSwitch']  == '1')
							{
								echo "Acquisto Switch 8 Porte<input type=\"checkbox\" name=\"OpzioneSwitch\" value=\"1\"  checked=\"checked\" />";
							} else 
								{
									echo "Acquisto Switch 8 Porte<input type=\"checkbox\" name=\"OpzioneSwitch\" value=\"1\" />";
									}
							
						echo "</td>
					</tr>
					<tr>
						<td colspan = \"8\">RID</td>
					</tr>
					<tr>
						<td>Banca</td><td colspan = \"2\"><input id=\"ContrattoBanca\" name=\"ContrattoBanca\" type=\"text\" placeholder=\"Banca\" value=\"".$rsContratto['ContrattoBanca']."\"></td>
						<td>Agenzia</td><td colspan = \"2\"><input id=\"ContrattoAgenzia\" name=\"ContrattoAgenzia\" type=\"text\" placeholder=\"Agenzia\" value=\"".$rsContratto['ContrattoAgenzia']."\" ></td>
						<td>Localit&agrave</td><td><input id=\"ContrattoLocalita\" name=\"ContrattoLocalita\" type=\"text\" placeholder=\"Localita\" value=\"".$rsContratto['ContrattoLocalita']."\" ></td>
					</tr>
					<tr>
						<td>Intestazione</td>
						<td colspan =\"7\"><input id=\"ContrattoIntestazione\" name=\"ContrattoIntestazione\" type=\"text\" placeholder=\"Intestazione\" value=\"".$rsContratto['ContrattoIntestazione']."\" ></td>
					</tr>
					<tr>
						<td>IBAN</td><td colspan = \"5\"><input id=\"ContrattoIban\" name=\"ContrattoIban\" type=\"text\" placeholder=\"IBAN\" value=\"".$rsContratto['ContrattoIban']."\" ></td>
					</tr>
					<tr>
						<td  colspan =\"8\">NOTE</td>
					</tr>
					<tr>
						<td colspan = \"8\">
						<textarea id=\"ContrattoNote\" name=\"ContrattoNote\" type=\"text\" placeholder=\"Note\" >".$rsContratto['ContrattoNote']."</textarea></td>
					</tr>
				</table>
				<input id=\"OffertaId\" name=\"OffertaId\" type=\"hidden\" value=\"".$rsOfferta['OffertaId']."\" >
				<input id=\"OpzioneId\" name=\"OpzioneId\" type=\"hidden\" value=\"".$rsOpzioni['OpzioneId']."\" >
				<input id=\"LineaId\" name=\"LineaId\" type=\"hidden\" value=\"".$rsLinea['LineaId']."\" >
				<input id=\"ContrattoId\" name=\"ContrattoId\" type=\"hidden\" value=\"".$_POST['ContrattoId']."\" >
				<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"update\" >
						<input  type=\"submit\" id=\"submit\" value=\"Applica\">
			</form>";
				}
			}
			// FINE
			break;
			
		case moreall:
			// VISUALIZZO TUTTI I CONTRATTI ATTIVI PER L'AGENTE
				
				$Contratti = "SELECT * FROM Contratti as C JOIN Agenti_Clienti_Contratti as A ON C.ContrattoId = A.ContrattoId where AgenteId = ".$_POST['idAgenti']."";
				$res = mysql_query($Contratti);
				$numrows=mysql_num_rows($res);
					
				if ($numrows == 0) {
						//NESSUN CONTRATTO ATTIVO
				echo "<div class=\"warning\">Impossibile Visualizzare CONTRATTI: Nessun Contratto Inserito</div>";
				} else {
						// VI SONO CONTRATTI ATTIVI
						echo "<h2>Tutti i Contratti dell'Agente</h2>";
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
								<td>
								<form action=\"admincontratti.php\" method=\"post\">
					<select id=\"ContrattoStato\" name=\"ContrattoStato\">";
							
							echo "<option value=\"".$rsContratti['ContrattoStato']."\">".$rsContratti['ContrattoStato']."</option>";
							$statoContratto = array("Inserito", "InLavorazione", "Scartato", "Attivato");
							reset($statoContratto);
							foreach ($statoContratto as $key10 => $value10) {
								if ( $value10 != $rsContratti['ContrattoStato'])
									{
										echo "<option value=\"".$value10."\">".$value10."</option>";
									}
								
							}
						echo"	</select>
					<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"editStato\" >
					<input id=\"ContrattoId\" name=\"ContrattoId\" type=\"hidden\" value=\"".$rsContratti['ContrattoId']."\" >
					<input name=\"AggiornaStato\" type=\"image\" src=\"image\\refresh.jpg\" alt=\"Aggiorna Stato\" title=\"Aggiorna Stato\"> 
								</td>
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
		// FINE MORE ALL
			
		case more: // VISUALIZZA DETTAGLIO
			echo "<h3>Richiesta Dettaglio Contratti numero ".$_POST['ContrattoId']."</h3>";
			$sqlContratto = "SELECT * FROM Contratti WHERE ContrattoId = '".$_POST['ContrattoId']."'";
				$resContratto = mysql_query($sqlContratto);
				$rsContratto = mysql_fetch_assoc($resContratto);
			$sqlLinea = "SELECT * FROM Contratti_Linea as C JOIN Linea as L on C.LineaId = L.LineaId WHERE C.ContrattoId = '".$_POST['ContrattoId']."'";
				$resLinea = mysql_query($sqlLinea);
				$rsLinea = mysql_fetch_assoc($resLinea);
			$sqlOfferta = "SELECT * FROM Contratti_Offerte as C JOIN Offerte as O ON C.OffertaId = O.OffertaId WHERE C.ContrattoId = '".$_POST['ContrattoId']."'";
				$resOfferta = mysql_query($sqlOfferta);
				$rsOfferta = mysql_fetch_assoc($resOfferta);
			$sqlOpzioni = "SELECT * FROM Contratti_Opzioni as C JOIN Opzioni as O ON C.OpzioneId = O.OpzioneId WHERE C.ContrattoId = '".$_POST['ContrattoId']."'";
				$resOpzioni = mysql_query($sqlOpzioni);
				$rsOpzioni = mysql_fetch_assoc($resOpzioni);
						// Prelevo il Cliente
			$sqlCliente = "SELECT * FROM Clienti WHERE idCliente = ".$rsContratto['Clienti_idCliente']."";
				$resCliente = mysql_query($sqlCliente);
				$rsCliente = mysql_fetch_assoc($resCliente);
			
			// START
			echo "
				<table>
										<tr>
						<td bgcolor = \"#1E90FF\" colspan = \"8\"><center><b>Dati Intestatario Contratto</b></center></td>
					</tr>
					<tr>
						<td><b>Cognome</b></td><td bgcolor = \"#E5E5E5\"><i>".$rsCliente['ClienteCognome']."</i></td>
						<td><b>Nome</b></td><td bgcolor = \"#E5E5E5\"><i>".$rsCliente['ClienteNome']."</i></td>
						<td><b>Codice Fiscale</b></td><td bgcolor = \"#E5E5E5\"><i>".$rsCliente['ClienteCF']."</i></td>
						<td><b>Sesso</b></td>
							<td bgcolor = \"#E5E5E5\"><i>".$rsCliente['ClienteSesso']."</i></td>
					</tr>
					<tr>
						<td><b>Ragione Sociale</b></td><td colspan = \"4\" bgcolor = \"#E5E5E5\"><i>".$rsCliente['ClienteRagione']."</i></td>
						<td><b>Partita Iva</b></td><td colspan = \"2\" bgcolor = \"#E5E5E5\"><i>".$rsCliente['ClientePI']."</i></td>
					</tr>
					<tr>
						<td><b>Data Nascita</b></td><td bgcolor = \"#E5E5E5\"><i>".$rsCliente['ClienteDataNascita']."</i></td>
						<td><b>Luogo di Nascita</b></td><td colspan = \"3\" bgcolor = \"#E5E5E5\"><i>".$rsCliente['ClienteLuogoNascita']."</i></td>
						<td><b>Provincia</b></td><td bgcolor = \"#E5E5E5\"><i>".$rsCliente['ClienteProvinciaNascita']."</i></td>
					</tr>
					<tr>
						<td><b>Indirizzo</b></td><td bgcolor = \"#E5E5E5\"><i>".$rsCliente['ClienteIndirizzo']."</i></td>
						<td><b>Numero</b></td><td bgcolor = \"#E5E5E5\"><i>".$rsCliente['ClienteNumero']."</i></td>
						<td><b>C.A.P.</b></td><td bgcolor = \"#E5E5E5\"><i>".$rsCliente['ClienteCap']."</i></td>
						<td><b>Citt&agrave</b></td><td bgcolor = \"#E5E5E5\"><i>".$rsCliente['ClienteCitta']."</i></td>
					</tr>
					<tr>
						<td><b>Indirizzo Installazione</b></td><td bgcolor = \"#E5E5E5\"><i>".$rsContratto['ClienteIndirizzo1']."</i></td>
						<td><b>Numero</b></td><td bgcolor = \"#E5E5E5\"><i>".$rsContratto['ClienteNumero1']."</i></td>
						<td><b>C.A.P.</b></td><td bgcolor = \"#E5E5E5\"><i>".$rsContratto['ClienteCap1']."</i></td>
						<td><b>Citt&agrave</b></td><td bgcolor = \"#E5E5E5\"><i>".$rsContratto['ClienteCitta1']."</i></td>
					</tr>
					<tr>
						<td><b>Indirizzo Corrisponenza</b></td><td bgcolor = \"#E5E5E5\"><i>".$rsContratto['ClienteIndirizzo2']."</i></td>
						<td><b>Numero</b></td><td bgcolor = \"#E5E5E5\"><i>".$rsContratto['ClienteNumero2']."</i></td>
						<td><b>C.A.P.</b></td><td bgcolor = \"#E5E5E5\"><i>".$rsContratto['ClienteCap2']."</i></td>
						<td><b>Citt&agrave</b></td><td bgcolor = \"#E5E5E5\"><i>".$rsContratto['ClienteCitta2']."</i></td>
					</tr>
					<tr>
						<td><b>Documento Identit&agrave</b></td>
							<td colspan = \"3\" bgcolor = \"#E5E5E5\"><i>".$rsCliente['ClienteTipoDocumento']."</i></td>
						<td><b>Numero Documento</b></td><td bgcolor = \"#E5E5E5\"><i>".$rsCliente['ClienteNumeroDocumento']."</i></td>
						<td><b>Rilasciato il </b></td><td bgcolor = \"#E5E5E5\"><i>".$rsCliente['ClienteRilascioDocumento']."</i></td>
					</tr>
					<tr>
						<td><b>Rilasciato da</b></td>
						<td colspan=\"4\" bgcolor = \"#E5E5E5\"><i>
							".$rsCliente['ClienteEnteDocumento']."</i>
						</td>
						<td><b>di</b></td>
						<td colspan = \"2\" bgcolor = \"#E5E5E5\"><i>".$rsCliente['ClienteEnteDiDocumento']."</i></td>
					</tr>
					<tr>
						<td><b>Telefono</b></td><td bgcolor = \"#E5E5E5\"><i>".$rsCliente['ClienteTelefono']."</i></td>
						<td><b>Fax</b></td><td bgcolor = \"#E5E5E5\"><i>".$rsCliente['ClienteFax']."</i></td>
						<td><b>Cellulare</b></td><td bgcolor = \"#E5E5E5\"><i>".$rsCliente['ClienteCelllulare']."</i></td>
						<td><b>E-Mail</b></td><td bgcolor = \"#E5E5E5\"><i>".$rsCliente['ClienteMail']."</i></td>
					</tr>
					<tr>
						<td bgcolor = \"#1E90FF\" colspan = \"8\"><center><b>Dati Servizio</b></center></td>
					</tr>
					<tr>
						<td><b>Codice Contratto</b></td>
						<td bgcolor = \"#E5E5E5\"><i>
							".$rsOfferta['OffertaNome']."</i>
						</td>
						<td><b>Descrizione</b></td><td colspan =\"3\" bgcolor = \"#E5E5E5\"><i>".$rsOfferta['OffertaDescrizione']."</i></td>
						<td><b>Importo Mensile</b></td><td bgcolor = \"#E5E5E5\"><i>€".$rsOfferta['OffertaCanone']."</i></td>
					</tr>
					<tr>
						<td><b>Linea Telefonica:</b></td>
						<td><b>Esistente</b></td>
						<td  bgcolor = \"#E5E5E5\"><i>
						".$rsLinea['LineaDatiMigrazione']."</i>
						</td>
						<td><b>Numero Da Migrare</b></td>
						<td colspan = \"2\" bgcolor = \"#E5E5E5\"><i>".$rsLinea['LineaNumero']."</i></td>
						<td><b>Codice Migrazione</b></td>
						<td bgcolor = \"#E5E5E5\"><i>".$rsLinea['LineaCodice']."</i></td>
					</tr>
					<tr>
						<td></td>
						<td><b>Nuova Attivazione</b></td>
						<td colspan = \"3\" bgcolor = \"#E5E5E5\"><i>
							".$rsLinea['LineaDatiAttivazione']."</i>
						</td>
						<td><b>Numero Pilota</b></td>
						<td colspan = \"2\" bgcolor = \"#E5E5E5\"><i>".$rsLinea['LineaPilota']."</i></td>
					</tr>
					<tr>
						<td><b>Pagamento Canone</b></td>
						<td bgcolor = \"#E5E5E5\"><i>
						".$rsContratto['ContrattoPagamento']."</i>
						</td>
						<td><b>U.T. Attivazione</b></td>
						<td bgcolor = \"#E5E5E5\"><i>
						".$rsContratto['ContrattoAttivazione']."</i>
						</td>
						<td><b>Ricezione fattura</b></td>
						<td bgcolor = \"#E5E5E5\"><i>
						".$rsContratto['ContrattoFattura']."</i>
					</tr>
					<tr>
						<td colspan = \"8\" bgcolor = \"#1E90FF\" ><center><b>Servizi Opzionali</b></center></td>
					</tr>
					<tr>
						<td><b>Ip Statico</b></td>
						<td colspan = \"7\"  bgcolor = \"#E5E5E5\"><i>
						<fieldset>";
						
							if ($rsOpzioni['OpzioneIp'] == '0')
							{
								echo "Nessuno<input type=\"radio\" name=\"OpzioneIp\" value=\"0\"  checked=\"checked\" disabled/> - ";
								echo "1 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"1\" disabled/> - ";
								echo "4 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"4\" disabled/> - ";
								echo "8 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"8\" disabled/> - ";	  
								echo "16 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"16\" disabled/> - ";	  
								echo "32 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"32\" disabled/>";	  
								}
							if ($rsOpzioni['OpzioneIp'] == '1')
							{
								echo "Nessuno<input type=\"radio\" name=\"OpzioneIp\" value=\"0\"  disabled/> - ";
								echo "1 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"1\" checked=\"checked\" disabled/> - ";
								echo "4 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"4\" disabled/> - ";
								echo "8 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"8\" disabled/> - ";	  
								echo "16 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"16\" disabled/> - ";	  
								echo "32 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"32\" disabled/>";	  
								}
							if ($rsOpzioni['OpzioneIp'] == '4')
							{
								echo "Nessuno<input type=\"radio\" name=\"OpzioneIp\" value=\"0\"   /> - ";
								echo "1 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"1\" /> - ";
								echo "4 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"4\" checked=\"checked\" /> - ";
								echo "8 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"8\" /> - ";	  
								echo "16 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"16\" /> - ";	  
								echo "32 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"32\" />";	  
								}
							if ($rsOpzioni['OpzioneIp'] == '8')
							{
								echo "Nessuno<input type=\"radio\" name=\"OpzioneIp\" value=\"0\"   /> - ";
								echo "1 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"1\" /> - ";
								echo "4 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"4\" /> - ";
								echo "8 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"8\" checked=\"checked\" /> - ";	  
								echo "16 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"16\" /> - ";	  
								echo "32 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"32\" />";	  
								}
							if ($rsOpzioni['OpzioneIp'] == '16')
							{
								echo "Nessuno<input type=\"radio\" name=\"OpzioneIp\" value=\"0\"   /> - ";
								echo "1 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"1\" /> - ";
								echo "4 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"4\" /> - ";
								echo "8 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"8\" /> - ";	  
								echo "16 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"16\" checked=\"checked\" /> - ";	  
								echo "32 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"32\" />";	  
								}
							if ($rsOpzioni['OpzioneIp'] == '32')
							{
								echo "Nessuno<input type=\"radio\" name=\"OpzioneIp\" value=\"0\"   /> - ";
								echo "1 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"1\" /> - ";
								echo "4 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"4\" /> - ";
								echo "8 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"8\" /> - ";	  
								echo "16 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"16\" /> - ";	  
								echo "32 IP<input type=\"radio\" name=\"OpzioneIp\" value=\"32\" checked=\"checked\" />";	  
								}
								
				echo "	</fieldset>
				</i>
					</td>
					</tr>
					<tr>
						<td colspan = \"4\">";
							if ($rsOpzioni['OpzioneAP'] == '1')
							{
								echo "Acquisto e installazione Access Point<input type=\"checkbox\" name=\"OpzioneAP\" value=\"1\"  checked=\"checked\" readonly/>";
							} else 
								{
									echo "Acquisto e installazione Access Point<input type=\"checkbox\" name=\"OpzioneAP\" value=\"1\"  readonly/>";
									}
							
						echo "</td>
						<td colspan = \"4\">";
							if ($rsOpzioni['OpzioneElenco'] == '1')
							{
								echo "Pubblicazione Numero in Elenco Telefonico<input type=\"checkbox\" name=\"OpzioneElenco\" value=\"1\"  checked=\"checked\" readonly/>";
							} else 
								{
									echo "Pubblicazione Numero in Elenco Telefonico<input type=\"checkbox\" name=\"OpzioneElenco\" value=\"1\" readonly/>";
									}
							
						echo "</td>
					</tr>
					<tr>
						<td colspan = \"4\">";
							if ($rsOpzioni['OpzioneChie'] == '1')
							{
								echo "Identificaticazione Chiamante (Chi &egrave)<input type=\"checkbox\" name=\"OpzioneChie\" value=\"1\"  checked=\"checked\" readonly/>";
							} else 
								{
									echo "Identificaticazione Chiamante (Chi &egrave)<input type=\"checkbox\" name=\"OpzioneChie\" value=\"1\" readonly/>";
									}
							
						echo "</td>
						<td colspan = \"4\">
						";
							if ($rsOpzioni['OpzioneClinascosto']  == '1')
							{
								echo "Blocco Identificazitvo Chiamante (CLI Nascosto)<input type=\"checkbox\" name=\"OpzioneClinascosto\" value=\"1\"  checked=\"checked\" readonly/>";
							} else 
								{
									echo "Blocco Identificazitvo Chiamante (CLI Nascosto)<input type=\"checkbox\" name=\"OpzioneClinascosto\" value=\"1\" readonly/>";
									}
							
						echo "</td>
					</tr>
					<tr>
						<td colspan = \"4\">";
							if ($rsOpzioni['OpzioneTrasferimento']  == '1')
							{
								echo "Trasferimento di chiamata<input type=\"checkbox\" name=\"OpzioneTrasferimento\" value=\"1\"  checked=\"checked\" readonly/>";
							} else 
								{
									echo "Trasferimento di chiamata<input type=\"checkbox\" name=\"OpzioneTrasferimento\" value=\"1\" readonly/>";
									}
							
						echo "</td>
						<td colspan = \"4\">";
							if ($rsOpzioni['OpzionePubblicita']  == '1')
							{
								echo "Vuole ricevere la pubblicit&agrave?<input type=\"checkbox\" name=\"OpzionePubblicita\" value=\"1\"  checked=\"checked\" readonly/>";
							} else 
								{
									echo "Vuole ricevere la pubblicit&agrave?<input type=\"checkbox\" name=\"OpzionePubblicita\" value=\"1\" readonly/>";
									}
							
						echo "</td>
					</tr>
					<tr>
						<td colspan = \"4\">";
							if ($rsOpzioni['OpzioneAttesa']  == '1')
							{
								echo "Chiamata in attesa<input type=\"checkbox\" name=\"OpzioneAttesa\" value=\"1\"  checked=\"checked\" readonly/>";
							} else 
								{
									echo "Chiamata in attesa<input type=\"checkbox\" name=\"OpzioneAttesa\" value=\"1\" readonly/>";
									}
							
						echo "</td>
						<td colspan = \"4\">";
							if ($rsOpzioni['OpzioneSwitch']  == '1')
							{
								echo "Acquisto Switch 8 Porte<input type=\"checkbox\" name=\"OpzioneSwitch\" value=\"1\"  checked=\"checked\" readonly/>";
							} else 
								{
									echo "Acquisto Switch 8 Porte<input type=\"checkbox\" name=\"OpzioneSwitch\" value=\"1\" readonly/>";
									}
							
						echo "</td>
					</tr>
					<tr>
						<td colspan = \"8\" bgcolor = \"#1E90FF\"><center><b>RID</b></center></td>
					</tr>
					<tr>
						<td><b>Banca</td><td colspan = \"2\" bgcolor = \"#E5E5E5\"><i>".$rsContratto['ContrattoBanca']."</i></td>
						<td><b>Agenzia</td><td colspan = \"2\" bgcolor = \"#E5E5E5\"><i>".$rsContratto['ContrattoAgenzia']."</i></td>
						<td><b>Localit&agrave</td><td bgcolor = \"#E5E5E5\"><i>".$rsContratto['ContrattoLocalita']."</i></td>
					</tr>
					<tr>
						<td><b>Intestazione</td>
						<td colspan =\"7\" bgcolor = \"#E5E5E5\"><i>".$rsContratto['ContrattoIntestazione']."</i></td>
					</tr>
					<tr>
						<td><b>IBAN</td><td colspan = \"5\" bgcolor = \"#E5E5E5\"><i>".$rsContratto['ContrattoIban']."</i></td>
					</tr>
					<tr>
						<td  colspan =\"8\" bgcolor = \"#1E90FF\"><center><b>NOTE</b></center></td>
					</tr>
					<tr>
						<td colspan = \"8\">
						<textarea id=\"ContrattoNote\" name=\"ContrattoNote\" type=\"text\" placeholder=\"Note\" readonly>".$rsContratto['ContrattoNote']."</textarea></td>
					</tr>
				</table>
				<form action=\"stampa.php\" method=\"POST\" name=\"form\">
				<input id=\"ContrattoId\" name=\"ContrattoId\" type=\"hidden\" value=\"".$_POST['ContrattoId']."\" >
				<input  type=\"submit\" id=\"submit\" value=\"STAMPA\">
			</form><br /><br />";
			break;
			
		case search:
			if(isset($_POST['ContrattoId']) && $_POST['ContrattoId'] != ''){
			$sql = "SELECT * FROM Contratti WHERE ContrattoId like '%".$_POST['ContrattoId']."%'";
			}
			if(isset($_POST['ContrattoTipo']) && $_POST['ContrattoTipo'] != ''){
				$sql = "SELECT * FROM Contratti WHERE ContrattoTipo like '%".$_POST['ContrattoTipo']."%'";
				}
			if(isset($_POST['ContrattoNome']) && $_POST['ContrattoNome'] != ''){
				$sql = "SELECT * FROM Contratti  WHERE ContrattoNome like '%".$_POST['ContrattoNome']."%'";
				}
			if(isset($_POST['ContrattoStato']) && $_POST['ContrattoStato'] != ''){
				$sql = "SELECT * FROM Contratti WHERE ContrattoStato like '%".$_POST['ContrattoStato']."%'";
				}
		
				//print_r($sql);
				$res = mysql_query($sql);
				$numrows=mysql_num_rows($res);
				
				if ($numrows == 0) // NESSUN CONTRATTO TROVATO
				{
						echo "<div class=\"warning\">Impossibile Visualizzare CONTRATTI: Nessun Corrispondenza Trovato</div>";
						break;
					}
					else  // CONTRATTO TROVATO
					{
						echo "<h2>Risultato Ricerca</h2>
						<div class=\"tabella\" >
							<table>
								<tr>
								<td>Contratto Numero</td>
								<td>Nome Contratto</td>
								<td>Tipologia</td>
								<td>Stato</td>
								<td></td>
								</tr>";
						while($rsContratti = mysql_fetch_assoc($res))
						{
							
							echo "<tr>
							<td>".$rsContratti['ContrattoId']."</td>
							<td>".$rsContratti['ContrattoNome']."</td>
							<td>".$rsContratti['ContrattoTipo']."</td>
							<td>
							<form action=\"admincontratti.php\" method=\"post\">
					<select id=\"ContrattoStato\" name=\"ContrattoStato\">";
							
							echo "<option value=\"".$rsContratti['ContrattoStato']."\">".$rsContratti['ContrattoStato']."</option>";
							$statoContratto = array("Inserito", "InLavorazione", "Scartato", "Attivato");
							reset($statoContratto);
							foreach ($statoContratto as $key10 => $value10) {
								if ( $value10 != $rsContratti['ContrattoStato'])
									{
										echo "<option value=\"".$value10."\">".$value10."</option>";
									}
								
							}
						echo"	</select>
					<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"editStato\" >
					<input id=\"ContrattoId\" name=\"ContrattoId\" type=\"hidden\" value=\"".$rsContratti['ContrattoId']."\" >
					<input name=\"AggiornaStato\" type=\"image\" src=\"image\\refresh.jpg\" alt=\"Aggiorna Stato\" title=\"Aggiorna Stato\"> 
							</td>
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
							<form action=\"adminclienti.php\" method=\"post\" style=\"float: right;\">
									<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"more\" >
									<input id=\"ClienteId\" name=\"ContrattoId\" type=\"hidden\" value=\"".$rsContratti['Clienti_ClienteId']."\" >
									<input name=\"Dettaglio Contratti\" type=\"image\" src=\"image\search.gif\" alt=\"Dettaglio Contratti\" title=\"Dettaglio Cliente\"> 
								</fieldset>
							</form>
							</td>
						</tr>";
						} // Fine WHILE
					
					echo "</table>
					</div>";
						
					} // FINE CONTRATTO TROVATO
				break; // FINE SEARCH
				
		case update: // INIZIO AGGIORNAMENTO CLIENTE
			echo "<h3>Aggiornamento Contratto</h3>";

			$sqlOfferta = "UPDATE  `Contratti_Offerte` SET  `OffertaId` =  '".$_POST['OffertaId']."' WHERE  `ContrattoId` = ".$_POST['ContrattoId']."";
			
				if (!mysql_query($sqlOfferta))
			  {
				
				$msg = 'Error Aggiornamento Offerta: ' . mysql_error();
				echo '<script language=javascript>document.location.href="admincontratti.php?id=koedit&msg='.$msg.'"</script>';
			  }
			  
			$sqlOpzioni = "UPDATE  `Opzioni` SET  
								`OpzioneIp` =  '".$_POST['OpzioneIp']."',
								  `OpzioneAP` =  '".$_POST['OpzioneAP']."',
									`OpzioneElenco` =  '".$_POST['OpzioneElenco']."',
										`OpzioneChie` =  '".$_POST['OpzioneChie']."',
											`OpzioneClinascosto` =  '".$_POST['OpzioneClinascosto']."',
												`OpzioneTrasferimento` =  '".$_POST['OpzioneTrasferimento']."',
													`OpzionePubblicita` =  '".$_POST['OpzionePubblicita']."',
														`OpzioneSwitch` =  '".$_POST['OpzioneSwitch']."',
															`OpzioneAttesa` =  '".$_POST['OpzioneAttesa']."'
									WHERE  `OpzioneId` = '".$_POST['OpzioneId']."'";
			
			if (!mysql_query($sqlOpzioni))
			  {
				
				$msg = 'Error Aggiornamento Opzioni: ' . mysql_error() .' - '. $sqlOpzioni;
				echo '<script language=javascript>document.location.href="admincontratti.php?id=koedit&msg='.$msg.'"</script>';
			  }
			  
			  
			$sqlLinea = "UPDATE  `Linea` SET  
								`LineaPilota` =  '".$_POST['LineaPilota']."',
								  `LineaDatiAttivazione` =  '".$_POST['LineaDatiAttivazione']."',
									`LineaDatiMigrazione` =  '".$_POST['LineaDatiMigrazione']."',
										`LineaNumero` =  '".$_POST['LineaNumero']."',
											`LineaCodice` =  '".$_POST['LineaCodice']."'
									WHERE  `LineaId` = ".$_POST['LineaId']."";
			
			if (!mysql_query($sqlLinea))
			  {
				
				$msg = 'Error Aggiornamento Linea: ' . mysql_error();
				echo '<script language=javascript>document.location.href="admincontratti.php?id=koedit&msg='.$msg.'"</script>';
			  }
			
			$sqlContratto = "UPDATE  `Contratti` SET  
								`ContrattoBanca` =  '".$_POST['ContrattoBanca']."',
								  `ContrattoAgenzia` =  '".$_POST['ContrattoAgenzia']."',
									`ContrattoLocalita` =  '".$_POST['ContrattoLocalita']."',
										`ContrattoIntestazione` =  '".$_POST['ContrattoIntestazione']."',
											`ContrattoIban` =  '".$_POST['ContrattoIban']."',
												`ContrattoNote` =  '".$_POST['ContrattoNote']."',
													`ContrattoPagamento` =  '".$_POST['ContrattoPagamento']."',
														`ContrattoAttivazione` =  '".$_POST['ContrattoAttivazione']."',
															`ContrattoFattura` =  '".$_POST['ContrattoFattura']."'
									WHERE  `ContrattoId` = ".$_POST['ContrattoId']."";
			
			if (!mysql_query($sqlContratto))
			  {
				
				$msg = 'Error Aggiornamento Contratto: ' . mysql_error();
				echo '<script language=javascript>document.location.href="admincontratti.php?id=koedit&msg='.$msg.'"</script>';
			  }
			  
			$msg = "ESITO POSITOVO";
			echo '<script language=javascript>document.location.href="admincontratti.php?id=okedit&msg'.$msg.'"</script>';
			
			//~ echo $sqlOfferta."<br />".$sqlOpzioni."<br />".$sqlLinea."<br />".$sqlContratto;
			break; // FINE AGGIORNAMENTO CLIENTE
			
		case update2:
				echo "<h3>Aggiornamento Contratto</h3>";

				$sqlOfferta = "UPDATE  `Contratti_Offerte` SET  `OffertaId` =  '".$_POST['OffertaId']."' WHERE  `ContrattoId` = ".$_POST['ContrattoId']."";
				
					if (!mysql_query($sqlOfferta))
				  {
					
					$msg = 'Error Aggiornamento Offerta: ' . mysql_error();
					echo '<script language=javascript>document.location.href="admincontratti.php?id=koedit&msg='.$msg.'"</script>';
				  }
				
				$sqlContratto = "UPDATE  `Contratti` SET  
									`ContrattoBanca` =  '".$_POST['ContrattoBanca']."',
									  `ContrattoAgenzia` =  '".$_POST['ContrattoAgenzia']."',
										`ContrattoLocalita` =  '".$_POST['ContrattoLocalita']."',
											`ContrattoIntestazione` =  '".$_POST['ContrattoIntestazione']."',
												`ContrattoIban` =  '".$_POST['ContrattoIban']."',
													`ContrattoNote` =  '".$_POST['ContrattoNote']."',
														`ContrattoPagamento` =  '".$_POST['ContrattoPagamento']."',
															`ContrattoAttivazione` =  '".$_POST['ContrattoAttivazione']."',
																`ContrattoFattura` =  '".$_POST['ContrattoFattura']."',
																	`ContrattoPunti` = '".$_POST['ContrattoPunti']."',
																	`ContrattoProvvigioni` = '".$_POST['ContrattoProvvigioni']."'
										WHERE  `ContrattoId` = ".$_POST['ContrattoId']."";
				
				if (!mysql_query($sqlContratto))
				  {
					
					$msg = 'Error Aggiornamento Contratto: ' . mysql_error();
					echo '<script language=javascript>document.location.href="admincontratti.php?id=koedit&msg='.$msg.'"</script>';
				  }
				  
				$msg = "ESITO POSITOVO";
				echo '<script language=javascript>document.location.href="admincontratti.php?id=okedit&msg'.$msg.'"</script>';
			break;
			
		case editStato: // INIZIO AGGIORNAMENTO Stato Contratto
			echo "<h3>Aggiornamento Contratto</h3>";
			if ($_POST['ContrattoStato'] == "Attivato") 
			{
				$sqlStato = "UPDATE  `Contratti` SET  `ContrattoStato` =  '".$_POST['ContrattoStato']."', `ContrattoDataAttivazione` = CURDATE() WHERE  `ContrattoId` = ".$_POST['ContrattoId']."";
				}
			else 
				{
			$sqlStato = "UPDATE  `Contratti` SET  `ContrattoStato` =  '".$_POST['ContrattoStato']."' WHERE  `ContrattoId` = ".$_POST['ContrattoId']."";
			}
				if (!mysql_query($sqlStato))
			  {
				
				$msg = 'Error Aggiornamento Stato: ' . mysql_error();
				echo '<script language=javascript>document.location.href="admincontratti.php?id=koedit&msg='.$msg.'"</script>';
			  }
			
			$msg = "ESITO POSITOVO";
			echo '<script language=javascript>document.location.href="admincontratti.php?id=okedit&msg'.$msg.'"</script>';
			
			//~ echo $sqlOfferta."<br />".$sqlOpzioni."<br />".$sqlLinea."<br />".$sqlContratto;
		break; // FINE AGGIORNAMENTO STATO
			
			} // FINE SWITCH(stato)
			
	}// FINE controllo variabile STATO
	else    // se viene chiamata direttamente la pagina senza passaggio variabili
	{
		// Seleziono Tutti i Contratti
		$query = "SELECT * FROM Contratti";
		// conto il numero di righe totali
		$all_rows = mysql_num_rows(mysql_query($query));
		// definisco il numero totale di pagine
		$all_pages = ceil($all_rows / $ContrattiPagina);
		// Calcolo da quale record iniziare
		$first = ($pag - 1) * $ContrattiPagina;
		// Recupero i record per la pagina corrente...
		// utilizzando LIMIT per partire da $first e contare fino a $ContrattiPagine
		$sql = "SELECT * FROM Contratti LIMIT ".$first.", ".$ContrattiPagina."";
		

		$resContratti = mysql_query($sql);
		
		echo "<h2>Contratti</h2>";
		
		$nr = mysql_num_rows($resContratti);
		echo "
				<div class=\"tabella\" >
					<table>
						<tr>
						<td>Contratto Numero</td>
						<td>Nome Contratto</td>
						<td>Tipologia</td>
						<td>Stato</td>
						<td>Provvigioni</td>
						<td>Punti</td>
						<td>Agente</td>
						<td></td>
						</tr>";
						
		if ($nr != 0){
		  for($x = 0; $x < $nr; $x++){
			$rsContratti = mysql_fetch_assoc($resContratti);
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
					<td>
					<form action=\"admincontratti.php\" method=\"post\">
					<select id=\"ContrattoStato\" name=\"ContrattoStato\">";
							
							echo "<option value=\"".$rsContratti['ContrattoStato']."\">".$rsContratti['ContrattoStato']."</option>";
							$statoContratto = array("Inserito", "InLavorazione", "Scartato", "Attivato");
							reset($statoContratto);
							foreach ($statoContratto as $key10 => $value10) {
								if ( $value10 != $rsContratti['ContrattoStato'])
									{
										echo "<option value=\"".$value10."\">".$value10."</option>";
									}
								
							}
						echo"	</select>
					<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"editStato\" >
					<input id=\"ContrattoId\" name=\"ContrattoId\" type=\"hidden\" value=\"".$rsContratti['ContrattoId']."\" >
					<input name=\"AggiornaStato\" type=\"image\" src=\"image\\refresh.png\" alt=\"Aggiorna Stato\" title=\"Aggiorna Stato\"> 
					</form>					
					</td>
					<td>".$rsContratti['ContrattoProvvigioni']."</td>
					<td>".$rsContratti['ContrattoPunti']."</td>";
					
					$sqlAgente = "SELECT * FROM Agenti as A JOIN Agenti_Clienti_Contratti as C on A.idAgenti = C.AgenteId WHERE C.ContrattoId = '".$rsContratti['ContrattoId']."' ";
					$resAgente = mysql_query($sqlAgente);
					$rsAgente = mysql_fetch_assoc($resAgente);
				echo "<td>
						".$rsAgente['AgenteCognome']." ".$rsAgente['AgenteNome']."
					</td>
					<td style=\"float:right\" >
					<form action=\"schedaagente.php\" method=\"post\" style=\"float: right;\">
								<input id=\"idAgenti\" name=\"idAgenti\" type=\"hidden\" value=\"".$rsAgente['idAgenti']."\" >
								<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"more\" >
								<input name=\"Dettaglio Agente\" type=\"image\" src=\"image\search.gif\" alt=\"Dettaglio\" title=\"Dettaglio Agente\"> 
							</fieldset>
						</form>
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
					<form action=\"adminclienti.php\" method=\"post\" style=\"float: right;\">
							<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"more\" >
							<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$rsContratti['Clienti_idCliente']."\" >
							<input name=\"Dettaglio Cliente\" type=\"image\" src=\"image\search.gif\" alt=\"Dettaglio Cliente\" title=\"Dettaglio Cliente\"> 
						</fieldset>
					</form>
					</td>
				</tr>";
		
			
		  
			} // Fine FOR
			
			echo "</table>
			</div>";
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
			
		} // Fine If ( numero record )
		else{
		  echo "Nessun record trovato!";
		}
		
		} // Fine Default view
?>
