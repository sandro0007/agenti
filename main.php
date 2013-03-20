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
                        
echo "INFORMAZIONE AGENTE <br />";

$agente = "SELECT * FROM Agenti where idAgenti=".$cod."";
$res = mysql_query($agente);
$rsAgente = mysql_fetch_assoc($res);
echo "		<table >
			<tr>
				<td colspan = \"2\" bgcolor = \"#1E90FF\" ><center><b>Dettagli Amministratore</b></center></td>
			</tr>
			<tr>
				<td><b>Cognome</b></td>
				<td bgcolor = \"#E5E5E5\"><i>".$rsAgente['AgenteCognome']."</i></td>
			</tr>
			<tr>
				<td><b>Nome</b></td>
				<td bgcolor = \"#E5E5E5\"><i>".$rsAgente['AgenteNome']."</i></td>
			</tr>
			<tr>
				<td><b>E-Mail</b></td>
				<td bgcolor = \"#E5E5E5\"><i>".$rsAgente['AgenteMail']."</i></td>
			</tr>
		</table>";
		
$anno = date("Y");
$mese = date("m");
$mese2 = date("n");
// Punteggio Potenziale Mese Corrente
$sqlMeseCorrente = "SELECT Sum(ContrattoPunti) as PuntiMese FROM `Contratti` as C
						JOIN Agenti_Clienti_Contratti as A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$cod."' 
							AND YEAR(C.ContrattoData) = '".$anno."' 
								AND MONTH(C.ContrattoData) = '".$mese."'";
$resMeseCorrente = mysql_query($sqlMeseCorrente);
$rsMeseCorrente = mysql_fetch_assoc($resMeseCorrente);
// Punteggio Reale Mese Corrente
$sqlMeseCorrente2 = "SELECT Sum(ContrattoPunti) as PuntiMese FROM `Contratti` as C
						JOIN Agenti_Clienti_Contratti as A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$cod."' 
						AND YEAR(C.ContrattoData) = '".$anno."' 
							AND MONTH(C.ContrattoData) = '".$mese."' 
								AND MONTH(C.ContrattoDataAttivazione) = '".$mese."'
									AND C.ContrattoStato = 'Attivato'";
$resMeseCorrente2 = mysql_query($sqlMeseCorrente2);
$rsMeseCorrente2 = mysql_fetch_assoc($resMeseCorrente2);

// Calcolo Trimestre Corrente
// $trimestre = 1 (primo trimestre);
// $trimestre = 4 (secondo trimestre);
// $trimestre = 7 (terzo trimestre);
// $trimestre = 10 (quarto trimestre);

$trimestre = ($mese2<=3?1:($mese2<=6?4:($mese2<=9?7:10)));

switch ($trimestre){
	case 1:	// primo trimestre
			$meseda = "01";
			$mesea	= "03";
		break;
	case 4: // secondo trimestre
			$meseda = "04";
			$mesea	= "06";
		break;
	case 7:	// terzo trimestre
			$meseda = "07";
			$mesea	= "09";
		break;
	case 10: // quarto trimestre
			$meseda = "10";
			$mesea	= "12";
		break;
	}

// Calcolo Semestre Corrente
// $semestre = 1 (primo semestre);
// $semestre = 2 (secondo semestre);

$semestre = ($mese2<=6?1:2);
switch ($semestre){
	case 1:	// primo semestre
			$meseda2 = "01";
			$mesea2	= "06";
		break;
	case 2: // secondo semestre
			$meseda2 = "07";
			$mesea2	= "12";
		break;
	}
// Punteggio Potenziale Trimestre Corrente
$sqlTrimestreCorrente = "SELECT Sum(ContrattoPunti) as PuntiTrimestre FROM `Contratti` as C
						JOIN Agenti_Clienti_Contratti as A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$cod."' 
							AND YEAR(C.ContrattoData) = '".$anno."' 
							AND MONTH(C.ContrattoData) >= '".$meseda."' 
								AND MONTH(C.ContrattoData) <= '".$mesea."'";
$resTrimestreCorrente = mysql_query($sqlTrimestreCorrente);
$rsTrimestreCorrente = mysql_fetch_assoc($resTrimestreCorrente);
// Punteggio Reale Trimestre Corrente
$sqlTrimestreCorrente2 = "SELECT Sum(ContrattoPunti) as PuntiTrimestre FROM `Contratti` as C
						JOIN Agenti_Clienti_Contratti as A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$cod."' 
							AND  YEAR(C.ContrattoData) = '".$anno."' 
								AND MONTH(C.ContrattoData) >= '".$meseda."' AND MONTH(C.ContrattoData) <= '".$mesea."' 
									AND MONTH(C.ContrattoDataAttivazione) >= '".$meseda."' AND MONTH(C.ContrattoDataAttivazione) <= '".$mesea."'
										AND C.ContrattoStato = 'Attivato'";
