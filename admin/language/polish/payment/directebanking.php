<?php
/**
 * @version		$Id: directebanking.php 3100 2013-03-04 18:11:21Z mic $
 * @package		Directebanking - Language Admin English
 * @copyright	(C) 2011 mic [ http://osworx.net ]. All Rights Reserved.
 * @author		mic - http://osworx.net
 * @license		OSWorX Commercial License http://osworx.net
 */

// Heading
$_['heading_title']         = 'Sofortbanking<span style="float:right; color:#777777;">Płatności online</span>';
$_['plain_title']           = 'Sofortbanking';

// Text
	// logo at overview
$_['text_directebanking']	= '<a onclick="window.open(\'https://www.sofort.com\');"><img src="view/image/payment/sofort.png" alt="Sofortbanking" title="Sofortbanking" style="border: 1px solid #EEEEEE;" height="25" width="94" /></a>';

	// standard
$_['text_payment']			= 'Płatność';
$_['text_success']			= 'Module został zmodyfikowany!';
$_['text_enabled']			= 'Włączony';
$_['text_disabled']			= 'Wyłączony';
$_['text_success_log']		= 'Log został wyczyszczony';
$_['text_log_empty']		= 'Brak tranzakcji';
$_['text_personal_data']	= 'Dane osobiste';
$_['text_current_time']		= 'Czas: %s';
$_['text_copy']				= 'Kopiuj';
$_['text_create_pw']		= 'Utwórz/Zmień hasło';
$_['text_show_hide']		= 'Pokaż/Ukryj pola';
$_['text_success_installed']= 'Moduł płatności został zainstalowany';
$_['text_sample']			= '<div><a onclick="javascript:window.open(\'https://images.sofort.com/en/su/landing.php?banner=banner_160x43_el\',\'customer_informationen\',\'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1020, height=900\'); return false;" href="#" style="float:left; width:auto;"><img src="https://images.sofort.com/en/su/banner_160x43_el.png" alt="sofort.com" border="0" /></a><p style="margin-left: 320px; width:auto;">With Sofort.com you can pay conveniently and easily with your online banking account without registering. Therefore you will need your login information to your online banking account (PIN / TAN).</p></div>';

// cpanel
	// icons
	// standard
$_['text_settings']			= 'Ustawienia';
$_['text_help']				= 'Pomoc';
	// individual
$_['text_text']				= 'Tekst';
$_['text_log']				= 'Log';
$_['text_new_project']		= 'Newy Projekt';

	// right pane
	// common
$_['text_common']			= 'Wspólne';
$_['text_accesskey_shift']	= 'Accesskey SHIFT + ALT';
$_['text_accesskey']		= 'Accesskey ALT';
$_['text_module']			= 'Moduł';
$_['text_installed']		= 'Wersja zainstalowana';
$_['text_current']			= 'Obecna wersja';
$_['text_license']			= 'Licencja';
$_['text_copyright']		= 'Copyright';
$_['text_author']			= 'Autor';
$_['text_support']			= 'Pomoc';
	// advanced = module specific
$_['text_advanced']			= 'Zawansowane';
$_['text_total_amount']		= 'Całkowicie';
$_['text_total_used']		= 'Użyto';
$_['text_total_percent']	= '% wszystkich zamówień';
    // version
$_['text_title']                = 'Tytuł';
$_['text_unknown_version']      = 'You are using a not supported version of this module.<br />Please get in <a href="mailto:support@osworx.net?subject=Unknown%20version%20of%20module%20sofort.com">contact with the developer</a>';
$_['text_description']          = 'Opis';
$_['text_no_info_available']    = 'Brak dodatkowych informacji';
$_['text_new_version_available']= 'Nowa wersja jest dostępna.';
$_['text_published']            = 'Opublikowana';
$_['text_changelog']            = 'Dziennik zmian';
$_['text_changelog_new']        = 'Nowy';
$_['text_changelog_fixed']      = 'Poprawki';
$_['text_support_key']		    = 'Klucz dostępu';

// Entry
	// standard
$_['entry_status']			= 'Stan';
$_['entry_sort_order']		= 'Kolejność';
	// module specific
