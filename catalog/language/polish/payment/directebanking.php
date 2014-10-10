<?php
/**
 * @version		$Id: directebanking.php 3097 2013-03-01 15:13:53Z mic $
 * @package		Directebanking - Language User English
 * @copyright	(C) 2011 mic [ http://osworx.net ]. All Rights Reserved.
 * @author		mic - http://osworx.net
 * @license		OSWorX Commercial License http://osworx.net
 */

// Text
$_['text_title']			= 'Sofortbanking';
$_['text_title_w_image']	= '<a onclick="window.open(\'https://www.sofort.com\');"><img src="' . HTTPS_SERVER . 'catalog/view/theme/default/image/payment/sofortbanking.png" alt="Sofortbanking" title="Sofortbanking" style="border: 1px solid #EEEEEE;" height="25" width="94" /></a>';

$_['text_reason_1']			= 'Order # %s';
$_['text_reason_2']			= 'from %s';
$_['text_testOrder']		= '!! Test order to check correct transaction with sofortbanking !!';
$_['text_failed']			= 'Transaction failed';
$_['text_failed_message']	= '<p>Unfortunately there are troubles with the payment at Sofort.com.</p><p><strong>Error:</strong> Transaction not successful.</p><p>Before you try it again, please <a href="%s">contact us</a> with the order details.</p>';
$_['text_cancel']			= 'Transaction cancelled';
$_['text_cancel_message']	= '<p>Current transaction was cancelled by yourself. We regret this and would like to know the reason and please you to <a href="%s">contact us</a>.</p>';
$_['text_test_reason_1']	= 'Test order # %s';

// log texts
$_['text_log_testorder']	= 'TEST ORDER';
$_['text_log_order']		= 'Order';
$_['text_log_new_order']	= '[%s] Order: %s / New %s initialized / Product: %s / User: %s / Amount: %s';
$_['text_log_return_success']	= '[%s] Order: %s / User returned successful from Sofort.com';
$_['text_log_valid']		= '[%s] Order: %s / Transaction is valid';
$_['text_log_hash_dif']		= '[%s] Order: %s / Hash values different, see more about this at Sofort.com - reports';
$_['text_log_security_invalid']	= '[%s] Order: %s / ATTENTION!! Either security check not okay or no order id!!';
$_['text_log_return_cancel']	= '[%s] Order: %s / Transaction cancelled by customer';
$_['text_log_unsupported_currency'] = 'ATTENTION: not supported CURRENCY [%s] changed in [EUR], amount is automatically converted from [%s] into [%s]';

// log texts at Sofortbanking
$_['text_log_hash_okay']	= 'MSG OC: [%s] hash values are equal - transaction valid!';
$_['text_log_hash_notokay']	= 'ERROR OC: [%s] hash values are different - tranaction not valid!';

// messages
$_['msg_testmode_on']		= 'ATTENTION!!<br />Test mode is on!';