<?php
/**
 * @version		$Id: directebanking_new_project.tpl 3002 2012-11-26 17:22:19Z mic $
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
				if( $use_encryption ) { ?>
					<a onclick="validate_form();" id="submitNewProject" class="button" style="text-decoration: none;"><span><?php echo $button_apply; ?></span></a>
					<?php
				} ?>
				<a onclick="location='<?php echo $links['cancel']; ?>';" class="button" title="<?php echo $button_cancel; ?>"><span><?php echo $button_cancel; ?></span></a>
			</div>
		</div>

		<div class="content">
			<form action="<?php echo $links['newProject']; ?>" method="post" id="form_new_project" name="form_new_project">
				<div class="info">
					<div class="info_img"></div>
					<div class="info_text"><?php echo $help_newProject; ?></div>
				</div>
				<div style="clear:both;"></div>
				<div>
					<a onclick="changeDisplayAll();"><?php echo $text_show_hide; ?></a>
				</div>

				<?php
				if( !$use_encryption ) { ?>
					<div class="errror">
						<div class="errror_img"></div>
						<div class="errror_text"><?php echo sprintf( $help_newProject_error, $links['settings'] ); ?></div>
					</div>
					<div style="clear:both;"></div>
					<?php
				} ?>

				<div class="content">
					<fieldset class="fieldset_red">
						<legend class="legend grey"><?php echo $leg_account_data; ?></legend>

						<fieldset class="fieldset_grey">
							<legend class="legend grey"><?php echo $text_personal_data; ?></legend>
							<div>
								<a onclick="changeDisplay('personal_data');"><?php echo $text_show_hide; ?></a>
							</div>
							<div id="personal_data" class="slide" style="display:none;">
								<table class="form">
									<tr>
										<td><?php echo $entry_salutation; ?></td>
										<td><?php echo $lists['user_salutation']; ?></td>
									</tr>
									<tr>
										<td><?php echo $entry_user_name; ?></td>
										<td>
											<input name="user_name1" id="user_name1" type="text" size="30" maxlength="30" value="" />
										</td>
									</tr>
									<tr>
										<td><?php echo $entry_legal_form; ?></td>
										<td><?php echo $lists['user_legal_form_id']; ?></td>
									</tr>
								</table>
							</div>
						</fieldset>

						<fieldset class="fieldset_grey">
							<legend class="legend grey"><?php echo $leg_address; ?></legend>
							<div>
								<a onclick="changeDisplay('address');"><?php echo $text_show_hide; ?></a>
							</div>
							<div id="address" class="slide" style="display:none;">
								<table class="form">
									<tr>
										<td><?php echo $entry_street; ?></td>
										<td><input name="user_street" id="user_street" type="text" size="30 "maxlength="30" value="" /></td>
									</tr>
									<tr>
										<td><?php echo $entry_zip; ?></td>
										<td><input name="user_zipcode" id="user_zipcode" type="text" size="20" maxlength="20" value="" /></td>
									</tr>
									<tr>
										<td><?php echo $entry_city; ?></td>
										<td><input name="user_city" id="user_city" type="text" size="30" maxlength="30" value="" /></td>
									</tr>
									<tr>
										<td><?php echo $entry_email; ?></td>
										<td><input name="user_email" id="user_email" type="text" value="" /></td>
									</tr>
									<tr>
										<td><?php echo $entry_country; ?></td>
							            <td><?php echo $lists['user_country_id']; ?></td>
									</tr>
									<tr>
										<td><?php echo $entry_phone; ?></td>
										<td><input name="user_phone" id="user_phone" type="text" maxlength="30" value="" /></td>
									</tr>
								</table>
							</div>
						</fieldset>

						<fieldset class="fieldset_grey">
							<legend class="legend grey"><?php echo $leg_banking_details; ?></legend>
							<div>
								<a onclick="changeDisplay('banking_details');"><?php echo $text_show_hide; ?></a>
							</div>
							<div id="banking_details" class="slide" style="display:none;">
								<table class="form">
									<tr>
										<td><?php echo $entry_account_holder; ?></td>
										<td><input name="usersdirectdebitbankaccount_holder" id="usersdirectdebitbankaccount_holder" type="text" size="27" maxlength="27" value="" /></td>
									</tr>
									<tr>
										<td><?php echo $entry_account_number; ?></td>
										<td><input name="usersdirectdebitbankaccount_account_number" id="usersdirectdebitbankaccount_account_number" type="text" value="" size="30" maxlength="30" /></td>
									</tr>
									<tr>
										<td><?php echo $entry_bank_code_number; ?></td>
										<td><input name="usersdirectdebitbankaccount_bank_code" id="usersdirectdebitbankaccount_bank_code" type="text" maxlength="30" size="30" value="" /></td>
									</tr>
									<tr>
										<td><?php echo $entry_bank_bic; ?></td>
										<td><input name="usersdirectdebitbankaccount_bank_bic" id="usersdirectdebitbankaccount_bank_bic" type="text" maxlength="30" size="30" value="" /></td>
									</tr>
									<tr>
										<td><?php echo $entry_bank_iban; ?></td>
										<td><input name="usersdirectdebitbankaccount_iban" id="usersdirectdebitbankaccount_iban" type="text" maxlength="30" size="30" value="" /></td>
									</tr>

									<tr>
										<td><?php echo $entry_country; ?></td>
										<td><?php echo $lists['usersdirectdebitbankaccount_country_id']; ?></td>
									</tr>
								</table>
							</div>
						</fieldset>
					</fieldset>

					<fieldset class="fieldset_green">
						<legend class="legend grey"><?php echo $leg_project_data; ?></legend>

						<fieldset class="fieldset_grey">
							<legend class="legend grey"><?php echo $leg_various_project_data; ?></legend>
							<div>
								<a onclick="changeDisplay('project_data');"><?php echo $text_show_hide; ?></a>
							</div>
							<div id="project_data" class="slide" style="display:none;">
								<div class="note">
									<div class="note_img"></div>
									<div class="note_text">
										<?php echo $help_copy_data; ?>
										&nbsp;
										<input type="button" onclick="copy_data();" value="<?php echo $btn_copy_data; ?>" title="<?php echo $btn_copy_data; ?>" />
									</div>
								</div>
								<div style="clear:both;"></div>
								<table class="form">
                                    <tr>
										<td>
											<span class="ttip" title="<?php echo $help_store; ?>"><?php echo $entry_store; ?></span>
										</td>
									 	<td>
										 	<?php echo $lists['stores']; ?>
										 </td>
									</tr>

									<tr>
										<td>
											<span class="ttip" title="<?php echo $help_password; ?>"><?php echo $entry_proj_password; ?></span>
										</td>
									 	<td>
										 	<input type="text" name="projectssetting_project_password" id="projectssetting_project_password" readonly="readonly" class="readonly" size="50" maxlength="60" />
											&nbsp;
											<input type="button" onclick="document.form_new_project.projectssetting_project_password.value=getPassword();" value="<?php echo $btn_create_password; ?>" title="<?php echo $text_create_pw; ?>" />
										 </td>
									</tr>
									<tr>
										<td>
											<span class="ttip" title="<?php echo $help_password; ?>"><?php echo $entry_notify_password; ?></span>
										</td>
									 	<td>
										 	<input type="text" name="project_notification_password" id="project_notification_password" class="readonly" readonly="readonly" size="50" maxlength="60" />
										 	&nbsp;
											<input type="button" onclick="document.form_new_project.project_notification_password.value=getPassword();" value="<?php echo $btn_create_password; ?>" title="<?php echo $text_create_pw; ?>" />
										 </td>
									</tr>
									<tr>
										<td><?php echo $entry_encryption_method; ?></td>
										<td><?php echo $lists['project_hash_algorithm']; ?></td>
									</tr>
									<tr>
										<td><?php echo $entry_proj_name; ?></td>
									 	<td><input type="text" name="project_name" id="project_name" maxlength="50" size="50" /></td>
									</tr>
									<tr>
										<td><?php echo $entry_proj_responsible; ?></td>
									 	<td><input type="text" name="project_name1" id="project_name1" maxlength="30" size="30"  /></td>
									</tr>
									<tr>
										<td><?php echo $entry_street; ?></td>
									 	<td><input type="text" name="project_street" id="project_street" maxlength="30" size="30" /></td>
									</tr>
									<tr>
										<td><?php echo $entry_zip; ?></td>
									 	<td><input type="text" name="project_zipcode" id="project_zipcode" maxlength="20" /></td>
									</tr>
									<tr>
										<td><?php echo $entry_city; ?></td>
									 	<td><input type="text" name="project_city" id="project_city" maxlength="30" size="30" /></td>
									</tr>
									<tr>
										<td><?php echo $entry_email_notification; ?></td>
									 	<td><input type="text" name="projectsnotification_email_email" id="projectsnotification_email_email" maxlength="30" size="30" /></td>
									</tr>
									<tr>
										<td><?php echo $entry_email_language; ?></td>
										<td><?php echo $lists['projectsnotification_email_language_id']; ?></td>
									</tr>
									<tr>
										<td><?php echo $entry_country; ?></td>
										<td><?php echo $lists['project_country_id']; ?></td>
									</tr>
								</table>
							</div>
						</fieldset>

						<fieldset class="fieldset_grey">
							<legend class="legend grey"><?php echo $leg_project_banking_details; ?></legend>
							<div>
								<a onclick="changeDisplay('proj_banking_details');"><?php echo $text_show_hide; ?></a>
							</div>
							<div id="proj_banking_details" class="slide" style="display:none;">
								<table class="form">
									<tr>
										<td><?php echo $entry_account_holder; ?></td>
									 	<td><input type="text" name="projectsbankaccount_holder" id="projectsbankaccount_holder" size="27" maxlength="27" /></td>
									</tr>
									<tr>
										<td><?php echo $entry_account_number; ?></td>
									 	<td><input type="text" name="projectsbankaccount_account_number" id="projectsbankaccount_account_number" size="30" maxlength="30" /></td>
									</tr>
									<tr>
										<td><?php echo $entry_bank_code_number; ?></td>
									 	<td><input type="text" name="projectsbankaccount_bank_code" id="projectsbankaccount_bank_code" /></td>
									</tr>
									<tr>
										<td><?php echo $entry_bank_bic; ?></td>
										<td><input name="projectsbankaccount_bank_bic" id="projectsbankaccount_bank_bic" type="text" maxlength="30" size="30" value="" /></td>
									</tr>
									<tr>
										<td><?php echo $entry_bank_iban; ?></td>
										<td><input name="projectsbankaccount_iban" id="projectsbankaccount_iban" type="text" maxlength="30" size="30" value="" /></td>
									</tr>
									<tr>
										<td><?php echo $entry_country; ?></td>
										<td><?php echo $lists['projectsbankaccount_country_id']; ?></td>
									</tr>
								</table>
							</div>
						</fieldset>
					</fieldset>
					<input type="hidden" name="user_shop_system_id" id="user_shop_system_id" value="140"/>
					<input type="hidden" name="project_shop_system_id" id="project_shop_system_id" value="140"/>
					<input type="hidden" name="projectssetting_interface_input_hash_check_enabled" value="1" />
					<input type="hidden" name="projectsnotification_email_activated" value="1" />
			 	 	<input type="hidden" name="projectssetting_locked_amount" value="1" />
			 	 	<input type="hidden" name="projectssetting_locked_reason_1" value="1" />
					<input type="hidden" name="projectsnotification_http_activated" value="1" />
				 	<input type="hidden" name="projectspaymentsetting_interface_success_link" value="<?php echo $links['successUrl']; ?>" />
				 	<input type="hidden" name="projectspaymentsetting_interface_success_link_redirect" value="1" />
				 	<input type="hidden" name="projectspaymentsetting_interface_cancel_link" value="<?php echo $links['cancelUrl']; ?>" />
					<input type="hidden" name="projectsnotification_http_method" value="1" />
					<input type="hidden" name="backlink" id="backlink" value="<?php echo $links['backLink']; ?>" />
					<input type="hidden" name="debug" value="1" />
				</div>
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
	function copy_data() {
		getid( 'project_street' ).value = getid( 'user_street' ).value;
		getid( 'project_zipcode').value = getid( 'user_zipcode' ).value;
		getid( 'project_city' ).value = getid( 'user_city' ).value;
		getid( 'project_country_id' ).value = getid( 'user_country_id' ).value;
		getid( 'projectsbankaccount_holder').value = getid( 'usersdirectdebitbankaccount_holder' ).value;
		getid( 'projectsbankaccount_account_number' ).value = getid( 'usersdirectdebitbankaccount_account_number' ).value;
	 	getid( 'projectsbankaccount_bank_code' ).value = getid( 'usersdirectdebitbankaccount_bank_code' ).value;
	 	getid( 'projectsbankaccount_country_id' ).value = getid( 'usersdirectdebitbankaccount_country_id' ).value;
	 	getid( 'projectsbankaccount_bank_bic' ).value = getid( 'usersdirectdebitbankaccount_bank_bic' ).value;
	 	getid( 'projectsbankaccount_iban' ).value = getid( 'usersdirectdebitbankaccount_iban' ).value;
	 	getid( 'projectsnotification_email_email' ).value = getid( 'user_email' ).value;

		return false;
	};
	function getRandomNum( lbound, ubound ) {
		return( Math.floor( Math.random() * ( ubound - lbound ) ) + lbound );
	};
	function getRandomChar( length ) {
		var numberChars	= '0123456789';
		var lowerChars	= 'abcdefghijklmnopqrstuvwxyz';
		var upperChars	= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		// var otherChars	= "@#$%&=+?";
		// var specialChars = "`~!^*()-_[{]}\\|;:'\",<.>/";
		var charSet = numberChars += lowerChars += upperChars; // += otherChars;

		return charSet.charAt( getRandomNum( 0, charSet.length ) );
	};
	function getPassword() {
		var rc		= '';
		var length	= 30;

		if( length > 0 )
			rc = rc + getRandomChar( length );

		for( var idx = 1; idx < length; ++idx ) {
			rc = rc + getRandomChar( length );
		}

		return rc;
	};
	function validate_form( theForm ) {
		if( !theForm ) {
			theForm = document.form_new_project;
		}

	    // loop through all fields
	    var errors = false;
	    var x;
		var textFields		= new Array( 'user_name1', 'user_street', 'user_zipcode', 'user_city', 'user_email', 'user_phone', 'usersdirectdebitbankaccount_holder', 'usersdirectdebitbankaccount_account_number', 'usersdirectdebitbankaccount_bank_code', 'usersdirectdebitbankaccount_bank_bic', 'usersdirectdebitbankaccount_iban', 'project_name', 'project_name1', 'project_street', 'project_zipcode', 'project_city', 'projectsnotification_email_email', 'projectsbankaccount_holder', 'projectsbankaccount_account_number', 'projectsbankaccount_bank_code', 'projectsbankaccount_bank_bic', 'projectsbankaccount_iban' );
		var selectFields	= new Array( 'user_salutation', 'user_legal_form_id', 'usersdirectdebitbankaccount_country_id', 'user_country_id', 'project_hash_algorithm', 'projectsnotification_email_language_id', 'project_country_id', 'projectsbankaccount_country_id' );
		// var checkFields		= new Array();
		// var radioFields		= new Array();

		// text fields
		for( x in textFields ) {
            if( isFinite(x) ) {
                if( jQuery('#' + textFields[x] ).val().length < 1 ) {
    				errors = true;
    				setFocus( textFields[x] );
    				break;
    			}else{
                    jQuery('#' + textFields[x] ).css({'background-color':'#FFFFFF','border':'1px solid #D9D9D9'});
    			}
            }
		}

		// select options
		for( x in selectFields ) {
            if( isFinite(x) ) {
                var val = jQuery('#' + selectFields[x]).val();

    			if( val <= 0 ) {
    	        	errors = true;
    	        	setFocus( selectFields[x] );
    	        	break;
    			}else{
                    jQuery('#' + selectFields[x] ).css({'background-color':'#FFFFFF','border':'1px solid #D9D9D9'});
    			}
            }
		}

        /*
		// checkboxes
		for( x in checkFields ) {
            if( isFinite(x) ) {
    			if( getid( checkFields[x] ).checked == false ) {
    	        	errors = true;
    	        	setFocus( checkFields[x] );
    	        	break;
    			}else{
    				getid( checkFields[x] ).style.backgroundColor = '';
    			}
            }
		}

		// radio buttons
		for( x in radioFields ) {
            if( isFinite(x) ) {
    			if( ( getid( radioFields[x][0] ).checked == false ) && ( getid( radioFields[x][1] ).checked == false ) ) {
    	        	errors = true;
    	        	setFocus( radioFields[x] );
    	        	break;
    	    	}else{
    				getid( radioFields[x] ).style.backgroundColor = '';
    			}
            }
  		}
        */

		if( errors ) {
			alert( '<?php echo $msg_all_fields_must_be_filled; ?>' );
			fieldDisplay( 'inline' );
			return false;
		}else{
			var r = confirm( '<?php echo $msg_submit_form; ?>' );

			if( r == true ) {
				// add several values to backlink
                var homepage = '';
                var storeId = jQuery('select[name=\'store\']').val();

                // loop through stores array and check if storeId match - then get url
                var stores = <?php echo json_encode( $stores ); ?>;

                // console.log(stores);

                jQuery.each( stores, function(i,val){
                    if( i == storeId ) {
                        jQuery.each(val, function(k,v){
                            if( k == 'url' ) {
                                homepage = v;
                            }
                        });
                    }
                });


				getid( 'backlink' ).value = '<?php echo $links['backLink']; ?>'
				+ '&pppw=' + encodeURIComponent( getid( 'projectssetting_project_password' ).value )
				+ '&pnp=' + encodeURIComponent( getid( 'project_notification_password' ).value )
                + '&storeId=' + encodeURIComponent( storeId )
				+ '&enc=<?php echo $encrypt; ?>';

                jQuery('#form_new_project').append('<input type="hidden" name="project_homepage" value="' + homepage + '" />');
                jQuery('#form_new_project').append('<input type="hidden" name="projectsnotification_http_url" value="' + homepage + 'index.php?route=payment/directebanking/verify" />');

			    jQuery('#form_new_project').submit();
 			}
 		}
	};
	function setPasswords() {
		getid( 'projectssetting_project_password' ).value = getPassword();
		getid( 'project_notification_password' ).value = getPassword();
	};
	function changeDisplay( field ) {
		var val = jQuery('#' + field).css('display');

		if( val == 'none' ) {
			jQuery('#' + field).show('slow');
		}else{
			jQuery('#' + field).hide('slow');
		}
	};
	function changeDisplayAll() {
		var val = jQuery('.slide').css('display');

		if( val == 'none' ) {
			jQuery('.slide').show('slow');
		}else{
			jQuery('.slide').hide('slow');
		}
	};
	function fieldDisplay( state ) {
		if( !state ) {
			state = inline;
		}

		getid( 'personal_data' ).style.display = state;
		getid( 'address' ).style.display = state;
		getid( 'banking_details' ).style.display = state;
		getid( 'project_data' ).style.display = state;
		getid( 'proj_banking_details' ).style.display = state;
	};
    function setFocus(elem) {
        jQuery('#' + elem).css({'background-color':'#FFF1F1','border':'1px solid #F00'}).focus();
    };

	setPasswords();
	/* ]]> */
</script>
<?php echo $footer;