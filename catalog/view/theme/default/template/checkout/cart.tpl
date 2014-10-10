<?php echo $header; ?>
<?php if ($attention) { ?>
<div class="attention"><?php echo $attention; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php // echo $column_left; ?><?php echo $column_right; ?>
<div id="content" class="block"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1 style="margin:10px 0;"><?php echo $heading_title; ?>
    <?php if ($weight) { ?>
    &nbsp;(<?php echo $weight; ?>)
    <?php } ?>
  </h1>
  <div class="buttons basket" style="margin-bottom:10px;">
	<div class="left mobileshow"><a class="button grey cofka"><?php echo $button_shopping; ?></a></div>
    <div class="right true"><a href="<?php echo $checkout; ?>" class="button action"><?php echo $button_checkout; ?> <i class="fa fa-arrow-right"></i></a></div>
   <?/* <div class="left"><a class="button grey cofka"><?php echo $button_shopping; ?></a></div> */?>
  </div>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <div class="cart-info">
      <table data-role="table" class="attribute ui-responsive table-stripe">
        <thead>
          <tr>
            <th scope="col" class="image widthauto"><?php echo $column_name; ?></th>
            <th scope="col" class="name"></th>
            <th scope="col" class="model widthauto"><?php echo $column_model; ?></th> 
            <th scope="col" class="quantity widthauto"><?php echo $column_quantity; ?></th>
            <th scope="col" class="price widthauto"><?php echo $column_price; ?></th>
            <th scope="col" class="total"><?php echo $column_total; ?></th>
			<th scope="col" class="total widthauto"><?php echo $this->language->get('text_delete'); ?></th>
          </tr>
        </thead>
        <tbody>
        <?php $stock_array = array(); ?>
          <?php foreach ($products as $product) { ?>
          <tr>
            <th scope="col" class="image"><?php if ($product['thumb']) { ?>
              <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
              <?php } ?></th>
            <th scope="col" class="name" style="text-align:left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?> <span class="mobileshow">(<?php echo $product['model']; ?>)</span></a>

              <?php if (!$product['stock']) { ?>
              <span class="stock">***</span>
                <?php $stock_array[] = $product['key']; ?>
              <?php } ?>
              <div>
                <?php foreach ($product['option'] as $option) { ?>
                - <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
                <?php } ?>
              </div>
              <?php if ($product['reward']) { ?>
              <small><?php echo $product['reward']; ?></small>
              <?php } ?>
              </th>
            <th scope="col" class="model mobilehide"><?php echo $product['model']; ?></th>
              <th scope="col" class="quantity">
				<div>
					<?php /* <a class="odejmij" href="javascript:void(0);" >-</a> */ ?>
					<input type="text" class="ilosc" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" />
					<?php /* <a class="dodaj" href="javascript:void(0);" >+</a> */ ?>
					<input type="hidden" class="p_id" value="<?php echo $product['key']; ?>" />
				</div>
              </th>
            <th scope="col" class="price mobilehide"><?php echo $product['price']; ?></th>
            <th scope="col" class="total widthauto"><?php echo $product['total']; ?></th>
			<th scope="col" class="delete widthauto center"><i class="fa fa-trash-o mobileabsolute" onclick="removeFromCart(this)"></i></th>
          </tr>
          <?php } ?>
          <?php foreach ($vouchers as $vouchers) { ?>
          <tr>
            <th scope="col" class="image"></th>
            <th scope="col" class="name" style="text-align:left"><?php echo $vouchers['description']; ?></th>
            <th scope="col" class="model"></th>
            <th scope="col" class="quantity"><input type="text" name="" value="1" size="1" disabled="disabled" />
            <?php /*  &nbsp;<a href="<?php echo $vouchers['remove']; ?>"><img src="catalog/view/theme/default/image/remove.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" /></a>*/ ?> </th>
            <th scope="col" class="price"><?php echo $vouchers['amount']; ?></th>
            <th scope="col" class="total"><?php echo $vouchers['amount']; ?></th>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </form>
  <?php /* if ($coupon_status || $voucher_status || $reward_status || $shipping_status) { ?>
  <h2><?php echo $text_next; ?></h2>
  <div class="content">
    <p><?php echo $text_next_choice; ?></p>
    <table class="radio">
      <?php if ($coupon_status) { ?>
      <tr class="highlight">
        <th scope="col"><?php if ($next == 'coupon') { ?>
          <input type="radio" name="next" value="coupon" id="use_coupon" checked="checked" />
          <?php } else { ?>
          <input type="radio" name="next" value="coupon" id="use_coupon" />
          <?php } ?></th>
        <th scope="col"><label for="use_coupon"><?php echo $text_use_coupon; ?></label></th>
      </tr>
      <?php } ?>
      <?php if ($voucher_status) { ?>
      <tr class="highlight">
        <th scope="col"><?php if ($next == 'voucher') { ?>
          <input type="radio" name="next" value="voucher" id="use_voucher" checked="checked" />
          <?php } else { ?>
          <input type="radio" name="next" value="voucher" id="use_voucher" />
          <?php } ?></th>
        <th scope="col"><label for="use_voucher"><?php echo $text_use_voucher; ?></label></th>
      </tr>
      <?php } ?>
      <?php if ($reward_status) { ?>
      <tr class="highlight">
        <th scope="col"><?php if ($next == 'reward') { ?>
          <input type="radio" name="next" value="reward" id="use_reward" checked="checked" />
          <?php } else { ?>
          <input type="radio" name="next" value="reward" id="use_reward" />
          <?php } ?></th>
        <th scope="col"><label for="use_reward"><?php echo $text_use_reward; ?></label></th>
      </tr>
      <?php } ?>
      <?php if ($shipping_status) { ?>
      <tr class="highlight">
        <th scope="col"><?php if ($next == 'shipping') { ?>
          <input type="radio" name="next" value="shipping" id="shipping_estimate" checked="checked" />
          <?php } else { ?>
          <input type="radio" name="next" value="shipping" id="shipping_estimate" />
          <?php } ?></th>
        <th scope="col"><label for="shipping_estimate"><?php echo $text_shipping_estimate; ?></label></th>
      </tr>
      <?php } ?>
    </table>
  </div>
  <div class="cart-module">
    <div id="coupon" class="content" style="display: <?php echo ($next == 'coupon' ? 'block' : 'none'); ?>;">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <?php echo $entry_coupon; ?>&nbsp;
        <input type="text" name="coupon" value="<?php echo $coupon; ?>" />
        <input type="hidden" name="next" value="coupon" />
        &nbsp;
        <input type="submit" value="<?php echo $button_coupon; ?>" class="button" />
      </form>
    </div>
    <div id="voucher" class="content" style="display: <?php echo ($next == 'voucher' ? 'block' : 'none'); ?>;">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <?php echo $entry_voucher; ?>&nbsp;
        <input type="text" name="voucher" value="<?php echo $voucher; ?>" />
        <input type="hidden" name="next" value="voucher" />
        &nbsp;
        <input type="submit" value="<?php echo $button_voucher; ?>" class="button" />
      </form>
    </div>
    <div id="reward" class="content" style="display: <?php echo ($next == 'reward' ? 'block' : 'none'); ?>;">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <?php echo $entry_reward; ?>&nbsp;
        <input type="text" name="reward" value="<?php echo $reward; ?>" />
        <input type="hidden" name="next" value="reward" />
        &nbsp;
        <input type="submit" value="<?php echo $button_reward; ?>" class="button" />
      </form>
    </div>
    <div id="shipping" class="content" style="display: <?php echo ($next == 'shipping' ? 'block' : 'none'); ?>;">
      <p><?php echo $text_shipping_detail; ?></p>
      <table>
        <tr>
          <th scope="col"><span class="required">*</span> <?php echo $entry_country; ?></th>
          <th scope="col"><select name="country_id">
              <option value=""><?php echo $text_select; ?></option>
              <?php foreach ($countries as $country) { ?>
              <?php if ($country['country_id'] == $country_id) { ?>
              <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></th>
        </tr>
        <tr>
          <th scope="col"><span class="required">*</span> <?php echo $entry_zone; ?></th>
          <th scope="col"><select name="zone_id">
            </select></th>
        </tr>
        <tr>
          <th scope="col"><span id="postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></th>
          <th scope="col"><input type="text" name="postcode" value="<?php echo $postcode; ?>" /></th>
        </tr>
      </table>
      <input type="button" value="<?php echo $button_quote; ?>" id="button-quote" class="button" />
    </div>
  </div>
  <?php } */ ?>
  <div class="cart-total">
	  <div>
		<div>
			<div class="table alignmiddle">
				<div class="zalety mobilehide">
					<div class="shadow">
						<ul>
							<?php echo $this->language->get('home_zalety_ul'); ?>
						</ul>
					</div>
				</div>
				<?/*
				<div id="nieosiagniete">
					<?php echo $this->language->get('text_left'); ?> <strong id="wartosc"></strong> <?php echo $this->language->get('text_toofree'); ?>
				</div>
				<div id="osiagniete">
					<strong class="cel"><?php echo $this->language->get('text_free_checkout'); ?></strong>
				</div>
				*/?>
			</div>
		</div>
		<div>
			<table id="total">
			  <?php foreach ($totals as $total) { ?>
			  <tr>
				<th scope="col" class="right"><b><?php echo $total['title']; ?></b></th>
				<th scope="col" class="right"><?php echo $total['text']; ?></th>
			  </tr>
			  <?php } ?>
			  </tbody><tfoot>
			  <tr>
				<th scope="col" colspan="2">
					<div id="payts">
						<img src="image/data/payment icons/visa_straight_32px.png" alt="visa">
						<img src="image/data/payment icons/mastercard_curved_32px.png" alt="mastercard">
						<img src="image/data/payment icons/moneybookers_curved_32px.png" alt="monebookers">
						<img src="image/data/payment icons/paypal_curved_32px.png" alt="paypal">
					</div>
				</th>
			 </tr>
			 </tfoot>
			</table>
		</div>
	  </div>
  </div>
  <div class="buttons">
    <div class="right true mobile100"><a href="<?php echo $checkout; ?>" class="button action"><?php echo $button_checkout; ?> <i class="fa fa-arrow-right"></i></a></div>
    <div class="left mobilehide"><a class="button grey cofka"><?php echo $button_shopping; ?></a></div>
  </div>
  		<div class="mobileshow">
				<div class="zalety">
					<div class="shadow" style="padding:10px; margin:10px 0;">
						<ul>
							<?php echo $this->language->get('home_zalety_ul'); ?>
						</ul>
					</div>
				</div>
		</div>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$('input[name=\'next\']').bind('change', function() {
	$('.cart-module > div').hide();
	
	$('#' + this.value).show();
});
//--></script>
<?php if ($shipping_status) { ?>
<script type="text/javascript"><!--
 var counter = 0;
 $(function() {
        $('#dodaj').click(function() {
			counter++;
            $('#ilosc').val(counter);
        });
    });
$('#button-quote').live('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/quote',
		type: 'post',
		data: 'country_id=' + $('select[name=\'country_id\']').val() + '&zone_id=' + $('select[name=\'zone_id\']').val() + '&postcode=' + encodeURIComponent($('input[name=\'postcode\']').val()),
		dataType: 'json',		
		beforeSend: function() {
			$('#button-quote').attr('disabled', true);
			$('#button-quote').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-quote').attr('disabled', false);
			$('.wait').remove();
		},		
		success: function(json) {
			$('.success, .warning, .attention, .error').remove();			
						
			if (json['error']) {
				if (json['error']['warning']) {
					$('#notification').html('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
					
					$('.warning').fadeIn('slow');
					
					$('html, body').animate({ scrollTop: 0 }, 'slow'); 
				}	
							
				if (json['error']['country']) {
					$('select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
				}	
				
				if (json['error']['zone']) {
					$('select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
				}
				
				if (json['error']['postcode']) {
					$('input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
				}					
			}
			
			if (json['shipping_method']) {
				html  = '<h2><?php echo $text_shipping_method; ?></h2>';
				html += '<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">';
				html += '  <table class="radio">';
				
				for (i in json['shipping_method']) {
					html += '<tr>';
					html += '  <th scope="col" colspan="3"><b>' + json['shipping_method'][i]['title'] + '</b></th>';
					html += '</tr>';
				
					if (!json['shipping_method'][i]['error']) {
						for (j in json['shipping_method'][i]['quote']) {
							html += '<tr class="highlight">';
							
							if (json['shipping_method'][i]['quote'][j]['code'] == '<?php echo $shipping_method; ?>') {
								html += '<th scope="col"><input type="radio" name="shipping_method" value="' + json['shipping_method'][i]['quote'][j]['code'] + '" id="' + json['shipping_method'][i]['quote'][j]['code'] + '" checked="checked" /></th>';
							} else {
								html += '<th scope="col"><input type="radio" name="shipping_method" value="' + json['shipping_method'][i]['quote'][j]['code'] + '" id="' + json['shipping_method'][i]['quote'][j]['code'] + '" /></th>';
							}
								
							html += '  <th scope="col"><label for="' + json['shipping_method'][i]['quote'][j]['code'] + '">' + json['shipping_method'][i]['quote'][j]['title'] + '</label></th>';
							html += '  <th scope="col" style="text-align: right;"><label for="' + json['shipping_method'][i]['quote'][j]['code'] + '">' + json['shipping_method'][i]['quote'][j]['text'] + '</label></th>';
							html += '</tr>';
						}		
					} else {
						html += '<tr>';
						html += '  <th scope="col" colspan="3"><div class="error">' + json['shipping_method'][i]['error'] + '</div></th>';
						html += '</tr>';						
					}
				}
				
				html += '  </table>';
				html += '  <br />';
				html += '  <input type="hidden" name="next" value="shipping" />';
				
				<?php if ($shipping_method) { ?>
				html += '  <input type="submit" value="<?php echo $button_shipping; ?>" id="button-shipping" class="button" />';	
				<?php } else { ?>
				html += '  <input type="submit" value="<?php echo $button_shipping; ?>" id="button-shipping" class="button" disabled="disabled" />';	
				<?php } ?>
							
				html += '</form>';
				
				$.colorbox({
					overlayClose: true,
					opacity: 0.5,
					width: '600px',
					height: '400px',
					href: false,
					html: html
				});
				
				$('input[name=\'shipping_method\']').bind('change', function() {
					$('#button-shipping').attr('disabled', false);
				});
			}
		}
	});
});
//--></script> 
<script type="text/javascript"><!--
$('select[name=\'country_id\']').bind('change', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#postcode-required').show();
			} else {
				$('#postcode-required').hide();
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
			
			$('select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thcolnError) {
			alert(thcolnError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});




$('select[name=\'country_id\']').trigger('change');
//--></script>
<?php } ?>
<script type="text/javascript" >
    var change_timer;

    var stock_flag = [];

    <?php foreach($stock_array as $st){ ?>
            stock_flag['<?php echo $st; ?>'] = true;
     <?php } ?>
	 
	
	/*
	function darmowa_wysylka() {	
	
		$('#nieosiagniete').hide();
		$('#osiagniete').hide();
	
		var suma = $('#total > tbody > tr:last-child > th:last-child');	 
		var suma = suma.html();
		var suma = parseFloat(suma.replace(' <?php echo $this->currency->getSymbolRight($this->session->data['currency']) ?>','').replace('.','').replace(',','.'));
		var suma = 250 - suma;
		var suma = Math.round(suma * 100) / 100;
		var wartosc = $('#wartosc').html(suma+' <?php echo $this->currency->getSymbolRight($this->session->data['currency']) ?>');
		
		if(suma <= 0) {
			$('#nieosiagniete').hide();
			$('#osiagniete').show();
		} else {
			$('#nieosiagniete').show();
			$('#osiagniete').hide();
		}
	}	
	
	$(document).ready(function() {	
		darmowa_wysylka();
	});
	*/

    $('.quantity input').bind('keyup', function() {
            var quantity = $(this).val();
        targetElem = $(this);

        $.ajax({
            url: 'index.php?route=checkout/cart/changeQuantity',
            dataType: 'json',
            type: 'POST',
            data     : {
                product_id : $(this).nextAll('.p_id').val(),
                change : 'replace',
                quantity: quantity
            },

            success: function(json) {			
			
                var html = '<tbody>';

                $.each(json['total'],function(key,value){
                    html+='<tr><th scope="col"><strong>'+value["title"]+'</strong></th><th scope="col">'+value["text"]+'</th></tr>';
										
					if(value["code"]=='total') {
				
							var wynik = parseFloat(value["text"].replace(' <?php echo $this->currency->getSymbolRight($this->session->data['currency']) ?>','').replace('.','').replace(',','.'));
							wynik = 250 - wynik;
							wynik = Math.round(wynik * 100) / 100;
							var wartosc = $('#wartosc').html(wynik+' <?php echo $this->currency->getSymbolRight($this->session->data['currency']) ?>');
							if(wynik <= 0) {
								$('#nieosiagniete').hide();
								$('#osiagniete').show();
							} else {
								$('#nieosiagniete').show();
								$('#osiagniete').hide();
							}						
								
					}
				
                });
				

				 html+='</tbody><tfoot>';
			
				html+= ' <tr>';
				html+= '	<th scope="col" colspan="2">';
				html+= '		<div id="payts">';
				html+= '			<img src="image/data/payment icons/visa_straight_32px.png" alt="visa">';
				html+= '			<img src="image/data/payment icons/mastercard_curved_32px.png" alt="mastercard">';
				html+= '			<img src="image/data/payment icons/moneybookers_curved_32px.png" alt="monebookers">';
				html+= '			<img src="image/data/payment icons/paypal_curved_32px.png" alt="paypal">';
				html+= '		</div>';
				html+= '	</th>';
				html+= '</tr>';

				html+= '</tfoot>';
               

                $('#total').html(html);

                var newTarget = $(targetElem).parents('.quantity').nextAll('.total');

                $(newTarget).html(json['product_total']);
                newTarget = $(targetElem).parents('.quantity').nextAll('.price');
                $(newTarget).html(json['price']);


                if(!json['stock'])
                {

                    if(stock_flag[json['key']] == true)
                    {

                    }
                    else
                    {
                        newTarget = $(targetElem).parents('.quantity').prev().prev().find('a');

                        $(newTarget).after('<span class="stock">***</span>');

                        $('#content').before('<div class="warning"><?php echo $error_warning_text; ?><img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                        stock_flag[json['key']] = true;
                    }

                }
                else
                {
                    if(stock_flag[json['key']] == true)
                    {
                        $(targetElem).parents('tr').find('.stock').remove();


                        delete stock_flag[json['key']];


                        if(stock_flag.length == 0)
                        {
                            $('.warning').remove();
                        }
                    }
                }
/*
                if($(targetElem).val() == 0 || $(targetElem).val() == '')
                {
                    $(targetElem).parents('tr').remove();
                }
*/

            },
            error: function (xhr, ajaxOptions, thcolnError) {


            }
        });
    });

    var targ;

    function removeFromCart(elem)
    {

        targ = elem;
        var key = $(elem).parent().prev().prev().prev().find('.p_id').val();
        $.ajax({
            url: 'index.php?route=checkout/cart/remove',
            dataType: 'json',
            type: 'POST',
            data: {key: key},
            success: function(json) {

                $(targ).parents('tr').remove();


            },
            error: function (xhr, ajaxOptions, thcolnError) {


            }
        });
    }

    $('.quantity a').bind('click', function() {

        targetElem = $(this).siblings('.ilosc');
        currentQuantity = $(targetElem).val();

        changeType = $(this).attr('class');

        if(changeType!='dodaj' && changeType!='odejmij')
        {
            return true;
        }

        $.ajax({
            url: 'index.php?route=checkout/cart/changeQuantity',
            dataType: 'json',
            type: 'POST',
            data     : {
                product_id : $(this).nextAll('.p_id').val(),
                change : changeType
            },

            success: function(json) {

                if(changeType=='dodaj')
                {
                    currentQuantity++
                    $(targetElem).val(currentQuantity);
                }
                if(changeType=='odejmij')
                {
                    currentQuantity--;
                    if(currentQuantity <= 0)
                    {
                        $(targetElem).parent().parent().remove();

                    }
                    $(targetElem).val(currentQuantity);
                }

                var html = '<tbody>';

                $.each(json['total'],function(key,value){
                    html+='<tr><th scope="col">'+value["title"]+'</th><th scope="col">'+value["text"]+'</th></tr>';
                })

                html+='</tbody>';

                $('#total').html(html);

                var newTarget = $(targetElem).parents('.quantity').nextAll('.total');

                $(newTarget).html(json['product_total']);

                newTarget = $(targetElem).parents('.quantity').nextAll('.price');

                $(newTarget).html(json['price']);


            },
            error: function (xhr, ajaxOptions, thcolnError) {


            }
        });
    });

	
</script>
<?php echo $footer; ?>
