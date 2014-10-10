


    <?php foreach ($products as $product) { ?>
    <div class="listprod">
	
	<a href="<?php echo $product['href']; ?>">

      <?php if ($product['thumb']) { ?>

        <div class="image"><img  src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></div>
		
        <?php } ?>
      <div class="name"><?php echo $product['name']; ?></div>
      <?/*<div class="description"><?php echo $product['description']; ?></div>*/?>
	  </a>
		  
	  <?php if ($product['rating']) { ?>
		<div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /> <small>(<?php echo $product['reviews']; ?>)</small></div>
      <?php } ?>
	  	  

	  
      <?php if ($product['price']) { ?>
      <div class="price">
        <?php if (!$product['special']) { ?>
        <?php /* echo $product['price']; */ ?>

        <?php } else { ?>
        <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
        <?php } ?>
        <?php if ($product['tax']) { ?>
        <span><?php echo $text_tax; ?> <?php echo $product['tax']; ?> netto</span>
        <?php } ?>
      </div>
      <?php } ?>

	  
    </div>
    <?php } ?>

    <?php if($last_ajax_page){ ?>
         <div class="pagination"><?php echo $pagination; ?></div>
    <?php } ?>



 

