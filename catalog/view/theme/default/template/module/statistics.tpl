<div class="box">
  <div class="box-content mapa"> 
  
  <div id="lewa" class="strzalki"></div>
  <div id="prawa" class="strzalki"></div>
  
	<div id="head-big">
	  	<h1>Diesel Land live</h1>
	</div>

        <div class="box-product nazywo">
            <?php if($products){ ?>
            <?php foreach ($products as $product) { ?>
            <div>
                <?php if ($product['thumb']) { ?>
                <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
                <?php } ?>
                <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
                <?php if ($product['price']) { ?>
                <div class="price">
                    <?php if (!$product['special']) { ?>
                    <?php echo $product['price']; ?>
                    <?php } else { ?>
                    <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
                    <?php } ?>
                </div>
                <div class="gdziekupione"><?php echo $product['city']; ?></div>
                <?php } ?>
                <?php if ($product['rating']) { ?>
                <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
                <?php } ?>
                <div class="cart"><input type="button" value="Jetzt Kaufen" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button" /></div>
            </div>
            <?php } ?>
            <?php } ?>
        </div>
		
    </div>
</div>
