<?php
/**
 * @version		$Id: Zip.php 3050 2013-02-04 20:45:07Z mic $
 * @package		Tools
 * @author		mic - http://osworx.net
 * @copyright	2012 OSWorX - http://osworx.net
 * @license		OSWorX Commercial
 */

/**
 * class zip
 * read infos, make or extract a zip archive
 * sample:
 * $zip = new Zip( $scr );
 * $zip->extract( $file );
 */
class Zip
{
    private $_src;

    /**
     * contructor
     */
    public function __construct( $source ) {
        $this->_src = $source;
    }

    /**
     * check if library is loaded
     * @return bool
     */
    public function isSupported() {
        if( !extension_loaded( 'zip' ) ) {
            return false;
        }

        return true;
    }

    /**
     * get several infos from zipped file
     * @param bool      $readme try to get content of possible existing readme file
     * @param bool      $asText return data as text (true) or array (false)
     * @param bool      $stat   return infos (size, etc.) about each file
     * @param bool      $data   read content of each zipped file
     * @return mixed string/array/bool
     */
    public function infosZip(
        $readme = true,
        $asText = true,
        $stat = false,
        $data = false
    )
    {
        if(
            ( $zip = zip_open( realpath( $this->_src ) ) )
        )
        {
            $ret    = array();
            $txt    = '';
            $info   = '';

            while( ( $zip_entry = zip_read( $zip ) ) ){
                $path = zip_entry_name( $zip_entry );

                if(
                    zip_entry_open( $zip, $zip_entry, 'rb' )
                )
                {
                    if( $stat ) {
                        $ratio = (
                            zip_entry_filesize( $zip_entry )
                            ? round( 100 - zip_entry_compressedsize( $zip_entry ) / zip_entry_filesize( $zip_entry ) * 100, 1 )
                            : '0'
                        );

                        $sizeComp   = zip_entry_compressedsize( $zip_entry );
                        $sizeNorm   = zip_entry_filesize( $zip_entry );

                        if( $asText ) {
                            $txt .= $path . "\n"
                            . 'Ratio: '
                                . $ratio
                            . "\n"
                            . 'CompressedSize: '
                                . $sizeComp
                            . "\n"
                            . 'NormalSize: '
                                . $sizeNorm
                            . "\n_______________\n";
                        }else{
                            $ret[$path] = array (
                                'Ratio'             => $ratio,
                                'Compressed Size'   => $sizeComp,
                                'Normal Size'       => $sizeNorm
                            );
                        }
                    }

                    if( $data ) {
                        $ret[$path]['Data'] = zip_entry_read(
                            $zip_entry,
                            zip_entry_filesize( $zip_entry )
                        );
                    }

                    if( $readme ) {
                        if( preg_match( '/readme.txt/', $path ) ) {
                            $info = zip_entry_read(
                                $zip_entry,
                                zip_entry_filesize( $zip_entry )
                            );
                        }

                        if( !$asText ) {
                            $ret[$path]['readme'] = $info;
                        }
                    }

                    zip_entry_close( $zip_entry );
                }
            }

            zip_close( $zip );

            if( $asText ) {
                $ret = ( $txt ? $txt . "\n\n~~~~~~~~~~~~~~~~~\n\n" : $info );
            }

            return $ret;
        }

        return false;
    }

    /**
     * extract a .zip-file
     * @param string    $dest   path to extract to
     * @return bool
     */
    public function extractZip( $dest ) {
        if( is_dir( $dest ) ) {
            $zip = new ZipArchive;

            if( $zip->open( $this->_src ) === true ) {
                $zip->extractTo( $dest );
                $zip->close();

                unlink( $this->_src );

                return true;
            }
        }

        return false;
    }

    /**
     * create a new zip archive
     * @param string    $dest   name (w. path) to zipped file
     * @return bool
     */
    public function makeZip( $dest ) {
        $zip = new ZipArchive;
        $src = is_array( $this->_src ) ? $this->_src : array( $this->_src );

        if( $zip->open( $dest, ZipArchive::CREATE ) === true) {
            foreach( $src as $item ) {
                if( file_exists( $item ) ) {
                    $this->addZipItem(
                        $zip,
                        realpath( dirname( $item ) ) . '/',
                        realpath( $item ) . '/'
                    );
                }
            }

            $zip->close();

            return true;
        }

        return false;
    }

    /**
     * recursive function add a single item
     * @param resource  $zip
     * @param string    $racine single item
     * @param string    $dir
     * @see makeZip()
     */
    private function addZipItem( $zip, $racine, $dir ) {
        if( is_dir( $dir ) ) {
            $zip->addEmptyDir( str_replace( $racine, '', $dir ) );
            $lst = scandir( $dir );
                array_shift( $lst );
                array_shift( $lst );

            foreach( $lst as $item ) {
                $this->addZipItem(
                    $zip,
                    $racine,
                    $dir . $item . ( is_dir( $dir . $item ) ?'/' :'' ) );
            }
        }elseif( is_file( $dir ) ) {
            $zip->addFile(
                $dir,
                str_replace( $racine, '', $dir )
            );
        }
    }

    /**
     * compress a file/folder (w. content) and force for downloading
     * notes:
     *  - filename will be constructed with a random number (32 chars)
     *  - after download file will be deleted automatically
     *  - file will be created temporarely in sys temp dir
     * @see makeZip()
     */
    public function zipDownload() {
        $name = md5( uniqid( '', true ) ) . '.zip';
        $dest = tempnam( sys_get_temp_dir(), 'zip' );

        $this->makeZip( $dest );

        header( 'Pragma: no-cache' );
        header( 'Expires: 0');
        header( 'Content-Description: File Transfer' );
        header( 'Content-type: application/zip');
        header( 'Content-Disposition: attachment; filename=' . $name );
        header( 'Content-Transfer-Encoding: binary' );

        readfile( $dest );
        @unlink( $dest );

        exit;
    }
}