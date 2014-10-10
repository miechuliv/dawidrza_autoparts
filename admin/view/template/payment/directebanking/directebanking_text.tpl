<?php
/**
 * @version		$Id: directebanking_text.tpl 2957 2012-10-26 11:00:06Z mic $
 * @package		Directebanking - Template Admin
 * @copyright	(C) 2011 mic [ http://osworx.net ]. All Rights Reserved.
 * @author		mic - http://osworx.net
 * @license		OSWorX Commercial License http://osworx.net
 */

echo $header;

// OC 1.5.x
if( isset( $breadcrumbs ) ) { ?>
	<div id="content">
		<div class="breadcrumb">
			<?php
			foreach( $breadcrumbs as $breadcrumb ) {
				echo $breadcrumb['separator']; ?>
				<a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
				<?php
			} ?>
		</div>
		<?php
	}
	if( !empty( $success ) ) { ?>
	<div class="success"><?php echo $success; ?></div>
		<?php
	}
	if( !empty( $error_warning ) ) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
		<?php
	}
	if( ${$_name . '_testMode'} ) { ?>
		<div class="note">
			<div class="note_img"></div>
			<div class="note_text"><?php echo $msg_test_mode_on; ?></div>
		</div>
		<div style="clear:both;"></div>
		<?php
	} ?>
	<div class="box">
		<?php
		// OC 1.4.x
		if( !isset( $breadcrumbs ) ) { ?>
			<div class="left"></div>
			<div class="right"></div>
			<div class="heading">
				<h1 style="background-image: url('view/image/<?php echo(!empty( $_type ) ? $_type : 'module' ); ?>.png');"><?php echo $plain_title; ?></h1>
			<?php
		}else{
			/* OC 1.5.x */ ?>
			<div class="heading">
				<h1><img src="view/image/<?php echo( !empty( $_type ) ? $_type : 'module' ); ?>.png" alt="" />&nbsp;<?php echo $plain_title; ?></h1>
			<?php
		} ?>
			<div class="buttons">
				<a onclick="addMode();" class="button" title="<?php echo $button_save; ?>"><span><?php echo $button_save; ?></span></a>
				<a onclick="addMode('apply');" class="button" title="<?php echo $button_apply; ?>"><span><?php echo $button_apply; ?></span></a>
				<a onclick="location='<?php echo $links['cancel']; ?>';" class="button" title="<?php echo $button_cancel; ?>"><span><?php echo $button_cancel; ?></span></a>
			</div>
		</div>

		<div class="content">
			<form action="<?php echo $links['action']; ?>" method="post" enctype="multipart/form-data" id="form">
				<div class="info">
					<div class="info_img"></div>
					<div class="info_text"><?php echo $help_instruction; ?></div>
				</div>
				<div style="clear:both;"></div>

				<div id="tabs" class="htabs">
					<?php
					foreach( $languages as $lang ) {
						if( $lang['status'] ) { ?>
							<a <?php echo $tab; ?>="#language<?php echo $lang['language_id']; ?>"><img src="view/image/flags/<?php echo $lang['image']; ?>" title="<?php echo $lang['name']; ?>" /> <?php echo $lang['name']; ?></a>
							<?php
						}
					} ?>
		        </div>
		        <?php
				foreach( $languages as $lang ) {
					if( $lang['status'] ) { ?>
						<div id="language<?php echo $lang['language_id']; ?>">
							<div class="form">
								<div style="width:97%; float:left; margin:5px 0 5px 15px; padding-bottom:5px; border-bottom:1px dotted #CECECE;">
									<div style="width:200px; float:left;">
										<?php echo $entry_title; ?>
									</div>
									<div style="float:left; margin-left:20px;">
										<input type="text" name="<?php echo $_name; ?>_title_<?php echo $lang['language_id']; ?>" id="<?php echo $_name; ?>_title_<?php echo $lang['language_id']; ?>" value="<?php echo ${$_name . '_title_' . $lang['language_id']}; ?>" size="60" />
									</div>
									<div style="clear:both;"></div>
								</div>
								<div style="width:600px; margin:10px; padding:10px;">
									<?php echo $sample[$lang['language_id']]; ?>
								</div>
								<div style="width:600px; border:1px solid #DDDDDD; margin:10px; padding:10px; color:#7B7B7B;">
									<?php echo htmlentities( $sample[$lang['language_id']], ENT_NOQUOTES, 'utf-8' ); ?>
								</div>
							</div>
							<div class="form" style="width:60%;">
								<textarea class="mceEditor" name="<?php echo $_name . '_instruction_' . $lang['language_id']; ?>" id="<?php echo $_name . '_instruction_' . $lang['language_id']; ?>" style="width:400px; height:250px;"><?php echo ${$_name . '_instruction_' . $lang['language_id']}; ?></textarea>
							</div>
						</div>
						<?php
					}
				} ?>
			</form>
		</div>
		<?php
		echo $oxfooter;
	// OC 1.5.x
	if( isset( $breadcrumbs ) ) { ?>
		</div>
		<?php
	} ?>
</div>
<div id="showtip">&nbsp;</div>
<script type="text/javascript">
	/* <![CDATA[ */
	// editor section
	// url valid for all editors
	var filemgr = '<?php echo $links['filemgr']; ?>';

	<?php
	if( $editor == 'ckeditor' ) { ?>
		// CKEditor section
		// prepare vars for external script (for replacement of <textarea>)
		// assign languages - php.array to js.array
		var langs	= new Array();

		<?php
		$i = 0;
		foreach( $languages as $language ) {
			echo 'langs[\'' . $i . '\'] = \'' . $language['language_id'] . '\';' . "\n";
			++$i;
		} ?>

		// assign fields for replacement
		var fields	= new Array();
		fields[0] = '<?php echo $_name; ?>' + '_instruction_';

		initCkeditor( langs, fields );
		<?php
	}elseif( $editor == 'tinymce' ) { ?>
		// tinymce section
		initTinymce( '<?php echo $code; ?>', true, '<?php echo $baseUrl; ?>' );
		<?php
	} ?>

	<?php if( $_ocversion == '1.4' ) { ?>
		jQuery.tabs('#tabs a');
		<?php
	}else{ ?>
		jQuery('#tabs a').tabs();
		<?php
	} ?>
	/* ]]> */
</script>
<?php echo $footer;