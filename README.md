agenti
======

gestione contratti agenti

L'applicativo si occupa di gestire il sistema di contratti stipulati da 
agenti e gestire i relativi clienti.

I Clienti sono associati ad ogni agente, per distinguerli e poter 
calcolare il numero di Clienti contattati con quelli con cui è stato
chiuso il contratto.

I contratti sono basati su profili ben definiti, l'agente una volta 
caricato il cliente, può associarli uno o più contratti.

I contratti possono avere uno o più allegati

Ogni Agente effettuando il login può aggiungere nuovi clienti o vedere
i clienti inseriti. NOTA: l'agente non può apportare direttamente 
modifiche sui dati inseriti, ma può chiederne variazione con apposito
form.

Ogni agente effettuando il login può aggiungere nuovi contratti o vedere
i contratti inseriti (e il relativo stato). NOTA: l'agente non può
apportare direttamente modifiche sui dati inseriti e loro stato, ma può
richiederne variazione con apposito form.

Ogni agente avrà la possibilità di visualizzare i contratti attivati tramite
la pagina contabilità. Nel caso sia attivi può emettere la propria fattura
ed indicare che è stata emessa. L'amministrazione verrà avvisata che è stata
emessa fattura e può procedere al pagamento. Una volta effettuato il pagamento
l'agente non potrà più modificare lo stato della contabilità.

I Contratti una volta inseriti, devono seguire un workflow procedurale di
emissione così composto:
- inserimento		-> la pratica è stata inserita dall'agente;
- in lavorazione	-> la pratica è stata presa in consegna dall'amministraione
- scartata			-> la pratica è stata scartata per mancaza dati o errori (form di chierimento)
- attivata			-> la pratica è stata attivata (installazione inclusa)

