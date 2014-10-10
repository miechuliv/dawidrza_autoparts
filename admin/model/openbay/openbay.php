<?php
class ModelOpenbayOpenbay extends Model
{
    private $url    = 'https://uk.openbaypro.com/';

    public function setUrl($url){
        $this->url = $url;
    }

    public function ftpTestConnection(){
        $this->load->language('extension/openbay');
        
        $data               = $this->request->post;
        $data['user']       = $data['openbay_ftp_username'];
        $data['pw']         = html_entity_decode($data['openbay_ftp_pw']);
        $data['server']     = $data['openbay_ftp_server'];
        $data['rootpath']   = $data['openbay_ftp_rootpath'];

        if(empty($data['user']))    { return array('connection' => false, 'msg' => $this->language->get('update_error_username')); }
        if(empty($data['pw']))      { return array('connection' => false, 'msg' => $this->language->get('update_error_password')); }
        if(empty($data['server']))  { return array('connection' => false, 'msg' => $this->language->get('update_error_server')); }

        $connection = @ftp_connect($data['server']); 
        
        if($connection != false){
            if (@ftp_login($connection, $data['user'], $data['pw'])) {
                if(!empty($data['rootpath'])){
                    @ftp_chdir($connection, $data['rootpath']);
                }
                
                $directory_list = ftp_nlist($connection, ".");
                
                $folders = array();
                foreach($directory_list as $key=>$list){
                    if($this->is_ftp_dir($list, $connection)){
                        $folders[] = $list;
                    }
                }
                
                $folder_error                                   = false;
                $folder_error_admin                             = false;
                if(!in_array('catalog', $folders))              { $folder_error = true;}
                if(!in_array('system', $folders))               { $folder_error = true;}
                if(!in_array('image', $folders))                { $folder_error = true;}
                if(!in_array('vqmod', $folders))                { $folder_error = true;}
                if(!in_array($data['openbay_admin_directory'], $folders))      { $folder_error_admin = true;}
                
                ftp_close($connection);
                
                if($folder_error_admin == true){
                    return array('connection' => false, 'msg' => $this->language->get('update_okcon_noadmin'));
                }else{
                    if($folder_error == true){
                        return array('connection' => false, 'msg' => $this->language->get('update_okcon_nofiles'), 'dir' => json_encode($directory_list));
                    }else{
                        return array('connection' => true, 'msg' => $this->language->get('update_okcon'));
                    }
                }
            } else {
                return array('connection' => false, 'msg' => $this->language->get('update_failed_user'));
            }
        }else{
            return array('connection' => false, 'msg' => $this->language->get('update_failed_connect'));
        }
    }
    
