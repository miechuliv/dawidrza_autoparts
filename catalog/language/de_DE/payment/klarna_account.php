<?php
/**
 * german translation by http://osworx.net
 */

// Text
$_['text_title']           = 'Klarna';
$_['text_pay_month']       = 'Klarna Zahlung von %s / Monat <span id="klarna_account_toc_link"></span><script text="javascript">$.getScript(\'http://cdn.klarna.com/public/kitt/toc/v1.0/js/klarna.terms.min.js\', function(){ var terms = new Klarna.Terms.Account({ el: \'klarna_account_toc_link\', eid: \'%s\',   country: \'%s\'});})</script>';
$_['text_information']     = 'Klarna Kontoinformation';
$_['text_additional']      = 'Klarna benötigt weitere Infos bevor die Bestellung bearbeitet werden kann.';
$_['text_wait']            = 'Bitte warten ..';
$_['text_male']            = 'Mann';
$_['text_female']          = 'Frau';
$_['text_year']            = 'Jahr';
$_['text_month']           = 'Monat';
$_['text_day']             = 'Tag';
$_['text_payment_option']  = 'Zahlungsoptionen';
$_['text_single_payment']  = 'Einmalzahlung';
$_['text_monthly_payment'] = '%s - %s pro Monat';
$_['text_comment']         = 'Klarna\'s Rechnungsnr.: %s\n%s/%s: %.4f';

// Entry
$_['entry_gender']         = 'Geschlecht';
$_['entry_pno']            = 'Sozialversicherungsnr.<span class="help">Bitte hier die Sozialversicherungsnummer angeben</span>';
$_['entry_dob']            = 'Geburtsdatum';
$_['entry_phone_no']       = 'Telefonnummer<span class="help">Bitte hier die Telefonnummer angeben</span>';
$_['entry_street']         = 'Straße<span class="help">Bitte beachten: die Lieferung kann nur an eine exixtierende Adresse erfolgen!</span>';
$_['entry_house_no']       = 'Hausnummer';
$_['entry_house_ext']      = 'Zusatz<span class="help">Falls die Hausnummer einen Zusatz hat (z.B. A, B, C, usw.)</span>';
$_['entry_company']        = 'Firmennummer<span class="help">Bitte hier die Firmennummer angeben</span>';

// Error
$_['error_deu_terms']      = 'Den Datenschutzbestimmungen muss zugestimmt werden';
$_['error_address_match']  = 'Liefer- und Zustelladresse müssen übereinstimmen wenn mit Klarna gezahlt wird';
$_['error_network']        = 'Leider gab es einen Netztwerkfehler - bitte nochmals probieren';