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

// Heading
$_['heading_title']             = 'giropay';

// Text
$_['text_payment']              = 'Zahlung';
$_['text_success']              = 'Erfolgreich: giropay erfolgreich geandert!';
$_['text_giropay']              = '<a onclick="window.open(\'http://www.giropay.de/\');"><img src="view/image/payment/giropay.png" alt="giropay.de" title="giropay.de" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_payonly']              = 'Pay Only: Es werden nur Kreditkarten-Daten vom Kunden abgefragt';
$_['text_payplus']              = 'Pay Plus: Zusätzlich wird die Rechnungsanschrift vom Kunden abgefragt';
$_['text_fullpay']              = 'Full Pay: Zusätzlich wird die Lieferanschrift vom Kunden abgefragt';

// Entry
$_['entry_merchant_id']         = 'Verkaufer-ID:';
$_['entry_project_id']          = 'Projekt-ID:';
$_['entry_secret']              = 'Projekt-Passwort:';
$_['entry_3d_secure']           = 'Akzeptiere Zahlungen ohne 3D-Secure:<br/><span class="help">Bitte beachten Sie die Ausfall-Risiken, wenn Sie diese Option aktivieren.</span>';
$_['entry_paymode']             = 'Bezahlmodus:';
$_['entry_cc_type']             = 'Akzeptierte Kreditkarten-Typen:';
$_['entry_description']         = 'Verwendungszweck:<br/><span class="help">bis zu 27 Zeichen</span>';
$_['entry_total']               = 'Summe:<br /><span class="help">Der Warenkorb muss diese Summe beinhalten, damit dieses Zahlungsverfahren verfugbar ist.</span>';
$_['entry_order_status']        = 'Status Fertig:';
$_['entry_geo_zone']            = 'Geo Zone:';
$_['entry_status']              = 'Status:';
$_['entry_sort_order']          = 'Reihenfolge:';

// Tab
$_['tab_giropay']               = 'giropay';
$_['tab_direct']                = 'Lastschrift';
$_['tab_credit']                = 'Kreditkarte';
$_['tab_paypal']                = 'PayPal';

// Error
$_['error_warning']             = 'Erforderliche Daten nicht angegeben! Bitte Felder überprüfen.';
$_['error_permission']          = 'Warnung: Sie haben keine Berechtigung, um giropay zu andern!';
$_['error_merchant_id']         = 'Verkaufer-ID erforderlich!';
$_['error_project_id']          = 'Projekt-ID erforderlich!';
$_['error_secret']              = 'Projekt-Passwort erforderlich!';
$_['error_cc_type']             = 'Akzeptierte Kreditkarten-Typen erforderlich!';
?>