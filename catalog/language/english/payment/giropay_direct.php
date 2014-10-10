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
$_['text_title']        = 'Direct Debit<br /><span class="help">You grant permission to collect the amount from your account. Please enter your bank account number and bank code. The security of your personal information is very important. Therefore we do not save your account information in our system. For every order a new one stating your bank account in our store is required.</span>';
$_['text_order']        = 'Order ID:';
$_['text_wait']         = 'Please wait!';

// Entry
$_['entry_bankcode']    = 'Bank code:';
$_['entry_bankaccount'] = 'Bank account number:';

// Error
$_['error_order']       = 'The Order not exists!';
$_['error_http']        = 'Error in http request.';
$_['error_sb200']       = 'Bank account is valid.';
$_['error_sb291']       = 'Bank account is not valid.';
$_['error_sb292']       = 'Bankcode is not valid.';
$_['error_sb293']       = 'Bank account number should be max. 10 digits.';
$_['error_sb299']       = 'Invalid parameter.';
$_['error_sb100']       = 'The bank you entered supports giropay and is available.';
$_['error_sb191']       = 'Your project is set to test mode,please use 12345679 as the test bank code.';
$_['error_sb192']       = 'Wrong parameter while checking for the bank status.';
$_['error_sb198']       = 'The bank you entered does not yet support giropay.';
$_['error_sb199']       = 'The bank you entered is temporarily not available.';
$_['error_sb900']       = 'Unknown bank code.';
$_['error_sb999']       = 'Invalid credentials.';
?>