<?php
/**
 * @version		$Id: directebanking_log.tpl 2957 2012-10-26 11:00:06Z mic $
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
				<?php
				if( $log['notEmpty'] ) { ?>
					<a href="<?php echo $links['clear']; ?>" class="button" style="text-decoration: none;"><span><?php echo $button_clear; ?></span></a>
					<?php
				} ?>
				<a onclick="location='<?php echo $links['cancel']; ?>';" class="button" title="<?php echo $button_cancel; ?>"><span><?php echo $button_cancel; ?></span></a>
			</div>
		</div>

		<div class="content">
			<div id="tab_log">
				<div class="info">
					<div class="info_img"></div>
					<div class="info_text"><?php echo $help_logText; ?></div>
				</div>
				<div style="clear:both;"></div>

				<div class="content">
					<div class="log"><?php echo $log['content']; ?></div>
				</div>
			</div>
		</div>
		<?php echo $oxfooter; ?>
		<?php
	// OC 1.5.x
	if( isset( $breadcrumbs ) ) { ?>
		</div>
		<?php
	} ?>
</div>
<?php echo $footer;