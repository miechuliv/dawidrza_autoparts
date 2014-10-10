<?php
/**
 * @version		$Id: directebanking.php 3100 2013-03-04 18:11:21Z mic $
 * @package		Directebanking - Language Admin German
 * @copyright	(C) 2011 mic [ http://osworx.net ]. All Rights Reserved.
 * @author		mic - http://osworx.net
 * @license		OSWorX Commercial License http://osworx.net
 */

// Heading
$_['heading_title']             = 'Sofortüberweisung<span style="float:right; color:#777777;">Zahlung mit Banküberweisung <a href="http://osworx.net" target="_blank">OSWorX</a></span>';
$_['plain_title']               = 'Sofortüberweisung';

// Text
	// logo at overview
$_['text_directebanking']		= '<a onclick="window.open(\'https://sofort.com\');"><img src="view/image/payment/sofort.png" alt="Sofortüberweisung" title="Sofortüberweisung" style="border: 1px solid #EEEEEE;" height="25" width="94" /></a>';

	// standard
$_['text_payment']				= 'Bezahlarten';
$_['text_success']				= 'Modul Sofortüberweisung erfolgreich aktualisiert!';
$_['text_enabled']				= 'Aktiviert';
$_['text_disabled']				= 'Deaktiviert';
$_['text_success_log']			= 'Logdaten erfolgreich gelöscht';
$_['text_log_empty']			= 'Noch keine Transaktionen erfasst';
$_['text_personal_data']		= 'Persönliche Daten';
$_['text_current_time']			= 'Aktuelle Zeit: %s';
$_['text_copy']					= 'Kopieren';
$_['text_create_pw']			= 'Erstellt/ändert ein Zufallspasswort';
$_['text_show_hide']			= 'Details anzeigen/verbergen';
$_['text_success_installed']	= 'Zahlungsart Sofortüberweisung erfolgreich installiert';
$_['text_sample']				= '<div><a onclick="javascript:window.open(\'https://images.sofort.com/de/su/landing.php?banner=banner_160x43_el\',\'kundeninformationen\',\'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1020, height=900\');return false;" href="#" style="float:left; width:auto;" title="sofortüberweisung"><img src="https://images.sofort.com/de/su/banner_160x43_el.png" alt="Sofortüberweisung" border="0" /></a><p style="margin-left: 320px; width:auto;">Mit Sofortüberweisung kann sofort ohne Registrierung bequem, einfach und sicher mit dem Online-Banking Konto bezahlt werden. Es werden dafür die Zugangsdaten zum Online-Banking der eigenen Bank (PIN/TAN) benötigt.</p></div>';

// cpanel
	// icons
	// standard
$_['text_settings']				= 'Einstellungen';
$_['text_help']					= 'Hilfe';
	// individual
$_['text_text']					= 'Text';
$_['text_log']					= 'Log';
$_['text_new_project']			= 'Neues Projekt';

	// right pane
	// common
$_['text_common']				= 'Allgemein';
$_['text_accesskey_shift']		= 'Schnellwahl SHIFT + ALT';
$_['text_accesskey']			= 'Schnellwahl ALT';
$_['text_module']				= 'Modul';
$_['text_installed']			= 'Installierte Version';
$_['text_current']				= 'Aktuelle Version';
$_['text_license']				= 'Lizenz';
$_['text_copyright']			= 'Copyright';
$_['text_author']				= 'Autor';
$_['text_support']				= 'Support';
	// advanced = module specific
$_['text_advanced']				= 'Erweitert';
$_['text_total_amount']			= 'Gesamtbetrag';
$_['text_total_used']			= 'Gesamt verwendet';
$_['text_total_percent']		= '% aller Aufträge';
    // version
$_['text_title']                = 'Name';
$_['text_unknown_version']      = 'Die hier verwendete Version ist keine der offiziell Unterstützten!<br />Bitte an den <a href="mailto:support@osworx.net?subject=Unbekannte%20Version%20des%20Moduls%20Sofort.com">Entwickler direkt wenden</a>';
$_['text_description']          = 'Beschreibung';
$_['text_no_info_available']    = 'Leider sind aktuell keine weiteren Infos verfügbar';
$_['text_new_version_available']= 'Es ist eine neue Version verfügbar.';
$_['text_published']            = 'Veröffentlicht';
$_['text_changelog']            = 'Änderungen';
$_['text_changelog_new']        = 'Neu';
$_['text_changelog_fixed']      = 'Fehler bereinigt';
$_['text_support_key']			= 'Supportschlüssel';

