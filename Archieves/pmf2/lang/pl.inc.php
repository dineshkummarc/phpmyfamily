<?php
	//phpmyfamily - opensource genealogy webbuilder
	//Copyright (C) 2002 - 2005  Simon E Booth (simon.booth@giric.com)

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

//=====================================================================================================================

//=====================================================================================================================
//  global definitions
//=====================================================================================================================

	$charset			= "UTF-8";
	$clang				= "pl";
	$dir				= "ltr";
	$datefmt 			= "'%d/%m/%Y'";
	// flags are from http://flags.sourceforge.net
	// I can't find a copyrigh to credit
	// but I'm sure somebody has it
	$flag				= "images/gb.gif";

//=====================================================================================================================
//=====================================================================================================================
	$currentRequest->setDateFormat($datefmt);

//=====================================================================================================================
// strings for translation
//=====================================================================================================================

	$strOnFile			= "osób w bazie";
	$strSelect			= "Wybierz osobę";
	$strUnknown			= "nieznane";
	$strLoggedIn		= "Jeste zalogowany jako ";
	$strAdmin			= "admin";
	$strLoggedOut		= "Nie jesteś zalogowany";
	$strYes				= "Tak";
	$strNo				= "Nie";
	$strSubmit			= "Wyślij";
	$strReset			= "Wyczyść";
	$strLogout			= "Wyloguj";
	$strHome			= "początek";
	$strEdit			= "edytuj";
	$strAdd				= "dodaj";
	$strDetails			= "Szczeguły";
	$strBorn			= "Urodzony";
	$strCertified		= "Certyfikat";
	$strFather			= "Ojciec";
	$strRestricted		= "Zabezpieczone";
	$strDied			= "Umarł";
	$strMother			= "Matka";
	$strChildren		= "Dzieci";
	$strSiblings		= "Rodzeństwo";
	$strMarried			= "żonaty";
	$strInsert			= "dodaj";
	$strNewMarriage		= "nowe małżeństwo";
	$strNotes			= "Uwagi";
	$strGallery			= "Galeria zdjęć";
	$strUpload			= "wgraj";
	$strNewImage		= "nowe rysunek";
	$strNoImages		= "Brak rysunków dostępnych";
	$strCensusDetails	= "Szczeguły spisu ludzi";
	$strNewCensus		= "nowy spis ludzi";
	$strNoInfo			= "Brak informacji dostępnych";
	$strYear			= "Rok";
	$strAddress			= "Adres";
	$strCondition		= "Stan cywilny";
	$strOf				= "z";
	$strAge				= "Wiek";
	$strProfession		= "Zawód";
	$strBirthPlace		= "Miejsce urodzenia";
	$strDocTrans		= "Dokumenty transkryptu";
	$strNewTrans		= "nowy transkrypt";
	$strTitle			= "Tytuł";
	$strDesc			= "Opis";
	$strDate			= "Data";
	$strRightClick		= "Naciśnij na tytuł dokumentu w celu pobrania go. (Możliwe, że będzie potrzebne kliknięcie prawym przyciskiem myszki i wybranie 'Zapisz jako..' w programie Internet Explorer)";
	$strStats			= "Statystyka serwisu";
	$strArea			= "Obszar";
	$strNo				= "Numer";
	$strCensusRecs		= "Wpisy w spisie ludzi";
	$strImages			= "Rysunki";
	$strLast20			= "Ostatnie 20 osób aktualizowane";
	$strPerson			= "Osoba";
	$strUpdated			= "Aktualizowana";
	$strEditing			= "Edytowana";
	$strName			= "Nazwisko";
	$strDOB				= "Data urodzin";
	$strDateFmt			= "Proszę używać formatu YYYY-MM-DD";
	$strDOD				= "Data śmierci";
	$strCauseDeath		= "Powód śmierci";
	$strMarriage		= "Małżeństwo";
	$strSpouse			= "Małżonek";
	$strDOM				= "Data małżeństwa";
	$strMarriagePlace	= "Miejsce ożenku";
	$strCensus			= "Lista osób";
	$strSchedule 		= "Harmonogram";
	$strDragons			= "Tu będą smoki!";
	$strGender			= "Płeć";
	$strMale			= "Mężczyzna";
	$strFemale			= "Kobieta";
	$strNewPassword		= "Nowe hasło";
	$strOldPassword		= "Stare hasło";
	$strReOldPassword	= "Wprowadź ponownie stare hasło";
	$strChange			= "Zmiana";
	$strPwdChange		= "Zmiana hasła";
	$strPwdChangeMsg	= "Proszę używać tego formularza w celu zmiany hasła.";
	$strLogin			= "login";
	$strUsername		= "Użytkownik";
	$strPassword		= "Hasło";
	$strRePassword		= "Wprowadź ponownie hasło";
	$strForbidden		= "Zabroniony";
	$strForbiddenMsg	= "Nie masz uprawnień do strona, którą chcesz oglądać.  Proszę nie poprawiać tego żądania.  Proszę kliknąć <a href=\"index.php\">tutaj</a> w celu kontynuacji.";
	$strDelete			= "usuń";
	$strFUpload			= "Plik do wgrania na serwer";
	$strFTitle			= "Tytuł pliku";
	$strFDesc			= "Opis pliku";
	$strFDate			= "Data pliku";
	$strIUpload			= "Rysunek do wgrania";
	$strISize			= "tylko JPEG (maksymalna wielkość to 1MB)";
	$strITitle			= "Tytuł rysunku";
	$strIDesc			= "Opis rysunku";
	$strIDate			= "Data rysunku";
	$strOn				= "po";
	$strAt				= "w";
	$strAdminFuncs		= "Funkcje administratorskie";
	$strAction			= "akcja";
	$strUserCreate		= "Dodaj nowego użytkownika";
	$strCreate			= "Doaj";
	$strBack			= "Wstecz";
	$strToHome			= "do strony domowej.";
	$strNewMsg			= "Proszę sprawdzić czy ta osoba już nie istnieje w bazie danych przed jej dodaniem!";
	$strIndex			= "Wszystkie szczegóły dla ludzi urodzonych po $currentRequest->dispdate są zabezpieczone przed ich identyfikacją.  Jeżeli jesteś zarejestrowanym użytkownikiem będziesz mógł zobaczyć te dane i edytować wpis.  Każdy może przeglądać odbezpieczone dane.  Jeżeli ktokolwiek pasuje do Twojego drzewa rodzinnego, proszę <a href=\"$1\">daj mi znać</a>";
	$strNote			= "Uwaga";
	$strFooter			= "Wyślij list do <a href=\"$1\">webmastera</a> w razie jakichkolwiek problemów.";
	$strPowered			= "Wspierane przez";
	$strPedigreeOf		= "Genealogia dla";
	$strBirths			= "Urodziny";
	$strAnniversary		= "Rocznice";
	$strUpcoming		= "Nadchodzące rocznice";
	$strMarriages		= "Małżeństwa";
	$strDeaths			= "Śmierci";
	$strConfirmDelete	= "\"Czy jesteś pewien, że chcesz usunąć sekcję \\n'\" + year + \"' ?\"";
	$strTranscript		= "transkrypcja";
	$strImage			= "rysunek";
	$strDoubleDelete	= "\"Czu napewno chcesz USUNĄĆ tą osobę \\nTen proces jest NIEODWRACALNY!!\"";
	$strBirthCert		= "Certyfikat urodzin?";
	$strDeathCert		= "Certyfikat śmierci?";
	$strMarriageCert	= "Certyfikat małżeństwa?";
	$strNewPerson		= "nową osobę";
	$strPedigree		= "genealogia";
	$strToDetails		= "szczegóły";
	$strSurnameIndex	= "Indeks nazwisk";
	$strTracking		= "Śledzenie";
	$strTrack			= "śledź";
	$strThisPerson		= "tą osobę";
	$strTrackSpeel		= "Użyj tego formularza w celu śledzenia tej osoby.  Automatycznie otrzymasz email każdorazowao gdy ten wpis zostanie zmieniony";
	$strEmail			= "Email";
	$strSubscribe		= "zapisz";
	$strUnSubscribe		= "wypisz";
	$strMonAccept		= "Twoje żądanie monitorowanie zostało zaakceptowane<br />Będziesz teraz otrzymywał listy email każdorazowo gdy wpis tej osoby zostanie zmieniony.<br />";
	$strMonCease		= "Twoje żądanie zaprzestania monitorowania zostało zaakceptowane<br />Nie będziesz otrzymywał więcej listów email.<br />";
	$strMonError		= "Wystąpił błąd podczas rozpatrywania Twojego żądania.<br />Prosimy o kontakt z administratorem serwisu w celu uzyskania pomocy";
	$strMonRequest		= "Twoje żądanie monitorowania osoby jest przetwarzane.<br />List email potwierdzający został wysłany na Twój adres email, powinieneś postępować zgodnie z instrukcjami zawartymi w liście.<br />";
	$strCeaseRequest	= "Twoje żądanie zaprzestania monitorowania tej osoby jest przetwarzane.<br />List email potwierdzający został wysłany na Twój adres email, powinieneś postępować zgodnie z instrukcjami zawartymi w liście.<br />";
	$strAlreadyMon		= "Już monitorujesz tą osobę.<br />Nie jest wymagana żadna akcja.<br />";
	$strNotMon			= "Nie monitorujesz tej osoby.<br />Nie jest wymagana żadna akcja.<br />";
	$strRandomImage		= "Losowy rysunek";
	$strMailTo			= "Wyślij wiadomość";
	$strSubject			= "Tytuł";
	$strNoEmail			= "\"Musisz podać adres email\"";
	$strEmailSent		= "Twója wiadomość została wysłana do administratora.";
	$strExecute			= "Czas wykonywania";
	$strSeconds			= "sekund";
	$strStyle			= "Styl";
	$strPreferences		= "ustawienia";
	$strRecoverPwd		= "odzyskaj hasło";
	$strStop			= "przerwij";
	$strRememberMe		= "Zapamiętaj mnie";
	$strSuffix			= "Przyrostek";
	$strLost			= "Zagubiłeś hasło";
	$strSent			= "Nowe hasło zostało wysłane";
	$strMyLoggedIn		= "Zalogowany do phpmyfamily";
	$strAdminUser		= "Jesteś <a href=\"admin.php\">administratorem</a>";
	$strMonitoring		= "Osoby, które monitorujesz";
	$strChangeStyle		= "Zmień styl serwisu";
	$strChangeEmail		= "Zmień adres email";
	$strGedCom			= "gedcom";

