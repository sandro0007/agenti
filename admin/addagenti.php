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

switch ($_POST['stato']) {
    case add:
			$sql = "INSERT INTO `Agenti` 
						(`idAgenti`, `AgenteNome`, `AgenteCognome`, 
							`AgenteTelefono`, `AgenteFax`, `AgenteCellulare`, `AgenteMail`,
								`AgenteIndirizzo`, `AgenteNumero`, `AgenteCap`, `AgenteCitta`, 
									`AgenteUser`, `AgentePass`, `AgenteAbilitato`) 
										VALUES (NULL, '".$_POST['AgenteNome']."', '".$_POST['AgenteCognome']."',
											'".$_POST['AgenteTelefono']."', '".$_POST['AgenteFax']."', '".$_POST['AgenteCellulare']."', '".$_POST['AgenteMail']."',
												'".$_POST['AgenteIndirizzo']."', '".$_POST['AgenteNumero']."', '".$_POST['AgenteCap']."', '".$_POST['AgenteCitta']."', 
													'".$_POST['AgenteUser']."', '".$_POST['AgentePass']."', '".$_POST['AgenteAbilitato']."');";
			if (!mysql_query($sql))
			  {
			  die('Error Inserimento Agente: ' . mysql_error());
			  }
			echo "Agente ".$_POST['AgenteCognome']."  ".$_POST['AgenteNome']." - Inserito Correttamente";
        break;
    default:
		echo "<h2>Aggiungi Nuovo Agente</h2>
         <form action=\"addagenti.php\" method=\"post\">
        <fieldset id=\"inputs\">
            <input id=\"AgenteCognome\" name=\"AgenteCognome\" type=\"text\" placeholder=\"Cognome\" autofocus required>
            <input id=\"AgenteNome\" name=\"AgenteNome\" type=\"text\" placeholder=\"Nome\"  required>
            <h2>Recapiti</h2>
            <input id=\"AgenteTelefono\" name=\"AgenteTelefono\" type=\"text\" placeholder=\"Telefono\" >
            <input id=\"AgenteFax\" name=\"AgenteFax\" type=\"text\" placeholder=\"Fax\" >
            <input id=\"AgenteCellulare\" name=\"AgenteCellulare\" type=\"text\" placeholder=\"Cellulare\" >
            <input id=\"AgenteMail\" name=\"AgenteMail\" type=\"text\" placeholder=\"E-Mail\" required>
            <h2>Dati Fatturazione</h2>
            <input id=\"AgenteIndirizzo\" name=\"AgenteIndirizzo\" type=\"text\" placeholder=\"Indirizzo\" >
            <input id=\"AgenteNumero\" name=\"AgenteNumero\" type=\"text\" placeholder=\"Numero Civico\" >
            <input id=\"AgenteCap\" name=\"AgenteCap\" type=\"text\" placeholder=\"C.A.P.\" >
            <input id=\"AgenteCitta\" name=\"AgenteCitta\" type=\"text\" placeholder=\"Citt&agrave\" >
            <h2>Dati Accesso Portale</h2>
            <input id=\"AgenteUser\" name=\"AgenteUser\" type=\"text\" placeholder=\"Username\" ><br />
            <input id=\"AgentePass\" name=\"AgentePass\" type=\"text\" placeholder=\"Password\" ><br />
            <label style=\"float: left;\">Abilitato Accesso</label><input type=\"checkbox\" name=\"AgenteAbilitato\" value=\"1\" style=\"float: left;\">
            <input id=\"stato\" name=\"stato\" type=\"hidden\" value=\"add\" >
        </fieldset>
        <fieldset id=\"actions\">
            <input type=\"submit\" id=\"submit\" value=\"INSERISCI\">
        </fieldset>
    </form>";
}                      

    


?>