// Entry
	// standard
$_['entry_status']		= 'Status';
$_['entry_sort_order']	= 'Reihenfolge';
	// module specific
$_['entry_order_status']= 'Auftragsstatus Erfolg';
$_['entry_geo_zone']	= 'Geografische Zone';
$_['entry_custId']		= 'Kundennummer';
$_['entry_projId']		= 'Projekt-ID';
$_['entry_successUrl']	= 'URL bei Erfolg';
$_['entry_cancelUrl']	= 'URL bei Abbruch';
$_['entry_useHash']		= 'Datenüberprüfung';
$_['entry_projectPassword']	= 'Projektpasswort';
$_['entry_notifyPassword']	= 'Benachrichtigungspasswort';
$_['entry_hashMethod']	= 'Verschlüsselungsmethode';
$_['entry_testMode']	= 'Testmodus';
$_['entry_successUrlStd']	= 'Verwende Standard-URL';
$_['entry_cancelUrlStd']	= '(Verwende Standard-URL)';
$_['entry_salutation']		= 'Anrede';
$_['entry_user_name']		= 'Name/Firmenname';
$_['entry_debug']			= 'Fehlermodus';
$_['entry_server_shift']	= 'Zeitverschiebung';
$_['entry_street']			= 'Straße';
$_['entry_zip']				= 'Postleitzahl';
$_['entry_city']			= 'Stadt';
$_['entry_email']			= 'Email';
$_['entry_country']			= 'Land';
$_['entry_phone']			= 'Telefon';
$_['entry_account_holder']	= 'Kontoinhaber';
$_['entry_account_number']	= 'Kontonummer';
$_['entry_bank_code_number']= 'Bankleitzahl';
$_['entry_bank_bic']		= 'BIC';
$_['entry_bank_iban']		= 'IBAN';
$_['entry_proj_password'] 	= 'Projektpasswort';
$_['entry_notify_password']	= 'Benachrichtigungspasswort';
$_['entry_encryption_method']	= 'Verschlüsselungsmethode';
$_['entry_proj_name'] 			= 'Projektname';
$_['entry_proj_responsible']	= 'Ansprechperson';
$_['entry_email_notification']	= 'Email für Benachrichtigungen';
$_['entry_email_language']		= 'Emailsprache';
$_['entry_legal_form']			= 'Rechtsform';
$_['entry_supportKey']			= 'Supportchlüssel';
$_['entry_text']				= 'Text';
$_['entry_title']				= 'Titel';
$_['entry_customer_groups']		= 'Kundengruppe(n)';
$_['entry_store']               = 'Geschäft';
$_['entry_protection']          = 'Käuferschutz';

// help - NOTE: tags (like links) must be build with entities!!
	// standard
	// module specific
