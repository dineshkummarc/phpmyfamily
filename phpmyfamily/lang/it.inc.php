<?php

	//Hacked File by FaberK
	//phpmyfamily - opensource genealogy webbuilder
	//Copyright (C) 2002 - 2004  Simon E Booth (simon.booth@giric.com)

	//This program is free software; you can redistribute it and/or
	//modify it under the terms of the GNU General Public License
	//as published by the Free Software Foundation; either version 2
	//of the License, or (at your option) any later version.

	//This program is distributed in the hope that it will be useful,
	//but WITHOUT ANY WARRANTY; without even the implied warranty of
	//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	//GNU General Public License for more details.

	//You should have received a copy of the GNU General Public License
	//along with this program; if not, write to the Free Software
	//Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
	
	//Italian translation by Fabrizio Gatti aka FaberK - f.gatti@dms.it

//=====================================================================================================================

//=====================================================================================================================
//  global definitions
//=====================================================================================================================

	$charset			= "ISO-8859-1";
	$clang				= "it";
	$dir				= "ltr";
	$datefmt 			= "'%d/%m/%Y'";
	// flags are from http://flags.sourceforge.net
	// I can't find a copyrigh to credit
	// but I'm sure somebody has it
	$flag				= "images/it.gif";

//=====================================================================================================================
// some date stuff
// This is really out of place, but $restictdate is defined in config.inc.php and $datefmt here.
// Neither should be moved to the other == catch 22
// if anybody can think of a better way to set nulldate and dispdate - let me know!
//=====================================================================================================================
	$dquery = "SELECT DATE_FORMAT('0000-00-00', ".$datefmt." ) , DATE_FORMAT( '".$restrictdate."', ".$datefmt." )";
	$dresult = mysql_query($dquery) or die("OOOOOppppps");
	while ($row = mysql_fetch_array($dresult)) {
		$nulldate = $row[0];
		$dispdate = $row[1];
	}
	mysql_free_result($dresult);

