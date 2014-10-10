<?php
// Heading
$_['heading_title']           = 'Land';

// Text
$_['text_success']            = 'Land erfolgreich geändert!';

// Column
$_['column_name']             = 'Bezeichnung';
$_['column_iso_code_2']       = 'ISO Code (639-1)';
$_['column_iso_code_3']       = 'ISO Code (639-2)';
$_['column_action']           = 'Aktion';

// Entry
$_['entry_name']              = 'Bezeichnung:';
$_['entry_iso_code_2']        = 'ISO Code:<span class="help">Offizieller, 2-stelliger ISO 639-1 Code<br />Mehr dazu <a onclick="window.open(\'http://en.wikipedia.org/wiki/ISO_3166-1\')">hier</a></span>';
$_['entry_iso_code_3']        = 'ISO Code:<span class="help">Offizieller, 3-stelliger ISO 639-2 Code<br />Mehr dazu <a onclick="window.open(\'http://en.wikipedia.org/wiki/ISO_3166-1\')">hier</a></span>';
$_['entry_address_format']    = 'Adressenformat<span class="help">Verwendung z.B. bei Emails. <br />Jeder Begriff (Datenbankfeldname) muss von einer geschwungenen Klammer begrenzt sein.<br /> Beispiel: {firstname} {lastname}<br />
Vorname = {firstname}<br />
Familienname = {lastname}<br />
Firma = {company}<br />
PLZ = {postcode}<br />
Stadt = {city}<br />
Strasse = {address_1}<br />
Adresszusatz = {address_2}<br />
Zone = {zone}<br />
Zonencode = {zone_code}<br />
Land = {country}</span>';
$_['entry_postcode_required'] = 'Postleitzahl benötigt';
$_['entry_status']            = 'Status';

// Error
$_['error_permission']        = 'Keine Rechte für diese Aktion!';
$_['error_name']              = 'Ländername muss zwischen 3 und 128 Buchstaben lang sein!';
$_['error_default']           = 'Land kann nicht gelöscht werden da es aktuell als Standard definiert ist!';
$_['error_store']             = 'Land kann nicht gelöscht werden da es aktuell %s Shops zugeordnet ist!';
$_['error_address']           = 'Land kann nicht gelöscht werden weil noch %s Adressbucheinträge zugeordnet sind!';
$_['error_affiliate']         = 'Land kann nicht gelöscht werden, da es %s Partnern zugeordnet ist!';
$_['error_zone']              = 'Land sind noch %s Zonen zugeordnet. Es kann daher nicht gelöscht werden!';
$_['error_zone_to_geo_zone']  = 'Land kann nicht gelöscht werden, da es %s Geo-Zonen zugeordnet ist!';