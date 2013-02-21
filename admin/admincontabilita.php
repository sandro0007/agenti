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

if(isset($_POST['stato'])){ // CONTROLLO VARIABILE STATO
	switch($_POST['stato']){ // INIZIO SWITCH
		case update:
				if ( $_POST['ContrattoFatturato'] == '0' ) {
					  $msg = 'Fattura non emessa, impossible emettere Pagamento';
					  echo '<script language=javascript>document.location.href="admincontabilita.php?id=koupdate&msg='.$msg.'"</script>';
					}
				if ( $_POST['ContrattoFatturato'] == '1' and $_POST['ContrattoPagato'] == '0') {
						$setPagamento = '1';
						$sql = "UPDATE  `Contratti` SET  
						`ContrattoPagato` =  '".$setPagamento."'
							WHERE  `ContrattoId` =".$_POST['ContrattoId']."";
					if (!mysql_query($sql))
					  {
					 $msg = 'Error Aggiornamento Contabilit&agrave: ' . mysql_error();
					  echo '<script language=javascript>document.location.href="admincontabilita.php?id=koupdate&msg='.$msg.'"</script>';
					  }
				echo '<script language=javascript>document.location.href="admincontabilita.php?id=okupdate"</script>';
				}
				
				if ($_POST['ContrattoFatturato'] == '1' and $_POST['ContrattoPagato'] == '1') {
						$setPagamento = '0';
						$sql = "UPDATE  `Contratti` SET  
						`ContrattoPagato` =  '".$setPagamento."'
							WHERE  `ContrattoId` =".$_POST['ContrattoId']."";
							if (!mysql_query($sql))
							  {
							  $msg = 'Error Aggiornamento Contabilit&agrave: ' . mysql_error();
							  echo '<script language=javascript>document.location.href="admincontabilita.php?id=koupdate&msg='.$msg.'"</script>';
							  }
						echo '<script language=javascript>document.location.href="admincontabilita.php?id=okupdate"</script>';
						}
				else {
					echo '<script language=javascript>document.location.href="admincontabilita.php?id=koupdate"</script>';
				}
				break;
	}	// FINE SWITCH
} // FINE CONTROLLO VARIABILE STATO

switch($_GET['id']){
		case okupdate:
			echo "<h2>Aggiornamento Effettuato Correttamente</h2>";
			break;
		
		case koupdate:
			echo "<h2>Aggiornamento Non Effettuato</h2><br />".$_POST['msg'];
			break;
		
		}
	
echo "<h2>Pagina Contabilit&agrave</h2>";

		
	$sql = "SELECT * FROM Contratti WHERE
					ContrattoStato like '%Attivato%' 
						order by ContrattoFatturato";
	
		
	//print_r($sql);
	$res = mysql_query($sql);
	$numrows=mysql_num_rows($res);
	
	if ($numrows == 0) {
			echo "<h2>Spiacente Nessuna Contratto Ancora Attivato</h2>";
		}
		else {
			echo "<h3>Lista Contratti Attivati </h3>";
	echo "
			<div class=\"tabella\" >
				<table>
					<tr>
					<td>Contratto Numero</td>
					<td>Nome Contratto</td>
					<td>Tipologia</td>
					<td>Stato</td>
					<td>Fatturato</td>
					<td>Fattura Saldata</td>
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
				if ($rsContratti['ContrattoPagato'] == '1'){
					echo "<td style=\" background-color:#90EE90\">Fattura Saldata</td>";
					} 
					else {
						echo "<td style=\" background-color:#FF0000\" >Fattura Da Saldare</td>";
						}
		echo "	<td style=\"float:right\" >
				<form action=\"admincontabilita.php\" method=\"post\" style=\"float: right;\">
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
		} // FINE CICLO WHILE
		echo "</table>
		</div>";
	}
	
	
?>
