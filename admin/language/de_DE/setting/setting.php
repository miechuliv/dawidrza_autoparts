<?php
/**
 * german translation by http://osworx.net
 */

// mic added
$_['entry_delivery_prefix']		= 'Lieferschein Vorzeichen<span class="help">Optionales Vorzeichen für Lieferscheine<br />Beispiel: LS-Jahr-</span>';

// Heading
$_['heading_title']               = 'Einstellungen';

// Text
$_['text_success']                = 'Einstellungen erfolgreich geändert!';
$_['text_items']                 = 'Einträge';
$_['text_product']               = 'Produkte';
$_['text_voucher']               = 'Gutscheine';
$_['text_tax']                   = 'Steuern';
$_['text_account']               = 'Konto';
$_['text_checkout']              = 'Warenkorb';
$_['text_stock']                 = 'Lager';
$_['text_affiliate']             = 'Partner';
$_['text_return']                = 'Retouren';
$_['text_image_manager']          = 'Bildverwaltung';
$_['text_browse']				= 'Einfügen';
$_['text_clear']				= 'Löschen';
$_['text_shipping']				= 'Zustelladresse';
$_['text_payment']				= 'Rechnungsadresse';
$_['text_mail']                   = 'php.Mail';
$_['text_smtp']                   = 'SMTP';

