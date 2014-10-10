<?php
/**
 * @version		$Id: directebanking_cpanel.tpl 3100 2013-03-04 18:11:21Z mic $
 * @package		Voucher - Template Admin
 * @copyright	(C) 2011 mic [ http://osworx.net ]. All Rights Reserved.
 * @author		mic http://osworx.net
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
		</div>

		<div class="content">
			<div id="cpanel">
				<div class="icons">
					<?php
					if( !empty( $icons ) ) {
						foreach( $icons as $icon ) {
							echo $icon;
						}
					}

					if( $outDated ) { ?>
						<div class="clear"></div>
						<div class="cpanelMessage">
							<?php echo $xmlReply; ?>
						</div>
						<?php
					} ?>
				</div>

				<div class="summary">
					<div id="accordion">
						<h3><a href="#"><?php echo $text_common; ?></a></h3>

						<div>
							<div class="summary_text">
								<div class="summary_section">
									<?php echo $text_module; ?>
								</div>
								<div class="summary_description">
									<?php echo $extension; ?>
								</div>
								<div class="clear"></div>
							</div>

							<div class="summary_text">
								<div class="summary_section">
									<?php echo $text_installed; ?>
								</div>
								<div class="summary_description">
									<?php echo $version; ?>
								</div>
								<div class="clear"></div>
							</div>

							<div class="summary_text">
								<div class="summary_section">
									<?php echo $text_current; ?>
								</div>
								<div class="summary_description<?php echo ( $class ? ' '. $class : '' ); ?>">
									<?php echo $cVersion; ?>
								</div>
								<div class="clear"></div>
							</div>

							<div class="summary_text">
								<div class="summary_section">
									<?php echo $text_license; ?>
								</div>
								<div class="summary_description">
									OSWorX Commercial
								</div>
								<div class="clear"></div>
							</div>

							<div class="summary_text">
								<div class="summary_section">
									<?php echo $text_copyright; ?>
								</div>
								<div class="summary_description">
									<?php echo date('Y'); ?> OSWorX
								</div>
								<div class="clear"></div>
							</div>

							<div class="summary_text">
								<div class="summary_section">
									<?php echo $text_author; ?>
								</div>
								<div class="summary_description">
									<a href="http://osworx.net" target="_blank" title="OSWorX">OSWorX</a>
								</div>
								<div class="clear"></div>
							</div>

							<div class="summary_text">
								<div class="summary_section">
									<?php echo $text_support; ?>
								</div>
								<div class="summary_description">
									<a href="<?php echo $links['help']['href']; ?>" target="_blank" title="<?php echo $links['help']['title']; ?>"><?php echo $links['help']['title']; ?></a>
								</div>
								<div class="clear"></div>
							</div>

							<div class="summary_text">
								<div class="summary_section">
									<?php echo $text_support_key; ?>
								</div>
								<div class="summary_description">
									<?php echo $supportKey; ?>
								</div>
								<div class="clear"></div>
							</div>
						</div>

						<h3><a href="#"><?php echo $text_advanced; ?></a></h3>
						<div>

							<div class="summary_text">
								<div class="summary_section">
									<?php echo $text_total_amount; ?>
								</div>
								<div class="summary_description">
									<?php echo $cpanel_total_amount; ?>
								</div>
								<div class="clear"></div>
							</div>

							<div class="summary_text">
								<div class="summary_section">
									<?php echo $text_total_used; ?>
								</div>
								<div class="summary_description">
									<?php echo $cpanel_total_used; ?>
								</div>
								<div class="clear"></div>
							</div>

							<div class="summary_text">
								<div class="summary_section">
									<?php echo $text_total_percent; ?>
								</div>
								<div class="summary_description">
									<?php echo $cpanel_total_percent; ?>&nbsp;%
								</div>
								<div class="clear"></div>
							</div>

						</div>
					</div>
				</div>
                <div class="clear"></div>
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
<div id="showtip">&nbsp;</div>
<script type="text/javascript">
	/* <![CDATA[ */
	jQuery(function() {
		jQuery("#accordion").accordion({
			collapsible	: true,
			autoHeight	: false
		});
	});
	/* ]]> */
</script>
<?php echo $footer; ?>