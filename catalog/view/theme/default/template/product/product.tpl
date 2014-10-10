<?php echo $header; ?><?php // echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>

  	<div class="mobileshow" style="padding:0 0 5px;"><h1><?php echo $heading_title; ?> <small><?php echo $model; ?></small></h1> 
	<?php if($top_lists) { ?><p style="margin:10px 0 10px" class="toplist"><i><?php foreach($top_lists as $top_list){ ?><?php echo $top_list; ?><?php } ?></i> <i class="fa fa-trophy" style="color:gold"></i></p><?php } ?>
	<?php if(!$rating=='0'){ ?><br/><img src="catalog/view/theme/default/image/stars-<?php echo $rating; ?>.png" alt="<?php echo $reviews; ?>" /> <small style="font-weight:normal;font-size:11px;">(<?php echo $reviews; ?>)</small><?php } ?></div>

		
	<div class="product-info">

    <?php if ($thumb || $images) { ?>	
		<div class="left autowidth">
			
			<div id="product-title">
				<h1><?php echo $heading_title; ?> <small>(# <?php echo $model; ?>)</small></h1>
			</div>
	
		  <div id="main"></div>
		  <ul id="thumbs">
			<?php foreach ($option_images as $key => $op_image) { ?>
			  <li>
				<a class="first" id="opt_img_<?php echo $key; ?>" href="<?php echo $op_image['popup']; ?>" title="<?php echo $op_image['title']; ?>">
					<img src="<?php echo $op_image['thumb']; ?>" title="<?php echo $op_image['title']; ?>" alt="<?php echo $op_image['alt']; ?>" />
				</a>
			  </li>
			<?php } ?>
			<?php foreach ($images as $image) { ?>
				<li>
					<a href="<?php echo $image['popup']; ?>">
						<img src="<?php echo $image['thumb']; ?>" alt="<?php echo $heading_title; ?>"/>
					</a>
				</li>
			<?php } ?>
			</ul>  
		</div>		
    <?php } else { ?>
		<div class="left autowidth" style="background:url(./image/no-image.jpg) no-repeat center center">
			<div id="product-title">
				<h1><?php echo $heading_title; ?> <small>(# <?php echo $model; ?>)</small></h1>
			</div>
		</div>
	<?php } ?>

    <div class="right">
		<div id="product-producent">
			<?php if ($manufacturer) { ?>
				<span><?php echo $text_manufacturer; ?></span> <a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a><br />
			<?php } /* usunąć -> */ else { ?>
				<img src="./image/demo/kross.jpg" alt=""/>
			<?php } ?>
		</div>	
	
	<div class="description">
		
		<div>
		
			<div id="product-price">	
				<div class="price">
					<?php if ($price) { ?>
						<?php // echo $text_price; ?>
						<?php if (!$special) { ?>
					  
						<?php echo $price; ?>
						
						<?php /* if ($tax) { ?>
						<span class="od"><?php echo $this->language->get('text_od'); ?> </span><span class="price-tax"><?php echo $tax; ?> <?php echo $text_tax; ?></span> <span class="kutang">/</span>
						<?php }			  
						<span class="price-normal"><?php echo $price; ?> <?php echo $this->language->get('text_brutto'); ?></span> */ ?>
						
						<?php } else { ?>
						<span class="price-old"><?php echo $price; ?></span> <span class="price-new"><?php echo $special; ?></span>			  
						<?php } ?>
					<?php } ?>
				</div>
			</div> 
			
			<?php if(!$rating=='0'){ ?>
				<div id="product-rating">
					<img src="catalog/view/theme/default/image/bigstar-<?php echo $rating; ?>.png" alt="<?php echo $reviews; ?>" /> <small style="font-weight:normal;font-size:11px;">(<?php echo $reviews; ?>)</small>
				</div>
			<?php } /* usunac -> */ else { ?>
				<div id="product-rating">
					<img src="catalog/view/theme/default/image/bigstar-4.png" alt="<?php echo $reviews; ?>" /><br/> <small style="font-weight:normal;font-size:11px;">(1<?php echo $reviews; ?>)</small>
				</div>
			<?php } ?>

			<?php if($top_lists) { ?><p style="margin:0 0 10px" class="toplist mobilehide"><i><?php foreach($top_lists as $top_list){ ?><?php echo $top_list; ?><?php } ?></i> <i class="fa fa-trophy" style="color:gold"></i></p><?php } ?>

		   <?php /* <span><?php echo $text_reward; ?></span> <?php echo $reward; ?><br /> 
			<span><?php echo $text_stock; ?></span> <?php echo $stock; ?>*/?>
		
		</div>
		<div>
		
			<div class="options">
			<table>	
		  <?php if ($options) { ?>
			<?php foreach ($options as $option) { ?>
			<?php if ($option['type'] == 'select') { ?>
			<tr id="option-<?php echo $option['product_option_id']; ?>" class="option">
				<?/*<td>
				  <div>
					  <?php if ($option['required']) { ?>
					  <span class="required">*</span>
					  <?php } ?>
					  <b><?php echo $option['name']; ?>:</b> 
				  </div>
				</td>*/?>
				<td>
				  <select id="opt_<?php echo $option['option_id']; ?>" name="option[<?php echo $option['product_option_id']; ?>]" class="select-element">
					<option value=""><?php echo $option['name']; ?></option>
					<?php foreach ($option['option_value'] as $option_value) { ?>
					<option quantity="<?php echo $option_value['quantity']; ?>" model="<?php echo $option_value['model']; ?>" id="opt_val_<?php echo $option_value['option_value_id']; ?>" value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
					<?php if ($option_value['price']) { ?>
					(<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
					<?php } ?>
					</option>
					<?php } ?>
				  </select>
				  <?php if($option['option_id']==8) { ?><a href="#">Need help? Jump to <span>Sizes Chart</span></a><? } ?>
				</td>
			</tr>
			<?php } ?>
			<?php if ($option['type'] == 'radio') { ?>
			<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
			  <?php if ($option['required']) { ?>
			  <span class="required">*</span>
			  <?php } ?>
			  <b><?php echo $option['name']; ?>:</b><br />
			  <?php foreach ($option['option_value'] as $option_value) { ?>
			  <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
			  <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
				<?php if ($option_value['price']) { ?>
				(<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
				<?php } ?>
			  </label>
			  <br />
			  <?php } ?>
			</div>
			<br />
			<?php } ?>
			<?php if ($option['type'] == 'checkbox') { ?>
			<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
			  <?php if ($option['required']) { ?>
			  <span class="required">*</span>
			  <?php } ?>
			  <b><?php echo $option['name']; ?>:</b><br />
			  <?php foreach ($option['option_value'] as $option_value) { ?>
			  <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
			  <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"> <?php echo $option_value['name']; ?>
				<?php if ($option_value['price']) { ?>
				(<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
				<?php } ?>
			  </label>
			  <br />
			  <?php } ?>
			</div>
			<br />
			<?php } ?>
			<?php if ($option['type'] == 'text') { ?>
			<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
			  <?php if ($option['required']) { ?>
			  <span class="required">*</span>
			  <?php } ?>
			  <b><?php echo $option['name']; ?>:</b><br />
			  <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" />
			</div>
			<br />
			<?php } ?>
			<?php if ($option['type'] == 'textarea') { ?>
			<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
			  <?php if ($option['required']) { ?>
			  <span class="required">*</span>
			  <?php } ?>
			  <b><?php echo $option['name']; ?>:</b><br />
			  <textarea name="option[<?php echo $option['product_option_id']; ?>]" cols="40" rows="5"><?php echo $option['option_value']; ?></textarea>
			</div>
			<br />
			<?php } ?>
			<?php if ($option['type'] == 'file') { ?>
			<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
			  <?php if ($option['required']) { ?>
			  <span class="required">*</span>
			  <?php } ?>
			  <b><?php echo $option['name']; ?>:</b><br />
			  <a id="button-option-<?php echo $option['product_option_id']; ?>" class="button"><span><?php echo $button_upload; ?></span></a>
			  <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" />
			</div>
			<br />
			<?php } ?>
			<?php if ($option['type'] == 'date') { ?>
			<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
			  <?php if ($option['required']) { ?>
			  <span class="required">*</span>
			  <?php } ?>
			  <b><?php echo $option['name']; ?>:</b><br />
			  <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="date" />
			</div>
			<br />
			<?php } ?>
			<?php if ($option['type'] == 'datetime') { ?>
			<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
			  <?php if ($option['required']) { ?>
			  <span class="required">*</span>
			  <?php } ?>
			  <b><?php echo $option['name']; ?>:</b><br />
			  <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="datetime" />
			</div>
			<br />
			<?php } ?>
			<?php if ($option['type'] == 'time') { ?>
			<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
			  <?php if ($option['required']) { ?>
			  <span class="required">*</span>
			  <?php } ?>
			  <b><?php echo $option['name']; ?>:</b><br />
			  <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="time" />
			</div>
			<br />
			<?php } ?>
			<?php } ?>

		  <?php } ?>
		  <?/*
		  <tr>
		  <td>
		   <b><?php echo $text_qty; ?></b>
		  </td><td>
			  <input type="text" name="quantity" size="2" id="ile" value="<?php echo $minimum; ?>" />
			  <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
			</td>
		  </tr>
		  */?>
			<input type="text" name="quantity" size="2" id="ile" value="<?php // echo $minimum; ?>1" style="display:none"/>
			<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
		</table>



			<?php /* if ($points) { ?>
			<span class="reward"><small><?php echo $text_points; ?> <?php echo $points; ?></small></span> <br />
			<?php } ?>
			<?php if ($discounts) { ?>
			<br />
			<div class="discount">
			  <?php foreach ($discounts as $discount) { ?>
			  <?php echo sprintf($text_discount, $discount['quantity'], $discount['price']); ?><br />
			  <?php } ?>
			</div>
			<?php } */ ?>
			
		<a id="button-cart" class="button cta"><span><?php echo $button_cart; ?> <i class="fa fa-shopping-cart"></i></span></a>
		
		<div id="product-moreinfo">
			<div class="table">
				<div>
					<i class="fa fa-truck"></i>
				</div>
				<div>
					Dostawa do: 11.12.2014<br/>
					Koszt dostawy: 12 €
				</div>
			</div>
		</div>
	<?/*
	<br/><span><?php echo $text_stock; ?></span> <?php // echo $stock; ?>  <span id="product_qty"><?php  echo $product_quantity; ?></span><br/>
    <span><?php if($delivery_time){  echo $delivery_time; }; ?></span>
	*/?>
	  </div>
	
	</div>
		  

	
		 <?/*
        <div><span>&nbsp;&nbsp;&nbsp;<?php echo $text_or; ?>&nbsp;&nbsp;&nbsp;</span></div>
        <div><a onclick="addToWishList('<?php echo $product_id; ?>');"><?php echo $button_wishlist; ?></a><br />
          <a onclick="addToCompare('<?php echo $product_id; ?>');"><?php echo $button_compare; ?></a></div>
		 */?>
		 
        <?php if ($minimum > 1) { ?>
        <div class="minimum"><?php echo $text_minimum; ?></div>
        <?php } ?>
      </div>
	  
	  <ul class="zalety">
	  	<li>Lorem ipsum dolor sit amet</li>
	  	<li>Lorem ipsum sit amet</li>
	  	<li>Lorem dolor sit amet</li>
	 	<li>Lorem ipsum dolor sit amet</li>
	  </ul>
	  

      <?php /* if ($review_status) { ?>
      <div class="review">
        <div><img src="catalog/view/theme/default/image/stars-<?php echo $rating; ?>.png" alt="<?php echo $reviews; ?>" />&nbsp;&nbsp;<a onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $reviews; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $text_write; ?></a></div>
        <div class="share"><!-- AddThis Button BEGIN -->
          <div class="addthis_default_style"><a class="addthis_button_compact"><?php echo $text_share; ?></a> <a class="addthis_button_email"></a><a class="addthis_button_print"></a> <a class="addthis_button_facebook"></a> <a class="addthis_button_twitter"></a></div>
          <script src="http://s7.addthis.com/js/250/addthis_widget.js"></script> 
          <!-- AddThis Button END --> 
        </div>
      </div>
      <?php } */ ?>
	  
	  <?/*
	<div>
		<div id="block3">
			<div class="table">
				<div><i class="fa fa-truck"></i></div>
				<div><?php echo $this->language->get('box_truck'); ?></div>
			</div>
			<div class="table">
				<div><i class="fa fa-clock-o"></i></div>
				<div><?php echo $this->language->get('box_time'); ?></div>
			</div>
			<div class="table">
				<div><i class="fa fa-phone"></i></div>
				<div><?php echo $this->language->get('box_contact'); ?></div>
			</div>
		</div>
	</div>
*/?>

   </div> 
</div>

<div id="product-navi">
	<div class="table">
		<div><a href="<?php echo $_SERVER["REQUEST_URI"]; ?>#beschrebung"><i class="fa fa-bicycle"></i> Beschrebung / Techn. Details</a></div>
		<div><a href="<?php echo $_SERVER["REQUEST_URI"]; ?>#size-chart"><i class="fa fa-list-ol"></i> Size Chart</a></div>
		<div><a href="<?php echo $_SERVER["REQUEST_URI"]; ?>#kundenmeinungen"><i class="fa fa-star-o"></i> Kundenmeinungen</a></div>
		<div><a href="<?php echo $_SERVER["REQUEST_URI"]; ?>#q-und-a"><i class="fa fa-question-circle"></i> Q&A </a></div>
	</div>
</div>

<div class="desc">
	<span class="anchor" id="beschrebung"></span>
	<div class="beschrebung">
		<div class="table">
			<div>
				<h2>Beschrebung</h2>
				<?php echo $description; ?>
			</div>
			<div>
				<h2>Techn. Details</h2>
				<table id="tech-det">
					<tr>
						<td>Frame</td>
						<td>Lorem</td>
					</tr>
					<tr>
						<td>Color</td>
						<td>Red</td>
					</tr>
					<tr>
						<td>Gears</td>
						<td>7</td>
					</tr>
					<tr>
						<td>Size</td>
						<td>L</td>
					</tr>
					<tr>
						<td>Tyres</td>
						<td>28</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	
	<span class="anchor" id="size-chart"></span>
	<div class="size-chart">
		<h2>Size Chart</h2>
		<div class="table">
			<div>
				<table>
					<thead>
						<tr>
							<td>Size</td>
							<td>CM</td>
							<td>Lorem</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>27</td>
							<td>180</td>
							<td>65</td>							
						</tr>
						<tr>
							<td>26</td>
							<td>170</td>
							<td>65</td>							
						</tr>
						<tr>
							<td>25</td>
							<td>160</td>
							<td>65</td>							
						</tr>
						<tr>
							<td>25</td>
							<td>150</td>
							<td>65</td>							
						</tr>
					</tbody>
				</table>
			</div>
			<div>
				<img src="./image/demo/przyklad.jpg" alt="" id="chartimg">
			</div>
		</div>
	</div>
	
<?/*	
	<div>
		<h3><?php echo $this->language->get('text_cennik'); ?></h3>
			<table data-role="table" class="attribute ui-responsive table-stripe" style="margin:0">
              <thead>
              <tr>			  
				<th class="autowidth"><?php echo $this->language->get('text_from'); ?></th>
				<?php foreach($product_quantity_discount as $discount){ ?>
					<th scope="col"><?php echo $discount['from']; ?></th>
				<?php } ?>				
              </tr>
              </thead>
              <tbody>
				  <?php for($i=1;$i<=$l_kolorow;$i++){ ?>															
					<tr>
						<th scope="row"><?php echo $this->language->get('text_print').' '.$i; ?> </th>
						<?php foreach($product_quantity_discount as $discount){ ?>
							<td><?php echo $discount['druk'][$i] ; ?></td>
						<?php }  ?>
					</tr>						 	
				 <?php } ?>
              </tbody>
          </table>            
	</div>
	
	<?/* POPRZEDNIA TABELA
	<div>
		<h3><?php echo $this->language->get('text_cennik'); ?></h3>
          <table class="attribute" style="margin:0">
              <thead>
              <tr>
                  <td class="autowidth"><?php echo $this->language->get('text_from'); ?></td>
                  <td><?php echo $this->language->get('text_percent'); ?></td>
                  <?php if($l_kolorow){ ?>
                    <?php for($i=1;$i<=$l_kolorow;$i++){ ?>
                      <td><?php echo $this->language->get('text_print').' '.$i; ?> </td>
                    <?php } ?>
                  <?php } ?>

              </tr>
              </thead>
              <tbody>
              <?php foreach($product_quantity_discount as $discount){ ?>
              <tr>
                  <td><?php echo $discount['from']; ?></td>
                  <td><?php echo $discount['percent']; ?></td>
				  <?php foreach($discount['druk'] as $druk){ ?>
                    <td><?php echo $druk; ?></td>
                  <?php } ?>
              </tr>
              <?php } ?>
              </tbody>
          </table>      
	</div>
	*/?>
	
  <?php if ($products) { ?>
  <div id="tab-related" class="tab-content">
    <div class="box-product">
      <?php foreach ($products as $product) { ?>
      <div>
        <?php if ($product['thumb']) { ?>
        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
        <?php } ?>
        <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
        <?php if ($product['rating']) { ?>
        <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
        <?php } ?>
        <a onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button"><span><?php echo $button_cart; ?></span></a></div>
      <?php } ?>
    </div>
  </div>
  <?php } ?>
  <?php if ($tags) { ?>
  <div class="tags"><b><?php echo $text_tags; ?></b>
    <?php foreach ($tags as $tag) { ?>
    <a href="<?php echo $tag['href']; ?>"><?php echo $tag['tag']; ?></a>,
    <?php } ?>
  </div>
  <?php } ?>
    
  <div class="descr autowidth">
  <?php if ($attribute_groups) { ?>
  <h2><?php echo $text_option; ?></h2>
  <div id="tab-attribute" class="tab-content">
    <table class="attribute">      
	<tbody>
	<tr><td><?php echo $this->language->get('text_waga'); ?></td><td><?php echo round($weight,2); ?></td></tr>
	<tr><td><?php echo $this->language->get('text_wymiary'); ?></td><td><?php echo round($length,2); ?> / <?php echo round($width,2); ?> / <?php echo round($height,2); ?></td></tr>
      <?php foreach ($attribute_groups as $attribute_group) { ?>	
        <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
        <tr>
          <td><?php echo $attribute['name']; ?></td>
          <td><?php echo $attribute['text']; ?></td>
        </tr>
        <?php } ?>     
      <?php } ?>
	   </tbody>
    </table>
  </div>
  <?php } ?>
  </div>
  </div>
  
  <div>	
  
  <?php if ($review_status) { ?>
  <div class="descl">
  <span class="anchor" id="kundenmeinungen"></span>
  <div class="tab-content">
  <h2><?php echo $entry_review; ?></h2>
    <div id="review"></div>
	<a href="javascript:void(0);" onclick="addreview();" class="button action" id="adrev"><?php echo $text_write; ?></a>
	<div id="add-review" style="display:none">
		<h2 id="review-title"><?php echo $text_write; ?></h2>
		<b><?php echo $entry_name; ?></b><br />
		<input type="text" name="name" value="" /><br />
		<br/>
        <b><?php echo $entry_email; ?></b><br />
        <input type="text" name="email" value="" />
		<br />
		<br />
		<b><?php echo $entry_review; ?></b>
		<textarea name="text" cols="40" rows="8" style="width: 98%;"></textarea>
		<span style="font-size: 11px;"><?php echo $text_note; ?></span><br />
		<br />	
		<div id="rating">	
		<b><?php echo $entry_rating; ?></b>
		<label for="ocena1"></label><input type="radio" name="rating" value="1" id="ocena1"/>
		&nbsp;
		<label for="ocena2"></label><input type="radio" name="rating" value="2" id="ocena2" />
		&nbsp;
		<label for="ocena3"></label><input type="radio" name="rating" value="3" id="ocena3"/>
		&nbsp;
		<label for="ocena4"></label><input type="radio" name="rating" value="4" id="ocena4"/>
		&nbsp;
		<label for="ocena5"></label><input type="radio" name="rating" value="5" id="ocena5"/>
		</div>
		<br /><br/>
		<?/*
		<br />
		<b><?php echo $entry_captcha; ?></b><br />
		<input type="text" name="captcha" value="" />
		<br />
		<img src="index.php?route=product/product/captcha" alt="" id="captcha" /><br />
		<br />	
		*/?>
		<div class="buttons">
		  <div class="right"><a id="button-review" onclick="review();" class="button action"><span><?php echo $this->language->get('text_add'); ?></span></a></div>
		</div>
	</div>
  </div>
  </div>
  <?php } ?>
  
  <span class="anchor" id="q-und-a"></span>
  <div class="q-und-a">
	<h2>Q&A </h2>
	<div id="qunda">
		<div>
			<div class="table">
				<div class="img"><span><i class="fa fa-bicycle"></i></span><br/><small class="verify"><i class="fa fa-check-square-o"></i> verify</small></div>
				<div>
					<h3>Mieszko Malawski <img src="catalog/view/theme/default/image/stars-4.png" alt="<?php echo $reviews; ?>" /></h3> 
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea, quibusdam, esse impedit maiores quaerat ad voluptatum praesentium dolor nostrum. Sapiente, minus praesentium veniam consectetur sit eius modi. Excepturi, impedit blanditiis? Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad, sunt, facilis, possimus tempora eveniet quas sint ipsam voluptatem facere qui laborum voluptatibus corporis sed consectetur tempore at cumque quae mollitia!</p>
				</div>
			</div>
			<div class="table">
				<div class="img"><span><i class="fa fa-bicycle"></i></span></div>
				<div>
					<h3>Mieszko Malawski</h3>
					<p>Beatae, saepe, nesciunt a porro labore deserunt vitae veritatis delectus at neque voluptatibus asperiores quo quidem numquam iure mollitia fugiat minima excepturi dolor aspernatur doloribus doloremque impedit reprehenderit expedita enim?</p>
				</div>
			</div>
			<div class="table">
				<div class="img"><span><i class="fa fa-bicycle"></i></span></div>
				<div>
					<h3>Mieszko Malawski</h3>
					<p>Vero, debitis cumque voluptates facilis obcaecati corporis veritatis at exercitationem eligendi hic!.</p>
				</div>
			</div>
			<div class="table">
				<div class="img"><span><i class="fa fa-bicycle"></i></span></div>
				<div>
					<h3>Mieszko Malawski</h3>
					<p>Earum, hic, fugit cum obcaecati facere corrupti sed molestias doloribus praesentium error omnis qui laboriosam consequatur nostrum eos ratione quam iste a est totam. Nihil quisquam adipisci quaerat deserunt consequuntur!</p>
				</div>
			</div>
			<div class="table">
				<div class="img"><span><i class="fa fa-bicycle"></i></span></div>
				<div>
					<h3>Mieszko Malawski</h3>
					<p>Corrupti, fuga, eligendi, itaque, unde delectus reprehenderit quisquam dolores. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil, iure, ullam, dolorum, mollitia distinctio dignissimos in nesciunt ad aut facilis illum quia praesentium aliquid consequatur aperiam veritatis corporis error animi.</p>
				</div>
			</div>
		</div>
	</div>
	<h3>Bework das Produkt</h3>
	<form action="" class="bework">
		<input type="text" placeholder="Titel lorem ipsum">
		<div id="rating">	
		<b><?php echo $entry_rating; ?></b>
		<label for="ocena1"></label><input type="radio" name="rating" value="1" id="ocena1"/>
		&nbsp;
		<label for="ocena2"></label><input type="radio" name="rating" value="2" id="ocena2" />
		&nbsp;
		<label for="ocena3"></label><input type="radio" name="rating" value="3" id="ocena3"/>
		&nbsp;
		<label for="ocena4"></label><input type="radio" name="rating" value="4" id="ocena4"/>
		&nbsp;
		<label for="ocena5"></label><input type="radio" name="rating" value="5" id="ocena5"/>
		</div>
		<textarea style="width:40%" rows="10" id="opin">Die lorem ipsum lorem details</textarea>
		<input type="submit" class="button action" value="Lorem ipsum submit">
	</form>
  </div>
  
    <?php echo $content_bottom; ?>
  </div>
  
<script>
function addreview() {

	$('#adrev').hide();
	$('#add-review').show();

}
$(document).ready(function(){

    // kolor
    var opts = $('#opt_1').find('option');

    if(opts.length < 3)
    {
       $(opts).last().attr('selected','selected'); 
    }

    // czy nadruk
    var opts = $('#opt_2').find('option');

    if(opts.length < 3)
    {
        $(opts).last().attr('selected','selected');
    }
	
	$(function() {
    $('#thumbs').desoSlide({
        main: {
            container: '#main',
            cssClass: 'img-responsive'
		},
        caption: false,
		controls: {
			keys: true,
			enable: true
		}
    });
	});
	
	setTimeout(function(){
		$('.desoSlide-wrapper').append('<div class="desoSlide-controls-wrapper"><a id="con-prev" href="javascript:void(0);"><span class="desoSlide-controls prev"></span></a><a id="con-next" href="javascript:void(0);"><span class="desoSlide-controls next"></span></a></div>');
	},1000);
	
	$("#ile").autocompleteArray(
		[
			"10","100"
			
		],
		{
			delay:10,
			minChars:0,
			matchSubset:1,
			autoFill:true,
			maxItemsToShow:10
		}
	);
	
	var timerek;

        $('#ile').keyup(function(){
				timerek = setTimeout(xxx,1000);
        });
		
		$('#ile').keydown(function(){
				clearTimeout(timerek);
        });
		
		$('#ile').change(
			function(){
				if($(this).val()!='1'){
					setTimeout(function(){
						$('.od').hide();
					},1000);
				}
			}
		);

        $('.select-element').change(
                function(){
                    var opt = $(this).find('option:selected').val();
                    var id = $(this).attr('id');


                    /* @todo pokazywać inne zdjęcia gdy wybrany jest kolor */

                    if($('#opt_img_'+opt).length > 0)
                    {

                        $('#opt_img_'+opt).click();
                    }

                    if(id == 'opt_1')
                    {
                        var model = $(this).find('option:selected').attr('model');
                       
                        $('.product-info .right h1:first small').html(model);
                    }

                    // kolor zmienia takze dostepnosc produktu ( ilosc )
                    if(id == 'opt_1')
                    {
                        var quantity = $(this).find('option:selected').attr('quantity');

                        $('#product_qty').html(quantity);
                    }

                    xxx();
                }
        );
		
		var kolory = $('#opt_2');
		kolory.parent().parent().hide();
		
		$('#opt_3').change(function() {
			var nadruk_id = $(this).children(":selected").attr("id");

            <?php if($show_color_count_option){ ?>
			if(nadruk_id=='opt_val_1'){ 
				kolory.parent().parent().show();
			} else {
				kolory.parent().parent().hide();
			}
            <?php }else{ ?>
                kolory.val(kolory.find('option:eq(2)').val());

            <?php } ?>
		});
			
		
		function xxx()
		{
			var product_id = $('input[name=\'product_id\']').val();
            var quantity = $('#ile').val();
            var nadruk = $('#opt_3').find('option:selected').val();


            var colors = $('#opt_2').find('option:selected').text();



            $.ajax({
                url: '<?php echo HTTP_SERVER; ?>index.php?route=product/product/getQuantityDiscount',
                type: 'post',
                dataType: 'text',
                data: {product_id: product_id, quantity : quantity, druk: nadruk, colors: colors},
                success: function(text){

                   
                    if(text != 'empty')
                    {
                        var res = text.split(':');

                       $('.product-info .price > span.price-normal').html(res[1]+' <?php echo $this->language->get('text_brutto'); ?> ');
                        $('.product-info .price > span.price-tax').html(res[0]+' <?php echo $text_tax; ?>');

                    }
                }

            })
		}
		
		$('#rating > label').mouseover(function(){			
			$('#rating > label').removeClass('wybrane');
			$(this).addClass('wybrane');
			$(this).prevAll().addClass('wybrane');
		});
		
		$('#rating > label').on('click',function(){			
			$('#rating > label').removeClass('wybrane');
			$(this).addClass('wybrane');
			$(this).prevAll().addClass('wybrane');
		});
		
		var textarea = 0;
		
		$('#opin').click(function(){
			if(textarea==0) {
				$(this).val('');
				textarea = 1;
			 }
		});
		
});

$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

	function review() {
	  $.ajax({
		type: 'POST',
		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
		beforeSend: function() {
		  $('#review_button').attr('disabled', 'disabled');
		  $('#review_title').after('<div class="wait"><img src="catalog/view/theme/default/image/loading_1.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
		  $('#review_button').attr('disabled', '');
		  $('.wait').remove();
		},
		success: function(data) {
		  if (data.error) {
			alert(data.error);
		  }

		  if (data.success) {
			alert(data.success);

			$('input[name=\'name\']').val('');
			$('textarea[name=\'text\']').val('');
			$('input[name=\'rating\']:checked').attr('checked', '');
			$('input[name=\'captcha\']').val('');
		  }
		}
	  });
	}
	
var distance = $('#product-navi').offset().top,
    $window = $(window);

$window.scroll(function() {
    if ( $window.scrollTop() >= distance +100 ) {
        $('#product-navi').addClass('magnet');
    } else {
		$('#product-navi').removeClass('magnet');
	}
});
	
</script>

<?php echo $footer; ?>