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


echo $menu;

echo "
 <div class=\"box\" \">
            <a href=\"javascript:slideonlyone('newboxes1');\" >Cerca Agente</a>
         </div>
         <div class=\"newboxes2\" id=\"newboxes1\" style=\" display: none;\">
         <form action=\"adminagenti.php\" method=\"post\">
        <fieldset id=\"inputs\">
            <input id=\"AgenteNome\" name=\"AgenteNome\" type=\"text\" placeholder=\"Nome Agente\" autofocus>
            <input id=\"AgenteCognome\" name=\"AgenteCognome\" type=\"text\" placeholder=\"Cognome Agente\">
            <input id=\"AgenteId\" name=\"AgenteId\" type=\"text\" placeholder=\"Codice Agente\">
            <input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"search\" >
			</fieldset> 
            <fieldset id=\"actions\">
            <input type=\"submit\" id=\"submit\" value=\"Cerca\">
			</fieldset>
           </form>
         </div>";


if(isset($_POST['stato'])){
switch($_POST['stato']){
	
		// AGGIORNAMENTO ACCESSO WEB
		case update:
				$query = "SELECT * from Agenti WHERE idAgenti = '".$_POST['idAgenti']."' ";
				//echo $query;
				$res = mysql_query($query);
				$rsAgente = mysql_fetch_assoc($res);
				echo $rsAgente['AgenteAbilitato'];
				if ($rsAgente['AgenteAbilitato'] == '0') {
						$setAccesso = '1';
						$sql = "UPDATE  `Agenti` SET  
						`AgenteAbilitato` =  '".$setAccesso."'
							WHERE  `idAgenti` =".$_POST['idAgenti']."";
					if (!mysql_query($sql))
					  {
					  die('Error Aggiornamento Agente: ' . mysql_error());
					  }
				echo '<script language=javascript>document.location.href="adminagenti.php?id=ok"</script>';
				}
				
				if ($rsAgente['AgenteAbilitato'] == '1') {
						$setAccesso = '0';
						$sql = "UPDATE  `Agenti` SET  
						`AgenteAbilitato` =  '".$setAccesso."'
							WHERE  `idAgenti` =".$_POST['idAgenti']."";
					if (!mysql_query($sql))
					  {
					  die('Error Aggiornamento Agente: ' . mysql_error());
					  }
					echo '<script language=javascript>document.location.href="adminagenti.php?id=ok"</script>';
				}
				break;
			// END AGGIORNAMENTO ACCESSO WEB
			
			// RICERCA
			case search:
				if(isset($_POST['AgenteNome']) && $_POST['AgenteNome'] != ''){
			$sql = "SELECT * FROM Agenti WHERE AgenteNome like '%".$_POST['AgenteNome']."%' order by AgenteNome ASC";
			}
		if(isset($_POST['AgenteCognome']) && $_POST['AgenteCognome'] != ''){
			$sql = "SELECT * FROM Agenti WHERE  
			AgenteCognome like '%".$_POST['AgenteCognome']."%' order by AgenteCognome ASC";
			}
		if(isset($_POST['AgenteId']) && $_POST['AgenteId'] != ''){
			$sql = "SELECT * FROM Agenti WHERE idAgenti like '%".$_POST['AgenteId']."%' order by idAgenti ASC";
			}
			
			//print_r($sql);
			$res = mysql_query($sql);
			$numrows=mysql_num_rows($res);
			
			if ($numrows == 0) {
			echo "<h3>Siacente Nessuna Corrispondenza Trovata nella ricerca : ".$_POST['AgenteCognome']."".$_POST['AgenteNome']."".$_POST['AgenteId']."";
			echo "<h2>Lista Agenti</h2>";
			$cliente = "SELECT * FROM Agenti order by AgenteCognome ASC";
			$res = mysql_query($cliente);
			echo "
					<div class=\"tabella\" >
						<table>
							<tr>
							<td>Cognome</td>
							<td>Nome</td>
							<td>Codice Agente</td>
							<td>Numero Contratti</td>
							<td></td>
							</tr>";
			while ($rsAgente = mysql_fetch_assoc($res)){
				echo "<tr>
						<td>".$rsAgente['AgenteNome']."</td>
						<td>".$rsAgente['AgenteCognome']."</td>
						<td>".$rsAgente['idAgenti']."</td>";
						$query = "SELECT COUNT( * ) AS NumeroContratti FROM Agenti_Clienti_Contratti AS a, Contratti AS c
										WHERE a.ContrattoId = c.ContrattoId
												AND AgenteId =  '".$rsAgente['idAgenti']."'";
						$resContratti = mysql_query($query);
						$rsContratti = mysql_fetch_assoc($resContratti);
					echo "<td>".$rsContratti['NumeroContratti']."</td>
						<td style=\"float:right\" >
						<form action=\"schedaagente.php\" method=\"post\" style=\"float: right;\">
								<input id=\"idAgenti\" name=\"idAgenti\" type=\"hidden\" value=\"".$rsAgente['idAgenti']."\" >
								<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"more\" >
								<input name=\"Dettaglio Agente\" type=\"image\" src=\"image\search.gif\" alt=\"Dettaglio\" title=\"Dettaglio Agente\"> 
							</fieldset>
						</form>
						<form action=\"schedaagente.php\" method=\"post\" style=\"float: right;\">
								<input id=\"idAgenti\" name=\"idAgenti\" type=\"hidden\" value=\"".$rsAgente['idAgenti']."\" >
								<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"edit\" >
								<input name=\"Edita Agente\" type=\"image\" src=\"image\edit.gif\" alt=\"Edita Agente\" title=\"Edita Agente\"> 
							</fieldset>
						</form>
						<form action=\"schedaagente.php\" method=\"post\" style=\"float: right;\">
								<input id=\"idAgenti\" name=\"idAgenti\" type=\"hidden\" value=\"".$rsAgente['idAgenti']."\" >
								<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"del\" >
								<input name=\"Cancella Agente\" type=\"image\" src=\"image\delete.gif\" alt=\"Cancella Agente\" title=\"Cancella Agente\"> 
							</fieldset>
						</form>
						<form action=\"schedacontratti.php\" method=\"post\" style=\"float: right;\">
								<input id=\"idAgenti\" name=\"idAgenti\" type=\"hidden\" value=\"".$rsAgente['idAgenti']."\" >
								<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"moreall\" >
								<input name=\"Dettaglio Contratti\" type=\"image\" src=\"image\contract.gif\" alt=\"Dettaglio Contratti\" title=\"Dettaglio Contratti\"> 
							</fieldset>
						</form>
						</td>
					</tr>";
			}
				echo "</table>
				</div>";
			}
		 else {
			 
			 echo "<h2>Risultato - Lista Agenti</h2>
					<div class=\"tabella\" >
						<table>
							<tr>
							<td>Cognome</td>
							<td>Nome</td>
							<td>Codice Agente</td>
							<td>Numero Contratti</td>
							<td></td>
							</tr>";
			while ($rsAgente = mysql_fetch_assoc($res)){
				echo "<tr>
						<td>".$rsAgente['AgenteNome']."</td>
						<td>".$rsAgente['AgenteCognome']."</td>
						<td>".$rsAgente['idAgenti']."</td>";
						$query = "SELECT COUNT( * ) AS NumeroContratti FROM Agenti_Clienti_Contratti AS a, Contratti AS c
										WHERE a.ContrattoId = c.ContrattoId
												AND AgenteId =  '".$rsAgente['idAgenti']."'";
						$resContratti = mysql_query($query);
						$rsContratti = mysql_fetch_assoc($resContratti);
					echo "<td>".$rsContratti['NumeroContratti']."</td>
						<td style=\"float:right\" >
						<form action=\"schedaagente.php\" method=\"post\" style=\"float: right;\">
								<input id=\"idAgenti\" name=\"idAgenti\" type=\"hidden\" value=\"".$rsAgente['idAgenti']."\" >
								<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"more\" >
								<input name=\"Dettaglio Agente\" type=\"image\" src=\"image\search.gif\" alt=\"Dettaglio\" title=\"Dettaglio Agente\"> 
							</fieldset>
						</form>
						<form action=\"schedaagente.php\" method=\"post\" style=\"float: right;\">
								<input id=\"idAgenti\" name=\"idAgenti\" type=\"hidden\" value=\"".$rsAgente['idAgenti']."\" >
								<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"edit\" >
								<input name=\"Edita Agente\" type=\"image\" src=\"image\edit.gif\" alt=\"Edita Agente\" title=\"Edita Agente\"> 
							</fieldset>
						</form>
						<form action=\"schedaagente.php\" method=\"post\" style=\"float: right;\">
								<input id=\"idAgenti\" name=\"idAgenti\" type=\"hidden\" value=\"".$rsAgente['idAgenti']."\" >
								<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"del\" >
								<input name=\"Cancella Agente\" type=\"image\" src=\"image\delete.gif\" alt=\"Cancella Agente\" title=\"Cancella Agente\"> 
							</fieldset>
						</form>
						<form action=\"schedacontratti.php\" method=\"post\" style=\"float: right;\">
								<input id=\"idAgenti\" name=\"idAgenti\" type=\"hidden\" value=\"".$rsAgente['idAgenti']."\" >
								<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"moreall\" >
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
			// END RICERCA
	}
		
}
else {
	switch($_GET['id']){
		case 1:
			echo "<h2>Aggiornamento Effettuato Correttamente</h2>";
			break;
		case ok:
			echo "<h2>Modifica Accesso Web Agente Effettuata </h2>";
			break;
		
		case ko:
			echo "<h2>Impossibile Modificare Accesso</h2>";
			break;
		
		}
	echo "<h2>Lista Agenti</h2>";
	$cliente = "SELECT * FROM Agenti order by AgenteCognome ASC";
	$res = mysql_query($cliente);
	echo "
			<div class=\"tabella\" >
				<table>
					<tr>
					<td>Cognome</td>
					<td>Nome</td>
					<td>Codice Agente</td>
					<td>Numero Contratti</td>
					<td>Accesso WEB</td>
					<td></td>
					</tr>";
	while ($rsAgente = mysql_fetch_assoc($res)){
		echo "<tr>
				<td>".$rsAgente['AgenteNome']."</td>
				<td>".$rsAgente['AgenteCognome']."</td>
				<td>".$rsAgente['idAgenti']."</td>";
				
				$query = "SELECT COUNT( * ) AS NumeroContratti FROM Agenti_Clienti_Contratti AS a, Contratti AS c
								WHERE a.ContrattoId = c.ContrattoId
										AND AgenteId =  '".$rsAgente['idAgenti']."'";
				$resContratti = mysql_query($query);
				$rsContratti = mysql_fetch_assoc($resContratti);
			echo "<td>".$rsContratti['NumeroContratti']."</td>";
			if ($rsAgente['AgenteAbilitato'] == '1'){
					echo "<td style=\" background-color:#90EE90\">Abilitato</td>";
					} 
					else {
						echo "<td style=\" background-color:#FF0000\" >Disabilitato</td>";
						}
			echo"	<td style=\"float:right\" >
			
				<form action=\"schedaagente.php\" method=\"post\" style=\"float: right;\">
						<input id=\"idAgenti\" name=\"idAgenti\" type=\"hidden\" value=\"".$rsAgente['idAgenti']."\" >
						<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"more\" >
						<input name=\"Dettaglio Agente\" type=\"image\" src=\"image\search.gif\" alt=\"Dettaglio\" title=\"Dettaglio Agente\"> 
					</fieldset>
				</form>
				<form action=\"schedaagente.php\" method=\"post\" style=\"float: right;\">
						<input id=\"idAgenti\" name=\"idAgenti\" type=\"hidden\" value=\"".$rsAgente['idAgenti']."\" >
						<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"edit\" >
						<input name=\"Edita Agente\" type=\"image\" src=\"image\edit.gif\" alt=\"Edita Agente\" title=\"Edita Agente\"> 
					</fieldset>
				</form>
				<form action=\"schedaagente.php\" method=\"post\" style=\"float: right;\">
						<input id=\"idAgenti\" name=\"idAgenti\" type=\"hidden\" value=\"".$rsAgente['idAgenti']."\" >
						<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"del\" >
						<input name=\"Cancella Agente\" type=\"image\" src=\"image\delete.gif\" alt=\"Cancella Agente\" title=\"Cancella Agente\"> 
					</fieldset>
				</form>
				<form action=\"schedacontratti.php\" method=\"post\" style=\"float: right;\">
						<input id=\"idAgenti\" name=\"idAgenti\" type=\"hidden\" value=\"".$rsAgente['idAgenti']."\" >
						<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"moreall\" >
						<input name=\"Dettaglio Contratti\" type=\"image\" src=\"image\contract.gif\" alt=\"Dettaglio Contratti\" title=\"Dettaglio Contratti\"> 
					</fieldset>
				</form>
				<form action=\"adminagenti.php\" method=\"post\" style=\"float: right;\">
						<input id=\"idAgenti\" name=\"idAgenti\" type=\"hidden\" value=\"".$rsAgente['idAgenti']."\" >
						<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"update\" >
						<input name=\"Accesso Web\" type=\"image\" src=\"image\lock.gif\" alt=\"Accesso WEB\" title=\"Accesso WEB\"> 
					</fieldset>
				</form>
				</td>
			</tr>";
	}
		echo "</table>
		</div>";
	
	}
?>