$_['help_advanced']		= 'Um den Dienst von Sofortüberweisung nutzen zu können, muss auf %s ein Konto errichtet werden.<br />Alle weiteren Einstellungen sind dort vorzunehmen.<br /><strong>Hinweis</strong>: es wird dringend empfohlen die Datenverschlüsselung (siehe <a href="%s">System -> Einstellungen</a>) zu verwenden!<br /><br />Wurde bisher noch keine Account auf Sofortüberweisung erstellt oder soll ein neues Projekt angelegt werden, dann kann dies direkt hier gemacht werden, siehe Button &quot;Neues Projekt&quot; in der Übersicht.';
$_['help_entry_order_status']	= 'Status bei erfolgreicher Zahlung.<br />War diese nicht erfolgreich (z.B. keine Zahlung, fehlerhafte Datenübermittlung oder Prüfsummencheck), wird der Status laut System gesetzt.';
$_['help_custId']		= 'Hier die von Sofortüberweisung erhaltene Kundennummer angeben.<br />Wird bei Projektneuanlage automatisch eingefügt';
$_['help_projId']		= 'Hier die passende Projekt-ID von Sofortüberweisung angeben.<br />Wird bei Projektneuanlage automatisch eingefügt';
$_['help_successUrl']	= 'Optionale URL wenn die Transaktion erfolgreich war und auf welche der Kunde automatisch zurückgeleitet werden soll (Angabe nur dann notwendig, wenn NICHT die Standard-URL verwendet werden soll - siehe Checkbox).<br />Wird hier nichts angegeben, bleibt der Kunde auf den Seiten von Sofortüberweisung<br /><strong>Hinweis</strong>: Markieren der Checkbox bewirkt dass die Rückleitungs-URL automatisch ausgefüllt wird, es muss keine URL mehr angegeben werden!';
$_['help_cancelUrl']	= 'Optionale URL wenn die Transaktion abgebrochen wurde und auf welche der Kunde automatisch zurückgeleitet werden soll (Angabe nur dann notwendig, wenn NICHT die Standard-URL verwendet weren soll - siehe Checkbox).<br />Wird hier nichts angegeben, bleibt der Kunde auf den Seiten von Sofortüberweisung<br /><strong>Hinweis</strong>: Markieren der Checkbox bewirkt dass die Rückleitungs-URL automatisch ausgefüllt wird, es muss keine URL mehr angegeben werden!';
$_['help_useHash']		= 'Wenn angekreuzt, wird aus verschiedenen Daten ein verschlüsselter Wert gebildet welcher dann an Sofortüberweisung mitgesendet wird. Dieser Wert dient zur verbesserten Überprüfung auf Richtigkeit und Authentizität.<br />Wird diese Option verwendet, muss nachstehend ein Passwort angegeben werden!';
$_['help_projectPassword']	= 'Hier das gleiche Passwort angeben welches im System auf Sofortüberweisung unter Projektpasswort angegeben wurde.';
$_['help_notifyPassword']	= 'Hier das gleiche Passwort angeben welches im System auf Sofortüberweisung unter Benachrichtigungspasswort angegeben wurde.';
$_['help_hashMethod']	= 'Je nachdem welche Methode in der Verwaltung auf Sofortüberweisung gewählt wurde, es MUSS hier dieselbe ausgewählt werden!<br />Hinweis: optional, nur anwenden wenn Datenüberprüfung ausgewählt wurde (empfohlen wird sha1)';
$_['help_instruction']	= 'Für jede installierte Sprache kann ein eigener, individueller Titel und Text welche die Kunden über den Zahlungsablauf informiert angegeben werden.<br /><br /><strong>Tipps</strong><ul><li>Es wird empfohlen auf die Vorteile und Sicherheit dieser Zahlungsmethode hinzuweisen.</li><li>Ist kein Titel definiert, wird der Begriff aus der Sprachendatei verwendet.</li><li>Sollte kein Text definiert sein, wird nichts angezeigt.</li></ul>Soll das unten angezeigte Beispiel verwendet werden (klick auf das Bild öffnet neues Fenster), Editor in den Quellmodmodus schalten und Code kopieren und einfügen.<br />Weitere Banner unter <a onclick="window.open(\'https://www.payment-network.com/sue_de/online-anbieterbereich/werbemittel\'">Sofort.com</a>';
$_['help_logText']		= 'Hier werden alle durchgeführten Transaktionen angezeigt.<br /><strong>Hinweise</strong>:<ul><li>siehe auch Meldungen auf Sofortüberweisung unter Berichte (speziell bei Fehlern)</li><li>Ist hier anstatt lesbarem Text nur Zeichen sichtbar, wurde seit dem ersten Eintrag der geheime Schlüssel geändert!</li></ul>';
$_['help_testMode']		= 'Auswählen ob das Modul im Test- oder Livemodus betrieben werden soll<br /><strong>Hinweis</strong>: damit Testbuchungen vorgenommen werden können, muss auch auf Sofortüberweisung im Projekt der Testmodus aktiviert werden!';
$_['help_test']			= 'Hier kann eine Überweisung simuliert werden.<br /><strong>Hinweis</strong>: es werden KEINE realen Daten übertragen<br />Im Testmodus 1 wird ein dt. Bankkonto, mit Testmodus 2 ein generisches Konto für Kunden aus AT, BE, CH, NL, UK verwendet (getestet werden kann mit beiden)';
$_['help_newProject']	= 'Mit diesem Formular kann sowohl ein neuer Account als auch nur ein neues Projekt bei Sofortüberweisung erstellt werden (dann muss bereits ein Account vorhanden sein).<br />Bitte nachstehend alle Felder gewissenhaft ausfüllen und abschließend auf den Button &quot;Anwenden&quot; klicken.<br /><strong>Hinweise:</strong><ol><li>Auf Sofortüberweisung können später noch weitere Daten angegeben bzw. geändert werden</li><li>Auf Sofortüberweisung wird eine Zusammenfassung aller hier erfassten Daten angezeigt (dazu am unteren Bildschirmrand auf &quot;Kundendaten&quot; klicken</li><li>Existiert bereits ein Kundenkonto und soll nur ein neues Projekt angelegt werden, MUSS auf der Webseite von Sofortüberweisung die Kundennummer und das Passwort angegeben werden!</li><li>Um die hier angegebenen Daten sowohl auf Sofortüberweisung als auch hier zu speichern, muss der Link auf Sofortüberweisung &quot;Zurück zum Shop&quot; angeklickt werden</li><li>Wurde bereits hier ein Projekt gespeichert, wird es wenn nach der Rückkehr auf &quot;Sichern&quot; oder &quot;Anwenden&quot; geklickt wird, überschrieben.</li></ol>';
$_['help_newProject_error']	= 'Achtung! Das System verwendet keine Datenverschlüsselung. Um aber die nachstehenden Angaben an Sofortüberweisung übermitteln zu können muss diese aktiviert werden, siehe <a href="%s">System -> Einstellungen -> Server</a>';
$_['help_debug']			= 'Mit dieser Einstellung werden erweiterte Meldungen in die Logdatei von Sofortüberweisung geschrieben.<br /><strong>Sollte nur verwendet werden wenn Fehler auftreten!</strong>';
$_['help_server_shift']		= 'Sollte der Server in einer anderen Zeitzone stehen, kann hier der Wert (in halben Stunden -/+ z.B.: 1.5) korrigiert werden.<br />Wird verwendet bei Logbuch';
$_['help_copy_data']		= 'Verwende für Projektdaten gleiche Angaben wie bei Kontodaten';
$_['help_password']			= 'Das Passwort muss mit dem Button erstellt/geändert werden';
$_['help_supportKey']		= 'Um Support für diese Installation zu erhalten, ist es wichtig einen gültigen Supportschlüssel zu besitzen. Bitte diesen hier angeben.';
$_['help_text']				= 'Hier kann die Anzeige des Bezahltextes definiert werden. Möglich ist&lt;ul&gt;&lt;li&gt;als Text&lt;/li&gt;&lt;li&gt;als Bild&lt;/li&gt;&lt;li&gt;Eigendefinition (siehe &quot;Menü Text&quot;)&lt;/li&gt;&lt;/ul&gt;Wenn das Textfeld ausgefüllt ist, hat es Vorrang gegenüber den 2 Anderen.<br />Zum Beispiel kann hier der HTML-Code für den Käuferschutz (wenn Qualifiziert) eingegeben werden.';
$_['help_customer_groups']	= 'Für welche Kundengruppe(n) soll diese Zahlart gelten?<br />Mehrfachauswahl ist möglich';
$_['help_store']            = 'Für welches Geschäft soll das neue Projekt angelegt werden';
$_['help_protection']       = 'Für den Käuferschutz muss ein Bankkonto bei der Sofort Bank existieren, andernfalls wird diese Einstellung nicht berücksichtigt';

	// select lists
