<?php
// Version
define('VERSION', '1.5.5.1');
$time = microtime(true);

ini_set('display_errors',1);
// Configuration
if (file_exists('config.php')) {
	require_once('config.php');
}  

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: ../install/index.php');
	exit;
}


//require_once(DIR_SYSTEM . 'library/GShoppingContent.php');

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
          //echo DIR_SYSTEM . 'library/extra/'.strtolower($name).'.php';
          //  throw new Exception('Nie udało się załądować klasy: '. $name .' w: '. $dir );
        }

    }

);


// vQmod
require_once('../vqmod/vqmod.php');
$vqmod = new VQMod();

// VQMODDED Startup
require_once($vqmod->modCheck(DIR_SYSTEM . 'startup.php'));

// Application Classes
require_once($vqmod->modCheck(DIR_SYSTEM . 'library/currency.php'));
require_once($vqmod->modCheck(DIR_SYSTEM . 'library/user.php'));
require_once($vqmod->modCheck(DIR_SYSTEM . 'library/weight.php'));
require_once($vqmod->modCheck(DIR_SYSTEM . 'library/length.php'));
require_once($vqmod->modCheck(DIR_SYSTEM . 'library/tax.php'));
require_once($vqmod->modCheck(DIR_SYSTEM . 'library/customer.php'));
require_once($vqmod->modCheck(DIR_SYSTEM . 'library/storage.php'));

require_once($vqmod->modCheck(DIR_SYSTEM . 'library/logger.php'));

// active record
require_once(DIR_SYSTEM . 'library/active_record/ActiveRecord.php');

$connections = array(
    'development' => 'mysql://'.DB_USERNAME.':'.DB_PASSWORD.'@'.DB_HOSTNAME.'/'.DB_DATABASE,
    'production' => 'mysql://'.DB_USERNAME.':'.DB_PASSWORD.'@'.DB_HOSTNAME.'/'.DB_DATABASE,
);

// initialize ActiveRecord
ActiveRecord\Config::initialize(function($cfg) use ($connections)
{
    $cfg->set_model_directory('./../active_record_models/');
    $cfg->set_connections($connections);
});



// Registry
$registry = new Registry();

require_once($vqmod->modCheck(DIR_SYSTEM . 'library/debugger.php'));
$debugger = new Debugger($time);

require_once($vqmod->modCheck(DIR_SYSTEM . 'library/extra/firephp/FirePHP.class.php'));
$firePHP = new FirePHP();

$registry->set('firephp', $firePHP);

$debugger->setFirePHP($firePHP);

$registry->set('debugger', $debugger);



// sms api
$ms_logger =  new Logger('./sms_admin.log.csv');
OpenCartSms::initialize($ms_logger,true);
//

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

$storage = Storage::getInstance();
$registry->set('storage',$storage);

// Config
$config = new Config();
$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE,$registry);
$db->setDebugger($debugger);
$registry->set('db', $db);


// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");
 
foreach ($query->rows as $setting) {
	if (!$setting['serialized']) {
		$config->set($setting['key'], $setting['value']);
	} else {
		$config->set($setting['key'], unserialize($setting['value']));
	}
}

$logger = new Logger($config);

$registry->set('logger',$logger);

// Url
$url = new Url(HTTP_SERVER, $config->get('config_secure') ? HTTPS_SERVER : HTTP_SERVER);	
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

                die();
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
$registry->set('response', $response); 

// Cache
$cache = new Cache();
$registry->set('cache', $cache); 

// Session
$session = new Session();
$registry->set('session', $session);

$debugger->setSession($session);

// Language
$languages = array();

$query = $db->query("SELECT * FROM `" . DB_PREFIX . "language`"); 

foreach ($query->rows as $result) {
	$languages[$result['code']] = $result;
}

$config->set('config_language_id', $languages[$config->get('config_admin_language')]['language_id']);

// Language	
$language = new Language($languages[$config->get('config_admin_language')]['directory']);
$language->load($languages[$config->get('config_admin_language')]['filename']);	
$registry->set('language', $language);

// Document
$registry->set('document', new Document()); 		
		
// Currency
$registry->set('currency', new Currency($registry));

$registry->set('tax', new Tax($registry));
		
// Weight
$registry->set('weight', new Weight($registry));

// Length
$registry->set('length', new Length($registry));

// User
$registry->set('user', new User($registry));

$registry->set('customer', new Customer($registry));
						
// Front Controller
$controller = new Front($registry);



if(((isset($request->get['route']) AND strpos($request->get['route'],'cron')===false) OR !isset($request->get['route'])) AND !isset($request->post['pass']) )
{

    // Login
    $controller->addPreAction(new Action('common/home/login'));

// Permission
    $controller->addPreAction(new Action('common/home/permission'));
}


// Router
if (isset($request->get['route'])) {
	$action = new Action($request->get['route']);
} else {
	$action = new Action('common/home');
}

// Dispatch
$controller->dispatch($action, new Action('error/not_found'));

// Output
$response->output();
?>