// Entry
$_['entry_name']                  = 'Geschäftsname';
$_['entry_owner']                 = 'Geschäftsinhaber';
$_['entry_address']               = 'Adresse';
$_['entry_email']                 = 'Email';
$_['entry_telephone']             = 'Telefon';
$_['entry_fax']                   = 'Fax';
$_['entry_title']                 = 'Titel';
$_['entry_meta_description']      = 'Metatag Beschreibung';
$_['entry_layout']                = 'Standardlayout';
$_['entry_template']              = 'Vorlage';
$_['entry_country']               = 'Land<span class="help">Land in dem dieses Geschäft seinen Stammsitzhat.<br />Wird u.a. zur Steuerberechnung verwendet (siehe auch Reiter `Optionen > Steuern`)</span>';
$_['entry_zone']                  = 'Bundesland';
$_['entry_language']              = 'Benutzersprache:<span class="help">Sind mehrere Sprachen installiert, kann der Besucher mit dem Sprachauswahlfeld wählen</span>';
$_['entry_admin_language']        = 'Adminsprache';
$_['entry_currency']              = 'Währung<span class="help">Standardwährung ändern. Bitte Browsercache und Cookies löschen um die Änderungen zu sehen.</span>';
$_['entry_currency_auto']         = 'Währungskurse aktualisieren<span class="help">Sollen die Währungskurse täglich 1x automatisch aktualisiert werden.<br />Ist nur eine Währung angelegt, dann deaktivieren!</span>';
$_['entry_length_class']          = 'Vorgabe Länge<span class="help">Standard bei Neuanlage, kann beim Produkt selbst geändert werden</span>';
$_['entry_weight_class']          = 'Vorgabe Gewicht<span class="help">Standard bei Neuanlage, kann beim Produkt selbst geändert werden</span>';
$_['entry_catalog_limit']         = 'Vorgabe Anzahl Produkte pro Seite (Shop)<span class="help">Wieviele Datensätze sollen Besuchern bei Listenansichten pro Seite angezeigt werden (Produkte, Kategorien, usw.)<br />Besucher können selber auswählen</span>';
$_['entry_admin_limit']           = 'Vorgabe Anzahl Produkte pro Seite (Admin)<span class="help">Wieviele Datensätze sollen Admins bei Listenansichten pro Seite angezeigt werden (Aufträge, Kunden, usw.)</span>';
$_['entry_product_count']         = 'Kategoriezähler<span class="help">Zeige Anzahl der Produkte innerhalb Unterkategorien in Menüauswahl.<br /><b>Achtung! Bei vielen Unterkategorien kann das zu einer Serverüberlastung führen!!</b></span>';
$_['entry_review']                = 'Beurteilungen<span class="help">Sollen vorhandene Beurteilungen angezeigt werden sowie Neue möglich sein</span>';
$_['entry_download']              = 'Downloads erlauben';
$_['entry_voucher_min']          = 'Gutschein Minimum<span class="help">Mindestbetrag für einen Gutscheinkauf</span>';
$_['entry_voucher_max']          = 'Gutschein Maximum<span class="help">Maximalbetrag für einen Gutscheinkauf</span>';
$_['entry_tax']                   = 'Zeige Preise inkl. Steuer<span class="help">Mit `Ja` werden alle Preise inkl. Steuer angezeigt, ansonsten Nettopreise</span>';
$_['entry_vat']                   = 'Steuernummer prüfen<span class="help">Überprüfe Steuernummern auf Gültigkeit, siehe <a onclick="window.open(\'http://ec.europa.eu\');" title="EU-Kommission">EU-Kommission</a></span>';
$_['entry_tax_default']         = 'Verwende Geschäftsadresse<span class="help">Falls Besucher nicht eingeloggt ist wird die Geschäftsadresse als Basis zur Steuerberechnung verwendet (entweder Versand- oder Rechnungsadresse)<br />Siehe auch Reiter `Lokales`</span>';
$_['entry_tax_customer']        = 'Verwende Kundenadresse<span class="help">Ist Kunde eingeloggt wird dessen Standardadresse als Basis der Steuerberechnung verwendet (entweder Versand- oder Rechnungsadresse)</span>';
$_['entry_customer_online']		= 'Kunden Online<span class="help">Anwesende Kunden aufzeichnen - Auswertung siehe Kundenberichte</span>';
$_['entry_customer_group']      = 'Kundengruppe<span class="help">Standardgruppe in welche neue Kunden eingeordnet werden</span>';
$_['entry_customer_group_display'] = 'Auswahl Kundengruppe<span class="help">Neuregistrierungen können zwischen Kundengruppen auswählen.</span>';
$_['entry_customer_price']      = 'Zeige Preise nur angemeldeten Besuchern<span class="help">Preise werden nur dann angezeigt, wenn der Besucher angemeldet ist</span>';
$_['entry_account']               = 'Text bei Registrierung<span class="help">Neue Kunden müssen die Bedingungen akzeptieren bevor sie sich registrieren können</span>';
$_['entry_cart_weight']           = 'Zeige Gewicht im Warenkorb';
$_['entry_guest_checkout']        = 'Direktbestellung<span class="help">Kunden sollen auch dann bezahlen können wenn sie nicht registriert sind.<br />Nicht möglich bei Downloadprodukten</span>';
$_['entry_checkout']              = 'Text vor Bezahlung<span class="help">Kunden müssen vor Bezahlung die Bedingungen akzeptieren</span>';
$_['entry_order_edit']              = 'Auftragsbearbeitung<span class="help">Anzahl der Tage eine Bearbeitung von Aufträgen möglich ist.<br />Notwendig da Preise und Konditionen geändert werden könnten und dann der Auftrag nicht mehr stimmt</span>';
$_['entry_invoice_prefix']        = 'Rechnungsnr. Vorzeichen<span class="help">Hier das Vorzeichen für die Rechnungsnummern angeben, z.B. RE/Jahr<br />Hinweis: es sind alle Zeichen erlaubt</span>';
$_['entry_order_status']          = 'Status Allgemein<span class="help">Standardvorgabe nach Auftragserteilung / Bestellabschluss</span>';
$_['entry_complete_status']       = 'Status Download/Gutschein<span class="help">Status welcher notwendig ist damit der Kunde auf einen Download oder Geschenksgutschein zugreifen kann</span>';
$_['entry_stock_display']         = 'Lagerstand<span class="help">Zeige Lagerstand auf der Produktseite</span>';
$_['entry_stock_warning']         = 'Kein Lagerstand<span class="help">Zeige eine Nachricht wenn das Produkt nicht auf Lager ist</span>';
$_['entry_stock_checkout']        = 'Ohne Lagerstand bezahlen<span class="help">Kunden können auch dann bezahlen wenn kein Lagerstand</span>';
$_['entry_stock_status']          = 'Status negativer Lagerstand<span class="help">Angezeigter Status wenn kein Lagerstand vorhanden ist</span>';
$_['entry_affiliate']             = 'Text bei Partneranfragen<br /><span class="help">Mögliche Partner müssen diese Bedingungen akzeptieren bevor ein neues Konto erstellt werden kann.</span>';
$_['entry_commission']            = 'Partnerprovision<span class="help">Prozentsatz den Partner für jede Vermittlung bekommen</span>';
$_['entry_return']                  = 'Retouren<span class="help">Kunden müssen diese Bedingungen akzeptieren bevor Retouren möglich sind.<br /><span style="color:red;">Achtung! In der EU sind Retouren grundsätzlich ohne Einschränkungen möglich! Es muss keinen Bedingungen zugestimmt werden</span></span>';
$_['entry_return_status']         = 'Status Retouren<span class="help">Standardvorgabe bei neuen Retourenanfragen</span>';
$_['entry_logo']                  = 'Shoplogo<span class="help">Ändern sich die Masse, muss auch die Vorlage dementsprechend manuell angepasst werden!</span>';
$_['entry_icon']                  = 'Icon<span class="help">Bild sollte im Format PNG vorliegen und 16x16 Pixel nicht übersteigen</span>';
$_['entry_image_category']        = 'Größe Kategoriebild';
$_['entry_image_thumb']           = 'Größe Produktbild Vorschau';
$_['entry_image_popup']           = 'Größe Produktbild Popup';
$_['entry_image_product']         = 'Größe Bilder Produktliste';
$_['entry_image_additional']      = 'Größe Bilder weitere Produkte';
$_['entry_image_related']         = 'Größe Bilder ähnliche Produkte';
$_['entry_image_compare']         = 'Größe Bild Vergleich';
$_['entry_image_wishlist']        = 'Größe Bild Wunschzettel';
$_['entry_image_cart']            = 'Größe Bild Einkaufswagen';
$_['entry_ftp_host']               = 'FTP Host';
$_['entry_ftp_port']               = 'FTP Port';
$_['entry_ftp_username']           = 'FTP Benutzername';
$_['entry_ftp_password']           = 'FTP Passwort';
$_['entry_ftp_root']               = 'FTP Root<span class="help">Das Basisverzeichnis der Shopinstallation</span>';
$_['entry_ftp_status']             = 'FTP ist aktiv';