$resTrimestreCorrente2 = mysql_query($sqlTrimestreCorrente2);
$rsTrimestreCorrente2 = mysql_fetch_assoc($resTrimestreCorrente2);

// Punteggio Potenziale Semetre Corrente
$sqlSemetreCorrente = "SELECT Sum(ContrattoPunti) as PuntiSemestre FROM `Contratti` as C
						JOIN Agenti_Clienti_Contratti as A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$cod."' 
							AND  YEAR(C.ContrattoData) = '".$anno."' 
						AND MONTH(C.ContrattoData) >= '".$meseda2."' 
								AND MONTH(C.ContrattoData) <= '".$mesea2."'";
$resSemetreCorrente = mysql_query($sqlSemetreCorrente);
$rsSemetreCorrente = mysql_fetch_assoc($resMeseCorrente);
// Punteggio Reale Semestre Corrente
$sqlSemetreCorrente2 = "SELECT Sum(ContrattoPunti) as PuntiSemestre FROM `Contratti`as C
						JOIN Agenti_Clienti_Contratti as A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$cod."' 
							AND YEAR(C.ContrattoData) = '".$anno."' 
							AND MONTH(C.ContrattoData) >= '".$meseda2."' AND MONTH(C.ContrattoData) <= '".$mesea2."' 
									AND MONTH(C.ContrattoDataAttivazione) >= '".$meseda2."' AND MONTH(C.ContrattoDataAttivazione) <= '".$mesea2."' 
										AND C.ContrattoStato = 'Attivato'";
$resSemetreCorrente2 = mysql_query($sqlSemetreCorrente2);
$rsSemetreCorrente2 = mysql_fetch_assoc($resSemetreCorrente2);


// Punteggio Potenziale Anno Corrente
$sqlAnnoCorrente = "SELECT Sum(ContrattoPunti) as PuntiAnno FROM `Contratti` as C
						JOIN Agenti_Clienti_Contratti as A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$cod."' 
							AND  YEAR(C.ContrattoData) = '".$anno."'";
$resAnnoCorrente = mysql_query($sqlAnnoCorrente);
$rsAnnoCorrente = mysql_fetch_assoc($resAnnoCorrente);
// Punteggio Reale Anno Corrente
$sqlAnnoCorrente2 = "SELECT Sum(ContrattoPunti) as PuntiAnno FROM `Contratti` as C
						JOIN Agenti_Clienti_Contratti as A on C.ContrattoId = A.ContrattoId WHERE A.AgenteId = '".$cod."' 
							AND  YEAR(C.ContrattoData) = '".$anno."' AND YEAR(C.ContrattoDataAttivazione) = '".$anno."' AND C.ContrattoStato = 'Attivato'";
$resAnnoCorrente2 = mysql_query($sqlAnnoCorrente2);
$rsAnnoCorrente2 = mysql_fetch_assoc($resAnnoCorrente2);
echo "
	<table >
			<tr>
				<td colspan = \"3\" bgcolor = \"#1E90FF\" ><center><b>Dettagli Punti</b></center></td>
			</tr>
			<tr>
				<td>Periodo</td>
				<td>Punti Potenziali</td>
				<td>Punti Reali</td>
			</tr>
			<tr>
				<td>Mese Corrente</td>
				<td bgcolor = \"#E5E5E5\">".$rsMeseCorrente['PuntiMese']."</td>
				<td bgcolor = \"#E5E5E5\">".$rsMeseCorrente2['PuntiMese']."</td>
			</tr>
			<tr>
				<td>Trimestre Corrente</td>
				<td bgcolor = \"#E5E5E5\">".$rsTrimestreCorrente['PuntiTrimestre']."</td>
				<td bgcolor = \"#E5E5E5\">".$rsTrimestreCorrente2['PuntiTrimestre']."</td>
			</tr>
			<tr>
				<td>Semetre Corrente</td>
				<td bgcolor = \"#E5E5E5\">".$rsSemetreCorrente['PuntiSemestre']."</td>
				<td bgcolor = \"#E5E5E5\">".$rsSemetreCorrente2['PuntiSemestre']."</td>
			</tr>
			<tr>
				<td>Anno Corrente</td>
				<td bgcolor = \"#E5E5E5\">".$rsAnnoCorrente['PuntiAnno']."</td>
				<td bgcolor = \"#E5E5E5\">".$rsAnnoCorrente2['PuntiAnno']."</td>
			</tr>
		</table>
";
?>
