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
// Form inserimento nuova tipologia
echo "<div class=\"box\" \">
            <a href=\"javascript:slideonlyone('newboxes1');\" >Aggiungi Tipologia</a>
         </div>
         <div class=\"newboxes2\" id=\"newboxes1\" style=\" display: none;\">
         <form action=\"admintipologia.php\" method=\"post\">
        <fieldset id=\"inputs\">
            <input id=\"TipologiaNome\" name=\"TipologiaNome\" type=\"text\" placeholder=\"Nome\" autofocus required>
            <input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"add\" >            
        </fieldset>
        <fieldset id=\"actions\">
            <input type=\"submit\" id=\"submit\" value=\"INSERISCI\">
        </fieldset>
    </form>
         </div>";
// FINE form inserimento         

$stato = $_POST['stato'];
// CONTROLLO GESTIONE MESSAGGI

switch($_GET['id']){
		case okadd:
			echo "<h2>Inserimento Nuova Tipologia Effettuato </h2>";
			break;
		
		case koadd:
			echo "<h2>Impossibile Inserire Tipologia - ERROR : ".$_GET['msg']."</h2>";
			break;
			
		case okupdate:
			echo "<h2>Aggiornamento Tipologia Effettuata </h2>";
			break;
		
		case koupdate:
			echo "<h2>Impossibile aggiornare Tipologia - ERROR : ".$_GET['msg']."</h2>";
			break;
			
		case nodel:
			echo "<h2>Impossibile Cancellare Tipologia associato a ".$_GET['num']." offerte!</h2>";
			break;
			
		case okdel:
			echo "<h2>Tipologia Cancellata Correttamente.</h2>";
			break;
		
		}
		
// FINE CONTROLLO GESTIONE MESSAGGI


if(isset($stato)){
	switch($stato){
		case add:
			$query = "INSERT INTO  Tipologie (
						`TipologiaId` ,
						`TipologiaNome` 
						)
						VALUES (
						'' ,  
						'".$_POST['TipologiaNome']."'
						);";
			if (!mysql_query($query))
			  {
					$msg = 'Error Inserimento Tipologia: ' . mysql_error();
					echo '<script language=javascript>document.location.href="admintipologia.php?id=koadd&msg='.$msg.'"</script>';
			  } 
			  else 
			  {
					echo '<script language=javascript>document.location.href="admintipologia.php?id=okadd"</script>';
			}
			break;
		
		case del:
			echo "Richiesta Cancellazione  Tipologie ".$_POST['TipologiaId']."";
			$query = "SELECT * FROM Offerte WHERE TipologiaId = ".$_POST['TipologiaId']."";

			$res = mysql_query($query);
			$numrows=mysql_num_rows($res);
			if ($numrows == 0) // NESSUNA TIPOLOGIA ASSOCIATA - PROCEDO AL DEL
				{
					$query2 = "DELETE FROM Tipologie WHERE TipologiaId = ".$_POST['TipologiaId']."";
					 if (!mysql_query($query2))
						  {
							  die('Error Cancellazione Tipologie: ' . mysql_error());
						  }
						echo '<script language=javascript>document.location.href="admintipologia.php?id=okdel"</script>';
				}
				else // TIPOLOGIA ASSOCIATA 
				{
						echo '<script language=javascript>document.location.href="admintipologia.php?id=nodel&num='.$numrows.'"</script>';
					}
			break;
			
		case edit:
			$query = "SELECT * FROM Tipologie WHERE TipologiaId = ".$_POST['TipologiaId']."";

			$res = mysql_query($query);
			echo "<h2>Tipologie Offerte</h2>";
						$rsTipologie = mysql_fetch_assoc($res);
						echo "
							<form action=\"admintipologia.php\" method=\"post\">
							<fieldset id=\"inputs\">
								<h2>Modifica Tipologia</h2>
								<input id=\"TipologiaNome\" name=\"TipologiaNome\" type=\"text\" placeholder=\"Nome\"  value=\"".$rsTipologie['TipologiaNome']."\" required>
								<input id=\"TipologiaId\" name=\"TipologiaId\" type=\"hidden\" value=\"".$rsTipologie['TipologiaId']."\" >
								<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"update\" >
							</fieldset>
							<fieldset id=\"actions\">
								<input type=\"submit\" id=\"submit\" value=\"Aggiorna\">
							</fieldset>
						</form>";
						
			break;
			
		case update: // INIZIO AGGIORNAMENTO
			$sql = "UPDATE  `Tipologie` SET  
					`TipologiaNome` =  '".$_POST['TipologiaNome']."'
						WHERE  `TipologiaId` = '".$_POST['TipologiaId']."'";
				
			if (!mysql_query($sql))
			  {
				
			  $msg = 'Error Aggiornamento Tipologia: ' . mysql_error();
			  echo '<script language=javascript>document.location.href="admintipologia.php?id=koupdate&msg='.$msg.'"</script>';
			  } 
			  else 
			  {
				echo '<script language=javascript>document.location.href="admintipologia.php?id=okupdate"</script>';
			}
			break;
			// FINE UPDATE AGENTE
				
		} // FINE SWITCH(stato)
	}
	else
	{
		// se viene chiamata direttamente alla pagina
		$query = "SELECT * FROM Tipologie";
		$res = mysql_query($query);
		echo "<h2>Tipologie Offerte</h2>
						<div class=\"tabella\" >
							<table>
								<tr>
								<td>Tipologia ID</td>
								<td>Nome</td>
								<td></td>
								</tr>";
						while($rsTipologie = mysql_fetch_assoc($res))
						{
							
							echo "<tr>
							<td>".$rsTipologie['TipologiaId']."</td>
							<td>".$rsTipologie['TipologiaNome']."</td>
							<td style=\"float:right\" >
							<form action=\"admintipologia.php\" method=\"post\" style=\"float: right;\">
									<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"edit\" >
									<input id=\"TipologiaId\" name=\"TipologiaId\" type=\"hidden\" value=\"".$rsTipologie['TipologiaId']."\" >
									<input name=\"Edita Contratto\" type=\"image\" src=\"image\edit.gif\" alt=\"Edita Tipologia\" title=\"Edita Tipologia\"> 
								</fieldset>
							</form>
							<form action=\"admintipologia.php\" method=\"post\" style=\"float: right;\">
									<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"del\" >
									<input id=\"TipologiaId\" name=\"TipologiaId\" type=\"hidden\" value=\"".$rsTipologie['TipologiaId']."\" >
									<input name=\"Cancella Contratto\" type=\"image\" src=\"image\delete.gif\" alt=\"Cancella Tipologia\" title=\"Cancella Tipologia\"> 
								</fieldset>
							</form>
							</td>
						</tr>";
						} // Fine WHILE
					
					echo "</table>
					</div>";
		} // Fine chiamata diretta pagina
?>
