<?=$header; ?>
<div id="content">
<?php if ($error_warning) { ?>
<div class="warning"><?=$error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/payment.png');"><?=$heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?=$button_save; ?></span></a><a onclick="location = '<?=$cancel; ?>';" class="button"><span><?=$button_cancel; ?></span></a></div>
  </div>
  <div class="content">
<form action="<?=$action; ?>" method="post" enctype="multipart/form-data" id="form">
    <table class="form">
	<tr>
        <td width="25%"><?=$entry_transferuj_status; ?></td>
        <td><select name="transferuj_status">
            <option value="1"><?=$entry_transferuj_status_yes; ?></option>
            <option value="0"<?=(!$transferuj_status ? ' selected="selected"' : '' ); ?>><?=$entry_transferuj_status_no; ?></option>
          </select></td>
      </tr>	       
      
      <tr>
        <td><?=$entry_sort_order; ?></td>
        <td><input type="text" name="transferuj_sort_order" value="<?=$transferuj_sort_order; ?>" size="1" /></td>
      </tr>

      <tr>
        <td><?=$entry_transferuj_ip; ?></td>
        <td><input type="text" name="transferuj_ip" value="<?=(empty($transferuj_ip) ? '195.149.229.109' : $transferuj_ip); ?>" size="16" maxlength="16" /></td>
      </tr>
    
	<tr>
		<td><strong><?=$entry_settings_seller; ?></strong></td>
		<td>&nbsp;</td>
	</tr>
    
	  <tr>
        <td><span class="required">*</span> <?=$entry_transferuj_seller_id; ?></td>
        <td><input type="text" size="16" maxlength="10" name="transferuj_seller_id" value="<?=$transferuj_seller_id; ?>" /><br />
          <?php if ($error_merchant) { ?>
          <span class="error"><?=$error_merchant; ?></span>
          <?php } ?></td>
      </tr>	  
      <tr>
        <td><span class="required">*</span> <?=$entry_transferuj_conf_code; ?></td>
        <td><input type="password" size="16" maxlength="16" name="transferuj_conf_code" value="<?=$transferuj_conf_code; ?>" /> (<?=$entry_transferuj_conf_code_hint; ?>)</td>
      </tr>

  <tr>
		<td><strong><?=$entry_settings_orders; ?></strong></td>
		<td>&nbsp;</td>
	</tr>
	    
  <tr>
    <td><span class="required">*</span> <?=$entry_transferuj_currency; ?></td>
    <td>
		<select name="transferuj_currency" id="transferuj_currency">
          <?php foreach ($curr as $name) { ?>
            <option value="<?=$name; ?>"<?=($transferuj_currency == $name ? ' selected="selected"' : ''); ?>><?=$name; ?></option>
          <?php } ?>
		</select>		
  </tr>
  	<tr>
        <td><?=$entry_transferuj_order_status_error; ?></td>
        <td><select name="transferuj_order_status_error"><?php
            foreach ($order_statuses as $order_status) {
				echo'<option value="'.$order_status['order_status_id'].'"'.($order_status['order_status_id'] == $transferuj_order_status_error ? ' selected="selected"' : '').'>'.$order_status['name'].'</option>';
            }?></select></td>
    </tr>
	
	<tr>
        <td><?=$entry_transferuj_order_status_completed; ?></td>
        <td><select name="transferuj_order_status_completed"><?php
            foreach ($order_statuses as $order_status) {
				echo'<option value="'.$order_status['order_status_id'].'"'.($order_status['order_status_id'] == $transferuj_order_status_completed ? ' selected="selected"' : '').'>'.$order_status['name'].'</option>';
            }?></select></td>
    </tr>
    </table>
</form>
</div>
</div>
</div>
<?=$footer; ?>