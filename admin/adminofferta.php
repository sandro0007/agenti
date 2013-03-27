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
echo "<div class=\"box\" \">
            <a href=\"javascript:slideonlyone('newboxes1');\" >Aggiungi Offerta</a>
         </div>
         <div class=\"newboxes2\" id=\"newboxes1\" style=\" display: none;\">
         <form action=\"adminofferta.php\" method=\"post\">
        <fieldset id=\"inputs\">
            <input id=\"OffertaNome\" name=\"OffertaNome\" type=\"text\" placeholder=\"Nome\" autofocus required>
            <input id=\"OffertaCanone\" name=\"OffertaCanone\" type=\"text\" placeholder=\"Canone\" autofocus required><br />
            <label>Pagamento: </label>
            <select name=\"OffertaPagamento\" id=\"OffertaPagamento\" placeholder=\"Pagamento\" required>
				<option>Mensile</option>
				<option>Bimestrale</option>
				<option>Trimestrale</option>
				<option>Semestrale</option>
				<option>Annuale</option>
            </select><br />
            <label>Destinazione: </label>
            <select name=\"OffertaDestinazione\" id=\"OffertaDestinazione\" placeholder=\"Destinazione\" required>
				<option>Privato</option>
				<option>Azienda</option>
            </select><br />
            <label>Tipologia Offerta</label>
            <select id=\"TipologiaId\" name=\"TipologiaId\" required>";
            $query3 = "SELECT * FROM Tipologie ";
							$res3 = mysql_query($query3);
							while ($rsTipologie = mysql_fetch_assoc($res3))
							{
								echo "<option value=\"".$rsTipologie['TipologiaId']."\">".$rsTipologie['TipologiaNome']."</option>";
							}
            echo "</select>
            <br />
           	<input id=\"OffertaPunti\" name=\"OffertaPunti\" type=\"text\" placeholder=\"Punti\"  required><br />
           	<textarea id=\"OffertaDescrizione\" name=\"OffertaDescrizione\" type=\"text\" placeholder=\"Descrizione\" required></textarea><br />
           	<label>Template Personalizzato:</label><br />
           	<input type=\"radio\" name=\"OffertaPersonalizzata\" value=\"0\">NO
           	<input type=\"radio\" name=\"OffertaPersonalizzata\" value=\"1\">SI<br />
            <input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"add\" >            
        </fieldset>
        <fieldset id=\"actions\">
            <input type=\"submit\" id=\"submit\" value=\"INSERISCI\">
        </fieldset>
    </form>
         </div>";
$stato = $_POST['stato'];
// CONTROLLO GESTIONE MESSAGGI

switch($_GET['id']){
	
		case okadd:
			echo "<div class=\"success\">Inserimento Nuova Offerta Effettuato </div>";
			break;
		
		case koadd:
			echo "<div class=\"error\">Impossibile Inserire Offerta - ERROR : ".$_GET['msg']."</div>";
			break;
			
		case okupdate:
			echo "<div class=\"success\">Aggiornamento Offerta Effettuato </div>";
			break;
		
		case koupdate:
			echo "<div class=\"error\">Impossibile aggiornare Offerta - ERROR : ".$_GET['msg']."</div>";
			break;
			
		case nodel:
			echo "<div class=\"error\">Impossibile Cancellare Offerta associato a ".$_GET['num']." Contratti!</div>";
			break;
			
		case okdel:
			echo "<div class=\"success\">Offerta Cancellata Correttamente: ".$_POST['msg']."</h2>";
			break;
		
		}
		
// FINE CONTROLLO GESTIONE MESSAGGI


