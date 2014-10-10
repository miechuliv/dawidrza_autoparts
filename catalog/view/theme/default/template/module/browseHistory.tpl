<?php if ($products): ?>

<div class="s_module_content">

  <h2 class="s_title_1"><?php echo $heading_title; ?></h2>
  <div class="clear"></div>

  <div class="product-list">
  <?php for ($i = 0; $i < sizeof($products); $i = $i + $listing_cols): ?>
    <?php for ($j = $i; $j < ($i + $listing_cols); $j++): ?>
      <?php if (isset($products[$j])): ?>
      <div>

	<a href="<?php echo $products[$j]['href']; ?>">
	  
	  <?  if ( $products[$j]['additional_image'] != false ) { ?>
		<div class="image">
          <?/*  <img id="<?=$products[$j]['product_id']?>_first" onmouseover="ReplaceImageOnHover(<?=$products[$j]['product_id']?>)" src="<?php echo $products[$j]['thumb']; ?>" title="<?php echo $products[$j]['name']; ?>" alt="<?php echo $products[$j]['name']; ?>" />*/?>
			  <img id="<?=$products[$j]['product_id']?>_second" onmouseout="ReplaceImageOnHoverOut(<?=$products[$j]['product_id']?>)" src="<?php echo $products[$j]['additional_image']; ?>" class="DisplayOff" title="<?php echo $products[$j]['name']; ?>" alt="<?php echo $products[$j]['name']; ?>" />
		</div>
			
          <?php } else { ?>
        
			<div class="image">
				<img src="<?php echo $products[$j]['thumb']; ?>" title="<?php echo $products[$j]['name']; ?>" alt="<?php echo $products[$j]['name']; ?>" />
			</div>
			
          <?php } ?>

		<div class="name">
			<?php echo $products[$j]['name']; ?>
		</div>
		
	</a>
		
		<div class="price">
		  <?php if ($products[$j]['price']): ?>
          <?php if (!$products[$j]['special']): ?>
        <strong>  <?php echo $products[$j]['price']; ?></strong>
          <?php else: ?>
          <?php echo $products[$j]['special']; ?> <span style="color:red; text-decoration:line-through;"><?php echo $products[$j]['price']; ?></span>
          <?php endif ?>
          <?php endif; ?>
		</div>

      </div>
      <?php endif; ?>
    <?php endfor; ?>

    <!--<div class="clear"></div>-->
  <?php endfor; ?>
  </div>

</div>
<?php endif; ?>