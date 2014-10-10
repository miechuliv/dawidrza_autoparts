<?php
/**
 * @version		$Id: OXGENList.php 2507 2011-10-30 18:06:57Z mic $
 * @package		NAME - Module 4 OpenCart_pay_directEbanking
 * @copyright	(C) 2010 mic [ http://osworx.net ]. All Rights Reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author		mic [ http://osworx.net ]
 */

class OXGENList
{

	public function __contruct() {}

	/**
	 * builds a select list
	 * @param array		$arr		array with values (id, name)
	 * @param string	$name		name for this list
	 * @param int		$selected	value if the stored, selected id
	 * @param array		$attribs	additional HTML attributes for the <select> tag (e.g. some javascript)[mic: prepared only]
	 * @param bool		$intro		display the intro lines
	 * @param bool		$css		use css styling
	 * @param string	$lngVar		(translated) value for intro
	 * @param array		$index		optional: if id and name have other keys/names
	 * @return array
	 */
	static function buildSelectList( $arr, $name, $selected, $attribs = null, $intro = true, $css = true, $lngVar = null, $index = null ) {
		$id		= $name;
		$k		= 0;
		$css0	= ' style="background-color: #F9F9F9;"';
		$css1	= ' style="background-color: #D4EDFE;"';
		$lngVar = $lngVar ? $lngVar : '-- Please select --';
		$key	= $index ? $index['id'] : 'id';
		$val	= $index ? $index['name'] : 'name';

		$ret = '<select name="'. $name .'" id="'. $id .'" '. $attribs .'>'
		. ( $intro ?
			"\n" . '<option value="-1" style="color:#666666;">' . $lngVar . '</option>'
			. "\n" . '<option value="" style="color:#666666;" disabled="disabled">- - - - - - - - - -</option>'
			: ''
		)
		;

		foreach( $arr as $ar ) {
			$extra	= '';
			if( $ar[$key] == $selected ) {
				$extra = ' selected="selected"';
			}
			$ret .= "\n" . '<option value="'. $ar[$key] .'"'. $extra
			. ( $css ? ( ( $k == '0' ) ? $css0 : $css1 ) : '' )
			. '>' . $ar[$val] . '</option>';

			$k = 1 - $k;
		}

		$ret	.= '</select>' . "\n";

		return $ret;
	}

	/**
	 * builds a radio list
	 * note: if {NBR} is in $attribs, it will be replaced with the key (e.g. for dynamically switching rows in the template)
	 * @param array		$arr		values
	 * @param string	$name		name of this option
	 * @param int		$selected	selected key
	 * @param array		$attribs	additional HTML attributes for the <select> tag (e.g. some javascript)
	 * @return string
	 */
	static function buildRadioList( $arr, $name, $selected = null, $attribs = null ) {
		$ret	= '';
		$k		= 0;

		foreach( $arr as $key => $value ) {
			$attrib = str_replace( '{NBR}', $key, $attribs );
			$extra	= '';

			if( $key == $selected ) {
				$extra = ' checked="checked"';
			}

			$ret .= "\n" . '<input type="radio" name="' . $name . '" id="' . $name . $k . '" value="' . $key . '"' . $extra . $attrib . ' />'
			. "\n" . '<label for="' . $name . $k . '">' . $value . '</label>';
			++$k;
		}

		return $ret;
	}

	/**
	 * generates a generic yes/no radio list
	 * @param string	$name		value of the HTML name attribute
	 * @param string	$selected	selected key
	 * @param array		$attribs	additional HTML attributes for the <select> tag (e.g. some javascript)
	 * @returns string HTML for the radio list
	 */
	static function booleanlist( $name, $selected = null, $attribs = null, $langArr = null ) {
		if( !$langArr ) {
			$langArr = array(
				'1' => 'Yes',
				'0' => 'No'
			);
		}

		return self::buildRadioList( $langArr, $name, $selected, $attribs );
	}
}