<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content" class="kasa"><?php echo $content_top; ?>

<?/*
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
*/?>
 <?/*<h1 style="color:#aaa;"><?php echo $heading_title; ?></h1> */?>


<div class="formy">
<div>
<script type="text/javascript"><!--
$('#checkout .checkout-content input[name=\'account\']').on('change', function() {
	if ($(this).attr('value') == 'register') {
		$('#payment-address .checkout-heading span').html('<?php echo $text_checkout_account; ?>');
	} else {
		$('#payment-address .checkout-heading span').html('<?php echo $text_checkout_payment_address; ?>');
	}
});

$('.checkout-heading a').on('click', function() {
	$('.checkout-content').slideUp('slow');
	
	$(this).parent().parent().find('.checkout-content').slideDown('slow');
});



// Login
$('#button-login').on('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/login/validate',
		type: 'post',
		data: $('#login :input'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-login').attr('disabled', true);
			$('#button-login').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},	
		complete: function() {
			$('#button-login').attr('disabled', false);
			$('.wait').remove();
		},				
		success: function(json) {
			$('.warning, .error').remove();
			
			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#button-login').after('<div class="warning" style="display: none; margin-bottom:0;">' + json['error']['warning'] + '</div>');
				
				$('.warning').fadeIn('slow');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
});

