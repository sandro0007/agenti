<?php
	$dbHost="127.0.0.1";
	$dbName="cmr";
	$dbUser="cmr";
	$dbPassword="cmr";
	
	//menu principale
	$menu = "<nav class=\"horizontal-nav full-width\">
                            <ul>
								<li><a href=\"main.php\">Home</a></li>
                                <li><a href=\"clienti.php\">Clienti</a></li>
                                <li><a href=\"addclienti.php\">Aggiungi Clienti</a></li>
                                <li><a href=\"contratti.php\">Contratti</a></li>
                                <li><a href=\"contabilita.php\">Contabilit&agrave;</a></a></li>
                                <li><a href=\"logout.php\">Logout</a></li>
                            </ul>
                        </nav>";
    
    // Cartella Upload, per i Documenti Clienti
   $path = "upload/Clienti/";
   // Cartella Upload per i Documenti Contratto
   $pathContratto = "upload/Contratti/";
   // Variabile IVA
   $iva = "1.21";
   
   // Variabili SMTP
	$smtphost = "smtp.linkspace.it";
	$smtpuser = "linkspace@linkspace.it";
	$smtppass = "linkspace";
	$email = "amministrazione@linkspace.it";
?>
