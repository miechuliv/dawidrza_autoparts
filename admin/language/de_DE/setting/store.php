<?php
/**
 * german translation by http://osworx.net
 */

// Heading
$_['heading_title']         = 'Shopverwaltung';

// Text
$_['text_success']          = 'Shop erfolgreich aktualisiert';
$_['text_items']            = 'Einträge';
$_['text_tax']              = 'Steuern';
$_['text_account']          = 'Konto';
$_['text_checkout']         = 'Warenkorb';
$_['text_stock']            = 'Lager';
$_['text_image_manager']    = 'Bildverwaltung';
$_['text_browse']			= 'Einfügen';
$_['text_clear']			= 'Löschen';
$_['text_shipping']			= 'Versandadresse';
$_['text_payment']			= 'Rechnungsadresse';

// Column
$_['column_name']			= 'Name';
$_['column_url']			= 'URL';
$_['column_action']			= 'Aktion';

// Entry
$_['entry_url']             = 'Shop-URL<span class="help">URL für diesen Shop, muss mit einem Slash \'/\' abschliessen.<br />Beispiel: http://www.meinedomain.com/path/</span>';
$_['entry_ssl']             = 'Verwende SSL<span class="help">Zertifikat muss schon am Server instaliert sein</span>';
$_['entry_name']            = 'Shopname';
$_['entry_owner']           = 'Geschäftsinhaber';
$_['entry_address']         = 'Adresse';
$_['entry_email']           = 'Email';
$_['entry_telephone']       = 'Telefon';
$_['entry_fax']             = 'Fax';
$_['entry_title']           = 'Name';
$_['entry_meta_description']= 'Metatag';
$_['entry_layout']          = 'Standardlayout';
$_['entry_template']        = 'Vorlage';
$_['entry_country']         = 'Land';
$_['entry_zone']            = 'Bundesland';
$_['entry_language']        = 'Sprache';
$_['entry_currency']        = 'Währung';
$_['entry_catalog_limit']   = 'Standardanzahl Produkte pro Seite (Shop)<span class="help">Legt fest, wie viele Datensätze in Listenansichten pro Seite angezeigt werden (Produkte, Kategorien, usw.)</span>';
$_['entry_tax']				= 'Preise mit Steuern anzeigen';
$_['entry_tax_default']		= 'Verwende Geschäftsadresse<span class="help">Falls Besucher nicht eingeloggt ist wird die Geschäftsadresse als Steuerberechnungsbasis verwendet (entweder für Versand- oder Rechnungsadresse</span>';
$_['entry_tax_customer']	= 'Verwende Kundenadresse<span class="help">Ist Kunde eingeloggt, verwende desses Standardadresse als Steuerberechnungbasis (entweder Versand- oder Rechnungsadresse</span>';
$_['entry_customer_group']    = 'Kundengruppe<span class="help">Standardgruppe in welche neue Kunden eingeordnet werden</span>';
$_['entry_customer_group_display'] = 'Kundengruppe(n)<span class="help">Zeige bei Neuregistrierung Auswahl für Kundengruppe.</span>';
$_['entry_customer_price']    = 'Zeige Preise nur angemeldeten Besuchern<span class="help">Preise werden nur dann angezeigt, wenn Besucher angemeldet sind</span>';
$_['entry_account']           = 'Text bei Registrierung<span class="help">Neue Kunden müssen diese Bedingungen akzeptieren bevor sie sich registrieren können</span>';
$_['entry_cart_weight']       = 'Zeige Gewicht im Warenkorb';
$_['entry_guest_checkout']    = 'Direktbestellung<span class="help">Sollen Kunden auch dann bezahlen dürfen wenn sie nicht angemeldet sind.<br />Gilt nicht für Downloadprodukte</span>';
$_['entry_checkout']          = 'Text bei Bezahlung:<span class="help">Kunden müssen vor der Bezahlung diese Bedingungen akzeptieren</span>';
$_['entry_order_status']      = 'Auftragsstatus<span class="help">Standardvorgabe nach Auftragserteilung / Bestellabschluss</span>';
$_['entry_stock_display']     = 'Lagerstand<span class="help">Zeige Lagerstand bei Produkten</span>';
$_['entry_stock_checkout']    = 'Ohne Lagerstand bezahlen<span class="help">Kunden können auch dann bezahlen wenn kein Lagerstand</span>';
$_['entry_logo']              = 'Shoplogo';
$_['entry_icon']              = 'Icon<span class="help">Das Icon sollte im Format PNG vorliegen und nicht größer als 16x16 Pixel sein.</span>';
$_['entry_image_category']    = 'Größe Bild Kategorie';
$_['entry_image_thumb']       = 'Größe Produktbild Vorschau';
$_['entry_image_popup']       = 'Größe Produktbild Popup';
$_['entry_image_product']     = 'Größe Bilder Produktliste';
$_['entry_image_additional']  = 'Größe Bilder weitere Produkte';
$_['entry_image_related']     = 'Größe Bilder ähnliche Produkte';
$_['entry_image_compare']     = 'Größe Bild Vergleich';
$_['entry_image_wishlist']    = 'Größe Bild Wunschzettel';
$_['entry_image_cart']        = 'Größe Bild Einkaufswagen';
$_['entry_secure']              = 'SSL<span class="help">Wenn eine SSL-Verbindung verwendet werden soll, hier aktivieren (Zertifikat muss zuvor am Server installiert sein!)</span>';

// Error
$_['error_warning']           = 'Erforderliche Daten nicht angegeben, bitte Felder überprüfen.';
$_['error_permission']        = 'Keine Rechte für diese Aktion!';
$_['error_name']              = 'Geschäftsname muss zwischen 3 und 32 Zeichenlang sein!';
$_['error_owner']             = 'Name des Geschäftsinhabers muss zwischen 3 und 64 Zeichen lang sein!';
$_['error_address']           = 'Shopadresse muss zwischen 10 und 256 Zeichen lang sein!';
$_['error_email']             = 'Emailadresse ist nicht gültig!';
$_['error_telephone']         = 'Telefon muss zwischen 3 und 32 Zeichen lang sein!';
$_['error_url']               = 'Shop-URL erforderlich!';
$_['error_title']             = 'Bezeichnung muss zwischen 3 und 32 Zeichen lang sein!';
$_['error_limit']             = 'Limit erforderlich!';
$_['error_customer_group_display'] = 'Es muss eine Kundengruppe als Standard definiert sein wenn diese Einstellung verwendet wird!';
$_['error_image_thumb']       = 'Größe Produktbild Vorschaubilder erforderlich!';
$_['error_image_popup']       = 'Größe Produktbild Popup erforderlich!';
$_['error_image_product']     = 'Größe Bilder Produktliste erforderlich!';
$_['error_image_category']    = 'Größe Kategoriebilder erforderlich!';
$_['error_image_additional']  = 'Größe Bilder weitere Produkte erforderlich!';
$_['error_image_related']     = 'Größe Bilder ähnliche Produkte erforderlich!';
$_['error_image_compare']     = 'Bildgröße Vergleich ist ein Pflichtfeld!';
$_['error_image_wishlist']    = 'Bildgröße Wunschzettel ist ein Pflichtfeld!';
$_['error_image_cart']        = 'Größe Einkaufswagenbild erforderlich!';
$_['error_default']           = 'Der Standardshop kann nicht gelöscht werden!';
$_['error_store']             = 'Dieser Shop kann nicht gelöscht werden da aktuell %s Aufträge damit verbunden sind!';