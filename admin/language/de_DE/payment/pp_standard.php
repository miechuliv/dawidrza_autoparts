<?php
/**
 * german translation by http://osworx.net
 */

// Heading
$_['heading_title']                     = 'PayPal Standard';

// Text
$_['text_payment']                      = 'Zahlung';
$_['text_success']                      = 'Zahlungsart erfolgreich geändert!';
$_['text_pp_standard']                  = '<a href="https://www.paypal.com/uk/mrb/pal=W9TBB5DTD6QJW" target="_blank"><img src="view/image/payment/paypal.png" alt="PayPal" title="PayPal" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_authorization']                = 'Genehmigung';
$_['text_sale']                         = 'Verkauf';

// Entry
$_['entry_email']                       = 'Email<span class="help">Hier entweder die Emailadresse der Sandbox oder die wirkliche Verkäuferemail eintragen.<br />Siehe dazu auch Einstellung "Testmodus"</span>';
$_['entry_test']                        = 'Testmodus<span class="help">Je nach gewählter Emailadresse hier den richtigen Modus aktivieren<br />Im Testmodus werden Transaktionen nur simuliert, dazu jedoch ist ein eigener Account bei PayPal notwendig</span>';
$_['entry_transaction']                 = 'Transaktionsmethode';
$_['entry_pdt_token']                   = 'PDT Token<span class="help">PDT Token wird für zusätzliche Sicherheit und Zuverlässigkeit verwendet. Informationen zum Aktivieren von PDT sind <a href="https://cms.paypal.com/us/cgi-bin/?&cmd=_render-content&content_ID=developer/howto_html_paymentdatatransfer" alt="">hier</a></span>';
$_['entry_debug']                       = '´Fehlermodus<span class="help">Zeichnet zusätzliche Informationen im Systemprotokoll auf.</span>';
$_['entry_total']        = 'Summe<span class="help">Mindestgesamtsumme im Warenkorb damit diese Zahlungsart verfügbar ist.</span>';
$_['entry_canceled_reversal_status']    = 'Auftragsstatus Erstattung Abgebrochen<span class="help">Eine Erstattung wurde abgebrochen. Dies passiert, wenn der Händler eine Reklamation ablehnt und die Gutschrift zurückgebucht wird.</span>';
$_['entry_completed_status']            = 'Status Fertig';
$_['entry_denied_status']               = 'Auftragsstatus Abgelehnt<span class="help">Händler (Webshop) hat die Zahlung abgelehnt. Dies passiert nur, wenn die Zahlung aufgrund der folgenden Gründe ausstehend war.</span>';
$_['entry_expired_status']              = 'Status abgelaufen';
$_['entry_failed_status']               = 'Auftragsstatus Fehlgeschlagen<span class="help">Die Zahlung ist fehlgeschlagen. Das passiert nur, wenn die Zahlung vom Kundenkonto abgebucht wurde.</span>';
$_['entry_pending_status']              = 'Auftragsstatus Ausstehend<span class="help">Die Zahlung ist ausstehend; Die pending_reason Variable enthält weitere Informationen. Wenn der Auftragsstatus auf Vollständig, Fehlgeschlagen oder Abgelehnt wechselt, wird eine weitere IPN Nachricht erzeugt.</span>';
$_['entry_processed_status']            = 'Status bearbeitet';
$_['entry_refunded_status']             = 'Auftragsstatus Erstattet<span class="help">Händler zahlt den Betrag zurück.</span>';
$_['entry_reversed_status']             = 'Auftragsstatus Gutschrift<span class="help">Eine Zahlung wurde aufgrund einer Rückbuchung gutgeschrieben, sie wird vom eigenen Konto dem Kunden gutgeschrieben. Der Grund wird in der reason_code Variable angegeben.</span>';
$_['entry_voided_status']               = 'Status ungültig';
$_['entry_geo_zone']                    = 'Geozone';
$_['entry_status']                      = 'Status';
$_['entry_sort_order']                  = 'Reihenfolge';

// Error
$_['error_permission']                  = 'Keine Rechte für diese Aktion!';
$_['error_email']                       = 'Email erforderlich!';