//=====================================================================================================================
//  email definitions
//=====================================================================================================================

	$eTrackSubject		= "[phpmyfamily] $1 zostało zaktualizowane";
	$eTrackBodyTop		= "To jest automatyczna wiadomość.  $1 dnia $2 zostało zmienione przez $3.  Kliknij poniżej by sprawdzić zmienione wpis\n\n";
	$eTrackBodyBottom	= "Ta wiadomość została wysłana ponieważ uprzednio zapisałeś się do śledzenia zmian wpisu dla tej osoby.  Naciśnij poniższy odnośnik w celu usunięcia się z tego monitorowania\n\n";
	$eSubSubject		= "[phpmyfamily] żądanie monitorowania";
	$eSubBody			= "To jest automatyczna wiadomość.  Otrzymujesz tą wiadomość ponieważ wybrałeś monitorowanie wpisów dla osoby $1.  W celu potwierdzenia monitorowania proszę kliknąć poniższy odnośnik w ciągu najbliższych 24 godzin.\n\n";
	$eUnSubBody			= "To jest automatyczna wiadomość.  Otrzymujesz tą wiadomość ponieważ wybrałeś anulowanie monitorowania wpisów dla osoby $1.  W celu potwierdzenia anulowania monitorowania proszę kliknąć poniższy odnośnik w ciągu najbliższych 24 godzin.\n\n";
	$eBBSubject			= "[phpmyfamily] Wielki brat zauważył zmienę w $1";
	$eBBBottom			= "Ta wiadomość została wysłana ponieważ instalacja phpmyfamily posiada włączonego Wielkiego brata.  Proszę sprawdzić plik konfiguracyjny jeżeli chcesz wyłączyć tą opcję.\n\n";
	$ePwdSubject		= "[phpmyfamily] Nowe hasło";
	$ePwdBody		= "Ktoś, najprawdopodobniej Ty, zażądał nowe hasło dla phpmyfamily.  Twoje hasło to $1 \n\n";