$_['entry_mail_protocol']         = 'Emailprotokoll<span class="help">\'SMTP\' nur dann wählen, wenn der Provider die PHP Mail Funktion nicht aktiviert hat oder es einen anderen bestimmten Grund dafür gibt<br />Siehe auch weitere Einstellungen dazu unten.</span>';
$_['entry_mail_parameter']        = 'Mailparameter<span class="help">Wenn \'php.Mail\' gewählt wurde, können hier zusätzliche Mailparameter hinzugefügt werden (z.B. "-femail@storeaddress.com")</span>';
$_['entry_smtp_host']             = 'SMTP Host';
$_['entry_smtp_username']         = 'SMTP Benutzername';
$_['entry_smtp_password']         = 'SMTP Passwort';
$_['entry_smtp_port']             = 'SMTP Port';
$_['entry_smtp_timeout']          = 'SMTP Timeout';
$_['entry_account_mail']          = 'Nachricht Neuregistrierung<span class="help">Soll bei einer Neuregistrierung eine Benachrichtigung per Email an Standardadresse erfolgen</span>';
$_['entry_alert_mail']            = 'Nachricht Neubestellung<span class="help">Soll bei neuen Bestellungen eine Benachrichtigung per Email an Standardadresse erfolgen</span>';
$_['entry_alert_emails']          = 'Weitere Nachrichtenempfänger<span class="help">Emailadressen, an welche zusätzlich zur Standardadresse Benachrichtigungen gesendet werden sollen.<br />Mehrere mit Komma trennen</span>';
$_['entry_fraud_detection']     = 'Verwende MaxMind Betrugsabwehrsystem<span class="help">MaxMind ist ein Anbieter von Systemen zur Betrugsabwehr. Falls noch keine gültiger Lizenschlüssel vorhanden ist, kann <a href="http://www.maxmind.com" target="_blank"><u>hier</u></a> einer beantragt werden. Danach diesen Schlüssel hier eintragen.</span>';
$_['entry_fraud_key']           = 'MaxMind Lizenzschlüssel</span>';
$_['entry_fraud_score']         = 'MaxMind Risikowert<span class="help">Je höher dieser Wert, desto höher die Möglichkeit eines Betrugsversuches (Wert zwischen 0 - 100).</span>';
$_['entry_fraud_status']        = 'MaxMind Auftragsstatus<span class="help">Aufträge welche über dem Schwellenwert liegen werden dem nebenstehenden Status zugeordnet und nicht automatisch auf "Abgeschlossen" gesetzt.</span>';
$_['entry_secure']              = 'SSL<span class="help">SSL verwenden<br />Wurde ein Zertifikat am Server installiert (= Voraussetzung) und soll es verwendet werden, dann hier bestätigen</span>';
$_['entry_shared']              = 'Verwende Shared Sessions<span class="help">Damit wird versucht das Session Cookie zwischen mehreren Stores zu teilen um den Warenkorb auf verschiedenen Domains (jedoch gleicher Server!) zu verwenden</span>';
$_['entry_robots']                 = 'Robots<span class="help">Eine Liste alle Webcrawler welche keine `Shared Session` verwenden dürfen.<br />Pro Eintrag eine Zeile verwenden</span>';
$_['entry_seo_url']               = 'Benutze SEO URLs<span class="help">Um SEO URLs zu verwenden, muss bei *nix_Servern das Apache module mod_rewrite installiert sein und die htaccess.txt in .htaccess umbenannt werden.<br />Für MS-IIS gibt es andere Richtlinien<br />Siehe <a href="http://de.wikipedia.org/wiki/Mod_rewrite" target="_blank">mod_rewrite bei Wikipedia</a></span>';
$_['entry_file_extension_allowed'] = 'Erlaubte Dateiendungen<span class="help">Hier alle erlaubten Dateiendungen welche für Uploads möglich sein sollen angeben.<br />Pro Eintrag eine Zeile verwenden</span>';
$_['entry_file_mime_allowed']      = 'Erlaubte Dateiarten<span class="help">Hier alle erlaubten Dateiarten angeben welche für einen Upload erlaubt sind.<br />Pro Eintrag eine Zeile verwenden</span>';
$_['entry_maintenance']           = 'Wartungsmodus<span class="help">Zugang zum Shopfrontend nur für Admins möglich, Besucher sehen eine Nachricht zur Wartung</span>';
$_['entry_password']               = 'Erlaube Passworterinnerung<span class="help">Ermöglicht es Admins für das Backend sich ein Email zu schicken falls das Psswort vergessen wurde.<br />Bei Einbruchsversuchen schaltet das System automatisch diese Möglichkeit ab!</span>';

