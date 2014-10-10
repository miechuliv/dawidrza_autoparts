<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 02.04.14
 * Time: 11:02
 * To change this template use File | Settings | File Templates.
 */

class ModelImportExcel extends Model{

    private $_fileLocation;

    public function load()
    {
        include_once(DIR_SYSTEM.'library/extra/excel_reader2.php');

        $this->_fileLocation = DIR_APPLICATION."../cennik.xls";

        if(!file_exists($this->_fileLocation))
        {
            throw new Exception('no fckn file:; '.$this->_fileLocation);
        }

        $xls = new Spreadsheet_Excel_Reader($this->_fileLocation);

        $xls->read($this->_fileLocation);

        $xls->dump();



        return true;

    }

}