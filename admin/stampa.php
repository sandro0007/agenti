<?

require('class/fpdf.php');
require ('include/config.php');
$conn=mysql_connect($dbHost,$dbUser,$dbPassword);
mysql_select_db($dbName);

$sqlContratto = "SELECT * FROM Contratti WHERE ContrattoId = '".$_POST['ContrattoId']."'";
	$resContratto = mysql_query($sqlContratto);
	$rsContratto = mysql_fetch_assoc($resContratto);
	
$sqlCliente = "SELECT * FROM Clienti WHERE idCliente = ".$rsContratto['Clienti_idCliente']."";
	$resCliente = mysql_query($sqlCliente);
	$rsCliente = mysql_fetch_assoc($resCliente);

$sqlLinea = "SELECT * FROM Contratti_Linea as C JOIN Linea as L on C.LineaId = L.LineaId WHERE C.ContrattoId = '".$_POST['ContrattoId']."'";
	$resLinea = mysql_query($sqlLinea);
	$rsLinea = mysql_fetch_assoc($resLinea);

$sqlOfferta = "SELECT * FROM Contratti_Offerte as C JOIN Offerte as O ON C.OffertaId = O.OffertaId WHERE C.ContrattoId = '".$_POST['ContrattoId']."'";
	$resOfferta = mysql_query($sqlOfferta);
	$rsOfferta = mysql_fetch_assoc($resOfferta);

$sqlOpzioni = "SELECT * FROM Contratti_Opzioni as C JOIN Opzioni as O ON C.OpzioneId = O.OpzioneId WHERE C.ContrattoId = '".$_POST['ContrattoId']."'";
	$resOpzioni = mysql_query($sqlOpzioni);
	$rsOpzioni = mysql_fetch_assoc($resOpzioni);
	
error_reporting(0);
define('EURO', chr(128));