$_['entry_order_status']	= 'Stan zamówienia po wykonaniu płatności';
$_['entry_geo_zone']		= 'Strefa';
$_['entry_custId']			= 'ID klienta';
$_['entry_projId']			= 'ID projektu';
$_['entry_successUrl']		= 'URL strony sukcesu';
$_['entry_cancelUrl']		= 'URL po anulowaniu';
$_['entry_useHash']			= 'Użyj hash-a';
$_['entry_projectPassword']	= 'Hasło projektu';
$_['entry_notifyPassword']	= 'Hasło powiadomień';
$_['entry_hashMethod']		= 'Metoda hashowania';
$_['entry_testMode']		= 'Tryb testowy';
$_['entry_successUrlStd']	= '(Use Standard-URL)';
$_['entry_cancelUrlStd']	= '(Use Standard-URL)';
$_['entry_salutation']		= 'Zwrot grzecznościowy';
$_['entry_user_name']		= 'Nazwa';
$_['entry_debug']			= 'Tryb debugowania';
$_['entry_server_shift']	= 'Server shift';
$_['entry_street']			= 'Ulica';
$_['entry_zip']				= 'Kod pocztowy';
$_['entry_city']			= 'Miasto';
$_['entry_email']			= 'Email';
$_['entry_country']			= 'Kraj';
$_['entry_phone']			= 'Telefon';
$_['entry_account_holder']	= 'Posiadacza konta';
$_['entry_account_number']	= 'Numer konta';
$_['entry_bank_code_number']= 'Kod banku';
$_['entry_bank_bic']		= 'BIC';
$_['entry_bank_iban']		= 'IBAN';
$_['entry_proj_password'] 	= 'Hasło projektu';
$_['entry_notify_password']	= 'Hasło powiadomień';
$_['entry_encryption_method']	= 'Metoda szyfrowania';
$_['entry_proj_name'] 			= 'Nazwa projektu';
$_['entry_proj_responsible']	= 'Odpowiedzialny';
$_['entry_email_notification']	= 'Email dla powiadomień';
$_['entry_email_language']		= 'Email language';
$_['entry_legal_form']			= 'Legal form';
$_['entry_supportKey']			= 'Supportkey';
$_['entry_text']				= 'Tekst';
$_['entry_title']				= 'Tytuł';
$_['entry_customer_groups']		= 'Grupa klientów';
$_['entry_store']               = 'Sklep';
$_['entry_protection']          = 'Ochrona kupującego';

// help - NOTE: tags (like links) must be build with entities!!
	// standard
	// module specific