    public function ftpUpdateModule(){
        /**
         * Disable error reporting due to noticed thrown when directories are checked
         * It will cause constant loading icon otherwise.
         */
        error_reporting(0);
        set_time_limit(0);
        ob_start();

        $this->load->model('setting/setting');
        $this->load->language('extension/openbay');
        
        $data               = $this->request->get;
        $data['user']       = $data['openbay_ftp_username'];
        $data['pw']         = html_entity_decode($data['openbay_ftp_pw']);
        $data['server']     = $data['openbay_ftp_server'];
        $data['rootpath']   = $data['openbay_ftp_rootpath'];
        $data['adminDir']   = $data['openbay_admin_directory'];
        $data['beta']       = ((isset($data['openbay_ftp_beta']) && $data['openbay_ftp_beta'] == 1) ? 1 : 0);

        if(empty($data['user']))    { return array('connection' => false, 'msg' => $this->language->get('update_error_username')); }
        if(empty($data['pw']))      { return array('connection' => false, 'msg' => $this->language->get('update_error_password')); }
        if(empty($data['server']))  { return array('connection' => false, 'msg' => $this->language->get('update_error_server')); }
        if(empty($data['adminDir'])){ return array('connection' => false, 'msg' => $this->language->get('update_error_admindir')); }
        
        $connection = @ftp_connect($data['server']);
        $updatelog = "Connecting to server\n";
        
        if($connection != false){
            $updatelog .= "Connected\n";
            $updatelog .= "Checking login details\n";
            
            if(isset($data['openbay_ftp_pasv']) && $data['openbay_ftp_pasv'] == 1){
                ftp_pasv($connection, true);
                $updatelog .= "Using pasv connection\n";
            }
            
            if (@ftp_login($connection, $data['user'], $data['pw'])){
                $updatelog .= "Logged in\n";
                
                if(!empty($data['rootpath'])){
                    $updatelog .= "Setting root path\n";
                    @ftp_chdir($connection, $data['rootpath']);
                    $directory_list = ftp_nlist($connection, $data['rootpath']);
                }
                
                $current_version = $this->config->get('openbay_version');
                
                $send = array('version' => $current_version, 'ocversion' => VERSION, 'beta' => $data['beta']);
                
                $files = $this->call('update/getList/', $send);
                $updatelog .= "Requesting file list\n";
                
                if($this->lasterror == true){
                    $updatelog .= $this->lastmsg;
                    return array('connection' => true, 'msg' => $this->lastmsg);
                }else{
                    $updatelog .= "Received list of files\n";
                    foreach($files['asset']['file'] as $file){
                        $dir        = '';
                        $dirLevel   = 0;
                        if(isset($file['locations']['location']) && is_array($file['locations']['location'])){
                            foreach($file['locations']['location'] as $location){
                                $dir            .= $location.'/';

                                $updatelog .= "Current location: ".$dir."\n";
                                
                                // Added to allow OC security where the admin directory is renamed
                                if($location == 'admin') { $location = $data['adminDir']; }

                                if(ftp_chdir($connection, $location)){

                                }else{
                                    ftp_mkdir($connection, $location);
                                }

                                $dirLevel++;
                            }
                        }
                        
                        $filedata = base64_decode($this->call('update/getFileContent/', array('file' => $dir.$file['name'], 'beta' => $data['beta']))); 

                        $tmpFile = DIR_CACHE.'openbay.tmp';

                        $fp = fopen($tmpFile, 'w');
                        fwrite($fp, $filedata);

                        fclose($fp);
                        
                        ftp_put($connection, $file['name'], $tmpFile, FTP_BINARY);
                        $updatelog .= "Updated file: ".$file['name']."\n";

                        unlink($tmpFile); 

                        while($dirLevel != 0){
                            ftp_cdup($connection);
                            $dirLevel--;
                        }
                    }
                    
                    $openbay_settings = $this->model_setting_setting->getSetting('openbaymanager');
                    $openbay_settings['openbay_version'] = $files['version'];
                    $this->model_setting_setting->editSetting('openbaymanager',$openbay_settings);

                    @ftp_close($connection);
                    
                    /**
                     * Run the patch files
                     */
                    $this->load->model('ebay/patch'); 
                    $this->model_ebay_patch->runPatch(false);
                    $this->load->model('amazon/patch'); 
                    $this->model_amazon_patch->runPatch(false);
                    $this->load->model('amazonus/patch'); 
                    $this->model_amazonus_patch->runPatch(false);

                    /**
                     * File remove operation (clean up old files)
                     */
                    $updatelog .= "\n\n\nStarting Remove\n\n\n";

                    $connection = @ftp_connect($data['server']);
                    @ftp_login($connection, $data['user'], $data['pw']);
                    if(!empty($data['rootpath'])) { @ftp_chdir($connection, $data['rootpath']); $directory_list = ftp_nlist($connection, $data['rootpath']); }
                    $filesUpdate = $files;
                    $files = $this->call('update/getRemoveList/', $send);

                    foreach($files['asset']['file'] as $file){
                        $dir        = '';
                        $dirLevel   = 0;
                        if(!empty($file['locations'])){
                            foreach($file['locations']['location'] as $location){
                                $dir       .= $location.'/';
                                $updatelog .= "Current location: ".$dir."\n";

                                // Added to allow OC security where the admin directory is renamed
                                if($location == 'admin') { $location = $data['adminDir']; }

                                if(ftp_chdir($connection, $location)) {
                                    $updatelog .= $location. "/ found\n";
                                }else{
                                    ftp_mkdir($connection, $location);
                                    $updatelog .= $location. "/ created\n";
                                }

                                $dirLevel++;
                            }
                        }

                        //remove the file
                        if(isset($file['name']) && @ftp_size($connection, $file['name']) > -1){
                            @ftp_delete($connection, $file['name']);
                            $updatelog .= "Removed file: ".$file['name']."\n";
                        }

                        //remove the directory if needed
                        /**
                        if(isset($file['removeDirectory']) && $file['removeDirectory'] != ''){
                            ftp_cdup($connection);
                            $dirLevel--;
                            @ftp_rmdir($connection, $file['removeDirectory']);
                            $updatelog .= "Removed directory: ". $file['removeDirectory']."\n";
                        }
                        */

                        while($dirLevel != 0){
                            ftp_cdup($connection);
                            $dirLevel--;
                        }
                    }
                    
                }

                $updatelog .= "Update complete\n\n\n";
                $output = ob_get_contents();
                ob_end_clean();

                $this->writeUpdateLog($updatelog . "\n\n\nErrors:\n".$output);

                return array('connection' => true, 'msg' => sprintf($this->language->get('update_success'), $filesUpdate['version']), 'version' => $filesUpdate['version']);
            } else {
                return array('connection' => false, 'msg' => $this->language->get('update_failed_user'));
            }
        }else{
            return array('connection' => false, 'msg' => $this->language->get('update_failed_connect'));
        }        
    }
    