if(isset($stato)){
	switch($stato){
		case add:
			$query = "INSERT INTO  Offerte (
						`OffertaNome` ,
						`OffertaCanone` ,
						`OffertaPagamento` ,
						`OffertaDescrizione` ,
						`OffertaDestinazione` ,
						`OffertaPunti` ,
						`OffertaPersonalizzata`,
						`TipologiaId`
						)
						VALUES (  
						'".$_POST['OffertaNome']."',  
						'".$_POST['OffertaCanone']."',  
						'".$_POST['OffertaPagamento']."',  
						'".$_POST['OffertaDescrizione']."',  
						'".$_POST['OffertaDestinazione']."',
						'".$_POST['OffertaPunti']."',
						'".$_POST['OffertaPersonalizzata']."',
						'".$_POST['TipologiaId']."'
						);";
			if (!mysql_query($query))
			  {
					$msg = 'Error Inserimento Offerta: ' . mysql_error();
					echo '<script language=javascript>document.location.href="adminofferta.php?id=koadd&msg='.$msg.'"</script>';
			  } 
			  else 
			  {
					echo '<script language=javascript>document.location.href="adminofferta.php?id=okadd"</script>';
			}
			break;
			
		case del:
			echo "Richiesta Cancellazione  Offerta ".$_POST['OffertaId']."";
			$query = "SELECT * FROM Contratti_Offerte WHERE OffertaId = ".$_POST['OffertaId']."";

			$res = mysql_query($query);
			$numrows=mysql_num_rows($res);
			if ($numrows == 0) // NESSUNA Offerta ASSOCIATA - PROCEDO AL DEL
				{
					$query2 = "DELETE FROM Offerte WHERE OffertaId = ".$_POST['OffertaId']."";
					 if (!mysql_query($query2))
						  {
						$msg = 'Error Cancellazione Offerta: ' . mysql_error();
						 echo '<script language=javascript>document.location.href="adminofferta.php?id=nodel&msg='.$msg.'"</script>';
					}
					else { // ok cancellazione
						$msg = 'succefull remove offerta id'.$_POST['OffertaId'];
						 echo '<script language=javascript>document.location.href="adminofferta.php?id=okdel&msg='.$msg.'"</script>';
						}
					
				}
				else // Offerta ASSOCIATA 
				{
						echo '<script language=javascript>document.location.href="adminofferta.php?id=nodel&num='.$numrows.'"</script>';
					}
			break;
			
		case edit:
			$query = "SELECT * FROM Offerte WHERE OffertaId = ".$_POST['OffertaId']."";

			$res = mysql_query($query);
			echo "<h2>Modifica Offerta</h2>";
						$rsOfferta = mysql_fetch_assoc($res);
						echo "
							<form action=\"adminofferta.php\" method=\"post\">
							<fieldset id=\"inputs\">
							<label>Nome Offerta</label>
								<input id=\"OffertaNome\" name=\"OffertaNome\" type=\"text\" placeholder=\"Nome\"  value=\"".$rsOfferta['OffertaNome']."\" required><br />
							<label>Canone Mensile</label>
								<input id=\"OffertaCanone\" name=\"OffertaCanone\" type=\"text\" placeholder=\"Canone\"  value=\"".$rsOfferta['OffertaCanone']."\" required><br />
								
							<label>Pagamento: </label>
								<select name=\"OffertaPagamento\" id=\"OffertaPagamento\" placeholder=\"Pagamento\" required>";
								echo "<option value=\"".$rsOfferta['OffertaPagamento']."\">".$rsOfferta['OffertaPagamento']."</option>";
										$scelta = array("Mensile", "Bimestrale", "Trimestrale", "Semestrale", "Annuale");
										reset($scelta);
										foreach ($scelta as $key => $value) {
											if ( $value != $rsOfferta['OffertaPagamento'])
												{
													echo "<option value=\"".$value."\">".$value."</option>";
												}
											
										}
								echo"	</select> <br />
								
							<label>Destinazione: </label>
								<select name=\"OffertaDestinazione\" id=\"OffertaDestinazione\" placeholder=\"Destinazione\" required>";
									echo "<option value=\"".$rsOfferta['OffertaDestinazione']."\">".$rsOfferta['OffertaDestinazione']."</option>";
										$scelta = array("Privato", "Azienda");
										reset($scelta);
										foreach ($scelta as $key => $value) {
											if ( $value != $rsOfferta['OffertaDestinazione'])
												{
													echo "<option value=\"".$value."\">".$value."</option>";
												}
											
										}
								echo"	</select> <br />";
								$query2 = "SELECT * FROM Tipologie";
								$res2 = mysql_query($query2);
								echo "<label>Tipologia</label>
								<select id=\"TipologiaId\" name=\"TipologiaId\">";
								while($rsTipologie = mysql_fetch_assoc($res2))
								{
									if ($rsOfferta['TipologiaId'] == $rsTipologie['TipologiaId']){
										echo "	<option value=\"".$rsTipologie['TipologiaId']."\" selected>".$rsTipologie['TipologiaNome']."</option>";
										}
									else {
										echo "	<option value=\"".$rsTipologie['TipologiaId']."\">".$rsTipologie['TipologiaNome']."</option>";
									}
								}
						echo "
						</select><br />
						<label>Punti: </label>
							<input id=\"OffertaPunti\" name=\"OffertaPunti\" type=\"text\" placeholder=\"Punti\"  value=\"".$rsOfferta['OffertaPunti']."\" required><br />
						<label>Descrizione</label>
						<textarea id=\"OffertaDescrizione\" name=\"OffertaDescrizione\" type=\"text\" placeholder=\"Descrizione\" required>".$rsOfferta['OffertaDescrizione']."</textarea><br />
						<label>Template Offerta Personalizzato: </label><br />";
						if ($rsOfferta['OffertaPersonalizzata'] == '0') 
						{
							echo "<input type=\"radio\" name=\"OffertaPersonalizzata\" value=\"0\" checked=\"enable\">No<br />
								 <input type=\"radio\" name=\"OffertaPersonalizzata\" value=\"1\">SI<br />";
							}
							else 
							{
								echo "<input type=\"radio\" name=\"OffertaPersonalizzata\" value=\"1\" checked=\"enable\">SI<br />
								 <input type=\"radio\" name=\"OffertaPersonalizzata\" value=\"0\">No<br />";
								}
						echo "<input id=\"OffertaId\" name=\"OffertaId\" type=\"hidden\" value=\"".$rsOfferta['OffertaId']."\" >
								<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"update\" >
							</fieldset>
							<fieldset id=\"actions\">
								<input type=\"submit\" id=\"submit\" value=\"Aggiorna\">
							</fieldset>
						</form>";
			break;
			
		case update: // INIZIO AGGIORNAMENTO
			$sql = "UPDATE  `Offerte` SET  
					`OffertaNome` =  '".$_POST['OffertaNome']."',
					`OffertaCanone` =  '".$_POST['OffertaCanone']."',
					`OffertaPagamento` =  '".$_POST['OffertaPagamento']."',
					`OffertaDescrizione` =  '".$_POST['OffertaDescrizione']."',
					`OffertaDestinazione` =  '".$_POST['OffertaDestinazione']."',
					`OffertaPunti` =  '".$_POST['OffertaPunti']."',
					`TipologiaId` =  '".$_POST['TipologiaId']."',
					`OffertaPersonalizzata` =  '".$_POST['OffertaPersonalizzata']."'
						WHERE  `OffertaId` = '".$_POST['OffertaId']."'";
				
			if (!mysql_query($sql))
			  {
				
			  $msg = 'Error Aggiornamento Offerta: ' . mysql_error();
			  echo '<script language=javascript>document.location.href="adminofferta.php?id=koupdate&msg='.$msg.'"</script>';
			  } 
			  else 
			  {
				echo '<script language=javascript>document.location.href="adminofferta.php?id=okupdate"</script>';
			}
			break;
			// FINE UPDATE AGENTE
				
		} // FINE SWITCH(stato)
	}
	else
	{
		// se viene chiamata direttamente alla pagina
		$query = "SELECT * FROM Offerte";
		$res = mysql_query($query);
		echo "<h2>Profili Offerte</h2>
						<div class=\"tabella\" >
							<table>
								<tr>
								<td>Id Offerta</td>
								<td>Nome</td>
								<td>Canone Mensile</td>
								<td>Pagamento</td>
								<td>Descrizione</td>
								<td>Destinazione</td>
								<td>Punti</td>
								<td>Tipologia</td>
								<td>Template Personalizzato</td>
								<td></td>
								</tr>";
						while($rsOfferta = mysql_fetch_assoc($res))
						{
							
							echo "<tr>
							<td>".$rsOfferta['OffertaId']."</td>
							<td>".$rsOfferta['OffertaNome']."</td>
							<td>".$rsOfferta['OffertaCanone']."</td>
							<td>".$rsOfferta['OffertaPagamento']."</td>
							<td>".$rsOfferta['OffertaDescrizione']."</td>
							<td>".$rsOfferta['OffertaDestinazione']."</td>
							<td>".$rsOfferta['OffertaPunti']."</td>";
							$query2 = "SELECT * FROM Tipologie WHERE TipologiaId = ".$rsOfferta['TipologiaId']."";
							$res2 = mysql_query($query2);
							$rsTipologie = mysql_fetch_assoc($res2);
							echo "<td>".$rsTipologie['TipologiaNome']."</td>";
							
							if ($rsOfferta['OffertaPersonalizzata'] == '0') // Template standard
								{
									echo "<td>NO</td>";
									}
									else //template personalizzato
									{
										echo "<td>SI</td>";
										}
							
							echo "<td style=\"float:right\" >
							<form action=\"adminofferta.php\" method=\"post\" style=\"float: right;\">
									<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"edit\" >
									<input id=\"OffertaId\" name=\"OffertaId\" type=\"hidden\" value=\"".$rsOfferta['OffertaId']."\" >
									<input name=\"Edita Offerta\" type=\"image\" src=\"image\edit.gif\" alt=\"Edita Offerta\" title=\"Edita Offerta\"> 
								</fieldset>
							</form>
							<form action=\"adminofferta.php\" method=\"post\" style=\"float: right;\">
									<input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"del\" >
									<input id=\"OffertaId\" name=\"OffertaId\" type=\"hidden\" value=\"".$rsOfferta['OffertaId']."\" >
									<input name=\"Cancella Offerta\" type=\"image\" src=\"image\delete.gif\" alt=\"Cancella Offerta\" title=\"Cancella Offerta\"> 
								</fieldset>
							</form>
							</td>
						</tr>";
						} // Fine WHILE
					
					echo "</table>
					</div>";
		} // Fine chiamata diretta pagina
?>