//=====================================================================================================================
// strings for translation
//=====================================================================================================================

	$strOnFile			= "persone registrate";
	$strSelect			= "Seleziona la persona";
	$strUnknown			= "sconosciuto";
	$strLoggedIn		= "Sei registrato come ";
	$strAdmin			= "admin";
	$strLoggedOut		= "Non sei registrato: ";
	$strYes				= "Si";
	$strNo				= "No";
	$strSubmit			= "Invia";
	$strReset			= "Resetta";
	$strLogout			= "esci";
	$strHome			= "home";
	$strEdit			= "edita";
	$strAdd				= "aggiungi";
	$strDetails			= "Dettagli";
	$strBorn			= "Nato";
	$strCertified		= "Certificato";
	$strFather			= "Padre";
	$strRestricted		= "Ristretto";
	$strDied			= "Morte";
	$strMother			= "Madre";
	$strChildren		= "Figlio";
	$strSiblings		= "Fratello";
	$strMarried			= "Sposato";
	$strInsert			= "inserisci";
	$strNewMarriage		= "nuovo matrimonio";
	$strNotes			= "Note";
	$strGallery			= "Galleria d'immagini";
	$strUpload			= "carica";
	$strNewImage		= "nuova immagine";
	$strNoImages		= "Nessuna immagine disponibile";
	$strCensusDetails	= "Dettagli Censiti";
	$strNewCensus		= "nuevo censimento";
	$strNoInfo			= "Nessuna informazione disponibile";
	$strYear			= "Anno";
	$strAddress			= "Indirizzo";
	$strCondition		= "Condizione";
	$strOf				= "di";
	$strAge				= "Età";
	$strProfession		= "Professione";
	$strBirthPlace		= "Luogo di nascita";
	$strDocTrans		= "Documenti Transcritti";
	$strNewTrans		= "nuova transcrizione";
	$strTitle			= "Titolo";
	$strDesc			= "Descrizione";
	$strDate			= "Data";
	$strRightClick		= "Clicca il titolo del documento da scaricare. (Potrebbe richiedere il click destro &amp; Salva Oggetto come.. in Internet Explorer)";
	$strStats			= "Statistiche sito";
	$strArea			= "Area";
	$strNo				= "Numero";
	$strCensusRecs		= "Census records";
	$strImages			= "Immagini";
	$strLast20			= "Ultime 20 Persone Aggiornate";
	$strPerson			= "Persona";
	$strUpdated			= "Aggiornata";
	$strEditing			= "Editando";
	$strName			= "Nome";
	$strDOB				= "Data di Nascita";
	$strDateFmt			= "Si prega usare il formato AAAA-MM-GG";
	$strDOD				= "Data di Morte";
	$strCauseDeath		= "Causa della Morte";
	$strMarriage		= "Sposato";
	$strSpouse			= "Moglie";
	$strDOM				= "Data del Matrimonio";
	$strMarriagePlace	= "Luogo del Matrimonio";
	$strCensus			= "Censimento";
	$strSchedule 		= "Programma";
	$strDragons			= "Here be dragons!";
	$strGender			= "Sesso";
	$strMale			= "Maschile";
	$strFemale			= "Femminile";
	$strNewPassword		= "Nuova Password";
	$strOldPassword		= "Vecchia Password";
	$strReOldPassword	= "Re-inserisci Vecchia Password";
	$strChange			= "Cambia";
	$strPwdChange		= "Cambia Password";
	$strPwdChangeMsg	= "Usa questo form se vuoi cambiare password.";
	$strLogin			= "Login";
	$strUsername		= "Utente";
	$strPassword		= "Password";
	$strRePassword		= "Re-inserisci Password";
	$strForbidden		= "Proibito";
	$strForbiddenMsg	= "La pagina che hai richiesto riporta che non hai sufficienti diritta per visionarla.  Non ripetere la richiesta.  Per favore clicca <a href=\"index.php\">quì</a> per continuare.";
	$strDelete			= "cancella";
	$strFUpload			= "File da Uploadare";
	$strFTitle			= "File Titolo";
	$strFDesc			= "File Descrizione";
	$strFDate			= "File Data";
	$strIUpload			= "Immagine da Uploadare";
	$strISize			= "Solo JPEG (dimensione massima 1MB)";
	$strITitle			= "Immagine Titolo";
	$strIDesc			= "Immagine Descrizione";
	$strIDate			= "Immagine Data";
	$strOn				= "il";
	$strAt				= "a";
	$strAdminFuncs		= "Funzioni Amministrative";
	$strAction			= "azione";
	$strUserCreate		= "Crea nuovo utente";
	$strCreate			= "Crea";
	$strBack			= "Indietro";
	$strToHome			= "all'homepage.";
	$strNewMsg			= "Accertati che la persona non sia già presente nel database prima di crearla!";
	$strIndex			= "Tutti i dettagli per le persone nate dopo $dispdate sono limitate a proteggere le loro identità.  Se sei un utente registrato puoi vedere i loro dettagli ed editarli.  Chiunque è libero di consultare i risultati non confidenziali. Se credi che qualcuno di questi appartenga al tuo albero genealogico, dei pregato <a href=\"$1\">d'informarmi</a>";
	$strNote			= "Note";
	$strFooter			= "Scrivi al <a href=\"$1\">webmaster</a> senza problema.";
	$strPowered			= "Powered by";
	$strPedigreeOf		= "Pedigree di";
	$strBirths			= "Nati";
	$strAnniversary		= "Anniversario";
	$strUpcoming		= "Prossimi Anniversari";
	$strMarriages		= "Matrimoni";
	$strDeaths			= "Morti";
	$strConfirmDelete	= "\"Sei sicuro di voler CANCELLARE\\n'\" + year + \"' \" + section +\"?\"";
	$strTranscript		= "trascrizione";
	$strImage			= "immagine";
	$strDoubleDelete	= "\"Sei sicuro di voler CANCELLARE questa persona\\nThis process is IRREVERSIBILE!!\"";
	$strBirthCert		= "Nascita Certificata?";
	$strDeathCert		= "Morte Certificata?";
	$strMarriageCert	= "Matrimonio Certificato?";
	$strNewPerson		= "una nuova persona";
	$strPedigree		= "pedigree";
	$strToDetails		= "i dettagli";
	$strSurnameIndex	= "Indice dei Cognomi";
	$strTracking		= "Tracciare";
	$strTrack			= "traccia";
	$strThisPerson		= "questa persona";
	$strTrackSpeel		= "Usa il form sottostante per tracciare questa persona.  Ti verrà inviata automaticamente una e-mail ogni volta che il record sarà aggionato";
	$strEmail			= "Email";
	$strSubscribe		= "iscriviti";
	$strUnSubscribe		= "cancellati";
	$strMonAccept		= "You request for monitoring has been accepted<br />You will now receive an email every time this person is updated.<br />";
	$strMonCease		= "You request to cease monitoring has been accepted<br />You will no longer receive any emails.<br />";
	$strMonError		= "There has been a problem with your monitoring request.<br />Please contact the site administrator for assistance";
	$strMonRequest		= "Your request for monitoring this person is being processed.<br />A confirmation email has been sent to your address and you should follow the instructions therein.<br />";
	$strCeaseRequest	= "Your request for ceasing monitoring this person is being processed.<br />A confirmation email has been sent to your address and you should follow the instructions therein.<br />";
	$strAlreadyMon		= "You already seem to be monitoring this person.<br />No action is required.<br />";
	$strNotMon			= "You do not seem to be monitoring this person.<br />No action is required.<br />";
	$strRandomImage		= "Immagine Casuale";
	$strMailTo			= "Invia Messaggio";
	$strSubject			= "Soggetto";
	$strNoEmail			= "\"Devi inserire un indirizzo e-mail\"";
	$strEmailSent		= "La tua e-mail, è stata inviata al Webmaster.";
	$strExecute			= "Tempo di esecuzione";
	$strSeconds			= "secondi";