$_['help_advanced']		= 'To be able to use sofort.com you must have an account at %s.<br />All further settings will be defined there.<br /><strong>Note</strong>: we strongly recommend to use the internal encryption (see <a href="%s">System -> Settings</a>)!<br /><br />If you have no account at Sofortbanking already or you want to create a new project only, see the button &quot;New Project&quot; at the cpanel.';
$_['help_entry_order_status']	= 'State after successful payment.<br />Was the payment not successful (e.g. no payment, connection not errorfree, etc.), the state will be set as defined in the system.';
$_['help_custId']		= 'Place here your sofort.com customer ID.<br />If you create a new project, this field will be filled automatically';
$_['help_projId']		= 'Place here the corresponding project ID.<br />If you create a new project, this field will be filled automatically';
$_['help_successUrl']	= 'Optional URL if the transaction was successful and the customer shall be redirected (use only if you are NOT using the standard URL - see checkbox).<br />If you leave this field empty and use not the checkbox, the customer will stay at sofort.com<br /><strong>Note</strong>: checking the checkbox means the correct URL for the redirect will be used automatically!';
$_['help_cancelUrl']	= 'Optional URL if the transaction was cancelled by the customer and the customer shall be redirected (use only if you are NOT using the standard URL - see checkbox).<br />If you leave this field empty and use not the checkbox, the customer will stay at sofortbanking<br /><strong>Note</strong>: checking the checkbox means the correct URL for the redirect will be used automatically!';
$_['help_useHash']		= 'If checked, the system will build a key which sofortbanking needs to verify the validity and correctness of the submitted data.<br />If this option is used, a password above must be defined!';
$_['help_projectPassword']	= 'Use here the same password you provided at sofortbanking under &quot;project&quot;.';
$_['help_notifyPassword']	= 'Use here the same password you provided at sofortbanking under &quot;notify&quot;.';
$_['help_hashMethod']		= 'Depending which method is defined at sofortbanking, you MUST use here the SAME!<br />Note: optional, use only if hash method is used (recommended sha1)';
$_['help_instruction']		= 'For each installed language you can define a seperate titel and text to inform your customers about the benefits of this payment method.<br /><strong>Tip</strong>: the acceptance for this payment method is much better if your customer can read about it.<br />If no title is defined, the value of the language file will be used.<br />If no text is defined, nothing will be displayed.<br /><br />If you want to use the sample shown below (click on the banner opens a new window), just switch HTML-mode of editor into plain text and copy and paste the displayed code.<br />More banners at <a onclick="window.open(\'https://www.payment-network.com/sue_de/online-anbieterbereich/werbemittel\'">Sofort.com</a>';
$_['help_logText']		= 'You can see here all transactions and messages.<br /><strong>Notes</strong>:<ul><li>see also the messages at Sofort.com (specially if an error occured)</li><li>if you see only strange characters instead of a text, then the secret key in the system is changed since the furst entry here</li></ul>';
$_['help_testMode']		= 'Choose either the test or live mode<br /><strong>Note</strong>: to use the test mode, you MUST enable this also at sofortbanking!';
$_['help_test']			= 'You can simulate here a transaction.<br /><strong>Note</strong>: no real data will be transfered.<br />Mode 1 simulates a german bank account, mode 2 for all other countries (you can use both)';
$_['help_newProject']	= 'With this form you can create 1. a new account, 2. a new project (in this case you must have already an account at sofortbanking).<br />All fields below must be filled - to transmit the data click finally on &quot;Apply&quot;.<br /><strong>Notes:</strong><ol><li>Additonal and existing data can be edited later at sofortbanking</li><li>At sofortbanking you will see a summary of this datas (click on the link at the bottom of the screen)</li><li>If you have already an account and create a new project only, you have to login at Sofortbanking with your existing login data!</li><li>To save the data here and at sofortbanking you have to click on the link &quot;Back to shop&quot; at sofortbanking</li><li>If you already have here a project stored, it will be overridden after the redirect if you click on &quot;Save&quot; or &quot;Apply&quot;.</li></ol>';
$_['help_newProject_error']	= 'Attention! The system use NO encryption! To submit the data below you have to enable the encryption (see <a href="%s">System -> Settings -> Server</a>';
$_['help_debug']			= 'With this setting additonal log messages will be saved at sofortbanking.<br /><strong>Use only in case of errors!</strong>';
$_['help_server_shift']		= 'If your server has not the the same time zone you can adjust here the value (in half hours -/+ e.g..: 1.5).<br />Used at logfile';
$_['help_copy_data']		= 'Use same data at project as stated at personal';
$_['help_password']			= 'The password must be created/changed with the button';
$_['help_supportKey']		= 'To recieve support for this extension within this installation, it is important to have a valid supportkey. Please fill in here this key.';
$_['help_text']				= 'You can define here the display of the payment text. Options are<ul><li>as text</li><li>as image</li><li>self defined (see &quot;Menu Text&quot;)</li></ul>The text field definition has priority against the two other.<br />For example if you are qualified for the extra customer security (Käuferschutz), you can paste the code in that field.';
$_['help_customer_groups']	= 'Which customergroup(s) have access to this payment method?<br />Multiple selection possible';
$_['help_store']            = 'For which store you want to create a new project';
$_['help_protection']       = 'To use this feature, you must have an account at the Sofort Bank';

	// select lists
