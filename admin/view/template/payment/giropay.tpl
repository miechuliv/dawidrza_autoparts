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
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_merchant_id; ?></td>
            <td><input type="text" name="giropay_merchant_id" value="<?php echo $giropay_merchant_id; ?>" />
              <?php if ($error_merchant_id) { ?>
              <span class="error"><?php echo $error_merchant_id; ?></span>
              <?php } ?></td>
          </tr>
        </table>
        <div id="tabs" class="htabs"><a href="#tab-giropay"><?php echo $tab_giropay; ?></a><a href="#tab-direct"><?php echo $tab_direct; ?></a><a href="#tab-credit"><?php echo $tab_credit; ?></a><a href="#tab-paypal"><?php echo $tab_paypal; ?></a></div>
        <div id="tab-giropay">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $entry_project_id; ?></td>
              <td><input type="text" name="giropay_project_id" value="<?php echo $giropay_project_id; ?>" />
                <?php if ($error_project_id) { ?>
                <span class="error"><?php echo $error_project_id; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_secret; ?></td>
              <td><input type="password" name="giropay_secret" value="<?php echo $giropay_secret; ?>" />
                <?php if ($error_secret) { ?>
                <span class="error"><?php echo $error_secret; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_description; ?></td>
              <td><input type="text" name="giropay_description" maxlength="27" value="<?php echo $giropay_description; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_total; ?></td>
              <td><input type="text" name="giropay_total" value="<?php echo $giropay_total; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_order_status; ?></td>
              <td><select name="giropay_order_status_id">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $giropay_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_geo_zone; ?></td>
              <td><select name="giropay_geo_zone_id">
                  <option value="0"><?php echo $text_all_zones; ?></option>
                  <?php foreach ($geo_zones as $geo_zone) { ?>
                  <?php if ($geo_zone['geo_zone_id'] == $giropay_geo_zone_id) { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="giropay_status">
                  <?php if ($giropay_status) { ?>
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
              <td><input type="text" name="giropay_sort_order" value="<?php echo $giropay_sort_order; ?>" size="3" /></td>
            </tr>
          </table>
        </div>
        <div id="tab-direct">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $entry_project_id; ?></td>
              <td><input type="text" name="giropay_direct_project_id" value="<?php echo $giropay_direct_project_id; ?>" />
                <?php if ($error_direct_project_id) { ?>
                <span class="error"><?php echo $error_direct_project_id; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_secret; ?></td>
              <td><input type="password" name="giropay_direct_secret" value="<?php echo $giropay_direct_secret; ?>" />
                <?php if ($error_direct_secret) { ?>
                <span class="error"><?php echo $error_direct_secret; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_description; ?></td>
              <td><input type="text" name="giropay_direct_description" maxlength="27" value="<?php echo $giropay_direct_description; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_total; ?></td>
              <td><input type="text" name="giropay_direct_total" value="<?php echo $giropay_direct_total; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_order_status; ?></td>
              <td><select name="giropay_direct_order_status_id">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $giropay_direct_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_geo_zone; ?></td>
              <td><select name="giropay_direct_geo_zone_id">
                  <option value="0"><?php echo $text_all_zones; ?></option>
                  <?php foreach ($geo_zones as $geo_zone) { ?>
                  <?php if ($geo_zone['geo_zone_id'] == $giropay_direct_geo_zone_id) { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="giropay_direct_status">
                  <?php if ($giropay_direct_status) { ?>
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
              <td><input type="text" name="giropay_direct_sort_order" value="<?php echo $giropay_direct_sort_order; ?>" size="3" /></td>
            </tr>
          </table>
        </div>
        <div id="tab-credit">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $entry_project_id; ?></td>
              <td><input type="text" name="giropay_credit_project_id" value="<?php echo $giropay_credit_project_id; ?>" />
                <?php if ($error_credit_project_id) { ?>
                <span class="error"><?php echo $error_credit_project_id; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_secret; ?></td>
              <td><input type="password" name="giropay_credit_secret" value="<?php echo $giropay_credit_secret; ?>" />
                <?php if ($error_credit_secret) { ?>
                <span class="error"><?php echo $error_credit_secret; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_3d_secure; ?></td>
              <td><select name="giropay_credit_3d_secure">
                  <?php if ($giropay_credit_3d_secure) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <option value="0"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_paymode; ?></td>
              <td><select name="giropay_credit_paymode">
                  <?php foreach ($paymodes as $paymode) { ?>
                  <?php if ($paymode['value'] == $giropay_credit_paymode) { ?>
                  <option value="<?php echo $paymode['value']; ?>" selected="selected"><?php echo $paymode['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $paymode['value']; ?>"><?php echo $paymode['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_cc_type; ?></td>
              <td><div>
                  <?php $class = 'odd'; ?>
                  <?php foreach ($cc_types as $cc_type) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($cc_type['value'], $giropay_credit_cc_type)) { ?>
                    <input type="checkbox" name="giropay_credit_cc_type[]" value="<?php echo $cc_type['value']; ?>" checked="checked" />
                    <?php echo $cc_type['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="giropay_credit_cc_type[]" value="<?php echo $cc_type['value']; ?>" />
                    <?php echo $cc_type['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>
                <?php if ($error_credit_cc_type) { ?>
                <span class="error"><?php echo $error_credit_cc_type; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_description; ?></td>
              <td><input type="text" name="giropay_credit_description" maxlength="27" value="<?php echo $giropay_credit_description; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_total; ?></td>
              <td><input type="text" name="giropay_credit_total" value="<?php echo $giropay_credit_total; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_order_status; ?></td>
              <td><select name="giropay_credit_order_status_id">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $giropay_credit_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_geo_zone; ?></td>
              <td><select name="giropay_credit_geo_zone_id">
                  <option value="0"><?php echo $text_all_zones; ?></option>
                  <?php foreach ($geo_zones as $geo_zone) { ?>
                  <?php if ($geo_zone['geo_zone_id'] == $giropay_credit_geo_zone_id) { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="giropay_credit_status">
                  <?php if ($giropay_credit_status) { ?>
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
              <td><input type="text" name="giropay_credit_sort_order" value="<?php echo $giropay_credit_sort_order; ?>" size="3" /></td>
            </tr>
          </table>
        </div>
        <div id="tab-paypal">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $entry_project_id; ?></td>
              <td><input type="text" name="giropay_paypal_project_id" value="<?php echo $giropay_paypal_project_id; ?>" />
                <?php if ($error_paypal_project_id) { ?>
                <span class="error"><?php echo $error_paypal_project_id; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_secret; ?></td>
              <td><input type="password" name="giropay_paypal_secret" value="<?php echo $giropay_paypal_secret; ?>" />
                <?php if ($error_paypal_secret) { ?>
                <span class="error"><?php echo $error_paypal_secret; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_description; ?></td>
              <td><input type="text" name="giropay_paypal_description" maxlength="27" value="<?php echo $giropay_paypal_description; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_total; ?></td>
              <td><input type="text" name="giropay_paypal_total" value="<?php echo $giropay_paypal_total; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_order_status; ?></td>
              <td><select name="giropay_paypal_order_status_id">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $giropay_paypal_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_geo_zone; ?></td>
              <td><select name="giropay_paypal_geo_zone_id">
                  <option value="0"><?php echo $text_all_zones; ?></option>
                  <?php foreach ($geo_zones as $geo_zone) { ?>
                  <?php if ($geo_zone['geo_zone_id'] == $giropay_paypal_geo_zone_id) { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="giropay_paypal_status">
                  <?php if ($giropay_paypal_status) { ?>
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
              <td><input type="text" name="giropay_paypal_sort_order" value="<?php echo $giropay_paypal_sort_order; ?>" size="3" /></td>
            </tr>
          </table>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#tabs a').tabs();

<?php if ($type == 'direct') { ?>
$('#tabs a[href="#tab-direct"]').click();
<?php } elseif ($type == 'credit') { ?>
$('#tabs a[href="#tab-credit"]').click();
<?php } elseif ($type == 'paypal') { ?>
$('#tabs a[href="#tab-paypal"]').click();
<?php } ?>
//--></script> 
<?php echo $footer; ?>
<!-- giropay.de payment gateway by Extensa Web Development - www.extensadev.com -->