// Register
$('#button-register').on('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/register/validate',
		type: 'post',
		data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'password\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-register').attr('disabled', true);
			$('#button-register').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},	
		complete: function() {
			$('#button-register').attr('disabled', false); 
			$('.wait').remove();
		},			
		success: function(json) {
			$('.warning, .error').remove();
						
			if (json['redirect']) {
				location = json['redirect'];				
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('#payment-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
					
					$('.warning').fadeIn('slow');
				}
				
				if (json['error']['firstname']) {
					$('#payment-address input[name=\'firstname\'] + br').after('<span class="error">' + json['error']['firstname'] + '</span>');
				}
				
				if (json['error']['lastname']) {
					$('#payment-address input[name=\'lastname\'] + br').after('<span class="error">' + json['error']['lastname'] + '</span>');
				}	
				
				if (json['error']['email']) {
					$('#payment-address input[name=\'email\'] + br').after('<span class="error">' + json['error']['email'] + '</span>');
				}
				
				if (json['error']['telephone']) {
					$('#payment-address input[name=\'telephone\'] + br').after('<span class="error">' + json['error']['telephone'] + '</span>');
				}	
					
				if (json['error']['company_id']) {
					$('#payment-address input[name=\'company_id\'] + br').after('<span class="error">' + json['error']['company_id'] + '</span>');
				}	
				
				if (json['error']['tax_id']) {
					$('#payment-address input[name=\'tax_id\'] + br').after('<span class="error">' + json['error']['tax_id'] + '</span>');
				}	
																		
				if (json['error']['address_1']) {
					$('#payment-address input[name=\'address_1\'] + br').after('<span class="error">' + json['error']['address_1'] + '</span>');
				}	
				
				if (json['error']['city']) {
					$('#payment-address input[name=\'city\'] + br').after('<span class="error">' + json['error']['city'] + '</span>');
				}	
				
				if (json['error']['postcode']) {
					$('#payment-address input[name=\'postcode\'] + br').after('<span class="error">' + json['error']['postcode'] + '</span>');
				}	
				
				if (json['error']['country']) {
					$('#payment-address select[name=\'country_id\'] + br').after('<span class="error">' + json['error']['country'] + '</span>');
				}	
				
				if (json['error']['zone']) {
					$('#payment-address select[name=\'zone_id\'] + br').after('<span class="error">' + json['error']['zone'] + '</span>');
				}
				
				if (json['error']['password']) {
					$('#payment-address input[name=\'password\'] + br').after('<span class="error">' + json['error']['password'] + '</span>');
				}	
				
				if (json['error']['confirm']) {
					$('#payment-address input[name=\'confirm\'] + br').after('<span class="error">' + json['error']['confirm'] + '</span>');
				}																																	
			} else {
				<?php if ($shipping_required) { ?>				
				var shipping_address = $('#payment-address input[name=\'shipping_address\']:checked').attr('value');
				
				if (shipping_address) {
					$.ajax({
						url: 'index.php?route=checkout/shipping_method',
						dataType: 'html',
						success: function(html) {
							$('#shipping-method .checkout-content').html(html);
							
							$('#payment-address .checkout-content').slideUp('slow');
							
							$('#shipping-method .checkout-content').slideDown('slow');
							
							$('#checkout .checkout-heading a').remove();
							$('#payment-address .checkout-heading a').remove();
							$('#shipping-address .checkout-heading a').remove();
							$('#shipping-method .checkout-heading a').remove();
							$('#payment-method .checkout-heading a').remove();											
							
							$('#shipping-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');									
							$('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');	

							$.ajax({
								url: 'index.php?route=checkout/shipping_address',
								dataType: 'html',
								success: function(html) {
									$('#shipping-address .checkout-content').html(html);
								},
								error: function(xhr, ajaxOptions, thrownError) {
									alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
								}
							});	
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});	
				} else {
					$.ajax({
						url: 'index.php?route=checkout/shipping_address',
						dataType: 'html',
						success: function(html) {
							$('#shipping-address .checkout-content').html(html);
							
							$('#payment-address .checkout-content').slideUp('slow');
							
							$('#shipping-address .checkout-content').slideDown('slow');
							
							$('#checkout .checkout-heading a').remove();
							$('#payment-address .checkout-heading a').remove();
							$('#shipping-address .checkout-heading a').remove();
							$('#shipping-method .checkout-heading a').remove();
							$('#payment-method .checkout-heading a').remove();							

							$('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');	
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});			
				}
				<?php } else { ?>
				$.ajax({
					url: 'index.php?route=checkout/payment_method',
					dataType: 'html',
					success: function(html) {
						$('#payment-method .checkout-content').html(html);
						
						$('#payment-address .checkout-content').slideUp('slow');
						
						$('#payment-method .checkout-content').slideDown('slow');
						
						$('#checkout .checkout-heading a').remove();
						$('#payment-address .checkout-heading a').remove();
						$('#payment-method .checkout-heading a').remove();								
						
						$('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');	
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});					
				<?php } ?>

				$.ajax({
					url: 'index.php?route=checkout/payment_address',
					dataType: 'html',
					success: function(html) {
						$('#payment-address .checkout-content').html(html);
							
						$('#payment-address .checkout-heading span').html('<?php echo $text_checkout_payment_address; ?>');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}	 
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
});

//--></script>
<div id="payment-address">
<h1>1. <?php echo $text_your_details; ?></h1>

<div class="left">
	<div class="lab"><?php echo $entry_firstname; ?> / <?php echo $entry_lastname; ?> <span class="required">*</span></div>
    <input id="pol1" type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $this->language->get('przyk_imie'); ?>" class="large-field" />
    <br />
</div>
<div class="right">	
    <input id="pol2" type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $this->language->get('przyk_nazwisko'); ?>" class="large-field" />
    <br />
</div>
<div class="left fullwidth"> 
    <div class="lab"><?php echo $entry_address_1; ?> <span class="required">*</span></div>
    <input type="text" name="address_1" value="<?php echo $address_1; ?>" placeholder="<?php echo $this->language->get('przyk_adres'); ?>" class="large-field" />
    <br />
</div>

<div class="left">
    <div class="lab"><?php echo $entry_postcode; ?> / <?php echo $entry_city; ?>  <span class="required">*</span></div>
    <input type="text" name="postcode" value="<?php echo $postcode; ?>" placeholder="<?php echo $this->language->get('przyk_kod'); ?>" class="large-field" />
    <br />
</div>
<div class="right">
    <input type="text" name="city" value="<?php echo $city; ?>" placeholder="<?php echo $this->language->get('przyk_miasto'); ?>" class="large-field" />
    <br />
</div>
<div class="left">
	<div class="lab"><?php echo $entry_email; ?> / <?php echo $entry_telephone; ?> <span class="required">*</span></div>
    <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $this->language->get('przyk_email'); ?>" class="large-field" />
    <br />
</div>
<div class="right">	
    <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $this->language->get('przyk_telefon'); ?>" class="large-field" />
	<br />	
</div>
<div class="left">		
    <div class="lab"><?php echo $entry_company; ?> / <?php echo $entry_company_id; ?> <span style="font-weight:normal; font-size:10px;"><?php echo $this->language->get('text_optional'); ?></span></div>
    <input type="text" name="company" value="<?php echo $company; ?>" placeholder="<?php echo $this->language->get('przyk_firma'); ?>" class="large-field" />
    <br />
</div>
<div class="right">
        <input type="text" name="company_id" value="<?php echo $company_id; ?>" placeholder="<?php echo $this->language->get('przyk_nip'); ?>" class="large-field" />
    </div>


<!-- DISABLE -->

	<div style="display:none">
    <div class="lab"><?php echo $entry_address_2; ?></div>
    <input type="text" name="address_2" value="<?php echo $address_2; ?>" class="large-field" />
    <br />
	</div>
	
	<div style="display:none">
	<div class="lab"> <span class="required">*</span> <?php echo $entry_zone; ?></div>
    <select name="zone_id" class="large-field">
	<option value="4033" selected="selected" ></option>
    </select>
    <br />
	</div>

	<div style="display:none">
    <div class="lab"><?php echo $entry_fax; ?></div>
    <input type="text" name="fax" value="<?php echo $fax; ?>" class="large-field" />
    <br />
	</div>

	<div style="display:none">
	<div class="lab"> <span class="required">*</span> <?php echo $entry_country; ?></div>
    <select name="country_id" class="large-field">
        <option value=""><?php echo $text_select; ?></option>
        <?php foreach ($countries as $country) { ?>
        <?php if ($country['country_id'] == $country_id) { ?>
        <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
        <?php } ?>
        <?php } ?>
    </select>
    <br />
	</div>	
	
	<div id="tax-id-display" style="display:none"><div class="lab"><span id="tax-id-required" class="required">*</span> <?php echo $entry_tax_id; ?></div>
    <input type="text" name="tax_id" value="<?php echo $tax_id; ?>" class="large-field" />
    <br />   
    </div>
	
<!-- / DISABLE -->
    

<?php if ($shipping_required) { ?>
<div style="float:left; width:100%; margin:20px 0" class="checkbox grey">
    <?php if ($shipping_address) { ?>
    <input type="checkbox" name="shipping_address" value="1" id="shipping" checked="checked" onchange="toogleShipping()"/>
    <?php } else { ?>
    <input type="checkbox" name="shipping_address" value="1" id="shipping" onchange="toogleShipping()" />
    <?php } ?>
    <label for="shipping"><strong><?php echo $entry_shipping; ?></strong></label>
</div>
<?php } ?>

<div style="float:left;" class="checkbox grey">
	<?php echo $this->language->get('text_important'); ?>
</div>

<?/*
<div class="right" style="padding:0 0 20px; width:100% !important;">
<div style="float:left; width:320px; margin-right:10px; margin-left:20px;">
    <div class="lab"><?php echo $entry_company; ?></div>
    <input type="text" name="company" value="<?php echo $company; ?>" class="large-field" />
</div><div style="float:left; width:320px;">
	<div id="company-id-display" class="lab"><?php echo $entry_company_id; ?></div>
    <input type="text" name="company_id" value="<?php echo $company_id; ?>" class="large-field" />
</div>
</div>	
*/?>

</div>

<script type="text/javascript"><!--
    function toogleShipping()
    {
        var state = $('input[type=\'checkbox\']').prop('checked');



        if(state)
        {
            $('#shipping-address').css('display','none');
        }
        else
        {
            $('#shipping-address').css('display','block');
        }

    }
    $('#payment-address input[name=\'customer_group_id\']:checked').on('change', function() {
        var customer_group = [];

        <?php foreach ($customer_groups as $customer_group) { ?>
            customer_group[<?php echo $customer_group['customer_group_id']; ?>] = [];
            customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_display'] = '<?php echo $customer_group['company_id_display']; ?>';
            customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_required'] = '<?php echo $customer_group['company_id_required']; ?>';
            customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_display'] = '<?php echo $customer_group['tax_id_display']; ?>';
            customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_required'] = '<?php echo $customer_group['tax_id_required']; ?>';
        <?php } ?>

        if (customer_group[this.value]) {
            if (customer_group[this.value]['company_id_display'] == '1') {
                $('#company-id-display').show();
            } else {
                $('#company-id-display').hide();
            }

            if (customer_group[this.value]['company_id_required'] == '1') {
                $('#company-id-required').show();
            } else {
                $('#company-id-required').hide();
            }

            if (customer_group[this.value]['tax_id_display'] == '1') {
                $('#tax-id-display').show();
            } else {
                $('#tax-id-display').hide();
            }

            if (customer_group[this.value]['tax_id_required'] == '1') {
                $('#tax-id-required').show();
            } else {
                $('#tax-id-required').hide();
            }
        }
    });

    $('#payment-address input[name=\'customer_group_id\']:checked').trigger('change');
    //--></script>
<script type="text/javascript"><!--
    $('#payment-address select[name=\'country_id\']').bind('change', function() {
        if (this.value == '') return;
        $.ajax({
            url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
            dataType: 'json',
            beforeSend: function() {
                $('#payment-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
            },
            complete: function() {
                $('.wait').remove();
            },
            success: function(json) {
                if (json['postcode_required'] == '1') {
                    $('#payment-postcode-required').show();
                } else {
                    $('#payment-postcode-required').hide();
                }

                html = '<option value=""><?php echo $text_select; ?></option>';

                if (json['zone'] != '') {
                    for (i = 0; i < json['zone'].length; i++) {
                        html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                        if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
                            html += ' selected="selected"';
                        }

                        html += '>' + json['zone'][i]['name'] + '</option>';
                    }
                } else {
                    html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
                }

                $('#payment-address select[name=\'zone_id\']').html(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('#payment-address select[name=\'country_id\']').trigger('change');
    //--></script>

<script type="text/javascript" >
    function useCoupon()
    {
        var coupon = $(this).find('input').val();
        $.ajax({
        url: 'index.php?route=checkout/checkout/useCoupon',
        type: 'post',
            dataType: 'json',
            data: {coupon: coupon},
            success: function(json)
            {

                    if(json['result'] == 'ok')
                    {
                        location.reload();
                    }
                    else
                    {
                        $('#coupon .error').html(json['result']);
                    }

            }
        })

    }
</script>

<!-- koniec kupon -->

<?php if ($shipping_address) { ?>
<div id="shipping-address" style="display:none;">
<?php }else{ ?>
<div id="shipping-address" >
<?php } ?>

    <div class="left">	
        <div class="lab"><?php echo $entry_firstname; ?> / <?php echo $entry_lastname; ?> <span class="required">*</span></div>
        <input type="text" name="shipping_firstname" value="<?php echo $shipping_firstname; ?>" placeholder="<?php echo $this->language->get('przyk_imie'); ?>" class="large-field" />
		<br/>
    </div>
    <div class="right">	
        <input type="text" name="shipping_lastname" value="<?php echo $shipping_lastname; ?>" placeholder="<?php echo $this->language->get('przyk_nazwisko'); ?>" class="large-field" />
		<br/>
    </div>

    <div class="left fullwidth">	
        <div class="lab"><?php echo $entry_address_1; ?> <span class="required">*</span></div>
        <input type="text" name="shipping_address_1" value="<?php echo $shipping_address_1; ?>" placeholder="<?php echo $this->language->get('przyk_adres'); ?>" class="large-field" />
		<br/>
    </div>
    <div class="left" style="display:none">	
        <div class="lab"><?php echo $entry_address_2; ?></div>
        <input type="text" name="shipping_address_2" value="<?php echo $shipping_address_2; ?>" class="large-field" />
		<br/>
    </div>
    <div class="left">	
		<div class="lab"><?php echo $entry_postcode; ?> / <?php echo $entry_city; ?> <span id="shipping-postcode-required" class="required">*</span></div>
        <input type="text" name="shipping_postcode" value="<?php echo $shipping_postcode; ?>" placeholder="<?php echo $this->language->get('przyk_kod'); ?>" class="large-field" />
		<br/>
    </div>
    <div class="right">	
        <input type="text" name="shipping_city" value="<?php echo $shipping_city; ?>" placeholder="<?php echo $this->language->get('przyk_miasto'); ?>" class="large-field" />
		<br/>
    </div>
	<div class="left">	
        <div class="lab"><?php echo $entry_company; ?></div>
        <input type="text" name="shipping_company" value="<?php echo $shipping_company; ?>" placeholder="<?php echo $this->language->get('przyk_firma'); ?>" class="large-field" />
		<br/>
    </div>
    <div class="left" style="margin:0; width:100%;">	
        <div class="lab"><?php echo $entry_country; ?> <span class="required">*</span></div>
        <select name="shipping_country_id" class="large-field">
                <option value=""><?php echo $text_select; ?></option>
                <?php foreach ($countries as $country) { ?>
                <?php if ($country['country_id'] == $shipping_country_id) { ?>
                <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                <?php } ?>
                <?php } ?>
            </select>
		<br/>
    </div>
    <div class="right" style="display:none">	
        <div class="lab"><?php echo $entry_zone; ?> <span class="required">*</span></div>
        <select name="shipping_zone_id" class="large-field">
            </select>

    </div>

<script type="text/javascript"><!--
    $('#shipping-address select[name=\'shipping_country_id\']').bind('change', function() {
        if (this.value == '') return;
        $.ajax({
            url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
            dataType: 'json',
            beforeSend: function() {
                $('#shipping-address select[name=\'shipping_country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
            },
            complete: function() {
                $('.wait').remove();
            },
            success: function(json) {
                if (json['postcode_required'] == '1') {
                    $('#shipping-postcode-required').show();
                } else {
                    $('#shipping-postcode-required').hide();
                }

                html = '<option value=""><?php echo $text_select; ?></option>';

                if (json['zone'] != '') {
                    for (i = 0; i < json['zone'].length; i++) {
                        html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                        if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
                            html += ' selected="selected"';
                        }

                        html += '>' + json['zone'][i]['name'] + '</option>';
                    }
                } else {
                    html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
                }

                $('#shipping-address select[name=\'shipping_zone_id\']').html(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('#shipping-address select[name=\'shipping_country_id\']').trigger('change');
    //--></script>
</div>



<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($shipping_methods) { ?>
<div id="shipping-methods">
<h1>2. <?php echo $this->language->get('text_shipping'); ?></h1>

<p><?php echo $text_shipping_method; ?></p>
<table class="radio">
    <?php foreach ($shipping_methods as $shipping_method) { ?>
    <tr>
        <td colspan="3"><b><?php echo $shipping_method['title']; ?></b></td>
    </tr>
    <?php if (!$shipping_method['error']) { ?>
    <?php foreach ($shipping_method['quote'] as $quote) { ?>
    <tr class="highlight">
        <td><?php if ($quote['code'] == $code || !$code) { ?>
            <?php $code = $quote['code']; ?>
            <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" checked="checked" />
            <?php } else { ?>
            <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" />
            <?php } ?><label for="<?php echo $quote['code']; ?>"><?php echo $quote['title']; ?> (<?php echo $quote['text']; ?>)</label>
		</td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
        <td colspan="3"><div class="error"><?php echo $shipping_method['error']; ?></div></td>
    </tr>
    <?php } ?>
    <?php } ?>
</table>
<br />
</div>
<?php } ?>


<br />


<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($payment_methods) { ?>
<div id="payment-methods">
<h1>3. <?php echo $this->language->get('text_payment_method_short'); ?></h1>

<p><?php echo $text_payment_method; ?></p>
<table class="radio">
    <?php foreach ($payment_methods as $payment_method) { ?>
    <tr class="highlight">
        <td><?php if ($payment_method['code'] == $code || !$code) { ?>
            <?php $code = $payment_method['code']; ?>
            <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" checked="checked" />
            <?php } else { ?>
            <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" />
            <?php } ?>
			<label for="<?php echo $payment_method['code']; ?>">
				<img src="./image/checkout/<?php echo $payment_method['code']; ?>.jpg" alt="<?php echo $payment_method['title']; ?>" /> &nbsp; <?php echo $payment_method['title']; ?>
				<i class="fa fa-question-circle">
					<div class="shadow">
						Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fuga, et obcaecati illum consequuntur.
					</div>
				</i>
			</label>
		</td>
    </tr>
    <?php } ?>
</table>
<br />
</div>
<?php } ?>

<div class="checkout-product">
	<h1>4. <?php echo $text_comments; ?></h1>
	<textarea name="comment" rows="3"><?php echo $comment; ?></textarea>
</div>

<!-- kupon -->
<div id="coupon">
    <label for="coupon" ><?php echo $this->language->get('text_coupon'); ?></label>
    <input name="coupon" type="text" />
    <a class="button" onclick="useCoupon()" ><?php echo $this->language->get('text_use_coupon'); ?></a>
    <div class="error" style="float:right" ></div>
	<p><?php echo $this->language->get('coupon_desc'); ?></p>
</div>

<script type="text/javascript"><!--
    $('input[name=\'shipping_method\']').change(function(){

        reloadTotals();

    });

    $('input[name=\'payment_method\']').change(function(){

        reloadTotals();

    });

    function reloadTotals()
    {
        $.ajax({
            url: 'index.php?route=checkout/checkout/reloadTotals',
            type: 'post',
            data: $('#shipping-methods input[type=\'radio\']:checked , #payment-methods input[type=\'radio\']:checked'),

            dataType: 'json',
            success: function(json) {

                html='';

                $.each( json['totals'], function( key, value ) {
                    html+=   '<tr>';
                    html+=    '<th scope="row"></th><th scope="row"  colspan="3" class="price"><b>'+value['title']+'</b></th>';
                    html+=    '<th scope="row" class="total">'+value['text']+'</th>';
                    html+=   '</tr>';
                });



                $('.checkout-product tfoot').html(html);

            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    //--></script>
</div>
<div id="finalize" class="shadow">
<?php if (!isset($redirect)) { ?>
<div class="checkout-product">
<h1>5. <?php echo $this->language->get('text_total'); ?></h1>

	<div id="potwierdzenia">

				<?php if ($text_agree) { ?>

					<div class="right checkbox">
						<div>							
							<?php if ($agree) { ?>
							<input type="checkbox" name="agree" value="1" checked="checked" id="potw1"/>
							<?php } else { ?>
							<input type="checkbox" name="agree" value="1" id="potw1"/>
							<?php } ?>
							<label for="potw1"><?php echo $text_agree; ?></label>
						</div>
					</div>
					<?/*
					<div class="right">Die <a href="<?php echo $this->config->get('config_url'); ?>index.php?route=information/information/info&information_id=7" alt="Widerrufsbelehrung" class="colorbox cboxElement"><b>Widerrufsbelehrung</b></a> habe ich zur Kenntnis genommen.
						<?php if ($agree2) { ?>
						<input type="checkbox" name="agree2" value="1" checked="checked" id="potw2"/>
						<?php } else { ?>
						<input type="checkbox" name="agree2" value="1" id="potw2"/>
						<?php } ?>	
					</div>
					*/?>

				<?php } ?>
				
	<div>
		<div class="checkbox">							
			<input type="checkbox" name="auto_account_newsletter" value="0" id="chcenewsletter"/><label for="chcenewsletter"><?php echo $this->language->get('text_auto_newsletter'); ?></label>
		</div>
	</div>

    <div>
		<div class="checkbox">							
			<input type="checkbox" name="auto_account" value="1" checked="checked" id="chcekonto"/><label for="chcekonto"><?php echo $this->language->get('text_auto_account'); ?></label>
		</div>
	</div>
	
	</div>

    <table data-role="table" class="attribute ui-responsive table-stripe" style="margin:0">
        <thead>
        <tr>
            <th scope="col" class="name"><?php echo $column_name; ?></th>
            <th scope="col" class="model"><?php echo $column_model; ?></th>
            <th scope="col" class="quantity"><?php echo $column_quantity; ?></th>
            <th scope="col" class="price"><?php echo $column_price; ?></th>
            <th scope="col" class="total"><?php echo $column_total; ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product) { ?>
        <tr>
            <th scope="row" class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                <?php foreach ($product['option'] as $option) { ?>
                <br />
                &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                <?php } ?>

            </th>
            <th scope="row" class="model"><?php echo $product['model']; ?></th>
            <th scope="row" class="quantity"><?php echo $product['quantity']; ?></th>
            <th scope="row" class="price"><?php echo $product['price']; ?></th>
            <th scope="row" class="total"><?php echo $product['total']; ?></th>
        </tr>
        <?php } ?>
        <?php foreach ($vouchers as $voucher) { ?>
        <tr>
            <th scope="row" class="name"><?php echo $voucher['description']; ?></th>
            <th scope="row" class="model"></th>
            <th scope="row" class="quantity">1</th>
            <th scope="row"  class="price"><?php echo $voucher['amount']; ?></th>
            <th scope="row" class="total"><?php echo $voucher['amount']; ?></th>
        </tr>
        <?php } ?>
        </tbody>
        <tfoot>
        <?php foreach ($totals as $total) { ?>
        <tr>
            <th scope="row"></th><th scope="row" colspan="3" class="price"><b><?php echo $total['title']; ?></b></th>
            <th scope="row" class="total"><?php echo $total['text']; ?></th>
        </tr>
        <?php } ?>
        </tfoot>
    </table>

		
    <div class="buttons">
        <div class="right true">
              <div class="trust mobilehide" style="display:inline-block; vertical-align:middle; margin:0 10px 0 0"></div> <input type="button" onclick="finalize()"  class="button action" value="<?php echo $text_order_confirm ?>"/>
        </div>
    </div>

</div>
<div class="payment" style="display:none;"></div>
<?php } else { ?>
<script type="text/javascript"><!--
    location = '<?php echo $redirect; ?>';
    //--></script>
<?php } ?>

<script>
     function finalize()
     {
	
	var spr1 = $('#potw1').is(':checked');
	//var spr2 = $('#potw2').is(':checked'); 	 
	 
	if (spr1) { $('#potw1').parent().removeClass('potwalert'); } else { $('#potw1').parent().addClass('potwalert'); }
	//if (spr2) { $('#potw2').parent().removeClass('potwalert'); } else { $('#potw2').parent().addClass('potwalert'); }
	 
		 
         $.ajax({
             url: 'index.php?route=checkout/checkout/validate',
             type: 'post',
             data: $('input[type=\'text\'], textarea, select, input[type=\'checkbox\'], input[type=\'radio\']:checked, input[type=\'password\'], input[type=\'hidden\']'),
             dataType: 'json',
             success: function(json) {
			
                 $('.warning, .error').remove();

                 if (json['redirect']) {		
				                     
					 location = json['redirect'];
					 
                 } else if (json['error']) { 
                     // warningi
                     // guest
                     if (json['error']['warning']) {
                         $('#payment-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                         $('.warning').fadeIn('slow');
                     }

                     if (json['error']['firstname']) { 
                         $('#payment-address input[name=\'firstname\'] + br').after('<span class="error">' + json['error']['firstname'] + '</span>');
                     }

                     if (json['error']['lastname']) { 
                         $('#payment-address input[name=\'lastname\'] + br').after('<span class="error">' + json['error']['lastname'] + '</span>');
                     }

                     if (json['error']['email']) {
                         $('#payment-address input[name=\'email\'] + br').after('<span class="error">' + json['error']['email'] + '</span>');
                     }

                     if (json['error']['telephone']) {
                         $('#payment-address input[name=\'telephone\'] + br').after('<span class="error">' + json['error']['telephone'] + '</span>');
                     }

                     if (json['error']['company_id']) {
                         $('#payment-address input[name=\'company_id\'] + br').after('<span class="error">' + json['error']['company_id'] + '</span>');
                     }

                     if (json['error']['tax_id']) {
                         $('#payment-address input[name=\'tax_id\'] + br').after('<span class="error">' + json['error']['tax_id'] + '</span>');
                     }

                     if (json['error']['address_1']) {
                         $('#payment-address input[name=\'address_1\'] + br').after('<span class="error">' + json['error']['address_1'] + '</span>');
                     }

                     if (json['error']['city']) {
                         $('#payment-address input[name=\'city\'] + br').after('<span class="error">' + json['error']['city'] + '</span>');
                     }

                     if (json['error']['postcode']) {
                         $('#payment-address input[name=\'postcode\'] + br').after('<span class="error">' + json['error']['postcode'] + '</span>');
                     }

                     if (json['error']['country']) {
                         $('#payment-address select[name=\'country_id\'] + br').after('<span class="error">' + json['error']['country'] + '</span>');
                     }

                     if (json['error']['zone']) {
                         $('#payment-address select[name=\'zone_id\'] + br').after('<span class="error">' + json['error']['zone'] + '</span>');
                     }

                     // guest shipping
                     if (json['error']['warning']) {
                         $('#shipping-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                         $('.warning').fadeIn('slow');
                     }

                     if (json['error']['shipping_firstname']) {
                         $('#shipping-address input[name=\'shipping_firstname\']').after('<span class="error">' + json['error']['shipping_firstname'] + '</span>');
                     }

                     if (json['error']['shipping_lastname']) {
                         $('#shipping-address input[name=\'shipping_lastname\']').after('<span class="error">' + json['error']['shipping_lastname'] + '</span>');
                     }

                     if (json['error']['shipping_email']) {
                         $('#shipping-address input[name=\'shipping_email\']').after('<span class="error">' + json['error']['shipping_email'] + '</span>');
                     }

                     if (json['error']['shipping_telephone']) {
                         $('#shipping-address input[name=\'shipping_telephone\']').after('<span class="error">' + json['error']['shipping_telephone'] + '</span>');
                     }

                     if (json['error']['shipping_address_1']) {
                         $('#shipping-address input[name=\'shipping_address_1\']').after('<span class="error">' + json['error']['shipping_address_1'] + '</span>');
                     }

                     if (json['error']['city']) {
                         $('#shipping-address input[name=\'shipping_city\']').after('<span class="error">' + json['error']['shipping_city'] + '</span>');
                     }

                     if (json['error']['postcode']) {
                         $('#shipping-address input[name=\'shipping_postcode\']').after('<span class="error">' + json['error']['shipping_postcode'] + '</span>');
                     }

                     if (json['error']['country']) {
                         $('#shipping-address select[name=\'shipping_country_id\']').after('<span class="error">' + json['error']['shipping_country'] + '</span>');
                     }

                     if (json['error']['zone']) {
                         $('#shipping-address select[name=\'shipping_zone_id\']').after('<span class="error">' + json['error']['shipping_zone'] + '</span>');
                     }
                     // end shipping

                     // shipping method
                     if (json['error']['shipping']) {
                         $('#shipping-methods > p').after('<span class="error">' + json['error']['shipping'] + '</span>');


                     }
                     // payment method
                     if (json['error']['payment']) {
                         $('#payment-methods > p').after('<span class="error">' + json['error']['payment'] + '</span>');


                     }
                     // end warnings
                     if (json['error']['agree']) {
                         $('#agree').after('<span class="error">' + json['error']['agree'] + '</span>');

                     }


                 } else {


				 
                     $.ajax({
                         url: 'index.php?route=checkout/checkout/getPayment',
                         dataType: 'json',
                         success: function(json) {
                             if(json['error'])
                             {

                             }
                             else
                             {
							
							 
                                 $('.payment').append(json['payment']);

                                 $("#button-confirm").trigger("click");


                             }

                         },
                         error: function(xhr, ajaxOptions, thrownError) {
                             alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                         }
                     }); 
					 


                 }
             },
             error: function(xhr, ajaxOptions, thrownError) {
                 alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
             }
         })

     }
</script>
<script>
    $(document).ready(function(){ reloadTotals(); })
</script>
</div>
</div>
<?/*
<div>
	<div id="login">
		<h1><?php echo $text_returning_customer; ?></h1>
		<b><?php echo $entry_email; ?></b><br />
		<input type="text" name="email" value="" />
		<br />
		<br />
		<b><?php echo $entry_password; ?></b><br />
		<input type="password" name="password" value="" />
		<br />
		<a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a><br />
		<br />
		<input type="button" value="<?php echo $button_login; ?>" id="button-login" class="button" /><br />
		<br />
	</div>
	<div id="checkout-promo">
		<div class="zalety">
			<div>
				<ul>
					<?php echo $this->language->get('home_zalety_ul'); ?>
				</ul>
			</div>
		</div>
	</div>
</div>
*/?>
</div>
<?php echo $footer; ?>