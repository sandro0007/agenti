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

switch ($_POST['stato']) {
    case add:
        if ($_POST['ClienteTipologia'] == 'Privato') {
			
			$sqlprivato = "INSERT INTO `Clienti` 
						(`idCliente`, `ClienteNome`, `ClienteCognome`, 
							`ClienteRagione`, `ClienteCF`, `ClientePI`, `ClienteMail`, 
								`ClienteTelefono`, `ClienteFax`, `ClienteCellulare`,
								 `ClienteDataNascita`, `ClienteLuogoNascita`, `ClienteProvinciaNascita`,
								 `ClienteTipoDocumento`, `ClienteNumeroDocumento`, `ClienteEnteDocumento`, `ClienteRilascioDocumento`,
								`ClienteIndirizzo`, `ClienteNumero`, `ClienteCap`, `ClienteCitta`, 
									`ClienteTipologia` ,
										`Agenti_idAgenti`) 
										VALUES (NULL, '".$_POST['ClienteNome']."', '".$_POST['ClienteCognome']."', 
											'', '".$_POST['ClienteCF']."', '', '".$_POST['ClienteMail']."',
												'".$_POST['ClienteTelefono']."', '".$_POST['ClienteFax']."', '".$_POST['ClienteCellulare']."',
												'".$_POST['ClienteDataNascita']."', '".$_POST['ClienteLuogoNascita']."', '".$_POST['ClienteProvinciaNascita']."',
												'".$_POST['ClienteTipoDocumento']."', '".$_POST['ClienteNumeroDocumento']."', '".$_POST['ClienteEnteDocumento']."', '".$_POST['ClienteRilascioDocumento']."',
												'".$_POST['ClienteIndirizzo']."', '".$_POST['ClienteNumero']."', '".$_POST['ClienteCap']."', '".$_POST['ClienteCitta']."', 
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
								`ClienteTelefono`, `ClienteFax`, `ClienteCellulare`,
								 `ClienteDataNascita`, `ClienteLuogoNascita`, `ClienteProvinciaNascita`,
								 `ClienteTipoDocumento`, `ClienteNumeroDocumento`, `ClienteEnteDocumento`, `ClienteRilascioDocumento`,
								`ClienteIndirizzo`, `ClienteNumero`, `ClienteCap`, `ClienteCitta`, 
									`ClienteTipologia` ,
										`Agenti_idAgenti`) 
										VALUES (NULL, '".$_POST['ClienteNome']."', '".$_POST['ClienteCognome']."', 
											'".$_POST['ClienteRagione']."', '".$_POST['ClienteCF']."' , '".$_POST['ClientePI']."', '".$_POST['ClienteMail']."', 
											'".$_POST['ClienteTelefono']."', '".$_POST['ClienteFax']."', '".$_POST['ClienteCellulare']."',
												'".$_POST['ClienteDataNascita']."', '".$_POST['ClienteLuogoNascita']."', '".$_POST['ClienteProvinciaNascita']."',
												'".$_POST['ClienteTipoDocumento']."', '".$_POST['ClienteNumeroDocumento']."', '".$_POST['ClienteEnteDocumento']."', '".$_POST['ClienteRilascioDocumento']."',
												'".$_POST['ClienteIndirizzo']."', '".$_POST['ClienteNumero']."', '".$_POST['ClienteCap']."', '".$_POST['ClienteCitta']."', 
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
            <input id=\"ClienteCognome\" name=\"ClienteCognome\" type=\"text\" placeholder=\"Cognome\" autofocus required>
            <input id=\"ClienteNome\" name=\"ClienteNome\" type=\"text\" placeholder=\"Nome\"  required>
            <input id=\"ClienteCF\" name=\"ClienteCF\" type=\"text\" placeholder=\"Codice Fiscale\" required><br />
            <!-- <input id=\"ClienteSesso\" name=\"ClienteSesso\" type=\"text\" placeholder=\"Sesso\" required> -->
            <label>Sesso: </label>
            <select name=\"ClienteSesso\" id=\"ClienteSesso\" placeholder=\"Sesso\" required>
            <option>M</option>
            <option>F</option>
            </select>
            <input id=\"datepicker\" name=\"ClienteDataNascita\" type=\"text\" required>
            <input id=\"ClienteLuogoNascita\" name=\"ClienteLuogoNascita\" type=\"text\" placeholder=\"Luogo di Nascita\" required>
            <input id=\"ClienteProvinciaNascita\" name=\"ClienteProvinciaNascita\" type=\"text\" placeholder=\"Provincia di Nascita\" required><br />
            <h2>Documenti</h2>
            <label>Documento: </label>
            <select id=\"ClienteTipoDocumento\" name=\"ClienteTipoDocumento\" required>
				<option>Carta Identit&agrave</option>
				<option>Patente</option>
				<option>Passaporto</option>
            </select>
            <input id=\"ClienteNumeroDocumento\" name=\"ClienteNumeroDocumento\" type=\"text\" placeholder=\"Numero Documetno\" required>
            <input id=\"ClienteEnteDocumento\" name=\"ClienteEnteDocumento\" type=\"text\" placeholder=\"Ente Documento\" required>
            <input id=\"ClienteRilascioDocumento\" name=\"ClienteRilascioDocumento\" type=\"text\" placeholder=\"Data Rilascio Documento\" required>
            <h2>Recapiti</h2>
            <input id=\"ClienteTelefono\" name=\"ClienteTelefono\" type=\"text\" placeholder=\"Telefono\" >
            <input id=\"ClienteFax\" name=\"ClienteFax\" type=\"text\" placeholder=\"Fax\" >
            <input id=\"ClienteCellulare\" name=\"ClienteCellulare\" type=\"text\" placeholder=\"Cellulare\" >
            <input id=\"ClienteMail\" name=\"ClienteMail\" type=\"text\" placeholder=\"E-Mail\" required>
            <h2>Dati Fatturazione</h2>
            <input id=\"ClienteIndirizzo\" name=\"ClienteIndirizzo\" type=\"text\" placeholder=\"Indirizzo\" required>
            <input id=\"ClienteNumero\" name=\"ClienteNumero\" type=\"text\" placeholder=\"Numero Civico\" required>
            <input id=\"ClienteCap\" name=\"ClienteCap\" type=\"text\" placeholder=\"C.A.P.\" required>
            <input id=\"ClienteCitta\" name=\"ClienteCitta\" type=\"text\" placeholder=\"Citt&agrave\" required>
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
            <h2>Legale Rappresentate</h2>
            <input id=\"ClienteCognome\" name=\"ClienteCognome\" type=\"text\" placeholder=\"Cognome\" autofocus required>
            <input id=\"ClienteNome\" name=\"ClienteNome\" type=\"text\" placeholder=\"Nome\" required>
            <input id=\"ClienteCF\" name=\"ClienteCF\" type=\"text\" placeholder=\"Codice Fiscale\" required><br />
            <label>Sesso: </label>
            <select name=\"ClienteSesso\" id=\"ClienteSesso\" placeholder=\"Sesso\" required>
				<option>M</option>
				<option>F</option>
            </select><input id=\"ClienteDataNascita\" name=\"ClienteDataNascita\" type=\"text\" placeholder=\"Data di Nascita\" required>
            <input id=\"ClienteLuogoNascita\" name=\"ClienteLuogoNascita\" type=\"text\" placeholder=\"Luogo di Nascita\" required>
            <input id=\"ClienteProvinciaNascita\" name=\"ClienteProvinciaNascita\" type=\"text\" placeholder=\"Provincia di Nascita\" required><br />
            <h2>Documenti</h2>
            <label>Documento: </label>
            <select id=\"ClienteTipoDocumento\" name=\"ClienteTipoDocumento\" required>
				<option>Carta Identit&agrave</option>
				<option>Patente</option>
				<option>Passaporto</option>
            </select>
            <input id=\"ClienteNumeroDocumento\" name=\"ClienteNumeroDocumento\" type=\"text\" placeholder=\"Numero Documetno\" required>
            <input id=\"ClienteEnteDocumento\" name=\"ClienteEnteDocumento\" type=\"text\" placeholder=\"Ente Documento\" required>
            <input id=\"ClienteRilascioDocumento\" name=\"ClienteRilascioDocumento\" type=\"text\" placeholder=\"Data Rilascio Documento\" required>
            <h2>Recapiti</h2>
            <input id=\"ClienteTelefono\" name=\"ClienteTelefono\" type=\"text\" placeholder=\"Telefono\" >
            <input id=\"ClienteFax\" name=\"ClienteFax\" type=\"text\" placeholder=\"Fax\" >
            <input id=\"ClienteCellulare\" name=\"ClienteCellulare\" type=\"text\" placeholder=\"Cellulare\" >
            <input id=\"ClienteMail\" name=\"ClienteMail\" type=\"text\" placeholder=\"E-Mail\" required>
            <h2>Dati Fatturazione</h2>
            <input id=\"ClienteIndirizzo\" name=\"ClienteIndirizzo\" type=\"text\" placeholder=\"Indirizzo\" required>
            <input id=\"ClienteNumero\" name=\"ClienteNumero\" type=\"text\" placeholder=\"Numero Civico\" required>
            <input id=\"ClienteCap\" name=\"ClienteCap\" type=\"text\" placeholder=\"C.A.P.\" required>
            <input id=\"ClienteCitta\" name=\"ClienteCitta\" type=\"text\" placeholder=\"Citt&agrave\" required>
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