    public function getNotifications(){
        $data = $this->call('notification/getPublicNotifications/');
        return $data;
    }

    public function getVersion(){
        $data = $this->call('notification/getStableVersion/');
        return $data;
    }

    public function faqGet($route){
        if($this->faqIsDismissed($route) != true){
            $data = $this->call('notification/getFaq/', array('route' => $route));
            return $data;
        }else{
            return false;
        }
    }

    public function faqIsDismissed($route){
        $this->faqDbTableCheck();

        $sql = $this->db->query("SELECT * FROM `".DB_PREFIX."openbay_faq` WHERE `route` = '".$this->db->escape($route)."'");

        if($sql->num_rows > 0){
            return true;
        }else{
            return false;
        }
    }

    public function faqDismiss($route){
        $this->faqDbTableCheck();
        $this->db->query("INSERT INTO `".DB_PREFIX."openbay_faq` SET `route` = '".$this->db->escape($route)."'");
    }

    public function faqClear(){
        $this->faqDbTableCheck();
        $this->db->query("TRUNCATE `" . DB_PREFIX . "openbay_faq`");
    }

    public function faqDbTableCheck(){
        if(!$this->openbay->testDbTable(DB_PREFIX . "openbay_faq")){
            $this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."openbay_faq` (`id` int(11) NOT NULL AUTO_INCREMENT,`route` text NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        }
    }
    
    private function is_ftp_dir($file, $connection){
        if(ftp_size($connection, $file) == '-1'){
            return true;
        }else{
            return false;
        }
    }
    
    public function checkMcrypt(){
        if(function_exists('mcrypt_encrypt')){
            return true;
        }else{
            return false;
        }
    }
    
    public function checkMbstings(){
        if(function_exists('mb_detect_encoding')){
            return true;
        }else{
            return false;
        }
    }
    
    public function checkFtpenabled(){
        if(function_exists('ftp_connect')){
            return true;
        }else{
            return false;
        }
    }
    
    private function call($call, array $post = NULL, array $options = array(), $content_type = 'json'){
        if(defined("HTTP_CATALOG")){
            $domain = HTTP_CATALOG;
        }else{
            $domain = HTTP_SERVER;
        }

        $data = array(
            'token'             => '', 
            'language'          => $this->config->get('openbay_language'), 
            'secret'            => '', 
            'server'            => 1, 
            'domain'            => $domain, 
            'openbay_version'   => (int)$this->config->get('openbay_version'),
            'data'              => $post, 
            'content_type'      => $content_type
        );

        $useragent="Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1";

        $defaults = array(
            CURLOPT_POST            => 1,
            CURLOPT_HEADER          => 0,
            CURLOPT_URL             => $this->url.$call,
            CURLOPT_USERAGENT       => $useragent, 
            CURLOPT_FRESH_CONNECT   => 1,
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_FORBID_REUSE    => 1,
            CURLOPT_TIMEOUT         => 0,
            CURLOPT_SSL_VERIFYPEER  => 0,
            CURLOPT_SSL_VERIFYHOST  => 0,
            CURLOPT_POSTFIELDS      => http_build_query($data, '', "&")
        );

        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        $result = curl_exec($ch);
        curl_close($ch);

        if($content_type == 'json'){
            $encoding = mb_detect_encoding($result);

            /* some json data may have BOM due to php not handling types correctly */
            if($encoding == 'UTF-8') {
              $result = preg_replace('/[^(\x20-\x7F)]*/','', $result);    
            } 

            $result             = json_decode($result, 1);
            $this->lasterror    = $result['error'];
            $this->lastmsg      = $result['msg'];

            if(!empty($result['data'])){
                return $result['data'];
            }else{
                return false;
            }
        }elseif($content_type == 'xml'){
            $result             = simplexml_load_string($result);
            $this->lasterror    = $result->error;
            $this->lastmsg      = $result->msg;

            if(!empty($result->data)){
                return $result->data;
            }else{
                return false;
            }
        }
    }
    
    public function writeUpdateLog($data){
        $file = DIR_LOGS . 'openbay_update_'.date('Y_m_d_G_i_s').'.log';

        $handle = fopen($file, 'w+');
        fwrite($handle, "** Update started: ". date('Y-m-d G:i:s') ." **" . "\n");

        fwrite($handle, $data);
        fclose($handle);
    }
}