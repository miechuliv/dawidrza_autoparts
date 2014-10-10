<?php
/**
 * @version		$Id: support.php 3156 2013-03-15 14:39:47Z mic $
 * @package		OSWorX Tools
 * @copyright	(C) 2013 mic [ http://osworx.net ]. All Rights Reserved.
 * @license		OSWorX Commercial License
 * @author		mic [ http://osworx.net ]
 */

class OXSupport
{
    static $_url        = 'https://osworx.net/support/index.php';
    static $_DS         = DIRECTORY_SEPARATOR;
    static $_root       = null;
    static $_debugMsg   = array();
    static $_name       = 'Support';
    static $_version    = '1.0.1';
    static $_debug      = false; // set to true if required
    static $_statistic  = array();

    /**
     * get current version for extension
     * @param string    $mod    name of extension
     * @param string    $iVer   version of installed extension
     * @param string    $sk     supportkey [optional if set]
     * @param string    $lang   language for return messages (e.g. changelog)
     * @return object
     */
	static public function getVersion( $mod, $iVer, $sk, $lang = 'en', $task = '' ) {
        $task = ( $task ? $task : 'getVersion' );
        $data = self::buildDataString( $task, $mod, $iVer, $sk, null, $lang );

        $obj  = new stdClass();
        $obj->cVer	= '0.0.0';
		$obj->dif	= '1';
        $obj->cLog	= '';

		$err = error_reporting();
		error_reporting( 0 );

        if( $ret = self::getData( $data ) ) {
            if( strpos( $ret, 'html' ) !== false ) {
                $ret = '';
            }

            if(
                $ret
                && $ret != '0.0.0'
            )
            {
                $ret = self::getResult( $ret );

                $obj->cVer	= $ret['latest'];
    			$obj->dif	= self::vCompare( $iVer, $ret['latest'] );
                $obj->cLog	= $ret['changelog'];
            }
        }

		error_reporting( $err );

		return $obj;
	}

    /**
     * get current version for extension
     * used for older extensions only
     * see function getVersion()
     */
	static public function getVersionOnly( $mod, $iVer, $sk, $lang = 'en' ) {
        return self::getVersion( $mod, $iVer, $sk, $lang, 'getVersionOnly' );
	}

    /**
     * get validity date for support
     * @param string    $mod    name of extension
     * @param string    $cVer   current version number
     * @param string    $sk     support key
     * @param string    $lang   user language
     * @return mixed
     */
    static public function isValidUntil( $mod, $iVer, $sk, $lang ) {
        $ret    = '';
        $data   = self::buildDataString( 'isValidUntil', $mod, $iVer, $sk, null, $lang );

        if( $ret = self::getData( $data ) ) {
            if( strpos( $ret, 'html' ) !== false ) {
                $ret = '';
            }
        }

        return $ret;
    }

    /**
     * do update
     * - 3 steps:
     *  1. get update key
     *  2. get update file
     *  3. do update
     * @param string    $mod    name of extension
     * @param string    $cVer   curretn version number
     * @param string    $sk     support key
     * @return mixed
     */
    static public function updateNow( $mod, $iVer, $sk, $backup ) {
        $ret    = false;
        $data   = self::buildDataString( 'getKey', $mod, $iVer, $sk );

        self::setMessage( 'FUNCTION [' . __FUNCTION__ . '] LINE [' . __LINE__ . ']' );
        self::setMessage( 'data [' . $data . ']' );

        // first action: get update data
        if( $ret = self::getData( $data ) ) {
            if( strpos( $ret, 'html' ) !== false ) {
                $ret = '';
            }

            self::setMessage( 'FUNCTION [' . __FUNCTION__ . '] LINE [' . __LINE__ . ']' );
            self::setMessage( 'ret [' . $ret . ']' );

            // 2nd & 3rd step
            if( $ret = self::getUpdateFile( $mod, $iVer, $sk, $ret, $backup ) ) {
                $ret = array(
                    'version'   => $ret,
                    'debug'     => self::getMessages( true ),
                    'statistic' => self::$_statistic
                );
            }else{
                $ret = false;
            }
        }

        return $ret;
    }