$_['entry_encryption']            = 'Geheimschlüssel<span class="help">Bitte einen beliebigen, nach Möglichkeit keinen zu einfachen Schlüssel eingeben.<br />Verwendung bei diversen sensiblen Daten</span>';
$_['entry_compression']           = 'Ausgabe Kompressionswert<span class="help">GZIP für schnellere Browserausgabe. Wert zwischen 0 - 9<br />Nur verwenden wenn Serverseitig noch keine Kompression verwendet wird!</span>';
$_['entry_error_display']         = 'Systemnachrichten anzeigen<span class="help">Zeigt z.B. bei Programmfehlern diese am Bildschirm an.<br /><strong style="color:red;">NICHT empfohlen im Live-Betrieb da Sicherheitsrisiko!</strong><br />Siehe stattdessen `Systemnachrichten speichern`</span>';
$_['entry_error_log']             = 'Systemnachrichten speichern<span class="help">Systemnachrichten  in einer Datei speichern (Name siehe unten) welche unter `System > Systemnachrichten` angesehen werden können</span>';
$_['entry_error_filename']        = 'Dateiname für Systemnachrichten';
$_['entry_google_analytics']      = 'Google Analytics Code<span class="help">Nach dem Erstellen eines Webseitenprofils auf <a href="http://www.google.com/analytics/" target="_blank"><u>Google Analytics</u></a> den angezeigten Code kopieren und hier einfügen</span>';

// Error
$_['error_warning']               = 'Erforderliche Daten nicht angegeben, bitte Felder überprüfen.';
$_['error_permission']            = 'Keine Rechte für diese Aktion!';
$_['error_name']                  = 'Geschäftsname muss zwischen 3 und 32 Zeichen lang sein!';
$_['error_owner']                 = 'Name des Geschäftsinhabers muss zwischen 3 und 64 Zeichen lang sein!';
$_['error_address']               = 'Geschäftsadresse muss zwischen 10 und 128 Zeichen lang sein!';
$_['error_email']                 = 'Emailadresse ist nicht gültig!';
$_['error_telephone']             = 'Telefon muss zwischen 3 und 32 Zeichen lang sein!';
$_['error_title']                 = 'Titel muss zwischen 3 und 32 Zeichen lang sein';
$_['error_limit']                 = 'Limit erforderlich!';
$_['error_customer_group_display'] = 'Wenn diese Einstellung gewählt ist, muss eine Kundengruppe als Standard definiert werden!';
$_['error_voucher_min']          = 'Mindestbetrag Gutscheine erforderlich!';
$_['error_voucher_max']          = 'Maximalbetrag Gutscheine erforderlich!';
$_['error_image_thumb']           = 'Größe Produktbild Vorschaubilder erforderlich!';
$_['error_image_popup']           = 'Größe Produktbild Popup erforderlich!';
$_['error_image_product']         = 'Größe Bilder Produktliste erforderlich!';
$_['error_image_category']        = 'Größe Kategoriebilder erforderlich!';
$_['error_image_additional']      = 'Größe Bilder weitere Produkte erforderlich!';
$_['error_image_related']         = 'Größe Bilder ähnliche Produkte erforderlich!';
$_['error_image_compare']         = 'Bildgröße Vergleich ist ein Pflichtfeld!';
$_['error_image_wishlist']        = 'Bildgröße Wunschzettel ist ein Pflichtfeld!';
$_['error_image_cart']            = 'Größe Einkaufswagenbild erforderlich!';
$_['error_ftp_host']               = 'FTP Host erforderlich!';
$_['error_ftp_port']               = 'FTP Port erforderlich!';
$_['error_ftp_username']           = 'FTP Benutzername erforderlich!';
$_['error_ftp_password']           = 'FTP Passwort erforderlich!';
$_['error_error_filename']        = 'Name für Protokolldatei erforderlich!';
$_['error_encryption']             = 'Verschlüsselung muss zwischen 3 und 32 Zeichen lang sein!';