$_['entry_yes']			= 'Ja';
$_['entry_no']			= 'Nein';
$_['sel_sha1']			= 'SHA1 (Standard)';
$_['sel_sha256']		= 'SHA256';
$_['sel_sha512']		= 'SHA512';
$_['sel_md5']			= 'MD5';
$_['sel_mode1']			= 'Testmodus 1';
$_['sel_mode2']			= 'Testmodus 2';
$_['sel_select']		= '-- Bitte wählen --';
$_['sel_debug1']		= 'Ja, Logdatei';
$_['sel_debug2']		= 'Log &amp; Bildschirm';
$_['sel_company']		= 'Firma';
$_['sel_mister']		= 'Herr';
$_['sel_miss']			= 'Frau';
$_['sel_legal1']		= 'Keine Rechtsform / Natürliche Person';
$_['sel_legal2']		= 'Nicht aufgeführte Rechtsform';
$_['sel_legal3']		= 'Selbständig';
$_['sel_legal4']		= 'Aktiengesellschaft';
$_['sel_legal5']		= 'Aktiengesellschaft &amp; Co. KG';
$_['sel_legal6']		= 'AG &amp; Co. OHG';
$_['sel_legal7']		= 'Genossenschaft';
$_['sel_legal8']		= 'Gesellschaft mit beschränkter Haftung';
$_['sel_legal9']		= 'Gesellschaft mit beschränkter Haftung &amp; Co. KG';
$_['sel_legal10']		= 'Gesellschaft mit beschränkter Haftung &amp; Co. KEG';
$_['sel_legal11']		= 'Gesellschaft mit beschränkter Haftung &amp; Co. OHG';
$_['sel_legal12']		= 'Gesellschaft nach bürgerlichem Recht';
$_['sel_legal13']		= 'Kommanditerwerbsgesellschaft';
$_['sel_legal14']		= 'Kommanditgesellschaft';
$_['sel_legal15']		= 'Offene Erwerbsgesellschaft';
$_['sel_legal16']		= 'Offene Handelsgesellschaft';
$_['sel_legal17']		= 'Registrierte Genossenschaft mit beschränkter Haftung';
$_['sel_legal18']		= 'Eingetragene Genossenschaft';
$_['sel_legal19']		= 'Eingetragener Kaufmann';
$_['sel_legal20']		= 'Eingetragener Verein';
$_['sel_legal21']		= 'Limited';
$_['sel_legal22']		= 'Public limited company';
$_['sel_legal23']		= 'Société anonyme';
$_['sel_legal24']		= 'Société à responsabilité limitée';
$_['sel_legal25']		= 'Societas Europaea';
$_['sel_legal26']		= 'Unternehmergesellschaft (beschränkt)';
$_['sel_german']		= 'Deutsch';
$_['sel_english']		= 'Englisch';
$_['sel_italian']		= 'Italienisch';
$_['sel_french']		= 'Französisch';
$_['sel_text_as_self']	= 'Eigendefinition';
$_['sel_text_as_text']	= 'Als Text';
$_['sel_text_as_image']	= 'Als Bild';
$_['text_all']			= 'Alle';

