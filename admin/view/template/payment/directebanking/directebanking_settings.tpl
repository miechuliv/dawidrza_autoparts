<?php
/**
 * @version		$Id: directebanking_settings.tpl 3100 2013-03-04 18:11:21Z mic $
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
	if( !empty( $attention ) ) { ?>
	<div class="attention"><?php echo $attention; ?></div>
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
				<a <?php echo $tab; ?>="#tab_common"><?php echo $tab_common; ?></a>
				<a <?php echo $tab; ?>="#tab_advanced"><?php echo $tab_advanced; ?></a>
                <a <?php echo $tab; ?>="#tab_stores"><?php echo $tab_stores; ?></a>
			</div>
			<form action="<?php echo $links['action']; ?>" method="post" enctype="multipart/form-data" id="form">
				<div id="tab_common">
					<table class="form">
						<tr>
							<td><?php echo $entry_status; ?></td>
							<td>
								<input type="radio" name="<?php echo $_name; ?>_status" id="status_1" value="1"<?php echo( ${$_name . '_status'} ? ' checked="checked"' : '' ); ?> />
								<label for="status_1"><?php echo $text_enabled; ?></label>
								<input type="radio" name="<?php echo $_name; ?>_status" id="status_0" value="0"<?php echo( !${$_name . '_status'} ? ' checked="checked"' : '' ); ?> />
								<label for="status_0"><?php echo $text_disabled; ?></label>
							</td>
						</tr>
						<tr>
							<td><?php echo $entry_sort_order; ?></td>
							<td><input type="text" id="<?php echo $_name; ?>_sort_order" name="<?php echo $_name; ?>_sort_order" value="<?php echo ${$_name . '_sort_order'}; ?>" size="1" /></td>
						</tr>
						<tr>
							<td>
								<span class="ttip" title="<?php echo $help_entry_order_status; ?>"><?php echo $entry_order_status; ?></span>
							</td>
							<td>
								<select name="directebanking_order_status_id">
									<?php
									foreach( $order_statuses as $order_status ) {
										if( $order_status['order_status_id'] == $directebanking_order_status_id ) { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
											<?php
										}else{ ?>
											<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php
										}
									} ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo $entry_geo_zone; ?></td>
							<td>
								<select name="directebanking_geo_zone_id">
									<option value="0"><?php echo $text_all_zones; ?></option>
									<?php
									foreach( $geo_zones as $geo_zone ) {
										if( $geo_zone['geo_zone_id'] == $directebanking_geo_zone_id ) { ?>
											<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
											<?php
										}else{ ?>
											<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
											<?php
										}
									} ?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="vtop">
								<span class="ttip" title="<?php echo $help_text; ?>"><?php echo $entry_text; ?></span>
							</td>
							<td>
								<select name="<?php echo $_name; ?>_title_as_text">
									<?php
									if( ${$_name . '_title_as_text'} == '' || ${$_name . '_title_as_text'} == '0' ) { ?>
										<option value="0" selected="selected"><?php echo $sel_text_as_self; ?></option>
										<option value="1"><?php echo $sel_text_as_text; ?></option>
										<option value="2"><?php echo $sel_text_as_image; ?></option>
										<?php
									}elseif( ${$_name . '_title_as_text'} == '1' ) { ?>
										<option value="0"><?php echo $sel_text_as_self; ?></option>
										<option value="1" selected="selected"><?php echo $sel_text_as_text; ?></option>
										<option value="2"><?php echo $sel_text_as_image; ?></option>
										<?php
									}elseif( ${$_name . '_title_as_text'} == '2' ) { ?>
										<option value="0"><?php echo $sel_text_as_self; ?></option>
										<option value="1"><?php echo $sel_text_as_text; ?></option>
										<option value="2" selected="selected"><?php echo $sel_text_as_image; ?></option>
										<?php
									} ?>
								</select>
							</td>
						</tr>
					</table>
				</div>

				<div id="tab_advanced">
					<div class="info">
						<div class="info_img"></div>
						<div class="info_text">
							<?php echo sprintf( $help_advanced, $links['vendor'], $links['settings'] ); ?>
						</div>
					</div>
					<div style="clear:both;"></div>
	                <table class="form">
						<tr>
							<td class="vtop">
								<span class="ttip" title="<?php echo $help_custId; ?>"><?php echo $entry_custId; ?></span>
							</td>
							<td class="vtop">
								<input type="text" id="<?php echo $_name; ?>_custId" name="<?php echo $_name; ?>_custId" value="<?php echo ${$_name . '_custId'}; ?>" size="50" />
								<?php
								if( $error_custId ) { ?>
						            <span class="error"><?php echo $error_custId; ?></span>
						            <?php
								} ?>
							</td>
						</tr>
						<tr>
							<td class="vtop">
								<span class="ttip" title="<?php echo $help_useHash; ?>"><?php echo $entry_useHash; ?></span>
							</td>
							<td class="vtop">
								<input type="radio" name="<?php echo $_name; ?>_useHash" id="useHash_1" value="1"<?php echo( ${$_name . '_useHash'} ? ' checked="checked"' : '' ); ?> />
								<label for="useHash_1"><?php echo $text_yes; ?></label>
								<input type="radio" name="<?php echo $_name; ?>_useHash" id="useHash_0" value="0"<?php echo( !${$_name . '_useHash'} ? ' checked="checked"' : '' ); ?> />
								<label for="useHash_0"><?php echo $text_no; ?></label>
							</td>
						</tr>
						<tr>
							<td class="vtop">
								<span class="ttip" title="<?php echo $help_hashMethod; ?>"><?php echo $entry_hashMethod; ?></span>
							</td>
							<td>
								<select name="<?php echo $_name; ?>_hashMethod">
									<option value="sha1"<?php echo( ( ${$_name . '_hashMethod'} == 'sha1' ) || !${$_name . '_hashMethod'} ? ' selected="selected"' : '' ); ?>><?php echo $sel_sha1; ?></option>
									<option value="sha256"<?php echo( ${$_name . '_hashMethod'} == 'sha256' ? ' selected="selected"' : '' ); ?>><?php echo $sel_sha256; ?></option>
									<option value="sha512"<?php echo( ${$_name . '_hashMethod'} == 'sha512' ? ' selected="selected"' : '' ); ?>><?php echo $sel_sha512; ?></option>
									<option value="md5"<?php echo( ${$_name . '_hashMethod'} == 'md5' ? ' selected="selected"' : '' ); ?>><?php echo $sel_md5; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="vtop">
								<span class="ttip" title="<?php echo $help_debug; ?>"><?php echo $entry_debug; ?></span>
							</td>
							<td class="vtop">
								<select name="<?php echo $_name; ?>_debug">
									<option value=""<?php echo( !${$_name . '_debug'} ? ' selected="selected"' : '' ); ?>><?php echo $entry_no; ?></option>
									<option value="1"<?php echo( ${$_name . '_debug'} == '1' ? ' selected="selected"' : '' ); ?>><?php echo $sel_debug1; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="vtop">
								<span class="ttip" title="<?php echo $help_server_shift; ?>"><?php echo $entry_server_shift; ?></span>
							</td>
							<td class="vtop">
								<input type="text" id="<?php echo $_name; ?>_server_shift" name="<?php echo $_name; ?>_server_shift" value="<?php echo ${$_name . '_server_shift'}; ?>" size="3" />
								&nbsp;
								[ <span style="color:#666666;"><?php echo $current_time; ?></span> ]
							</td>
						</tr>
					</table>
					<div class="info">
						<div class="info_img"></div>
						<div class="info_text"><?php echo $help_test; ?></div>
					</div>
					<div style="clear:both;"></div>
					<table class="form">
						<tr>
							<td class="vtop">
								<span class="ttip" title="<?php echo $help_testMode; ?>"><?php echo $entry_testMode; ?></span>
							</td>
							<td>
								<select name="<?php echo $_name; ?>_testMode">
									<?php
									if( ${$_name . '_testMode'} == '' || ${$_name . '_testMode'} == '0' ) { ?>
										<option value="0" selected="selected"><?php echo $entry_no; ?></option>
										<option value="1"><?php echo $sel_mode1; ?></option>
										<option value="2"><?php echo $sel_mode2; ?></option>
										<?php
									}elseif( ${$_name . '_testMode'} == '1' ) { ?>
										<option value="0"><?php echo $entry_no; ?></option>
										<option value="1" selected="selected"><?php echo $sel_mode1; ?></option>
										<option value="2"><?php echo $sel_mode2; ?></option>
										<?php
									}elseif( ${$_name . '_testMode'} == '2' ) { ?>
										<option value="0"><?php echo $entry_no; ?></option>
										<option value="1"><?php echo $sel_mode1; ?></option>
										<option value="2" selected="selected"><?php echo $sel_mode2; ?></option>
										<?php
									} ?>
								</select>
							</td>
						</tr>
					</table>
				</div>

                <div id="tab_stores">
					<div class="info">
						<div class="info_img"></div>
						<div class="info_text">
							<?php echo sprintf( $help_advanced, $links['vendor'], $links['settings'] ); ?>
						</div>
					</div>
					<div style="clear:both;"></div>
                    <div id="subtabs" class="vtabs">
						<?php
						foreach( $stores as $store ) { ?>
							<a <?php echo $tab; ?>="#tab-store-<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></a>
							<?php
						} ?>
					</div>

                    <?php
					foreach( $stores as $store ) {
                        // set default values when new
                        if( !isset( ${$_name . '_stores'}[$store['store_id']]['status'] ) ) {
                            ${$_name . '_stores'}[$store['store_id']] = $defaultValues;
                        } ?>
						<div id="tab-store-<?php echo $store['store_id']; ?>" class="vtabs-content">
							<table class="form">
								<tr>
									<td><?php echo $entry_status; ?></td>
									<td>
                                        <label>
                                            <input type="radio" name="<?php echo $_name; ?>_stores[<?php echo $store['store_id']; ?>][status]" value="1"<?php echo ${$_name . '_stores'}[$store['store_id']]['status'] ? ' checked="checked"' : ''; ?> />
                                            <?php echo $text_enabled; ?>
                                        </label>
										<label>
                                            <input type="radio" name="<?php echo $_name; ?>_stores[<?php echo $store['store_id']; ?>][status]" value="0"<?php echo !${$_name . '_stores'}[$store['store_id']]['status'] ? ' checked="checked"' : ''; ?> />
                                            <?php echo $text_disabled; ?>
                                        </label>
									</td>
								</tr>
                                <tr>
        							<td class="vtop">
        								<span class="ttip" title="<?php echo $help_projId; ?>"><?php echo $entry_projId; ?></span>
        							</td>
        							<td class="vtop">
        								<input type="text" name="<?php echo $_name; ?>_stores[<?php echo $store['store_id']; ?>][projId]" value="<?php echo ${$_name . '_stores'}[$store['store_id']]['projId']; ?>" size="50" />
        								<?php
        								if( $error_projId ) { ?>
        						            <span class="error"><?php echo $error_projId; ?></span>
        						            <?php
        								} ?>
        							</td>
        						</tr>
                                <tr>
        							<td class="vtop">
        								<span class="ttip" title="<?php echo $help_protection; ?>"><?php echo $entry_protection; ?></span>
        							</td>
        							<td class="vtop">
        								<label>
                                            <input type="radio" name="<?php echo $_name; ?>_stores[<?php echo $store['store_id']; ?>][protection]" value="1"<?php echo ${$_name . '_stores'}[$store['store_id']]['protection'] ? ' checked="checked"' : ''; ?> />
                                            <?php echo $text_enabled; ?>
                                        </label>
										<label>
                                            <input type="radio" name="<?php echo $_name; ?>_stores[<?php echo $store['store_id']; ?>][protection]" value="0"<?php echo !${$_name . '_stores'}[$store['store_id']]['protection'] ? ' checked="checked"' : ''; ?> />
                                            <?php echo $text_disabled; ?>
                                        </label>
        							</td>
        						</tr>
        						<tr>
        							<td class="vtop">
        								<span class="ttip" title="<?php echo $help_successUrl; ?>"><?php echo $entry_successUrl; ?></span>
        							</td>
        							<td class="vtop">
        								<?php echo $entry_successUrlStd; ?>
        								&nbsp;
                                        <label>
        								    <input type="radio" name="<?php echo $_name; ?>_stores[<?php echo $store['store_id']; ?>][successUrlStd]" value="1"<?php echo ${$_name . '_stores'}[$store['store_id']]['successUrlStd'] ? ' checked="checked"' : ''; ?> onclick='successUrl_<?php echo $store['store_id']; ?>.style.display="none";' />
    								        <?php echo $text_yes; ?>
                                        </label>
        								<label>
                                            <input type="radio" name="<?php echo $_name; ?>_stores[<?php echo $store['store_id']; ?>][successUrlStd]" id="successUrlStd_0" value="0"<?php echo !${$_name . '_stores'}[$store['store_id']]['successUrlStd'] ? ' checked="checked"' : ''; ?> onclick='successUrl_<?php echo $store['store_id']; ?>.style.display="inline";' />
        								    <?php echo $text_no; ?>
                                        </label>
        								&nbsp;
        								<input type="text" id="successUrl_<?php echo $store['store_id']; ?>" name="<?php echo $_name; ?>_stores[<?php echo $store['store_id']; ?>][successUrl]" value="<?php echo !empty( ${$_name . '_stores'}[$store['store_id']]['successUrl'] ) ? ${$_name . '_stores'}[$store['store_id']]['successUrl'] : ''; ?>" size="60" style="display:none;"/>
        							</td>
        						</tr>
        						<tr>
        							<td class="vtop">
        								<span class="ttip" title="<?php echo $help_cancelUrl; ?>"><?php echo $entry_cancelUrl; ?></span>
        							</td>
        							<td class="vtop">
        								<?php echo $entry_successUrlStd; ?>
        								&nbsp;
                                        <label>
        								    <input type="radio" name="<?php echo $_name; ?>_stores[<?php echo $store['store_id']; ?>][cancelUrlStd]" value="1"<?php echo ${$_name . '_stores'}[$store['store_id']]['cancelUrlStd'] ? ' checked="checked"' : ''; ?> onclick='cancelUrl_<?php echo $store['store_id']; ?>.style.display="none";' />
        								    <?php echo $text_yes; ?>
                                        </label>
        								<label>
                                            <input type="radio" name="<?php echo $_name; ?>_stores[<?php echo $store['store_id']; ?>][cancelUrlStd]" value="0"<?php echo !${$_name . '_stores'}[$store['store_id']]['cancelUrlStd'] ? ' checked="checked"' : ''; ?> onclick='cancelUrl_<?php echo $store['store_id']; ?>.style.display="inline";' />
        								    <?php echo $text_no; ?>
                                        </label>
        								&nbsp;
        								<input type="text" id="cancelUrl_<?php echo $store['store_id']; ?>" name="<?php echo $_name; ?>_stores[<?php echo $store['store_id']; ?>][cancelUrl]" value="<?php echo !empty( ${$_name . '_stores'}[$store['store_id']]['cancelUrl'] ) ? ${$_name . '_stores'}[$store['store_id']]['cancelUrl'] : ''; ?>" size="60" style="display:none;"/>
        							</td>
        						</tr>
        						<tr>
        							<td class="vtop">
        								<span class="ttip" title="<?php echo $help_projectPassword; ?>"><?php echo $entry_projectPassword; ?></span>
        							</td>
        							<td class="vtop">
        								<input type="text" name="<?php echo $_name; ?>_stores[<?php echo $store['store_id']; ?>][projectPassword]" value="<?php echo ${$_name . '_stores'}[$store['store_id']]['projectPassword']; ?>" size="50" />
        								<?php
        								if( $error_projectPassword ) { ?>
        						            <span class="error"><?php echo $error_projectPassword; ?></span>
        						            <?php
        								} ?>
        							</td>
        						</tr>
        						<tr>
        							<td class="vtop">
        								<span class="ttip" title="<?php echo $help_notifyPassword; ?>"><?php echo $entry_notifyPassword; ?></span>
        							</td>
        							<td class="vtop">
        								<input type="text" name="<?php echo $_name; ?>_stores[<?php echo $store['store_id']; ?>][notifyPassword]" value="<?php echo ${$_name . '_stores'}[$store['store_id']]['notifyPassword']; ?>" size="50" />
        								<?php
        								if( $error_notifyPassword ) { ?>
        						            <span class="error"><?php echo $error_notifyPassword; ?></span>
        						            <?php
        								} ?>
        							</td>
        						</tr>
							</table>
						</div>
						<?php
					} ?>
				</div>
			</form>
		</div>
		<?php echo $oxfooter;
		// OC 1.5.x
		if( isset( $breadcrumbs ) ) { ?>
			</div>
			<?php
		} ?>
</div>
<div id="showtip">&nbsp;</div>
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

	function displayField(field,val) {
		var value = ( val == 1 ? 'none' : 'inline' );

		document.getElementById(field).style.display=value;
	};

    <?php
    foreach( $stores as $store ) { ?>
        displayField('successUrl_<?php echo $store['store_id']; ?>','<?php echo ${$_name . '_stores'}[$store['store_id']]['successUrlStd']; ?>');
        displayField('cancelUrl_<?php echo $store['store_id']; ?>','<?php echo ${$_name . '_stores'}[$store['store_id']]['cancelUrlStd']; ?>');
        <?php
    } ?>
	/* ]]> */
</script>
<?php echo $footer;