class PDF extends FPDF
{
	
public function setContratto($contratto){
		$this->Contratto = $contratto;
	}
// Page header
function Header()
{
    // Logo
    $this->Image('image/logo.png',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',20);
    // Move to the right
    $this->Cell(60);
    // Title
    $this->Cell(70,10,'MODULO ADESIONE',0,0,'C');
    $this->Cell(30);
    $this->SetFont('Arial');
    $this->SetFontSize(6);
    $this->cell(20,5,$this->Contratto[ContrattoNome],'LTR',0,'C');
    $this->Ln();
    $this->Cell(160);
    $this->cell(20,5,$this->Contratto[ContrattoData],'LTBR',0,'C');
    // Line break
    $this->Ln(10);
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}

}


// Instanciation of inherited class
$pdf = new PDF();
$pdf->SetTitle('Contratto');
$pdf->setContratto($rsContratto);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',6);
$pdf->Ln();
$pdf->Cell(10,0,'Il Sottoscritto chiede a Linkspace Soc. Coop. di poter usufruire dei servizi alle condifizone di seguito riportate ivi comprese le condizioni generali di contratto presenti sul sito http://www.linkspace.it/condizioni_generali.pdf',0,0,'L');
$pdf->Ln();
$pdf->Cell(10,5,'che dichiera di aver esaminato e accettato. Il sottoscritto dichiara di voler mantenere ferma la presenta proposta per il periodo di giorni 30 (trenta) dalla ricezione, ai sensi e per gli effetti dell\'art. 1329 del c.c.',0,0,'L');
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Times','B',12);
$pdf->SetFillColor('30','144','255');
$pdf->Cell(190,5,'Dati Intestatario Contratto','1',0,'C',True);
$pdf->Ln();
$pdf->Ln();
$pdf->SetFillColor('240','240','240');
$pdf->SetFont('Times','',10);
$pdf->Cell(30,5,'Cognome e Nome','0','0','L');
$pdf->Cell(60,5,$rsCliente['ClienteCognome'].' '.$rsCliente['ClienteNome'],0,0,'L',True);
$pdf->Cell(30,5,'Codice Fiscale',0,0,'L');
$pdf->Cell(50,5,$rsCliente['ClienteCF'],0,0,'L',True);
$pdf->Cell(10,5,'Sesso',0,0,'L');
$pdf->Cell(10,5,$rsCliente['ClienteSesso'],0,0,'L',True);
$pdf->Ln();
$pdf->Cell(30,5,'Ragione Sociale',0,0,'L');
$pdf->Cell(100,5,$rsCliente['ClienteRagione'],0,0,'L',True);
$pdf->Cell(30,5,'Partita Iva',0,0,'L');
$pdf->Cell(30,5,$rsCliente['ClientePI'],0,0,'L',True);
$pdf->Ln();
$pdf->Cell(30,5,'Data di Nascita',0,0,'L');
$pdf->Cell(30,5,$rsCliente['ClienteDataNascita'],0,0,'L',True);
$pdf->Cell(30,5,'Luogo di Nascita',0,0,'L');
$pdf->Cell(70,5,$rsCliente['ClienteLuogoNascita'],0,0,'L',True);
$pdf->Cell(20,5,'Provincia',0,0,'L');
$pdf->Cell(10,5,$rsCliente['ClienteProvinciaNascita'],0,0,'L',True);
$pdf->Ln();
$pdf->Cell(40,5,'Indirizzo',0,0,'L');
$pdf->Cell(50,5,$rsCliente['ClienteIndirizzo'],0,0,'L',True);
$pdf->Cell(20,5,'Numero',0,0,'L');
$pdf->Cell(10,5,$rsCliente['ClienteNumero'],0,0,'L',True);
$pdf->Cell(10,5,'C.A.P.',0,0,'L');
$pdf->Cell(10,5,$rsCliente['ClienteCap'],0,0,'L',True);
$pdf->Cell(10,5,'Citta',0,0,'L');
$pdf->Cell(40,5,$rsCliente['ClienteCitta'],0,0,'L',True);
$pdf->Ln();
$pdf->Cell(40,5,'Indirizzo Installazione',0,0,'L');
$pdf->Cell(50,5,$rsContratto['ContrattoIndirizzo1'],0,0,'L',True);
$pdf->Cell(20,5,'Numero',0,0,'L');
$pdf->Cell(10,5,$rsContratto['ContrattoNumero1'],0,0,'L',True);
$pdf->Cell(10,5,'C.A.P.',0,0,'L');
$pdf->Cell(10,5,$rsContratto['ContrattoCap1'],0,0,'L',True);
$pdf->Cell(10,5,'Citta',0,0,'L');
$pdf->Cell(40,5,$rsContratto['ContrattoCitta1'],0,0,'L',True);
$pdf->Ln();
$pdf->Cell(40,5,'Indirizzo Corrispondenza',0,0,'L');
$pdf->Cell(50,5,$rsContratto['ContrattoIndirizzo2'],0,0,'L',True);
$pdf->Cell(20,5,'Numero',0,0,'L');
$pdf->Cell(10,5,$rsContratto['ContrattoNumero2'],0,0,'L',True);
$pdf->Cell(10,5,'C.A.P.',0,0,'L');
$pdf->Cell(10,5,$rsContratto['ContrattoCap2'],0,0,'L',True);
$pdf->Cell(10,5,'Citta',0,0,'L');
$pdf->Cell(40,5,$rsContratto['ContrattoCitta2'],0,0,'L',True);
$pdf->Ln();
$pdf->Cell(30,5,'Documento Identita',0,0,'L');
$pdf->Cell(40,5,$rsCliente['ClienteTipoDocumento'],0,0,'L',True);
$pdf->Cell(20,5,'Numero',0,0,'L');
$pdf->Cell(50,5,$rsCliente['ClienteNumeroDocumento'],0,0,'L',True);
$pdf->Cell(20,5,'Rilasciata il',0,0,'L');
$pdf->Cell(30,5,$rsCliente['ClienteRilascioDocumento'],0,0,'L',True);
$pdf->Ln();
$pdf->Cell(30,5,'Rilasciata da',0,0,'L');
$pdf->Cell(40,5,$rsCliente['ClienteEnteDocumento'],0,0,'L',True);
$pdf->Cell(10,5,'di',0,0,'L');
$pdf->Cell(110,5,$rsCliente['ClienteEnteDiDocumento'],0,0,'L',True);
$pdf->Ln();
$pdf->Cell(15,5,'Telefono',0,0,'L');
$pdf->Cell(30,5,$rsCliente['ClienteTelefono'],0,0,'L',True);
$pdf->Cell(7,5,'Fax',0,0,'L');
$pdf->Cell(30,5,$rsCliente['ClienteFax'],0,0,'L',True);
$pdf->Cell(15,5,'Cellulare',0,0,'L');
$pdf->Cell(25,5,$rsCliente['ClienteCellulare'],0,0,'L',True);
$pdf->Cell(15,5,'E-Mail',0,0,'L');
$pdf->Cell(53,5,$rsCliente['ClienteMail'],0,0,'L',True);
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Times','B',12);
$pdf->SetFillColor('30','144','255');
$pdf->Cell(190,5,'Dati Servizio','1',0,'C',True);
$pdf->Ln();
$pdf->Ln();
$pdf->SetFillColor('236','236','236');
$pdf->SetFont('Times','',10);
$pdf->Cell(30,5,'Codice Contratto','0','0','L');
$pdf->Cell(20,5,$rsOfferta['OffertaNome'],0,0,'L',True);
$pdf->Cell(20,5,'Descrizione',0,0,'L');
$pdf->Cell(70,5,$rsOfferta['OffertaDescrizione'],0,0,'L',True);
$pdf->Cell(30,5,'Importo Mensile',0,0,'L');
$pdf->Cell(20,5,''.EURO.''.$rsOfferta['OffertaCanone'].'',0,0,'L',True);
$pdf->Ln();
$pdf->Cell(30,5,'Linea Telefonica',0,0,'L');
$pdf->Cell(20,5,'Esistente',0,0,'L');
$pdf->Cell(20,5,$rsLinea['LineaDatiMigrazione'],0,0,'L',True);
$pdf->Cell(30,5,'Numero Da Migrare',0,0,'L');
$pdf->Cell(20,5,$rsLinea['LineaNumero'],0,0,'L',True);
$pdf->Cell(30,5,'Codice Migrazione',0,0,'L');
$pdf->Cell(40,5,$rsLinea['LineaCodice'],0,0,'L',True);
$pdf->Ln();
$pdf->Cell(30);
$pdf->Cell(30,5,'Nuova Attivazione',0,0,'L');
$pdf->Cell(30,5,$rsLinea['LineaDatiAttivazione'],0,0,'L',true);
$pdf->Cell(70,5,'Numero Pilota di un dominicilio adiacente',0,0,'L');
$pdf->Cell(30,5,$rsLinea['LineaPilota'],0,0,'L',True);
$pdf->Ln();
$pdf->Cell(30,5,'Pagamento Canone',0,0,'L');
$pdf->Cell(30,5,$rsContratto['ContrattoPagamento'],0,0,'L',True);
$pdf->Cell(30,5,'U.T. Attivazione',0,0,'L');
$pdf->Cell(30,5,$rsContratto['ContrattoAttivazione'],0,0,'L',True);
$pdf->Cell(40,5,'Ricezione Fattura',0,0,'L');
$pdf->Cell(30,5,$rsContratto['ContrattoFattura'],0,0,'L',True);
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Times','B',12);
$pdf->SetFillColor('30','144','255');
$pdf->Cell(190,5,'Servizi Opzionali','1',0,'C',True);
$pdf->Ln();
$pdf->Ln();
$pdf->SetFillColor('0','0','0');
$pdf->SetFont('Times','',10);
$pdf->Cell(50,5,'Ip Statico',0,0,'L');
if ($rsOpzioni['OpzioneIp'] == '0')
{
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'1 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'4 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'8 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'16 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'32 IP',0,0,'L');
}
if ($rsOpzioni['OpzioneIp'] == '1')
{
	$pdf->Cell(2,2,'',1,0,'L',True);
	$pdf->Cell(20,5,'1 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'4 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'8 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'16 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'32 IP',0,0,'L');
}
if ($rsOpzioni['OpzioneIp'] == '4')
{
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'1 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L',true);
	$pdf->Cell(20,5,'4 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'8 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'16 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'32 IP',0,0,'L');
}
if ($rsOpzioni['OpzioneIp'] == '8')
{
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'1 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'4 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L',true);
	$pdf->Cell(20,5,'8 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'16 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'32 IP',0,0,'L');
}
if ($rsOpzioni['OpzioneIp'] == '16')
{
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'1 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'4 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'8 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L', true);
	$pdf->Cell(20,5,'16 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'32 IP',0,0,'L');
}

