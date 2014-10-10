<?php echo $header; ?>

<div id="content">

  <div class="box">
    <div class="heading">
      <h1><img src="view/image/product.png" alt="" /> <?php echo $this->language->get('heading_title'); ?></h1>
      <div class="buttons"></div>
         
    </div>
    <div class="content">
		<form>
        <table class="list" style="table-layout:fixed;">
          <thead>
            <tr>
             

                <td class="left"><?php echo $this->language->get('column_name'); ?></td>
                <td class="left"><?php echo $this->language->get('column_sold'); ?></td>
              
            </tr>
          </thead>
          <tbody>
           


            <?php if ($products) { ?>
            <?php foreach ($products as $product) { ?>
            <tr>
              
                <td class="left" ><?php echo $product['name']; ?>
				<?php foreach ($product['option'] as $option) { ?>
                        <br />
                       
                        &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                        
                        <?php } ?>
						</td>
                <td class="left" ><?php echo $product['sold']; ?></td>
                
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="8"><?php echo $this->language->get('text_no_results'); ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      
    </div>
  </div>
</div>


<?php echo $footer; ?>