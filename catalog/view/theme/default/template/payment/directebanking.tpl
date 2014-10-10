<?php
/**
 * @version		$Id: directebanking.tpl 3221 2013-04-14 14:41:22Z mic $
 * @package		Directebanking - Template User
 * @copyright	(C) 2011 mic [ http://osworx.net ]. All Rights Reserved.
 * @author		mic - http://osworx.net
 * @license		OSWorX Commercial License http://osworx.net
 */

if( isset( $error ) ) { ?>
	<div class="warning"><?php echo $error; ?></div>
	<?php
}
if( $testMode ) { ?>
	<div class="warning">
		<?php echo $msg_testmode_on; ?>
	</div>
	<?php
}
if( $instruction ) { ?>
	<div class="content">
		<?php echo $instruction; ?>
	</div>
	<?php
} ?>
<form method="post" action="https://www.sofort.com/payment/start" id="checkout_form">
	<?php
	foreach( $form as $k => $v ) {
		if( $v ) { ?>
			<input type="hidden" name="<?php echo $k; ?>" value="<?php echo $v; ?>" />
			<?php
		}
	} ?>
</form>
<div class="buttons">
	<?php
	if( $_ocversion == '1.4' ) { ?>
		<table>
			<tr>
				<td style="text-align: left;">
					<a onclick="location='<?php echo str_replace('&', '&amp;', $back); ?>'" class="button"><span><?php echo $button_back; ?></span></a>
				</td>
				<td style="text-align: right;">
					<a onclick="jQuery('#checkout_form').submit();" class="button"><span><?php echo $button_confirm; ?></span></a>
				</td>
			</tr>
		</table>
		<?php
	}else{ ?>
		<div class="right">
			<a onclick="jQuery('#checkout_form').submit();" id="button-confirm" class="button"><span><?php echo $button_confirm; ?></span></a>
		</div>
		<?php
	} ?>
</div>