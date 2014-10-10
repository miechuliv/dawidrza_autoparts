<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 09.04.14
 * Time: 14:46
 * To change this template use File | Settings | File Templates.
 */
include_once(__DIR__.'/config.php');
require_once(DIR_SYSTEM . 'library/db.php');

$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

echo "Generowanie modelu z podanej tabeli, podaj nazwę sekcję oraz nazwę tabeli \n\r";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
$t = explode(' ',trim($line));
$section = $t[0];
$name = $t[1];
echo "Analiza tabeli \n\r";
$res = $db->query('DESCRIBE '.$name.' ');

$primary_key = false;

echo "generowanie zapytań \n\r";
$insert_query = '" INSERT INTO `".DB_PREFIX."'.$name.'` SET ';
$update_query = '" UPDATE `".DB_PREFIX."'.$name.'` SET ';
$get_query = '" SELECT * FROM `".DB_PREFIX."'.$name.'` ';
$get_many_query = '" SELECT * FROM `".DB_PREFIX."'.$name.'` "';
$delete_query = '"  DELETE FROM `".DB_PREFIX."'.$name.'` ';

$i = 0;

foreach($res->rows as $field)
{


    if(!isset($field['Type']))
    {
        continue;
    }
    if(isset($field['Key']) AND $field['Key'] == 'PRI')
    {
        $primary_key = $field['Field'];
        continue;
    }


    if($i)
    {
        $insert_query .= ' , ';
        $update_query .= ' , ';
    }


    if(strpos($field['Type'],'int')!==false OR strpos($field['Type'],'tinyint')!==false)
    {
        $insert_query .= ' `'.$field['Field'].'` = \'".(int)$data[\''.$field['Field'].'\']."\'  '."\n";
        $update_query .= ' `'.$field['Field'].'` = \'".(int)$data[\''.$field['Field'].'\']."\'  '."\n";
    }
    elseif(strpos($field['Type'],'float')!==false OR strpos($field['Type'],'decimal')!==false)
    {
        $insert_query .= ' `'.$field['Field'].'` = \'".(float)$data[\''.$field['Field'].'\']."\'  '."\n";
        $update_query .= ' `'.$field['Field'].'` = \'".(float)$data[\''.$field['Field'].'\']."\'  '."\n";
    }
    else
    {
        $insert_query .= ' `'.$field['Field'].'` = \'".$this->db->escape($data[\''.$field['Field'].'\'])."\'  '."\n";
        $update_query .= ' `'.$field['Field'].'` = \'".$this->db->escape($data[\''.$field['Field'].'\'])."\'  '."\n";
    }


    $i++;
}

$insert_query .= ' " ';

if($primary_key)
{

    $update_query .= ' WHERE `'.$primary_key.'` = \'".$id."\' "';
    $get_query .= ' WHERE `'.$primary_key.'` = \'".$id."\' "';

    $delete_query .= ' WHERE `'.$primary_key.'` = \'".$id."\' "';
}

echo "uzupełnianie szablonu \n\r";

$f = file_get_contents(__DIR__.'/model_template');

$ready = str_ireplace(array(
    '{section}',
    '{name}',
    '{primary_key}',
    '{insert_query}',
'{update_query}',
'{get_query}',
'{get_many_query}',
'{delete_query}',
),array(
    ucfirst($section),
    ucfirst($name),
    $primary_key,
    $insert_query,
    $update_query,
    $get_query,
    $get_many_query,
    $delete_query,
),$f);

if(!file_exists(__DIR__.'/generated/'.$section))
{
    mkdir(__DIR__.'/generated/'.$section);
}

file_put_contents(__DIR__.'/generated/'.$section.'/'.$name.'.php','<?php '.$ready);

echo "gotowe \n\r";
