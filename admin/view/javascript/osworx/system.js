// <?php !! This fools phpdocumentor into parsing this file
/**
 * @version		$Id: system.js 2961 2012-10-31 14:21:46Z mic $
 * @package		Various - System library file for OSWorX Extensions
 * @copyright	(C) 2010 mic [ http://osworx.net ]. All Rights Reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author		mic [ http://osworx.net ]
 */

// check if vars are set
if( typeof nothingSelected == undefined ) {
	var nothingSelected = 'Please select at least one item';
}
if( typeof textConfirm == undefined ) {
	var textConfirm = 'Please confirm this action, it cannot be undone!';
}

jQuery(document).ready(function() {
	jQuery("span[title]").tooltip({
		tip				: '#showtip',
		effect			: 'fade',
		lazy			: false,
		fadeOutSpeed	: 100,
		predelay		: 400,
		position		: 'bottom right',
		offset			: [-10, 0]
	}).dynamic( {
    bottom: {
        direction		: 'down',
        bounce			: true
    	}
	});
});
function addMode( mode, check, theForm, n, aText, fldName, action ) {
	var ret = false;
	var doit = false;

	if( !aText ) {
		aText = nothingSelected;
	}

	if( check == true ) {
		if( isChecked( theForm, n, fldName ) ) {
			ret = true;
		}else{
			alert( aText );
		}
	}else{
		ret = true;
	}

	if( ret == true ) {
		if( mode ) {
			jQuery('#form').append('<input type="hidden" name="mode" value="' + mode + '" />');
		}
		if( action ) {
			jQuery('#form').append('<input type="hidden" name="act" value="' + action + '" />');
		}
	}

	// check if action is delete**** and show confirm popup
	var patt = new RegExp( 'delete' );
	var result = patt.test( action );

	if( result ) {
		if( !show_confirm() ) {
			ret = false;
		}
	}

	if( ret ) {
		jQuery('#form').submit();
	}

	return false;
};
function show_confirm(){
	var r = confirm(textConfirm);
	if( r == true ) {
		return true;
	}else{
		return false;
	}
};
function isChecked( theForm, n, fldName ) {
	var ret = false;

	if( !fldName ) {
		fldName = 'cb';
	}
	if( !theForm ) {
		theForm = document.form;
	}

	for( i = 0; i < n; i++ ) {
		field = fldName + '' + i;

		if( getid( field ).checked == true ) {
			ret = true;
		}
	}

	return ret;
};
function show_hide( field, val ) {
	if( val == '1' || val == 'show' ) {
		getid( field ).style.display = 'block';
	}else{
		getid( field ).style.display = 'none';
	}
};
function getid( id ) {
	return document.getElementById( id );
};
function submitContent( editor, mode, name ){
	var textContent = editAreaLoader.getValue(editor);
	jQuery('#form').append('<input type="hidden" name="' + name + '" value="' + textContent + '" />');
	if( mode ) {
		addMode(mode);
	}
};