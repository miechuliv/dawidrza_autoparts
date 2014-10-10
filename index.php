<?php
// Version
define('VERSION', '1.5.5.1');
$time = microtime(true);

// Configuration
if (file_exists('config.php')) {
	require_once('config.php');
}  

// Install 
if (!defined('DIR_APPLICATION')) {
	header('Location: install/index.php');
	exit;
}


ini_set('display_errors', '1');



// miechu auto loader class
spl_autoload_register(
function ($name) {
   $dir =  DIR_SYSTEM . 'library/extra/'.strtolower($name).'.php';
   if(file_exists($dir))
   {
       require_once($dir);
   }
   else
   {
     //  throw new Exception('Nie udało się załądować klasy: '. $name .' w: '. $dir );
   }

}

);





// vQmod
require_once('./vqmod/vqmod.php');
$vqmod = new VQMod();

// VQMODDED Startup
require_once($vqmod->modCheck(DIR_SYSTEM . 'startup.php'));

// Application Classes
require_once($vqmod->modCheck(DIR_SYSTEM . 'library/customer.php'));
require_once($vqmod->modCheck(DIR_SYSTEM . 'library/currency.php'));
require_once($vqmod->modCheck(DIR_SYSTEM . 'library/tax.php'));
require_once($vqmod->modCheck(DIR_SYSTEM . 'library/weight.php'));
require_once($vqmod->modCheck(DIR_SYSTEM . 'library/length.php'));
require_once($vqmod->modCheck(DIR_SYSTEM . 'library/cart.php'));
require_once($vqmod->modCheck(DIR_SYSTEM . 'library/affiliate.php'));
// miechu mods
require_once($vqmod->modCheck(DIR_SYSTEM . 'library/logger.php'));
require_once($vqmod->modCheck(DIR_SYSTEM . 'library/timing.php'));
require_once($vqmod->modCheck(DIR_SYSTEM . 'library/storage.php'));
require_once($vqmod->modCheck(DIR_SYSTEM . 'library/browser.php'));
// active record
require_once(DIR_SYSTEM . 'library/active_record/ActiveRecord.php');

$connections = array(
    'development' => 'mysql://'.DB_USERNAME.':'.DB_PASSWORD.'@'.DB_HOSTNAME.'/'.DB_DATABASE,
    'production' => 'mysql://'.DB_USERNAME.':'.DB_PASSWORD.'@'.DB_HOSTNAME.'/'.DB_DATABASE,
);

// initialize ActiveRecord
ActiveRecord\Config::initialize(function($cfg) use ($connections)
{
    $cfg->set_model_directory('./active_record_models/');
    $cfg->set_connections($connections);
});


//var_dump(Book::first()->attributes());




// Registry
$registry = new Registry();

require_once($vqmod->modCheck(DIR_SYSTEM . 'library/debugger.php'));
$debugger = new Debugger($time,false);

require_once($vqmod->modCheck(DIR_SYSTEM . 'library/extra/firephp/FirePHP.class.php'));
$firePHP = new FirePHP();

$registry->set('firephp', $firePHP);

$debugger->setFirePHP($firePHP);

$registry->set('debugger', $debugger);

$browser = new Browser();
$registry->set('browser',$browser);

require_once($vqmod->modCheck(DIR_SYSTEM . 'library/Mobile_Detect.php'));

$mobile_detect = new Mobile_Detect();
$registry->set('mobile_detect',$mobile_detect);

$timing = new Timing();
$registry->set('timing', $timing);

$storage = Storage::getInstance();
$registry->set('storage',$storage);

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);




// Config
$config = new Config();
$registry->set('config', $config);


// Database 
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE,$registry);
$db->setDebugger($debugger);
//$db->setLog();
$registry->set('db', $db);





// Store
if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
	$store_query = $db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`ssl`, 'www.', '') = '" . $db->escape('https://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
} else {
	$store_query = $db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`url`, 'www.', '') = '" . $db->escape('http://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
}

if ($store_query->num_rows) {
	$config->set('config_store_id', $store_query->row['store_id']);
} else {
	$config->set('config_store_id', 0);
}

$logger = new Logger($config);

$registry->set('logger',$logger);

// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0' OR store_id = '" . (int)$config->get('config_store_id') . "' ORDER BY store_id ASC");

foreach ($query->rows as $setting) {
	if (!$setting['serialized']) {
		$config->set($setting['key'], $setting['value']);
	} else {
		$config->set($setting['key'], unserialize($setting['value']));
	}
}

if (!$store_query->num_rows) {
	$config->set('config_url', HTTP_SERVER);
	$config->set('config_ssl', HTTPS_SERVER);
}

// Url
$url = new Url($config->get('config_url'), $config->get('config_secure') ? $config->get('config_ssl') : $config->get('config_url'));	
$registry->set('url', $url);

