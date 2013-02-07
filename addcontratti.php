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
$cod = $_SESSION['cod']; //id cod recuperato nel file di verifica
echo $menu;



if(isset($_POST['step'])){
	switch ($_POST['step']) {
		case 1:
			echo "<h3>Tipologia Contratto</h3>";
			echo "<p>Seleziona la tipologia di contratto che si desidera attivare;</p>";
			$sql = "SELECT * FROM Tipologie";
			$res = mysql_query($sql);
			
			echo"<form action=\"addcontratti.php\" method=\"post\">
			<fieldset>
				<label>Tipologia</label>
				<select>
				<option>...</option>
				";
				while($rsTipologie = mysql_fetch_assoc($res)){
					echo "<option value=\"".$rsTipologie['TipologiaId']."\">".$rsTipologie['TipologiaNome']."</option>";
					}
			echo"</select>
			<input id=\"IdCliente\" name=\"IdCliente\" type=\"hidden\" value=\"".$_POST['idCliente']."\" >
			<input id=\"ClienteTipologia\" name=\"ClienteTipologia\" type=\"hidden\" value=\"".$_POST['ClienteTipologia']."\" >
			<input id=\"step\" name=\"step\" type=\"hidden\" value=\"2\" >
			</fieldset>
			<fieldset id=\"actions\">
            <input type=\"submit\" id=\"submit\" value=\"avanti\">
            </fieldset>
			</form>";
			break;
		case 2:
			echo "<h3>Offerta</h3>";
			echo "<p>Seleziona l'offerta da associare al contratto;</p>";
			if ($_POST['ClienteTipologia'] == 'Privato'){
				echo "Profilo Privato";
				}
			if ($_POST['ClienteTipologia'] == 'Azienda'){
				echo "Profilo Azienda";
				}
			break;
	}
}	else {
		// se viene chiamata direttamente la pagina
		echo '<script language=javascript>document.location.href="clienti.php"</script>';
}             

    


?>
