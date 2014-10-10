// <?php !! This fools phpdocumentor into parsing this file
/**
 * @version		$Id: editor.js 2507 2011-10-30 18:06:57Z mic $
 * @package		System library file for editor
 * @copyright	(C) 2010 mic [ http://osworx.net ]. All Rights Reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author		mic [ http://osworx.net ]
 */

// section CKEditor
function initCkeditor( langs, fields ) {
	if( langs ) {
		for( f in fields ) {
			for( l in langs ) {
				CKEDITOR.replace( fields[f] + langs[l], {
					filebrowserBrowseUrl: filemgr,
					filebrowserUploadUrl: filemgr,
					filebrowserImageBrowseUrl: filemgr,
					filebrowserImageUploadUrl: filemgr,
					filebrowserFlashBrowseUrl: filemgr,
					filebrowserFlashUploadUrl: filemgr
				});
			}
		}
	}else{
		for( f in fields ) {
			CKEDITOR.replace( fields[f], {
				filebrowserBrowseUrl: filemgr,
				filebrowserUploadUrl: filemgr,
				filebrowserImageBrowseUrl: filemgr,
				filebrowserImageUploadUrl: filemgr,
				filebrowserFlashBrowseUrl: filemgr,
				filebrowserFlashUploadUrl: filemgr
			});
		}
	}
};

// section: tinyMCE
/**
 * langCode		operate editor in this language
 * specific		convert textareas only with this class
 * baseUrl		documents base URL
 * forceBr		forcing br.tags instead p.tags
 * @see http://tinymce.moxiecode.com/wiki.php/Configuration
 */
function initTinymce( langCode, specific, baseUrl, forceBr ) {
	if( !specific ) {
		convert = '';
	}else{
		convert = 'specific_';
	}

	if( typeof forceBr == 'undefined' ) {
		// use p
		var frb = '';
		var fbn = false;
		var fpn = true;
	}else{
		// use br
		var frb = false;
		var fbn = true;
		var fpn = false;
	}

	tinyMCE.init({
		language		: langCode,
		theme			: 'advanced',
		skin			: 'o2k7',
		skin_variant	: 'silver',
		mode			: convert + 'textareas',
		editor_selector	: ( convert != '' ? 'mceEditor' : '' ),
		document_base_url: baseUrl,
		content_css		: 'view/stylesheet/editor.css',
		relative_urls	: false,
		remove_script_host : false,
		entity_encoding : 'raw',
		// force br instead p tags
		force_br_newlines	: fbn,
		force_p_newlines	: fpn,
		forced_root_block	: frb,
		plugins			: 'advimage,advlink,media,safari,spellchecker,pagebreak,style,layer,table,save,advhr,advlink,emotions,inlinepopups,searchreplace,print,contextmenu,paste,directionality,fullscreen,visualchars,nonbreaking,xhtmlxtras,preview',

		//plugins (not used) 	: 'iespell,insertdatetime,noneditable,template,imagemanager,filemanager,fancybox',

		theme_advanced_toolbar_location		: 'top',
		theme_advanced_toolbar_align		: 'left',
		theme_advanced_statusbar_location	: 'bottom',
		theme_advanced_resizing				: true,
		theme_advanced_styles				: 'Header 1=header1;Header 2=header2;Header 3=header3;Table Row=tableRow1',
		theme_advanced_blockformats			: 'p,div,h1,h2,h3,h4,h5,h6,blockquote,dt,dd,code,samp',

		file_browser_callback				: 'fileBrowserPDW',

		//filemgr							: filemgr, // mic: not used, button is imgmanager

		// Theme options
		theme_advanced_buttons1 : 'bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect,|,forecolor,backcolor',
		theme_advanced_buttons2 : 'undo,redo,|,cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,blockquote,|,link,unlink,anchor,image,media,|,help',
		theme_advanced_buttons3 : 'cleanup,removeformat,visualaid,|,sub,sup,|,charmap,|,emotions,advhr,ltr,rtl,fullscreen,|,visualchars,nonbreaking,blockquote,pagebreak,|,insertlayer,absolute,moveforward,movebackward,|,code',
		theme_advanced_buttons4 : 'cite,abbr,acronym,del,ins,attribs,|,tablecontrols,|,search,replace,|,preview,styleprops'

		// mic: not used: save,newdocument,insertdate,inserttime,iespell,print,imgmanager,fullpage,hr,template,styleprops,insertfile,insertimage,spellchecker

		//theme_advanced_buttons3_add : ''
	}
)};

// pdw filebrowser
function fileBrowserPDW(field_name, url, type, win) {

    //fileBrowserUrl = "http://..path../system/osworx/filebrowser/index.php?editor=tinymce&filter=" + type;

	//alert( 'fileBrowserUrl [' + fileBrowserUrl + ']' );
    //alert("Field_Name: " + field_name + "\nURL: " + url + "\nType: " + type + "\nWin: " + win); // debug/testing

    // compatibility
    if( typeof fileBrowserUrl == 'undefined' ) {
    	var fileBrowserUrl = filemgr;
    }

    tinyMCE.activeEditor.windowManager.open({
        title			: 'Media Browser',
        url				: fileBrowserUrl + type,
        file			: fileBrowserUrl + type,
        width			: 950,
        height			: 650,
        inline			: 0,
        maximizable		: 1,
        close_previous	: 0
      },{
        window : win,
        input : field_name
      });

      return false;
};

// former filebrowser
function fileBrowser( field_name, url, type, win ) {

    //alert("Field_Name: " + field_name + "\nURL: " + url + "\nType: " + type + "\nWin: " + win); // debug/testing

    tinyMCE.activeEditor.windowManager.open({
        file			: filemgr + '&ed=tinymce',
        title			: 'Image / File Browser',
        width			: 850,
        height			: 400,
        resizable		: 1,		// 'yes',
        inline			: 1,		// 'yes',  // This parameter only has an effect if you use the inlinepopups plugin!
        maximizable		: 1,
        popup_css		: false,	// Disable TinyMCE's default popup CSS
        close_previous	: 0			// 'no'
    }, {
        window	: win,
        input	: field_name
    });
    return false;
};

function toggleEditor( id ) {
	if( !tinyMCE.get( id ) ) {
		tinyMCE.execCommand( 'mceAddControl', false, id );
	}else{
		tinyMCE.execCommand( 'mceRemoveControl', false, id );
	}
};