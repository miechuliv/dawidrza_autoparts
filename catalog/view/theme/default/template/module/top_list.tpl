<div class="box">
    <?php if($top_lists){ ?>
		<div class="toplist">
			<?php foreach($top_lists as $top_list){ ?>
				<div>
					<h3><?php echo $this->language->get('toplist'); ?> <?php echo $top_list['name']; ?></h3>
					<div>
						<?php $i=1; ?>
						<?php foreach($top_list['products'] as $product){ ?>
							<div class="prod">
								<div>
									<strong><?php echo $i; echo'.'; ?></strong>
								</div>
								<div class="img">									
									<a href="<?php echo $product['href']; ?>" >
										<img src="<?php echo $product['image']; ?>" width="80" height="80" alt="<?php echo $product['name']; ?>" />
									</a>
								</div>
								<div>
									<a href="<?php echo $product['href']; ?>" >
										<?php echo $product['name']; ?>
									</a>
									
									 <?php if ($product['rating']) { ?>
										<div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /> <small>(<?php echo $product['reviews']; ?>)</small></div>
									 <?php } ?>
					
									<span>										
										<?php echo $this->language->get('toplist_od'); ?> <?php echo $product['price']; ?>
									</span>									
								</div>
							</div>
							<?php $i++; ?>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		</div>
    <?php } ?>
</div>
