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
echo "
<div class=\"box\" \">
            <a href=\"javascript:slideonlyone('newboxes1');\" >Cerca Contratti</a>
         </div>
         <div class=\"newboxes2\" id=\"newboxes1\" style=\" display: none;\">
         <form action=\"admincontratti.php\" method=\"post\">
        <fieldset id=\"inputs\">
            <input id=\"ContrattoId\" name=\"ContrattoId\" type=\"text\" placeholder=\"Id Contratto\" autofocus>
            <input id=\"ContrattoTipo\" name=\"ContrattoTipo\" type=\"text\" placeholder=\"Tipologia\">
            <input id=\"ContrattoNome\" name=\"ContrattoNome\" type=\"text\" placeholder=\"Contratto\">
            <input id=\"ContrattoStato\" name=\"ContrattoStato\" type=\"text\" placeholder=\"Stato Contratto \">
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
		case 1:
			echo "<h2>Aggiornamento Effettuato Correttamente</h2>";
			break;
		case ok:
			echo "<h2>Modifica Agente Effettuata </h2>";
			break;
		
		case ko:
			echo "<h2>Impossibile Modificare Agente</h2>";
			break;
			
		case nodel:
			echo "<h2>Impossibile Cancellare Contratto in Lavorazione o Attivato!</h2>";
			break;
			
		case okdel:
			echo "<h2>Contratto Cancellato Correttamente.</h2>";
			break;
		
		}

if(isset($stato)) // se la variabile stato è settata
{ 
	switch($stato)
	{
		case del:
			echo "Richiesta Cancellazione  Contratti numero ".$_POST['ContrattoId']."";
			$sql = "SELECT * FROM Contratti WHERE ContrattoId = '".$_POST['ContrattoId']."'";
			$res = mysql_query($sql);
			$rsContratti = mysql_fetch_assoc($res);
			if ($rsContratti['ContrattoStato'] != 'Inserito')
			{
				echo '<script language=javascript>document.location.href="admincontratti.php?id=nodel"</script>';
				}
				else 
				{
					$query="DELETE FROM Agenti_Clienti_Contratti WHERE ContrattoId = ".$_POST['ContrattoId']."";
					$query2="DELETE FROM Contratti WHERE ContrattoId = ".$_POST['ContrattoId']."";
						if (!mysql_query($query))
						  {
							die('Error Cancellazione Associazione Contratto - Agente: ' . mysql_error());
						  }
						 if (!mysql_query($query2))
						  {
							  die('Error Cancellazione Contratto: ' . mysql_error());
						  }
						echo '<script language=javascript>document.location.href="admincontratti.php?id=okdel"</script>';
					} 
			
			break;
			
		case edit:
			echo "Richiesta Modifica  Contratto numero ".$_POST['ContrattoId']."";
			
			break;
			
		case moreall:
			echo "Richiesta Dettaglio Contratti per il cliente ".$_POST['IdCliente']."";
			
			break;
			
		case more:
			echo "Richiesta Dettaglio Contratti numero ".$_POST['ContrattoId']."";
			
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
						echo "Spiacente Nessuna Corrispondenza Trovata";
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
