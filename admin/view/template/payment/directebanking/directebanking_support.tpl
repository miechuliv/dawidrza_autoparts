<?php
/**
 * @version		$Id: directebanking_support.tpl 3101 2013-03-04 18:11:53Z mic $
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
            <div id="tabs" class="htabs">
                <?php echo $support['tab']; ?>
			</div>
			<form action="<?php echo $links['action']; ?>" method="post" enctype="multipart/form-data" id="form">
				<?php eval( $support['tpl'] ); ?>
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
<script type="text/javascript">
	/* <![CDATA[ */
    <?php if( $_ocversion == '1.4' ) { ?>
		jQuery.tabs('#tabs a');
        jQuery.tabs('#subtabs a');
		<?php
	}else{ ?>
		jQuery('#tabs a').tabs();
        jQuery('#subtabs a').tabs();
		<?php
	} ?>
	<?php eval( $support['js'] ); ?>
	/* ]]> */
</script>
<?php echo $footer;