// buttons
$_['button_help']		= 'Hilfe';
$_['button_apply']		= 'Anwenden';
$_['button_clear']		= 'Log löschen';
$_['btn_create_password']	= 'Passwort erstellen/ändern';
$_['btn_copy_data']		= 'Daten kopieren';

	// tabs
$_['tab_common']		= 'Allgemein';
$_['tab_advanced']		= 'Erweitert';
$_['tab_log']			= 'Log';
$_['tab_html']			= 'Text';
$_['tab_new_project']	= 'Neues Projekt';
$_['tab_stores']        = 'Stores';

	// legends
$_['leg_account_data']		= 'Kontodaten';
$_['leg_project_data']		= 'Projektdaten';
$_['leg_address']			= 'Adresse';
$_['leg_banking_details']	= 'Bankverbindung';
$_['leg_various_project_data']	= 'Diverse Projektdaten';
$_['leg_project_banking_details']	= 'Projekt-Bankverbindung';

	// messages (e.g. logfile)
$_['msg_successful_project_creation']	= 'Benutzer (%s - %s) hat erfolgreich ein neues Projekt (%s) angelegt.';
$_['msg_unsuccessful_project_creation']	= 'Benutzer (%s - %s) hat vergeblich versucht ein neues Projekt (%s) anzulegen.';
	// alerts (e.g. javascript)
$_['msg_all_fields_must_be_filled'] 	= 'Alle Felder müssen ausgefüllt werden!';
$_['msg_submit_form']					= 'Alle Angaben richtig - Daten übermitteln?';
	// other
$_['msg_back_from_directebanking']		= 'Neue Projektdaten eingetragen, wenn sie gespeichert werden sollen bitte jetzt auf Sichern oder Anwenden klicken';
$_['msg_test_mode_on']					= 'Hinweis: aktuell ist der Testmodus eingeschaltet! Für Livetransaktionen bitte ausschalten.';

// Error
	// common
$_['error_permission']		= 'Warnung: zuwenig Rechte für diese Aktion!';
	// specific
$_['error_fields']			= 'Einige Angaben fehlen - bitte Felder im Formular überprüfen!';
$_['error_custId']			= 'Keine Kunden-ID angegeben!';
$_['error_projId']			= 'Keine Projekt-ID angegeben!';
$_['error_projectPassword']	= 'Projektpasswort muss vergeben werden!';
$_['error_notifyPassword']	= 'Benachrichtigungspasswort muss vergeben werden!';