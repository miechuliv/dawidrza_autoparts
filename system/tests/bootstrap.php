<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 20.09.13
 * Time: 15:30
 * To change this template use File | Settings | File Templates.
 */

define('DIR_APPLICATION', 'c;//xampp/htdocs/nowy/catalog/');
define('DIR_SYSTEM', 'c;//xampp/htdocs/nowy/system/');

require_once(DIR_SYSTEM . 'engine/action.php');
require_once(DIR_SYSTEM . 'engine/controller.php');
require_once(DIR_SYSTEM . 'engine/front.php');
require_once(DIR_SYSTEM . 'engine/loader.php');
require_once(DIR_SYSTEM . 'engine/model.php');
require_once(DIR_SYSTEM . 'engine/registry.php');

$register =  new Registry();

$loader =  new Loader($registry,'testing');
$registry->set('load', $loader);