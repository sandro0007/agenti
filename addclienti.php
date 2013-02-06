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

switch ($_POST['stato']) {
    case add:
			$indirizzo2 = $_POST['ClienteIndirizzo2'];
			$numero2 = $_POST['ClienteNumero2'];
			$cap2 = $_POST['ClienteCap2'];
			$citta2 = $_POST['ClienteCitta2'];
			$indirizzo3 = $_POST['ClienteIndirizzo3'];
			$numero3 = $_POST['ClienteNumero3'];
			$cap3 = $_POST['ClienteCap3'];
			$citta3 = $_POST['ClienteCitta3'];
			
        if ($_POST['ClienteTipologia'] == 'Privato') {
			
			$sqlprivato = "INSERT INTO `Clienti` 
						(`idCliente`, `ClienteNome`, `ClienteCognome`, 
							`ClienteRagione`, `ClienteCF`, `ClientePI`, `ClienteMail`, 
								`ClienteIndirizzo1`, `ClienteNumero1`, `ClienteCap1`, `ClienteCitta1`, 
									`ClienteIndirizzo2`, `ClienteNumero2`, `ClienteCap2`, `ClienteCitta2`, 
										`ClienteIndirizzo3`, `ClienteNumero3`, `ClienteCap3`, `ClienteCitta3`,
											`ClienteTipologia` ,
											`Agenti_idAgenti`) 
										VALUES (NULL, '".$_POST['ClienteNome']."', '".$_POST['ClienteCognome']."', 
											'', '".$_POST['ClienteCF']."', '', '".$_POST['ClienteMail']."', 
												'".$_POST['ClienteIndirizzo1']."', '".$_POST['ClienteNumero1']."', '".$_POST['ClienteCap1']."', '".$_POST['ClienteCitta1']."', 
													'".$indirizzo2."', '".$numero2."', '".$cap2."', '".$citta2."', 
														'".$indirizzo3."', '".$numero3."', '".$cap3."', '".$citta3."', 
															'".$_POST['ClienteTipologia']."',
															'".$cod."');";
			//print_r($sqlprivato);
			if (!mysql_query($sqlprivato))
			  {
			  die('Error Inserimento Cliente: ' . mysql_error());
			  }
			echo "Utente ".$_POST['ClienteCognome']."  ".$_POST['ClienteNome']." - Inserito Correttamente";
			}
		if ($_POST['ClienteTipologia'] == 'Azienda') {
				$sqlazienda = "INSERT INTO `Clienti` 
						(`idCliente`, `ClienteNome`, `ClienteCognome`, 
							`ClienteRagione`, `ClienteCF`, `ClientePI`, `ClienteMail`, 
								`ClienteIndirizzo1`, `ClienteNumero1`, `ClienteCap1`, `ClienteCitta1`, 
									`ClienteIndirizzo2`, `ClienteNumero2`, `ClienteCap2`, `ClienteCitta2`, 
										`ClienteIndirizzo3`, `ClienteNumero3`, `ClienteCap3`, `ClienteCitta3`,
											`ClienteTipologia` ,
											`Agenti_idAgenti`) 
										VALUES (NULL, '', '', 
											'".$_POST['ClienteRagione']."', '' , '".$_POST['ClientePI']."', '".$_POST['ClienteMail']."', 
												'".$_POST['ClienteIndirizzo1']."', '".$_POST['ClienteNumero1']."', '".$_POST['ClienteCap1']."', '".$_POST['ClienteCitta1']."', 
													'".$indirizzo2."', '".$numero2."', '".$cap2."', '".$citta2."', 
														'".$indirizzo3."', '".$numero3."', '".$cap3."', '".$citta3."', 
															'".$_POST['ClienteTipologia']."',
															'".$cod."');";
			//print_r($sqlazienda);
			if (!mysql_query($sqlazienda))
			  {
			  die('Error Inserimento Cliente: ' . mysql_error());
			  }
			echo "Azienda ".$_POST['ClienteRagione']." - Inserita Correttamente";
			}
        break;
    default:
		echo "<h2>Aggiungi Nuovo Cliente</h2>
		     <div class=\"box\" \">
            <a href=\"javascript:slideonlyone('newboxes1');\" >PRIVATO</a>
         </div>
         <div class=\"newboxes2\" id=\"newboxes1\" style=\" display: block;\">
         <form action=\"addclienti.php\" method=\"post\">
        <fieldset id=\"inputs\">
            <input id=\"ClienteNome\" name=\"ClienteNome\" type=\"text\" placeholder=\"Nome\" autofocus required>
            <input id=\"ClienteCognome\" name=\"ClienteCognome\" type=\"text\" placeholder=\"Cognome\" required>
            <input id=\"ClienteCF\" name=\"ClienteCF\" type=\"text\" placeholder=\"Codice Fiscale\" required><br />
            <h2>Recapito E-Mail</h2>
            <input id=\"ClienteMail\" name=\"ClienteMail\" type=\"text\" placeholder=\"E-Mail\" required>
            <h2>Dati Fatturazione</h2>
            <input id=\"ClienteIndirizzo1\" name=\"ClienteIndirizzo1\" type=\"text\" placeholder=\"Indirizzo\" required>
            <input id=\"ClienteNumero1\" name=\"ClienteNumero1\" type=\"text\" placeholder=\"Numero Civico\" required>
            <input id=\"ClienteCap1\" name=\"ClienteCap1\" type=\"text\" placeholder=\"C.A.P.\" required>
            <input id=\"ClienteCitta1\" name=\"ClienteCitta1\" type=\"text\" placeholder=\"Citt&agrave\" required>
            <h2>Dati Installazione</h2>
            <input id=\"ClienteIndirizzo2\" name=\"ClienteIndirizzo2\" type=\"text\" placeholder=\"Indirizzo\" >
            <input id=\"ClienteNumero2\" name=\"ClienteNumero2\" type=\"text\" placeholder=\"Numero Civico\" >
            <input id=\"ClienteCap2\" name=\"ClienteCap2\" type=\"text\" placeholder=\"C.A.P.\">
            <input id=\"ClienteCitta2\" name=\"ClienteCitta2\" type=\"text\" placeholder=\"Citt&agrave\" >
            <h2>Dati Spedizione</h2>
            <input id=\"ClienteIndirizzo3\" name=\"ClienteIndirizzo3\" type=\"text\" placeholder=\"Indirizzo\" >
            <input id=\"ClienteNumero3\" name=\"ClienteNumero3\" type=\"text\" placeholder=\"Numero Civico\" >
            <input id=\"ClienteCap3\" name=\"ClienteCap3\" type=\"text\" placeholder=\"C.A.P.\">
            <input id=\"ClienteCitta3\" name=\"ClienteCitta3\" type=\"text\" placeholder=\"Citt&agrave\">
            <input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"add\" >
            <input id=\"ClienteTipologia\" name=\"ClienteTipologia\" type=\"hidden\" value=\"Privato\" >
            <input id=\"Agenti_idAgenti\" name=\"Agenti_idAgenti\" type=\"hidden\" value=\"".$cod."\" >
        </fieldset>
        <fieldset id=\"actions\">
            <input type=\"submit\" id=\"submit\" value=\"INSERISCI\">
        </fieldset>
    </form>
         </div>
         <div class=\"box\" \">
            <a href=\"javascript:slideonlyone('newboxes2');\" >AZIENDA</a>
         </div>
         <div class=\"newboxes2\" id=\"newboxes2\" style=\"display: none; \">
         <form action=\"addclienti.php\" method=\"post\">
        <fieldset id=\"inputs\">
            <input id=\"ClienteRagione\" name=\"ClienteRagione\" type=\"text\" placeholder=\"Ragione Sociale\" autofocus required>
            <input id=\"ClientePI\" name=\"ClientePI\" type=\"text\" placeholder=\"Partita Iva\" required><br />
            <h2>Recapito E-Mail</h2>
            <input id=\"ClienteMail\" name=\"ClienteMail\" type=\"text\" placeholder=\"E-Mail\" required>
            <h2>Dati Fatturazione</h2>
            <input id=\"ClienteIndirizzo1\" name=\"ClienteIndirizzo1\" type=\"text\" placeholder=\"Indirizzo\" required>
            <input id=\"ClienteNumero1\" name=\"ClienteNumero1\" type=\"text\" placeholder=\"Numero Civico\" required>
            <input id=\"ClienteCap1\" name=\"ClienteCap1\" type=\"text\" placeholder=\"C.A.P.\" required>
            <input id=\"ClienteCitta1\" name=\"ClienteCitta1\" type=\"text\" placeholder=\"Citt&agrave\" required>
            <h2>Dati Installazione</h2>
            <input id=\"ClienteIndirizzo2\" name=\"ClienteIndirizzo2\" type=\"text\" placeholder=\"Indirizzo\" >
            <input id=\"ClienteNumero2\" name=\"ClienteNumero2\" type=\"text\" placeholder=\"Numero Civico\" >
            <input id=\"ClienteCap2\" name=\"ClienteCap2\" type=\"text\" placeholder=\"C.A.P.\">
            <input id=\"ClienteCitta2\" name=\"ClienteCitta2\" type=\"text\" placeholder=\"Citt&agrave\" >
            <h2>Dati Spedizione</h2>
            <input id=\"ClienteIndirizzo3\" name=\"ClienteIndirizzo3\" type=\"text\" placeholder=\"Indirizzo\" >
            <input id=\"ClienteNumero3\" name=\"ClienteNumero3\" type=\"text\" placeholder=\"Numero Civico\" >
            <input id=\"ClienteCap3\" name=\"ClienteCap3\" type=\"text\" placeholder=\"C.A.P.\">
            <input id=\"ClienteCitta3\" name=\"ClienteCitta3\" type=\"text\" placeholder=\"Citt&agrave\">
            <input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"add\" >
            <input id=\"ClienteTipologia\" name=\"ClienteTipologia\" type=\"hidden\" value=\"Azienda\" >
            <input id=\"Agenti_idAgenti\" name=\"Agenti_idAgenti\" type=\"hidden\" value=\"".$cod."\" >
        </fieldset>
        <fieldset id=\"actions\">
            <input type=\"submit\" id=\"submit\" value=\"INSERISCI\">
        </fieldset>
    </form>
         </div>
    ";
}                      

    


?>