// Log 
$log = new Log($config->get('config_error_filename'));
$registry->set('log', $log);



function error_handler($errno, $errstr, $errfile, $errline) {
	global $log, $config, $debugger;

	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$error = 'Notice';
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$error = 'Warning';
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$error = 'Fatal Error';
			break;
		default:
			$error = 'Unknown';
			break;
	}


	
		$msg = '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';

        if(ENV=='TESTING')
        {

            // jesli mamy do czynienia z ajaxem to wyswietlamy w firebugu
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                /* special ajax here */
                $debugger->toFirebug($msg);
            }
            else
            {

                ob_end_clean();
                echo $debugger->displayError($msg);

              //  die();
            }

        }
        elseif(ENV=='DEVELOPMENT')
        {
            $debugger->toFirebug($msg);
        }
        elseif(ENV=='PRODUCTION')
        {

            $log->write($msg);
            // @todo
            $debugger->log($msg);
        }


	return true;
}
	
// Error Handler
set_error_handler('error_handler');

// Request
$request = new Request();
$registry->set('request', $request);

$debugger->setRequest($request);
 
// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$response->setCompression($config->get('config_compression'));
$registry->set('response', $response); 
		
// Cache
$cache = new Cache();
$registry->set('cache', $cache); 

// Session
$session = new Session();
$registry->set('session', $session);

$debugger->setSession($session);

// Language Detection
$languages = array();

$query = $db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE status = '1'"); 

foreach ($query->rows as $result) {
	$languages[$result['code']] = $result;
}

require_once($vqmod->modCheck(DIR_SYSTEM . 'library/translator.php'));
$translator = new Translator($languages);

$registry->set('translator', $translator);

$detect = '';

if (isset($request->server['HTTP_ACCEPT_LANGUAGE']) && $request->server['HTTP_ACCEPT_LANGUAGE']) { 
	$browser_languages = explode(',', $request->server['HTTP_ACCEPT_LANGUAGE']);
	
	foreach ($browser_languages as $browser_language) {
		foreach ($languages as $key => $value) {
			if ($value['status']) {
				$locale = explode(',', $value['locale']);

				if (in_array($browser_language, $locale)) {
					$detect = $key;
				}
			}
		}
	}
}

if (isset($session->data['language']) && array_key_exists($session->data['language'], $languages) && $languages[$session->data['language']]['status']) {
	$code = $session->data['language'];
} elseif (isset($request->cookie['language']) && array_key_exists($request->cookie['language'], $languages) && $languages[$request->cookie['language']]['status']) {
	$code = $request->cookie['language'];
} elseif ($detect) {
	$code = $detect;
} else {
	$code = $config->get('config_language');
}



if (!isset($session->data['language']) || $session->data['language'] != $code) {
	$session->data['language'] = $code;
}

if (!isset($request->cookie['language']) || $request->cookie['language'] != $code) {	  
	setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/'.HTTP_SUB, $request->server['HTTP_HOST']);
}			

$config->set('config_language_id', $languages[$code]['language_id']);
$config->set('config_language', $languages[$code]['code']);

// Language	
$language = new Language($languages[$code]['directory']);
$language->load($languages[$code]['filename']);	
$registry->set('language', $language); 

// Document
$registry->set('document', new Document()); 		

// Customer
$registry->set('customer', new Customer($registry));

// Affiliate
$registry->set('affiliate', new Affiliate($registry));

if (isset($request->get['tracking'])) {
	setcookie('tracking', $request->get['tracking'], time() + 3600 * 24 * 1000, '/'.HTTP_SUB);
}
		
// Currency
$registry->set('currency', new Currency($registry));

// Tax
$registry->set('tax', new Tax($registry));

// Weight
$registry->set('weight', new Weight($registry));

// Length
$registry->set('length', new Length($registry));

// Cart
$registry->set('cart', new Cart($registry));

// Encryption
$registry->set('encryption', new Encryption($config->get('config_encryption')));
		
// Front Controller 
$controller = new Front($registry);

// SEO URL's
//$controller->addPreAction(new Action('common/seo_url'));
$controller->addPreAction(new Action('common/oneslashseo'));

// Maintenance Mode
$controller->addPreAction(new Action('common/maintenance'));

// Router
if (isset($request->get['route'])) {
	$action = new Action($request->get['route']);
}elseif(isset($request->get['_route_']))
{
  $action = new Action($request->get['_route_']);
} else {
  $action = new Action('common/home');
}

// Dispatch
$controller->dispatch($action, new Action('error/not_found'));

if(isset($_GET['debug']))
{
    ob_end_clean();
    $debugger->displayError();
}

// Output
$response->output();


?>