if ($rsOpzioni['OpzioneIp'] == '32')
{
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'1 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'4 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'8 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L');
	$pdf->Cell(20,5,'16 IP',0,0,'L');
	$pdf->Cell(2,2,'',1,0,'L',True);
	$pdf->Cell(20,5,'32 IP',0,0,'L');
}

$pdf->Ln();
$pdf->Cell(30);
if ($rsOpzioni['OpzioneAp'] == '0')
{
	$pdf->Cell(2,2,'',1,0,'L');
	}
else {
	$pdf->Cell(2,2,'',1,0,'L',True);
	}
$pdf->Cell(50,5,'Acquisto e installazione Access Point',0,0,'L');
$pdf->Cell(30);
if ($rsOpzioni['OpzioneElenco'] == '0')
{
	$pdf->Cell(2,2,'',1,0,'L');
	}
else {
	$pdf->Cell(2,2,'',1,0,'L',True);
	}
$pdf->Cell(50,5,'Pubblicazione Numero in Elenco Telefonico',0,0,'L');
$pdf->Ln();
$pdf->Cell(30);
if ($rsOpzioni['OpzioneChie'] == '0')
{
	$pdf->Cell(2,2,'',1,0,'L');
	}
else {
	$pdf->Cell(2,2,'',1,0,'L',True);
	}
