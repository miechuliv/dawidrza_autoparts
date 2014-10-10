<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><span class="fa fa-truck"></span> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
          
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="apaczka_status">
              <?php if ($apaczka_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>

        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="apaczka_sort_order" value="<?php echo $apaczka_sort_order; ?>" /></td>
        </tr>

        <tr>
          <td><?php echo $entry_api_login; ?></td>
          <td><input type="text" size="38" name="apaczka_login" value="<?php echo $apaczka_login; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_api_password; ?></td>
          <td><input type="text" size="38" name="apaczka_password" value="<?php echo $apaczka_password; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_api_key; ?></td>
          <td><input type="text" size="38" name="apaczka_apikey" value="<?php echo $apaczka_apikey; ?>" /></td>
        </tr>

        <tr>
          <td><?php echo $entry_api_test; ?></td>
          <td><input type="checkbox" name="apaczka_test" <?php if ($apaczka_test) { echo 'checked'; } ?> /></td>
        </tr>

        <tr>
          <td><?php echo $entry_serviceCode; ?></td>
          <td><select name="apaczka_serviceCode">
	  <option value="UPS_K_STANDARD" <?php if ($apaczka_serviceCode == 'UPS_K_STANDARD') { echo "selected"; } ?>>UPS Standard</option>
	  <option value="UPS_K_EX_SAV" <?php if ($apaczka_serviceCode == 'UPS_K_EX_SAV') { echo "selected"; } ?>>UPS Express Saver</option>
	  <option value="UPS_K_EX" <?php if ($apaczka_serviceCode == 'UPS_K_EX') { echo "selected"; } ?>>UPS Express</option>
	  <option value="UPS_K_EXP_PLUS" <?php if ($apaczka_serviceCode == 'UPS_K_EXP_PLUS') { echo "selected"; } ?>>UPS Express Plus</option>
	  </select>
	  </td>
        </tr>

        <tr>
          <td><?php echo $entry_shipment_price; ?></td>
          <td><input size="8" id="shipment_price" type="text" name="apaczka_shipment_price" value="<?php echo $apaczka_shipment_price; ?>" /> zł</td>
        </tr>

        <tr>
          <td colspan="2">
            <strong>Dane nadawcy (sklepu)</strong>
          </td>
        </tr>

        <tr>
          <td><?php echo $entry_sender_name; ?></td>
          <td><input type="text" name="apaczka_sender_name" value="<?php echo $apaczka_sender_name; ?>" /></td>
        </tr>

        <tr>
          <td><?php echo $entry_sender_address1; ?></td>
          <td><input type="text" name="apaczka_sender_address1" value="<?php echo $apaczka_sender_address1; ?>" /></td>
        </tr>

        <tr>
          <td><?php echo $entry_sender_address2; ?></td>
          <td><input type="text" name="apaczka_sender_address2" value="<?php echo $apaczka_sender_address2; ?>" /></td>
        </tr>

        <tr>
          <td><?php echo $entry_sender_postcode; ?></td>
          <td><input type="text" name="apaczka_sender_postcode" value="<?php echo $apaczka_sender_postcode; ?>" /></td>
        </tr>

        <tr>
          <td><?php echo $entry_sender_city; ?></td>
          <td><input type="text" name="apaczka_sender_city" value="<?php echo $apaczka_sender_city; ?>" /></td>
        </tr>

        <tr>
          <td><?php echo $entry_sender_contactName; ?></td>
          <td><input type="text" name="apaczka_sender_contactName" value="<?php echo $apaczka_sender_contactName; ?>" /></td>
        </tr>

        <tr>
          <td><?php echo $entry_sender_phone; ?></td>
          <td><input type="text" name="apaczka_sender_phone" value="<?php echo $apaczka_sender_phone; ?>" /></td>
        </tr>

        <tr>
          <td><?php echo $entry_sender_email; ?></td>
          <td><input type="text" name="apaczka_sender_email" value="<?php echo $apaczka_sender_email; ?>" size="30" /></td>
        </tr>

        <tr>
          <td><?php echo $entry_account; ?></td>
          <td><input type="text" name="apaczka_account" value="<?php echo $apaczka_account; ?>" size="35" /></td>
        </tr>

      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>
