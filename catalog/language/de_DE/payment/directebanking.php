<?php
/**
 * @version		$Id: directebanking.php 3097 2013-03-01 15:13:53Z mic $
 * @package		Directebanking - Language User
 * @copyright	(C) 2011 mic [ http://osworx.net ]. All Rights Reserved.
 * @author		mic - http://osworx.net
 * @license		OSWorX Commercial License http://osworx.net
 */

// Text
$_['text_title']			= 'Sofortüberweisung';
$_['text_title_w_image']	= '<a onclick="window.open(\'https://www.sofort.com\');"><img src="' . HTTPS_SERVER . 'catalog/view/theme/default/image/payment/sofortueberweisung.png" alt="Sofortüberweisung.com" title="Sofortüberweisung.com" style="border: 1px solid #EEEEEE;" height="25" width="94" /></a>';

$_['text_reason_1']			= 'Bestellung # %s';
$_['text_reason_2']			= 'vom %s';
$_['text_testOrder']		= '!! Testauftrag zum Testen von Sofortüberweisung.de !!';
$_['text_failed']			= 'Transaktion fehlgeschlagen';
$_['text_failed_message']	= '<p>Leider gab es in der Zahlungsabwicklung mit Sofortüberweisung.de Probleme.</p><p><strong>Fehler:</strong> Transaktion nicht erfolgreich.</p><p>Vor einem neuerlichen Bestellvorgang bitte uns mit den Bestelldetails <a href="%s">kontaktieren</a>.</p>';
$_['text_cancel']			= 'Transaktionsabbruch';
$_['text_cancel_message']	= '<p>Aktuelle Transaktion wurde auf eigenen Wunsch abgebrochen. Wir bedauern dies, würden gerne den Grund dafür erfahren und bitten um <a href="%s">Kontaktaufnahme</a>.</p>';
$_['text_test_reason_1']	= 'Testbestellung # %s';

// log texts
$_['text_log_testorder']	= 'TESTAUFTRAG';
$_['text_log_order']		= 'Auftrag';
$_['text_log_new_order']	= '[%s] Auftrag: %s / Neuer %s initialisiert / Produkt: %s / Kunde: %s / Betrag: %s';
$_['text_log_return_success']	= '[%s] Auftrag: %s / Kunde erfolgreich zurück von Sofortüberweisung.de';
$_['text_log_valid']		= '[%s] Auftrag: %s / Transaktion ist gültig';
$_['text_log_hash_dif']		= '[%s] Auftrag: %s / Prüfsummen unterschiedlich, mehr siehe Sofortüberweisung - Benachrichtigungen';
$_['text_log_security_invalid']	= '[%s] Auftrag: %s / ACHTUNG!! Sicherheitscheck nicht okay oder keine Auftragsnummer!!';
$_['text_log_return_cancel']	= '[%s] Auftrag: %s / Transaktion durch Benutzer abgebrochen';
$_['text_log_unsupported_currency'] = 'ACHTUNG: nicht unterstützte Währung [%s] in [EUR] geändert, Betrag wurde automatisch von [%s] in [%s] angepasst';

// log texts at directebanking
$_['text_log_hash_okay']	= 'MSG OC: [%s] Hashwerte sind gleich - Transaktion gültig!';
$_['text_log_hash_notokay']	= 'FEHLER OC: [%s] Hashwerte sind verschieden - Transaltion ungültig!';

// messages
$_['msg_testmode_on']		= 'ACHTUNG!!<br />Testmodus ist an!';