<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>

    <div class="box">
        <div class="heading">
            <h1><?php echo $lang_title; ?></h1>
            <div class="buttons">
                <a class="button" onclick="location = '<?php echo $link_overview; ?>';" ><span><?php echo $lang_btn_cancel; ?></span></a>
                <a class="button" onclick="$('#settings-form').submit()" ><span><?php echo $lang_btn_save ?></span></a>
            </div>
        </div>

        <div class="content">

            <div id="tabs" class="htabs">
                <a href="#page-settings" id="tab-settings"><?php echo $lang_main_settings; ?></a>
                <a href="#page-product" id="tab-product"><?php echo $lang_listing; ?></a>
                <a href="#page-orders" id="tab-settings"><?php echo $lang_orders; ?></a>
            </div>

            <form method="POST" action="" id="settings-form">

                <div id="page-settings">
                    <table class="form">
                        <h2><?php echo $lang_main_settings_heading ?></h2>
                        <tr>
                            <td><?php echo $lang_enabled ?></td>
                            <td>               
                                <select class="width100" name="amazonus_status" id="enabled">
                                    <?php if ($is_enabled) { ?>
                                        <option value="1" selected="selected"><?php echo $lang_yes ?></option>
                                        <option value="0"><?php echo $lang_no ?></option>
                                    <?php } else { ?>
                                        <option value="1"><?php echo $lang_yes ?></option>
                                        <option value="0" selected="selected"><?php echo $lang_no ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_token ?></td>
                            <td><input class="width390" type="text" name="openbay_amazonus_token" value="<?php echo $openbay_amazonus_token ?>" /></td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_enc_string1 ?></td>
                            <td><input class="width390" type="text" name="openbay_amazonus_enc_string1" value="<?php echo $openbay_amazonus_enc_string1 ?>" /></td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_enc_string2 ?></td>
                            <td><input class="width390" type="text" name="openbay_amazonus_enc_string2" value="<?php echo $openbay_amazonus_enc_string2 ?>"/></td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_api_status; ?></td>
                            <td>
                                <?php if(!$API_status) { ?>
                                    <img class="apiConnectionImg" src="view/image/delete.png" />  <?php echo $lang_api_error; ?>
                                <?php } else if(!$API_auth) { ?>
                                    <img class="apiConnectionImg" src="view/image/delete.png" />  <?php echo $lang_api_auth_error; ?>
                                <?php } else { ?>
                                    <img class="apiConnectionImg" src="view/image/success.png" />  <?php echo $lang_api_ok; ?>
                                <?php } ?>
                                
                            </td>
                        </tr>
                        
                    </table>                    
                </div>
                
                <div id="page-product">
                    <table class="form">
                        <h2><?php echo $lang_listings_heading ?></h2>
                        <tr>
                            <td><?php echo $lang_tax_percentage ?></td>
                            <td><input class="width100" type="text" name="openbay_amazonus_listing_tax_added" value="<?php echo $openbay_amazonus_listing_tax_added ?>" />%</td>
                        </tr>
                    </table>
                </div>

                <div id="page-orders">
                    <table class="form">
                       <h2><?php echo $lang_order_statuses ?></h2>
                        <?php foreach ($amazonus_order_statuses as $key => $amazonus_order_status): ?>
                            <tr>
                                <td><?php echo $amazonus_order_status['name'] ?></td>
                                <td>
                                    <select class="width120" name="openbay_amazonus_order_status_<?php echo $key ?>">
                                        <?php foreach ($order_statuses as $order_status): ?>
                                            <?php if ($amazonus_order_status['order_status_id'] == $order_status['order_status_id']): ?>
                                                <option value="<?php echo $order_status['order_status_id'] ?>" selected="selected"><?php echo $order_status['name'] ?></option>
                                            <?php else: ?>
                                                <option value="<?php echo $order_status['order_status_id'] ?>"><?php echo $order_status['name'] ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>     
                    
                    <table class="form">
                        <h2><?php echo $lang_marketplaces ?></h2>
                            
                        <!-- Marketplaces -->
                        <?php foreach($marketplace_ids as $mpId) : ?>
                        <tr>
                            <td>
                                <label>Marketplace ID</label>
                            </td>
                            <td>
                                <input class="width120" type="text" name="openbay_amazonus_orders_marketplace_ids[]" value="<?php echo $mpId; ?>" />
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                    
                    <table class="form">
                        <h2><?php echo $lang_other ?></h2>
                        <tr>
                            <td>
                                <label for="openbay_amazonus_order_tax"><?php echo $lang_import_tax ?></label>
                            </td>
                            <td>
                                <input class="width120" type="text" name="openbay_amazonus_order_tax" value="<?php echo $openbay_amazonus_order_tax ?>" />%
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="customer_group_input"><?php echo $lang_customer_group ?></label><br />
                                <span class="help"><?php echo $lang_customer_group_help ?></span>
                            </td>
                            <td>
                                <select class="width120" name="openbay_amazonus_order_customer_group">
                                    <?php foreach($customer_groups as $customer_group): ?>
                                        <?php if ($openbay_amazonus_order_customer_group == $customer_group['customer_group_id']): ?>
                                        <option value="<?php echo $customer_group['customer_group_id']?>" selected="selected"><?php echo $customer_group['name'] ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo $customer_group['customer_group_id']?>"><?php echo $customer_group['name'] ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_admin_notify ?></td>
                            <td>
                                <select class="width100" name="openbay_amazonus_notify_admin">
                                    <?php if ($openbay_amazonus_notify_admin) { ?>
                                    <option value="1" selected="selected"><?php echo $lang_yes ?></option>
                                    <option value="0"><?php echo $lang_no ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $lang_yes ?></option>
                                    <option value="0" selected="selected"><?php echo $lang_no ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript">
    $(document).ready(function(){
        $('#tabs a').tabs(); 
    });
</script>
<?php echo $footer; ?>