    /**
     * get update file
     * @param string    $mod    name of extension
     * @param string    $cVer   curretn version number
     * @param string    $sk     support key
     * @param string    $uk     update key (code)
     * @param bool      $backup do backup of old files
     * @return mixed
     */
    static private function getUpdateFile( $mod, $iVer, $sk, $uk, $backup ) {
        $ret    = false;
        $data   = self::buildDataString( 'getUpdateFile', $mod, $iVer, $sk, $uk );

        self::setMessage( 'FUNCTION [' . __FUNCTION__ . '] LINE [' . __LINE__ . ']' );
        self::setMessage( '$mod [' . $mod . ']' );
        self::setMessage( '$iVer [' . $iVer . ']' );
        self::setMessage( '$sk [' . $sk . ']' );
        self::setMessage( '$uk [' . $uk . ']' );
        self::setMessage( 'data [' . $data . ']' );

        if( $ret = self::getData( $data ) ) {
            if( strpos( $ret, 'html' ) !== false ) {
                $ret = '';
            }

            self::setMessage( 'FUNCTION [' . __FUNCTION__ . '] LINE [' . __LINE__ . ']' );
            self::setMessage( 'ret [' . $ret . ']' );

            // okay, request is valid, get file
            if(
                $ret
                && ( strpos( $ret, 'update|@' ) !== false )
            )
            {
                $file       = str_replace( 'update|@', '', $ret );
                $fileName   = basename( $file );
                $ext        = strrchr( $fileName, '.' );
                $version    = str_replace( $ext, '', $fileName );
                $folder     = self::getRootPath() . 'tmp' . self::$_DS;

                self::$_url = $file;

                self::setMessage( 'FUNCTION [' . __FUNCTION__ . '] LINE [' . __LINE__ . ']' );
                self::setMessage( 'ret [' . $ret . ']' );
                self::setMessage( 'URL from file [' . self::$_url . ']' );

                $ret = self::getData( $fileName, true );

                if( $ret === 'success'  ) {
                    if( strpos( $ret, 'html' ) !== false ) {
                        $ret = false;
                    }else{
                        $ret = false;
                        // check integrity
                        if( self::checkIntegrity( $folder . $fileName, $uk ) ) {
                            if( self::unzipPackage( $folder, $fileName ) ) {
                                // install now (copy files)
                                if( self::copyFiles
                                    (
                                        $folder . self::$_DS . 'install',
                                        self::$_root,
                                        ( $backup === 'true'
                                            ? self::$_root . 'backups' . self::$_DS . date('Ymd_His') . self::$_DS . $mod .'_'. $iVer
                                            : ''
                                        )
                                    )
                                )
                                {
                                    // remove all installer files
                                    if( self::removeDir( $folder ) ) {
                                        $ret = $version;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $ret;
    }

    /**
     * build the post string for cURL query
     * @param string    $type
     * @param string    $mod
     * @param string    $ver
     * @param string    $key
     * @param string    $ukey   update key [optional]
     * @param string    $lng    language [optional]
     * @return string
     */
    static private function buildDataString( $type, $mod, $ver, $key, $ukey = null, $lng = null ) {
        $str = $type . '|'
        . $mod . '|'
        . $ver . '|'
        . $key . '|'
        . ( $ukey ? $ukey : '' ) . '|'
        . ( $lng ? $lng : '' );

        return 'str=' . base64_encode( $str  );
    }

    /**
     * check integrity of recieved file by crc32
     * @param string    $file   file with qualified full path
     * @param string    $crc    recieved crc32 code
     * @return bool
     */
    static private function checkIntegrity( $file, $crc ) {
        $tmp = explode( '_', $crc );

        if( hash_file( 'crc32', $file ) === $tmp[0] ) {
            return true;
        }

        return false;
    }

    /**
     * recursive function to copy the content of a folder from a to b
     * @param string    $src            source folder to start from
     * @param string    $dest           destination folder to copy to
     * @param string    $bu             optional backup path
     * @param bool      $path           [optional]
     * @param bool      $force          [optional]
     * @param bool      $use_streams    [optional]
     * @return bool
     */
    static private function copyFiles( $src, $dest, $bu = '', $path = '', $force = false, $use_streams = false ) {
		@set_time_limit( ini_get( 'max_execution_time' ) );

        // eliminate trailing directory separators, if any
        $src        = self::cleanPath( $src, '', true );
        $dest       = self::cleanPath( $dest, '', true );
        $notAllowed = array( '.', '..' );

        if( $bu ) {
            $bu = self::cleanPath( $bu, '', true );
        }else{
            $bu = '';
        }

        // check if dest & bu directory exist, if not create them
        if( filetype( $src ) == 'dir' ) {
            self::checkDir( $dest, false );
            self::checkDir( $bu, false );
        }

        if( ( $dh = @opendir( $src ) ) ) {
        	// walk through the directory copying files and recursing into folders
        	while( ( $file = readdir( $dh ) ) !== false ) {
                if( !in_array( $file, $notAllowed ) ) {
            		$sfid = $src . self::$_DS . $file;
            		$dfid = $dest . self::$_DS . $file;

                    if( $bu ) {
                        $buid = $bu . self::$_DS . $file;
                    }else{
                        $buid = '';
                    }

            		switch( filetype( $sfid ) )
            		{
            			case 'dir':
                            if( empty( self::$_statistic['folders'] ) ) {
                                self::$_statistic['folders'] = 1;
                            }else{
                                ++ self::$_statistic['folders'];
                            }

        					$ret = self::copyFiles( $sfid, $dfid, $buid, $path, $force, $use_streams );

                            if( $ret !== true ) {
        						return $ret;
        					}

            				break;

            			case 'file':
                            if( empty( self::$_statistic['files'] ) ) {
                                self::$_statistic['files'] = 1;
                            }else{
                                ++ self::$_statistic['files'];
                            }

                            if( $bu ) {
                                // backup first
                                @copy( $dfid, $buid );
                            }

                            if( !@copy( $sfid, $dfid ) ) {
        						return false;
        					}

            				break;
            		}
                }
        	}

            closedir( $dh );
        }else{
            return false;
        }

        return true;
    }

    /**
     * call supportserver and get data - depending on task
     * @param string    $data   post data
     * @param bool      $dl     donwload file true|[false]
     * @return mixed
     */
	static private function getData( $data, $dl = false )  {
        if(
            function_exists( 'curl_init' )
            && in_array( 'curl', get_loaded_extensions() )
        )
        {
            // backward comp: OC 1.4.x do not have https
            $referrer = ( defined( 'HTTPS_CATALOG' ) ? HTTPS_CATALOG : HTTP_CATALOG );

            if( $dl ) {
                $toFolder = self::getRootPath() . 'tmp';
                self::checkDir( $toFolder );

                self::setMessage( 'FUNCTION [' . __FUNCTION__ . '] LINE [' . __LINE__ . ']' );
                self::setMessage( 'data [' . $data . ']' );
                self::setMessage( 'URL [' . self::$_url . ']' );
                self::setMessage( 'folder - file [' . $toFolder . self::$_DS . $data . ']' );

                $fp = fopen( $toFolder . self::$_DS . $data, 'wb' );
            }

            $ch = curl_init( self::$_url );

            if( $dl ) {
                curl_setopt( $ch, CURLOPT_ENCODING,         'gzip' );
                curl_setopt( $ch, CURLOPT_TIMEOUT,          30 );
                curl_setopt( $ch, CURLOPT_FILE,             $fp );
                curl_setopt( $ch, CURLOPT_BINARYTRANSFER,   true );
                // curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-type: multipart/form-data' ) );
            }else{
                curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT,   20 );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER,   true );
                curl_setopt( $ch, CURLOPT_POST,             true );
                curl_setopt( $ch, CURLOPT_POSTFIELDS,       $data );
            }

            curl_setopt( $ch, CURLOPT_USERAGENT,        'OSWorX ' . self::$_name .' '. self::$_version );
            curl_setopt( $ch, CURLOPT_REFERER,          $referrer );
            curl_setopt( $ch, CURLOPT_HEADER,           false );
            // curl_setopt( $ch, CURLOPT_NOBODY,        true );

            // if SSL shall be used, otherwise disable
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER,   false );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST,   2 ); // liburl > 7.27 needs 2

            if(
                ini_get( 'open_basedir' ) == ''
                && !ini_get( 'safe_mode' )
            )
            {
            	@curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
           	}

            if( $dl ) {
                curl_exec( $ch );
            }else{
                $ret = curl_exec( $ch );
            }

            if( curl_errno( $ch ) ) {
                $ret = curl_error( $ch );
            }

            curl_close( $ch );

            if( $dl ) {
                fclose( $fp );

                if( empty( $ret ) ) {
                    $ret = 'success';
                }
            }

            return $ret;
        }else{
            return false;
        }
    }

    /**
     * clean a URL
     * @param string    $var    basic string containing URL
     * @param string    $base   base URL, if set will be removed
     * @param bool      $trim   trim trailing slash
     * @return string
     */
    static private function cleanPath( $var, $base = '', $trim = false ) {
        if( $base ) {
            // remove base dir
            $var = str_replace( $base, '', $var );
        }

        // replace not needed slashes
        $var = str_replace(
            array( '\\', '/', '\\\\', '/\\', '//', '\\/', '\\//' ),
            self::$_DS,
            $var
        );

        if( $trim ) {
            $var = rtrim( $var, self::$_DS );
        }

        return $var;
    }

    /**
     * remove a directory (recursive if not empty)
     * @param string    $dir
     * @return bool
     * @see function rrmdir()
     */
    static private function removeDir( $dir ) {
        if( !file_exists( $dir ) ) {
            return;
        }

		if( is_file( $dir ) ) {
            chmod( $dir, 0777 ); // mic: because if WIN* is used
			unlink( $dir );
		}elseif( is_dir( $dir )) {
			self::rrmdir( $dir );
		}

        clearstatcache();

        return true;
    }

    /**
     * remove recursively a complete directory incl subfolders and files
     * @param string    $dir
     * @return bool
     * @see funtion removeDir()
     */
    static private function rrmdir( $dir ) {
        $dir = $dir . self::$_DS;
        $dir = self::cleanPath( $dir );

        $handle = null;

		if( is_dir( $dir ) ) {
			$handle = opendir( $dir );
		}

		if( !$handle ) {
			return false;
		}

        $excludes = array( '.', '..' );

		while( false !== ( $file = readdir( $handle ) ) ) {
			if( !in_array( strtolower( $file ), $excludes ) ) {
				if( !is_dir( $dir .self::$_DS. $file ) ) {
				    chmod( $dir . self::$_DS . $file, 0777 ); // mic: because for WIN*
					unlink( $dir . self::$_DS . $file );
				}else{
					self::rrmdir( $dir . self::$_DS . $file );
				}
			}
		}

		closedir( $handle );
        clearstatcache();

        if( is_dir( $dir ) ) {
            @rmdir( $dir );
        }

		return true;
	}

    /**
     * compare 2 versions
     * @param string    $iVer   installed
     * @param string    $cVer   current (returndedn value from support server)
     * @return mixed
     */
    static private function vCompare( $iVer, $cVer ) {
		return version_compare( $iVer, $cVer );
	}

    /**
     * create changelog text from returned value into readable text
     * @param string    $data
     * @return array
     */
    static private function getResult( $data ) {
        $array  = explode( '|#|', $data );
        $ret    = '';

        foreach( $array as $str ) {
            $arr = explode( '|', $str );

            foreach( $arr as $k => $v ) {
                if( $v ) {
                    ( empty( $ret['latest'] ) ? $ret['latest'] = $arr[0] : '' );
                    (
                        !empty( $ret['changelog'] )
                        ? $ret['changelog'] .= $v . '<br />'
                        : $ret['changelog'] = $v . '<br />'
                    );
                }
            }

            $ret['changelog'] .= '<hr />';
        }

        return str_replace( '<hr /><hr />', '<hr />', $ret );
    }

    /**
     * get root path
     * @return string
     */
    static private function getRootPath() {
        if( !is_null( self::$_root ) ) {
            return self::$_root;
        }

        $ret    = str_replace( 'system/', '/', DIR_SYSTEM );
        // avoid invalide path
        $ret    = str_replace( array( '//', '\\\\' ), '', $ret ) . '/';
        $ret    = str_replace( '/', self::$_DS, $ret );

        self::$_root = $ret;

        return $ret;
    }

    /**
     * check if a dir exist
     * if not, create it - also all subdirs inside
     * @param string    $dir
     */
    static private function checkDir( $dir, $index = true ) {
        $path   = '';
        // remove possible root path and trailing slash
        $dir    = str_replace( trim( self::$_root, self::$_DS ), '', $dir );
        $dirs   = explode( self::$_DS, $dir );

        if( !file_exists( self::$_root . $dir ) ) {
            foreach( $dirs as $d ) {
                if( $path ) {
                    $path = $path . self::$_DS . $d;
                }else{
                    $path = $d;
                }

                if( !file_exists( self::$_root . $path ) ) {
        			if( mkdir( self::$_root . $path ) ) {
                        if( $index ) {
                            $index  = '<!DOCTYPE html><title></title>';
                            $file   = self::$_root . $path . self::$_DS . 'index.html';
                            $handle	= fopen( $file, 'wb' );

                    		fwrite( $handle, $index );
                    		fclose( $handle );
                        }
                    }else{
                        break;
                    }
                }
            }
        }
    }

    /**
     * unzip the package
     * @param string    $folder
     * @param string    $fileName
     * @return bool
     */
    static private function unzipPackage( $folder, $fileName ) {
        $path       = self::$_root . 'osworx' . self::$_DS . 'libraries'
                    . self::$_DS. 'filesystem' . self::$_DS . 'archive' . self::$_DS;
        $fileExt    = pathinfo( $fileName, PATHINFO_EXTENSION );
        $source     = $folder . $fileName;
        $target     = $folder . 'install' . self::$_DS;
        $ret        = false;

        if( file_exists( $source ) ) {
            // create temporary install dir
            if( !is_dir( $target ) ) {
                if( !mkdir( $target, 0777, true ) ) {
                    return false;
                }
            }

            switch( $fileExt ) {
                case 'zip':
                    // depending on php.version we call the correct one
                    if( version_compare( PHP_VERSION, '5.2.0' ) >= 0 ) {
                        $zip = new ZipArchive;

                        if( $zip->open( $source ) === true ) {
                             $zip->extractTo( $target );
                             $zip->close();

                             $ret = true;
                        }
                    }else{
                        require_once( $path. 'Zip.php' );
                        $archive = new Zip( $source );

                        if( $archive->extractZip( $target ) ) {
                            $ret = true;
            	        }
                    }
                    break;

                case 'gz':
                    require_once( $path. 'PEAR.php' );
            		require_once( $path. 'Tar.php' );

        			$archive = new Archive_Tar( $source );
        	        $archive->setErrorHandling( PEAR_ERROR_PRINT );

        	        if( $archive->extractModify( $target, '' ) ) {
        	            $ret = true;
        	        }

                    break;
            }
        }

        return $ret;
    }

    /**
     * set a debug message into array
     * @param string
     */
    static private function setMessage( $text ) {
        self::$_debugMsg[] = $text;
    }

    /**
     * get recorded debug messages IF debug = true
     * @param bool  $asString   format message array as string
     * @return string
     */
    static private function getMessages( $asString = false ) {
        $ret = '';

        if( self::$_debug ) {
            if( $asString ) {
                foreach( self::$_debugMsg as $msg ) {
                    $ret .= $msg . '<br />' . "\n";
                }
            }else{
                $ret = self::$_debugMsg;
            }
        }

        return $ret;
    }

    /**
     * display recorded debug messages
     * - IF FirePHP is installed
     * - OR as 'plain' javascript
     * @param string    $type   e.g. info [info]
     */
    static private function displayMessages( $type = 'info' ) {
        if( class_exists( 'FB' ) ) {
            foreach( self::$_debugMsg as $msg ) {
                FB::$type( self::$_name .': '. $msg );
            }

            return true;
        }else{         ?>
            <script type="text/javascript">
                <?php
                foreach( self::$_debugMsg as $msg ) { ?>
                    console.log( <?php echo $msg; ?> );
                    <?php
                } ?>
            </script>
            <?php
        }
    }
}