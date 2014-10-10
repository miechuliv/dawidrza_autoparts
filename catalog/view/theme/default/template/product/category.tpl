<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content" class="bord"><?php echo $content_top; ?>
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <div id="hook">

  
   <?php  if(!empty($filter_labels)){ ?>
      <div id="filtery">
          <?php  foreach($filter_labels as $label){ ?>
                <div class="kill-filter">
                    <input type="hidden" name="input_name" value="<?php echo $label['input_name']; ?>"  />
                     <small class="filter-name"><?php echo $label['name']; ?></small><small class="filter-value" style="margin-left: 5px" ><?php echo $label['value']; ?></small> <span>X</span>
                </div>
          <?php }  ?>
	</div>
  <?php  } ?>
  
 <?php /*  <h1 style="float:left; margin-bottom:0; font-size:25px;"><?php echo $heading_title; ?></h1>
  if ($thumb || $description) { ?>
  <div class="category-info">
    <?php if ($thumb) { ?>
    <div class="image"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" /></div>
    <?php } ?>
    <?php if ($description) { ?>
    <?php echo $description; ?>
    <?php } ?>
  </div>
  <?php } ?>
  <?php if ($categories) { ?>
  <h2><?php echo $text_refine; ?></h2>
  <div class="category-list">
    <?php if (count($categories) <= 5) { ?>
    <ul>
      <?php foreach ($categories as $category) { ?>
      <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
      <?php } ?>
    </ul>
    <?php } else { ?>
    <?php for ($i = 0; $i < count($categories);) { ?>
    <ul>
      <?php $j = $i + ceil(count($categories) / 4); ?>
      <?php for (; $i < $j; $i++) { ?>
      <?php if (isset($categories[$i])) { ?>
      <li><a href="<?php echo $categories[$i]['href']; ?>"><?php echo $categories[$i]['name']; ?></a></li>
      <?php } ?>
      <?php } ?>
      <?php } ?>
    </ul>
    <?php } ?>
    <?php } ?>
  </div>
  <?php } */ ?>
  <?php if ($products) { ?>  
  <?php /*
  <div class="product-filter" style="margin-bottom:10px; border:none; float:right; margin:5px 0; width:520px;">
  <!--  <div class="display"><b><?php echo $text_display; ?></b> <?php echo $text_list; ?> <b>/</b> <a onclick="display('grid');"><?php echo $text_grid; ?></a></div> -->
  <div class="limit"><b><?php echo $text_limit; ?></b>
      <select onchange="universalCallback(this.value,'limit')">
        <?php foreach ($limits as $limits) { ?>
        <?php if ($limits['value'] == $limit) { ?>
        <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div class="sort"><b><?php echo $text_sort; ?></b>
      <select onchange="universalCallback(this.value,'sort')">
        <?php foreach ($sorts as $sorts) { ?>
        <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
    <br/>
  </div> */?>
  <? /* <div class="product-compare"><a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a></div> */ ?>
  <div class="product-list">
    <?php foreach ($products as $product) { ?>
    <div class="listprod">
	
	<a href="<?php echo $product['href']; ?>">
		<div class="image">
			<?php if ($product['thumb']) { ?>
				<img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" />			
			<?php } else { ?>
				<img src="./image/no-image.jpg" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" />	
			<?php } ?>
		</div>
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
		
		        <?php if ($product['tax']) { ?>
        <?php // echo $text_tax; ?> <?php echo $product['tax']; ?>
        <?php } ?>
         
        <?php } else { ?>
       <span class="price-new"><?php echo $product['special']; ?> netto</span> <span class="price-old"><?php echo $product['price']; ?></span> 
        <?php } ?>

      </div>
      <?php } ?>

	  
    </div>
    <?php } ?>

  </div>
 <?php /* <div class="pagination"><?php echo $pagination; ?></div> */ ?>
  <?php }else{ ?>
	<div id="search-error" style="text-align:left;">
		404 <?php // echo $contact_link_text; ?><br/>
	</div>
  <?php } ?>
</div>


  <?php echo $content_bottom; ?>
  </div>
 

<?php echo $footer; ?>