//=====================================================================================================================
//  error definitions
//=====================================================================================================================

	$err_listpeeps		= "Błąd podczas listowania ludzi z bazy danych";
	$err_image_insert	= "Błąd dodawania rysunku do bazy danych";
	$err_list_enums		= "Błąd enumeracji typów kolmn";
	$err_list_census	= "Błąd listowania dostępnych spisów osób";
	$err_keywords		= "Błąd pobierania nazwisk dla słów kluczy z bazy danych";
	$err_changed		= "Błąd pobierania listy ostatnio zmienianych wpisów osób";
	$err_father			= "Błąd pobierania inforamcji o ojcu z bazy danych";
	$err_mother			= "Błąd pobierania inforamcji o matce z bazy danych";
	$err_spouse			= "Błąd pobierania inforamcji o małżonku z bazy danych";
	$err_marriage		= "Błąd pobierania inforamcji o małżeństwie z bazy danych";
	$err_census_ret		= "Błąd pobierania inforamcji o spisie osób z bazy danych";
	$err_children		= "Błąd pobierania inforamcji o dzieciach z bazy danych";
	$err_siblings		= "Błąd pobierania inforamcji o rodzeństwie z bazy danych";
	$err_transcript		= "Błąd dodawnia transkryptu do bazy danych";
	$err_trans			= "Błąd pobierania inforamcji o transkrypcie z bazy danych";
	$err_detail			= "Błąd dodawnia informacji o osobie do bazy danych";
	$err_census			= "Błąd dodawnia spisu osób do bazy danych";
	$err_logon			= "Błąd logowania";
	$err_change			= "Błąd sprawdzania zmiany hasła";
	$err_pwd_incorrect	= "Błąd - podano niepoprawne hasło";
	$err_pwd_match		= "Błąd - nowe hasła nie zgadzają się";
	$err_update			= "Błąd aktualizacji hasła";
	$err_pwd_success	= "Hasło porawnie zmieniono";
	$err_image			= "Błąd pobierania rysunku z bazy danych";
	$err_images			= "Błąd pobierania rysunków z bazy danych";
	$err_person			= "Błąd pobierania osoby z bazy danych";
	$err_new_user		= "Błąd dodawania nowej osoby do bazy danych";
	$err_user_exist		= "Błąd - osoba już istnieje";
	$err_pwd			= "Błąd pobierania hasła z bazy danych";
	$err_delete_user	= "Błąd usuwania osoby z bazy danych";
	$err_users			= "Błąd pobierania osób z bazy danych";
	$err_census_delete	= "Błąd usuwania spisu osób z bazy danych";
	$err_marriage_delete= "Błąd usuwania małżeństwa z bazy danych";
	$err_trans_delete	= "Błąd usuwania transkryptu z bazy danych";
	$err_person_delete	= "Błąd usuwania osoby z bazy danych";
	$err_trans_file		= "Błąd usuwania pliku transkryptu";
	$err_image_file		= "Błąd usuwania pliku rysunku";
	$err_child_update	= "Błąd aktualizacji wpisów dla dzieci";
	$err_person_update	= "Błąd aktualizacji informacji o osobie";
	$err_marriage_insert= "Błąd dodawania małżeństwa do bazy danych";

	// eof
?>