$_['entry_yes']			= 'Yes';
$_['entry_no']			= 'No';
$_['sel_sha1']			= 'SHA1 (Standard)';
$_['sel_sha256']		= 'SHA256';
$_['sel_sha512']		= 'SHA512';
$_['sel_md5']			= 'MD5';
$_['sel_mode1']			= 'Test mode 1';
$_['sel_mode2']			= 'Test mode 2';
$_['sel_select']		= '-- Please choose --';
$_['sel_debug1']		= 'Logfile only';
$_['sel_debug2']		= 'Logfile &amp; Display';
$_['sel_company']		= 'Company';
$_['sel_mister']		= 'Mister';
$_['sel_miss']			= 'Miss';
$_['sel_legal1']		= 'No legal / Natural person';
$_['sel_legal2']		= 'Legal not instanced';
$_['sel_legal3']		= 'Autonomous';
$_['sel_legal4']		= 'Stock corporation';
$_['sel_legal5']		= 'Aktiengesellschaft &amp; Co. KG';
$_['sel_legal6']		= 'AG &amp; Co. OHG';
$_['sel_legal7']		= 'Cooperative';
$_['sel_legal8']		= 'Limited corporation';
$_['sel_legal9']		= 'Limited corporation &amp; Co. KG';
$_['sel_legal10']		= 'Limited corporation &amp; Co. KEG';
$_['sel_legal11']		= 'Limited corporation &amp; Co. OHG';
$_['sel_legal12']		= 'Gesellschaft nach bürgerlichem Recht';
$_['sel_legal13']		= 'Kommanditerwerbsgesellschaft';
$_['sel_legal14']		= 'Limited partnership';
$_['sel_legal15']		= 'Offene Erwerbsgesellschaft';
$_['sel_legal16']		= 'General partnership';
$_['sel_legal17']		= 'Registered cooperative limited';
$_['sel_legal18']		= 'Registered cooperative';
$_['sel_legal19']		= 'Registered prudent businessman';
$_['sel_legal20']		= 'Registered association';
$_['sel_legal21']		= 'Limited';
$_['sel_legal22']		= 'Public limited company';
$_['sel_legal23']		= 'Société anonyme';
$_['sel_legal24']		= 'Société à responsabilité limitée';
$_['sel_legal25']		= 'Societas Europaea';
$_['sel_legal26']		= 'Unternehmergesellschaft (beschränkt)';
$_['sel_german']		= 'German';
$_['sel_english']		= 'Englisch';
$_['sel_italian']		= 'Italian';
$_['sel_french']		= 'French';
$_['sel_text_as_self']	= 'Self definition';
$_['sel_text_as_text']	= 'As text';
$_['sel_text_as_image']	= 'As image';
$_['text_all']			= 'All';

// buttons
$_['button_help']		= 'Help';
$_['button_apply']		= 'Apply';
$_['button_clear']		= 'Delete log';
$_['btn_create_password']	= 'Create/change password';
$_['btn_copy_data']		= 'Copy data';

	// tabs
$_['tab_common']		= 'Common';
$_['tab_advanced']		= 'Advanced';
$_['tab_log']			= 'Log';
$_['tab_html']			= 'Text';
$_['tab_new_project']	= 'New Project';
$_['tab_stores']        = 'Stores';

	// legends
$_['leg_account_data']		= 'Account data';
$_['leg_project_data']		= 'Project data';
$_['leg_address']			= 'Address';
$_['leg_banking_details']	= 'Banking Details';
$_['leg_various_project_data']	= 'Various Project Data';
$_['leg_project_banking_details']	= 'Projekt Banking Details';

	// messages (e.g. logfile)
$_['msg_successful_project_creation']	= 'User (%s - %s) created successful a new project (%s).';
$_['msg_unsuccessful_project_creation']	= 'User (%s - %s) created without success a new project (%s).';
	// alerts (e.g. javascript)
$_['msg_all_fields_must_be_filled'] 	= 'All fields must be filled in!';
$_['msg_submit_form']					= 'All data correct - submit data?';
	// other
$_['msg_back_from_directebanking']		= 'New project data, to save them please click now either on Save or Apply';
$_['msg_test_mode_on']					= 'Note: testmode is currently activated! For live transactions you have to deactivate it.';

// Error
	// common
$_['error_permission']	= 'Warning: not enough permissions to perform this action!';
	// specific
$_['error_fields']		= 'Some data is missing!';
$_['error_custId']		= 'No customer ID provided!';
$_['error_projId']		= 'No project ID provided!';
$_['error_projectPassword']	= 'You must provide a project password!';
$_['error_notifyPassword']	= 'You must provide a notify password!';