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

if(isset($_POST['stato'])){
	switch($_POST['stato']){
		case update:
				if ($_POST['ContrattoFatturato'] == '0') {
						$setFattura = '1';
						$sql = "UPDATE  `Contratti` SET  
						`ContrattoFatturato` =  '".$setFattura."'
							WHERE  `ContrattoId` =".$_POST['ContrattoId']."";
					if (!mysql_query($sql))
					  {
					  die('Error Aggiornamento Cliente: ' . mysql_error());
					  }
				echo '<script language=javascript>document.location.href="contabilita.php?id=ok"</script>';
				}
				
				if ($_POST['ContrattoFatturato'] == '1' and $_POST['ContrattoPagato'] == '0') {
						$setFattura = '0';
						$sql = "UPDATE  `Contratti` SET  
						`ContrattoFatturato` =  '".$setFattura."'
							WHERE  `ContrattoId` =".$_POST['ContrattoId']."";
							if (!mysql_query($sql))
							  {
							  die('Error Aggiornamento Cliente: ' . mysql_error());
							  }
						echo '<script language=javascript>document.location.href="contabilita.php?id=ok"</script>';
						}
				else {
					echo '<script language=javascript>document.location.href="contabilita.php?id=ko"</script>';
				}
				break;
	}
}

switch($_GET['id']){
		case ok:
			echo "<h2>Aggiornamento Effettuato Correttamente</h2>";
			break;
		
		case ko:
			echo "<h2>Aggiornamento Non Effettuato - Pagamento fattura effettuato</h2>";
			break;
		
		}
	
echo "<h2>Pagina Contabilit&agrave</h2>";

		
	$sql = "SELECT * FROM Contratti AS C JOIN Agenti_Clienti_Contratti AS A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$cod."' and
	C.ContrattoStato like '%Attivato%' order by C.ContrattoFatturato";
	
		
	//print_r($sql);
	$res = mysql_query($sql);
	$numrows=mysql_num_rows($res);
	
	if ($numrows == 0) {
			echo "<h2>Spiacente Nessuna Contratto Ancora Attivato</h2>";
		}
		else {
			echo "<h3>Lista Contratti Agente </h3>";
	$contratti = "SELECT * FROM Contratti AS C JOIN Agenti_Clienti_Contratti AS A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$cod."'";
	echo "
			<div class=\"tabella\" >
				<table>
					<tr>
					<td>Contratto Numero</td>
					<td>Nome Contratto</td>
					<td>Tipologia</td>
					<td>Stato</td>
					<td>Fatturato</td>
					<td></td>
					</tr>";
	while ($rsContratti = mysql_fetch_assoc($res)){
		/**
		 *  
		 * $rsContratti['ContrattoId']
		 * $rsContratti['ContrattoNome']
		 * $rsContratti['ContrattoTipo']
		 * $rsContratti['ContrattoStato']
		 * $rsContratti['ContrattoFatturato']
		 * $rsContratti['ContrattoPagato']
		 * 
		 * */
		echo "<tr>
				<td>".$rsContratti['ContrattoId']."</td>
				<td>".$rsContratti['ContrattoNome']."</td>
				<td>".$rsContratti['ContrattoTipo']."</td>
				<td>".$rsContratti['ContrattoStato']."</td>";
				if ($rsContratti['ContrattoFatturato'] == '1'){
					echo "<td style=\" background-color:#90EE90\">Fattura Emessa</td>";
					} 
					else {
						echo "<td style=\" background-color:#FF0000\" >Fattura Da Emettere</td>";
						}
		echo "	<td style=\"float:right\" >
				<form action=\"contabilita.php\" method=\"post\" style=\"float: right;\">
						<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"update\" >
						<input id=\"ContrattoId\" name=\"ContrattoId\" type=\"hidden\" value=\"".$rsContratti['ContrattoId']."\" >
						<input id=\"ContrattoFatturato\" name=\"ContrattoFatturato\" type=\"hidden\" value=\"".$rsContratti['ContrattoFatturato']."\" >						
						<input id=\"ContrattoPagato\" name=\"ContrattoPagato\" type=\"hidden\" value=\"".$rsContratti['ContrattoPagato']."\" >
						<input name=\"Edita Contratto\" type=\"image\" src=\"image\edit.gif\" alt=\"Edita Contratto\" title=\"Edita Contratto\"> 
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
