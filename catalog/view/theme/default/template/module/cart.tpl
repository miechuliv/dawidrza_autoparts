<div id="cart" class="mobilehide">
<div>
  <div class="heading">
	<div>
		<i class="fa fa-shopping-cart headeri"></i>
		<?/*
		<a href="./index.php?route=checkout/cart">
			<h4><?php echo $heading_title; ?></h4>
			<span id="cart-total"><?php echo $text_items; ?></span>
		</a>
		*/?>
	</div>
   </div>
</div>

  <div class="content">
  <div class="rog"></div>
  <div class="bg shadow">
    <?php if ($products || $vouchers) { ?>
    <div class="mini-cart-info">
      <table>
        <?php foreach ($products as $product) { ?>
        <tr>
          <td class="image"><?php if ($product['thumb']) { ?>
            <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
            <?php } ?></td>
          <td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
		  <?/*
            <div>
              <?php foreach ($product['option'] as $option) { ?>
              <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small><br />
              <?php } ?>
            </div>
			*/?>
			<div>
				<small>x&nbsp;<?php echo $product['quantity']; ?> / <?php echo $product['total']; ?></small>
			</div>
		  </td>
		  <?/*
          <td class="remove"><img src="catalog/view/theme/default/image/remove-small.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" onclick="(getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') ? location = 'index.php?route=checkout/cart&remove=<?php echo $product['key']; ?>' : $('#cart').load('index.php?route=module/cart&remove=<?php echo $product['key']; ?>' + ' #cart > *');" /></td>
		  */?>
        </tr>
        <?php } ?>
        <?php foreach ($vouchers as $voucher) { ?>
        <tr>
          <td class="image"></td>
          <td class="name"><?php echo $voucher['description']; ?></td>
          <td class="quantity">x&nbsp;1</td>
          <td class="total"><?php echo $voucher['amount']; ?></td>
          <td class="remove"><img src="catalog/view/theme/default/image/remove-small.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" onclick="(getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') ? location = 'index.php?route=checkout/cart&remove=<?php echo $voucher['key']; ?>' : $('#cart').load('index.php?route=module/cart&remove=<?php echo $voucher['key']; ?>' + ' #cart > *');" /></td>
        </tr>
        <?php } ?>
      </table>
    </div>
	<?/*
    <div class="mini-cart-total">
      <table>
        <?php foreach ($totals as $total) { ?>
        <tr>
          <td class="right"><b><?php echo $total['title']; ?>:</b></td>
          <td class="right"><?php echo $total['text']; ?></td>
        </tr>
        <?php } ?>
      </table>
    </div>
	*/?>
	<?/*
	<div class="podlicz">
	
	<?php 
		$darmowa_od = 250;
		$wartosc = $this->data['podsum']; 
		$do_darmowej = $darmowa_od-$wartosc;
	?>
	
	<?php if($wartosc >= $darmowa_od) { ?>
		<strong class="cel"><?php echo $this->language->get('text_free'); ?></strong>
	<?php } else { ?>
		<?php echo $this->language->get('text_left'); ?> <strong><?php echo $do_darmowej; ?> <?php echo $this->currency->getSymbolRight($this->session->data['currency']) ?></strong> <?php echo $this->language->get('text_toofree'); ?>
	<?php } ?>

	</div>
	*/?>
    <div class="butki">
	<a href="<?php echo $checkout; ?>" class="button action long"><?php echo $text_checkout; ?> <i class="fa fa-arrow-right"></i></a>
	<a href="<?php echo $cart; ?>" class="button long"><?php echo $text_cart; ?></a> 
	</div>
    <?php } else { ?>
    <div class="empty"><?php echo $text_empty; ?></div>
    <?php } ?>
  </div>
</div>
</div>