//=====================================================================================================================
//  email definitions
//=====================================================================================================================

	$eTrackSubject		= "[phpmyfamily] $1 è stato aggiornato";
	$eTrackBodyTop		= "Questo è un messaggio automatico.  $1 a $2 è stato aggiornato.  Clicca quì sotto per vedere il record aggiornato\n\n";
	$eTrackBodyBottom	= "Questo messaggio è stato inviato perchè hai precedentemente richiesto di monitorare questa persona.  Clicca quì sotto per rimuovere da solo questo monitoraggio\n\n";
	$eSubSubject		= "[phpmyfamily] richiesta monitoraggio";
	$eSubBody			= "Questo è un messaggio automatico.  Hai ricevuto questo messaggio perchè hai scelto di monitorare il record di $1.  Per confermare la sottoscrizione, clicca il collegamente quì sotto nella prossime 24 ore.\n\n";
	$eUnSubBody			= "Questo è un messaggio automatico.  Hai ricevuto questo messaggio perchè hai scelto di cessare di monitorare il record di $1.  Per confermare la cancellazione, clicca il collegamente quì sotto nella prossime 24 ore.\n\n";

//=====================================================================================================================
//  error definitions
//=====================================================================================================================

	$err_listpeeps		= "Errore mostrando le persone nel database";
	$err_image_insert	= "Errore inserendo l'immagine nel database";
	$err_list_enums		= "Errore enumerando i tipi in colonna";
	$err_list_census	= "Errore mostrandi i censimenti disponibili";
	$err_keywords		= "Errore richiamando i nomi per parole chiave dal database";
	$err_changed		= "Errore richiamando lista delle ultime persone cambiate";
	$err_father			= "Errore richiamando i dettagli del padre dal database";
	$err_mother			= "Errore richiamando i dettagli della madre dal database";
	$err_spouse			= "Errore richiamando i dettagli della moglie dal database";
	$err_marriage		= "Errore richiamando i dettagli del matrimonio dal database";
	$err_census_ret		= "Errore richiamando i dettagli del censimento from database";
	$err_children		= "Errore richiamando i dettagli dei figli dal database";
	$err_siblings		= "Errore richiamando i dettagli sibling dal database";
	$err_transcript		= "Errore inserendo transcript nel database";
	$err_trans			= "Errore richiamando transcripts dal database";
	$err_detail			= "Errore inserendo dettagli della persona nel database";
	$err_census			= "Errore inserendo il censimento nel database";
	$err_logon			= "Errore loggandoti";
	$err_change			= "Errore controllando il cambio password";
	$err_pwd_incorrect	= "Errore - Fornita password non corretta";
	$err_pwd_match		= "Errore - La nuova password non combacia";
	$err_update			= "Errore aggiornando la nuova password";
	$err_pwd_success	= "Password aggiornata con successo";
	$err_image			= "Errore richiamando immagine dal database";
	$err_images			= "Errore richiamando immagini dal database";
	$err_person			= "Errore richiamando la persona dal database";
	$err_new_user		= "Errore inserendo il nuovo utente nel database";
	$err_user_exist		= "Errore - l'utente già esiste";
	$err_pwd			= "Errore richiamando la password dal database";
	$err_delete_user	= "Errore cancellando l'utente dal database";
	$err_users			= "Errore richiamando gli utenti dal database";
	$err_census_delete	= "Errore cancellando il censimento dal database";
	$err_marriage_delete= "Errore cancellando il matrimonio dal database";
	$err_trans_delete	= "Errore cancellando transcript dal database";
	$err_person_delete	= "Errore cancellando la persona dal database";
	$err_trans_file		= "Errore cancellando transcript file";
	$err_image_file		= "Errore cancellando il file dell'immagine";
	$err_child_update	= "Errore aggiornando i records dei figli";
	$err_person_update	= "Errore aggiornando i dettagli della persona";
	$err_marriage_insert= "Errore inserendo il matrimonio nel database";

	// eof
?>
