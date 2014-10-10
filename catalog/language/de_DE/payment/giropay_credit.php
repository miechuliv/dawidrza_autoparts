<?php

/**
 * giropay.de payment gateway for Opencart by Extensa Web Development
 *
 * Copyright © 2012-2013 Extensa Web Development Ltd. All Rights Reserved.
 * This file may not be redistributed in whole or significant part.
 * This copyright notice MUST APPEAR in all copies of the script!
 *
 * @author 		Extensa Web Development Ltd. (www.extensadev.com)
 * @copyright	Copyright (c) 2012-2013, Extensa Web Development Ltd.
 * @package 	giropay.de payment gateway
 * @link		http://www.opencart.com/index.php?route=extension/extension/info&extension_id=8683
 */

// Text
$_['text_title']    = 'Kreditkarte<br /><span class="help">Nach Auswahl des Kreditkartentyps, werden Sie im nächsten Schritt aufgefordert, Ihre Kreditkartennummer und Kartenprüfnummer einzugeben. Wenn Ihre Kreditkarte für 3D-Secure (Verified by Visa bzw. MasterCard SecureCode) freigeschaltet ist, werden Sie während des Bezahlprozesses zur Sicherheitsabfrage Ihrer Hausbank weitergeleitet.</span>';
$_['text_order']    = 'Auftragsnummer:';
$_['text_wait']     = 'Bitte warten!';

// Entry
$_['entry_cc_type'] = 'Kreditkarten-Typ:<br/><span class="help">Bitte wählen Sie hier Ihren Kreditkartentyp aus. Die Eingabe der Kreditkartendaten ist durch eine 128-bit SSL Verschlüsselung gesichert.</span>';

// Error
$_['error_order']   = 'Der Orden existiert nicht!';
$_['error_http']    = 'Fehler bei http-Aufruf.';
?>