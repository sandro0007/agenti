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
echo "
<div class=\"box\" \">
            <a href=\"javascript:slideonlyone('newboxes1');\" >Cerca Contratti</a>
         </div>
         <div class=\"newboxes2\" id=\"newboxes1\" style=\" display: none;\">
         <form action=\"contratti.php\" method=\"post\">
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

if(isset($_POST['stato'])){
		if(isset($_POST['ContrattoId']) && $_POST['ContrattoId'] != ''){
			$sql = "SELECT * FROM Contratti AS C JOIN Agenti_Clienti_Contratti AS A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$cod."' and
			C.ContrattoId like '%".$_POST['ContrattoId']."%'";
			}
		if(isset($_POST['ContrattoTipo']) && $_POST['ContrattoTipo'] != ''){
			$sql = "SELECT * FROM Contratti AS C JOIN Agenti_Clienti_Contratti AS A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$cod."' and
				C.ContrattoTipo like '%".$_POST['ContrattoTipo']."%'";
			}
		if(isset($_POST['ContrattoNome']) && $_POST['ContrattoNome'] != ''){
			$sql = "SELECT * FROM Contratti AS C JOIN Agenti_Clienti_Contratti AS A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$cod."' and
					C.ContrattoNome like '%".$_POST['ContrattoNome']."%'";
			}
		if(isset($_POST['ContrattoStato']) && $_POST['ContrattoStato'] != ''){
			$sql = "SELECT * FROM Contratti AS C JOIN Agenti_Clienti_Contratti AS A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$cod."' and
					C.ContrattoStato like '%".$_POST['ContrattoStato']."%'";
			}
		
	//print_r($sql);
	$res = mysql_query($sql);
	$numrows=mysql_num_rows($res);
	
	if ($numrows == 0) {
			echo "Spiacente Nessuna Corrispondenza Trovata";
			echo "<h2>Lista Contratti Agente</h2>";
	$contratti = "SELECT * FROM Contratti AS C JOIN Agenti_Clienti_Contratti AS A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$cod."'";
	$res = mysql_query($contratti);
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
	else {
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
} else {
	echo "<h2>Lista Contratti Agente</h2>";
	$contratti = "SELECT * FROM Contratti AS C JOIN Agenti_Clienti_Contratti AS A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$cod."'";
	$res = mysql_query($contratti);
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
                      

	
?>
