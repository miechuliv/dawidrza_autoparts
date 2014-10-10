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
$_['text_title']        = 'Lastschrift<br /><span class="help">Hiermit ermächtigen Sie uns , den Betrag von Ihrem Konto einzuziehen. Aus Datenschutzgründen werden Ihre Kontodaten nicht gespeichert. Bei jeder Bestellung ist deshalb eine Neueingabe erforderlich.</span>';
$_['text_order']        = 'Auftragsnummer:';
$_['text_wait']         = 'Bitte warten!';

// Entry
$_['entry_bankcode']    = 'Bankleitzahl:';
$_['entry_bankaccount'] = 'Kontonummer:';

// Error
$_['error_order']       = 'Der Orden existiert nicht!';
$_['error_http']        = 'Fehler bei http-Aufruf.';
$_['error_sb200']       = 'Bankverbindung ist valide.';
$_['error_sb291']       = 'Bankverbindung ist nicht valide.';
$_['error_sb292']       = 'Bankleitzahl ist ungültig.';
$_['error_sb293']       = 'Kontonummer darf maximal 10 Zeichen lang sein.';
$_['error_sb299']       = 'Ungültige Parameter.';
$_['error_sb100']       = 'Die angegebene Bank unterstützt giropay und ist verfügbar.';
$_['error_sb191']       = 'Ihr Projekt befindet sich im Test-Mode, bitte nutzen Sie 12345679 als Test-BLZ.';
$_['error_sb192']       = 'Falsche Parameter bei Abfrage des Banken-Status.';
$_['error_sb198']       = 'Die angegebene Bank unterstützt derzeit noch kein giropay.';
$_['error_sb199']       = 'Die angegebene Bank ist vorübergehend nicht erreichbar.';
$_['error_sb900']       = 'Ungültige Bankleitzahl.';
$_['error_sb999']       = 'Ungültige Zugangsdaten.';
?>