<?php
/**
 * @version		$Id: tax_rate.php 3159 2013-03-16 15:32:50Z mic $
 * @package		Translation German
 * @author		mic - http://osworx.net
 * @copyright	2013 OSWorX - http://osworx.net
 * @license		GPL - www.gnu.org/copyleft/gpl.html
 */

// Heading
$_['heading_title']        = 'Steuersatz';

// Text
$_['text_percent']         = 'Prozent';
$_['text_amount']          = 'Fixbetrag';
$_['text_success']         = 'Steuersatz wurde erfolgreich bearbeitet!';

// Column
$_['column_name']          = 'Name';
$_['column_rate']          = 'Betrag';
$_['column_type']          = 'Berechnungsart';
$_['column_geo_zone']      = 'Geozone';
$_['column_date_added']    = 'Erstellt';
$_['column_date_modified'] = 'Geändert';
$_['column_action']        = 'Aktion';

// Entry
$_['entry_name']           = 'Name<span class="help">Begriff wie er im Warenkorb, Rechnung usw. angezeigt wird</span>';
$_['entry_rate']           = 'Betrag<span class="help">Abhängig von der Berechnungsart hier Wert angeben (nur Zahlen &amp; Punkt)</span>';
$_['entry_type']           = 'Berechnungsart<span class="help">Möglich sind Fixbetrag oder Prozent, jeweils gerechnet vom Nettowarenwert</span>';
$_['entry_customer_group'] = 'Kundengruppe<span class="help">Für welche Kundengruppe(n) soll dieser Steuersatz gelten</span>';
$_['entry_geo_zone']       = 'Geozone<span class="help">Ist hier die passende Zone nicht vorhanden, muss sie zuerst im Menü Geozonen (System > Lokale Einst.) angelegt werden!</span>';

// Error
$_['error_permission']     = 'Hinweis: keine Rechte für diese Aktion!';
$_['error_tax_rule']       = 'Achtung: Betrag kann nicht gelöscht werden da er aktuell %s Steuerklassen zugeordnet ist!';
$_['error_name']           = 'Name muss zwischen 3 und 32 Zeichen lang sein!';
$_['error_rate']           = 'Betrag erforderlich!';