$pdf->Cell(50,5,'Identificaticazione Chiamante (Chi è)',0,0,'L');
$pdf->Cell(30);
if ($rsOpzioni['OpzioneClinascosto'] == '0')
{
	$pdf->Cell(2,2,'',1,0,'L');
	}
else {
	$pdf->Cell(2,2,'',1,0,'L',True);
	}
$pdf->Cell(50,5,'Blocco Identificazitvo Chiamante (CLI Nascosto)',0,0,'L');
$pdf->Ln();
$pdf->Cell(30);
if ($rsOpzioni['OpzioneTrasferimento'] == '0')
{
	$pdf->Cell(2,2,'',1,0,'L');
	}
else {
	$pdf->Cell(2,2,'',1,0,'L',True);
	}
$pdf->Cell(50,5,'Trasferimento di chiamata',0,0,'L');
$pdf->Cell(30);
if ($rsOpzioni['OpzionePubblicita'] == '0')
{
	$pdf->Cell(2,2,'',1,0,'L');
	}
else {
	$pdf->Cell(2,2,'',1,0,'L',True);
	}
$pdf->Cell(50,5,'Vuole ricevere la pubblicità?',0,0,'L');
$pdf->Ln();
$pdf->Cell(30);
if ($rsOpzioni['OpzioneAttesa'] == '0')
{
	$pdf->Cell(2,2,'',1,0,'L');
	}
else {
	$pdf->Cell(2,2,'',1,0,'L',True);
	}
$pdf->Cell(50,5,'Chiamata in attesa',0,0,'L');
$pdf->Cell(30);
if ($rsOpzioni['OpzioneSwitch'] == '0')
{
	$pdf->Cell(2,2,'',1,0,'L');
	}
else {
	$pdf->Cell(2,2,'',1,0,'L',True);
	}
$pdf->Cell(50,5,'Acquisto Switch 8 Porte',0,0,'L');
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Times','B',12);
$pdf->SetFillColor('30','144','255');
$pdf->Cell(190,5,'Dati RID','1',0,'C',True);
$pdf->Ln();
$pdf->Ln();
$pdf->SetFillColor('236','236','236');
$pdf->SetFont('Times','',10);
$pdf->Cell(20,5,'Banca',0,0,'L');
$pdf->Cell(50,5,$rsContratto['ContrattoBanca'],0,0,'L',True);
$pdf->Cell(20,5,'Agenzia',0,0,'L');
$pdf->Cell(30,5,$rsContratto['ContrattoAgenzia'],0,0,'L',True);
$pdf->Cell(20,5,'Località',0,0,'L');
$pdf->Cell(50,5,$rsContratto['ContrattoLocalita'],0,0,'L',True);
$pdf->Ln();
$pdf->Cell(20,5,'Intestazione',0,0,'L');
$pdf->Cell(170,5,$rsContratto['ContrattoIntestazione'],0,0,'L',True);
$pdf->Ln();
$pdf->Cell(20,5,'IBAN',0,0,'L');
$pdf->Cell(80,5,$rsContratto['ContrattoIban'],0,0,'L',True);
$pdf->Cell(30,5,'Firma Correntista',0,0,'L');
$pdf->Cell(60,5,'',0,0,'L',True);
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Times','B',12);
$pdf->SetFillColor('30','144','255');
$pdf->Cell(190,5,'Note','1',0,'C',True);
$pdf->Ln();
$pdf->Ln();
$pdf->SetFillColor('236','236','236');
$pdf->SetFont('Times','',10);
$pdf->MultiCell(190,5,$rsContratto['ContrattoNote'],0,0,'L',True);
$pdf->Ln();
$pdf->Ln();
//Linea di chiusura Contratto
$pdf->Line($pdf->GetX(),$pdf->GetY(),200,$pdf->GetY());
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(30,5,'Luogo e data,',0,0,'L');
$pdf->Cell(50,5,'','B',0,'L');
$pdf->Cell(50);
$pdf->Cell(20,5,'Firma',0,0,'L');
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(110);
$pdf->Cell(50,5,'','B',0,'L');
$pdf->Output('contratto-'.$contratto[ContrattoNome].'.pdf','I');
?>
