<?php
	$dbHost="127.0.0.1";
	$dbName="cmr";
	$dbUser="cmr";
	$dbPassword="cmr";
	   // Variabili SMTP
	$smtphost = "smtp.linkspace.it";
	$smtpuser = "linkspace@linkspace.it";
	$smtppass = "linkspace";
	$email = "amministrazione@linkspace.it";
	
	// Paginazione
	
	// Contratti per pagina
	$ContrattiPagina = '20';
	// Agenti per pagina
	$AgentiPagina = '20';
	// Clienti per Pagina
	$ClientiPagina = '20';
	// Contabilità per pagina
	$ContabilitàPagina = '20';
	
	
	//menu principale
	$menu = "<nav class=\"horizontal-nav full-width\">
                            <ul>
								<li><a href=\"main.php\">Home</a></li>
                                <li><a href=\"adminagenti.php\">Agenti</a></li>
                                <li><a href=\"addagenti.php\">Aggiungi Agenti</a></li>
                                <li><a href=\"adminclienti.php\">Clienti</a></li>
                                <li><a href=\"admincontratti.php\">Contratti</a></li>
                                <li><a href=\"admincontabilita.php\">Contabilit&agrave;</a></li>
                                <li><a href=\"admintipologia.php\">Tipologia Offerte</a></li>
                                <li><a href=\"adminofferta.php\">Offerte</a></li>
                                <li><a href=\"logout.php\">Logout</a></li>
                            </ul>
                        </nav>";
    
?>
