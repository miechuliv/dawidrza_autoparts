<?php
/**
 * @version		$Id: file.php 3050 2013-02-04 20:45:07Z mic $
 * @package		EU-Cookie
 * @author		mic - http://osworx.net
 * @copyright	2012 OSWorX - http://osworx.net
 * @license		AGPL - www.gnu.org/licenses/agpl-3.0.html GPL - www.gnu.org/copyleft/gpl.html / OCL OSWorX Commercial - http://osworx.net
 */

echo 'FILE [' . __FILE__ . ']<br />';
echo 'post:<br />' . "\n";
print_r( $_POST );
echo '</pre>' . "\n";
echo 'file:<br />' . "\n";
print_r( $_FILES );
echo '</pre>' . "\n";