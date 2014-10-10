<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-product">
      <?php foreach ($products as $product) { ?>
      <div>
		<a href="<?php echo $product['href']; ?>">
        <?php if ($product['thumb']) { ?>
        <div class="image"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" width="185" height="185"/></div>
        <?php } ?>
        <div class="name"><?php echo $product['name']; ?></div>
		</a>
        <?php if ($product['price']) { ?>
        <div class="price">
          <?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
          <?php } ?>
        </div>
        <?php } ?>
        <?php /* if ($product['rating']) { ?>
        <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
        <?php } */ ?>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
