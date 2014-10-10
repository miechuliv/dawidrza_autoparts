<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/total.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">

          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="quantity_discount_status">
                <?php if ($quantity_discount_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="quantity_discount_sort_order" value="<?php echo $quantity_discount_sort_order; ?>" size="1" /></td>
          </tr>

        </table>
          <table id="tb">
              <thead>
              <tr>
                  <td><?php echo $this->language->get('column_from'); ?></td>

                  <td><?php echo $this->language->get('column_to');  ?></td>

                  <td><?php echo $this->language->get('text_percent');  ?></td>

                  <td><?php echo $this->language->get('column_action');  ?></td>
              </tr>
              </thead>
              <tbody>
              <?php $product_quantity_discount_row = 0; ?>
              <?php if($quantity_discount_values){ ?>
              <?php foreach($quantity_discount_values as $discount){ ?>
              <tr>
                  <td><input type="text" name="quantity_discount_values[<?php echo $product_quantity_discount_row; ?>][from]" value="<?php echo $discount['from'] ?>" /></td>
                  <td><input type="text" name="quantity_discount_values[<?php echo $product_quantity_discount_row; ?>][to]" value="<?php echo $discount['to'] ?>" /></td>
                  <td><input type="text" name="quantity_discount_values[<?php echo $product_quantity_discount_row; ?>][percent]" value="<?php echo $discount['percent'] ?>" /></td>
                  <td><div onclick="removeProductQuantitydiscount(this)"><img src="view/image/delete.png" /><?php echo $this->language->get('text_remove'); ?></div></td>
              </tr>
              <?php $product_quantity_discount_row++; ?>
              <?php } ?>

              <?php } ?>

              </tbody>
              <tfoot>

              <tr>
                  <td><input type="text" name="pd_from" /></td>
                  <td><input type="text" name="pd_to" /></td>
                  <td><input type="text" name="pd_percent" /></td>
                  <td><div onclick="addProductQuantitydiscount(this)"><img src="view/image/add.png" /><?php echo $this->language->get('text_add'); ?></div></td>
              </tr>
              </tfoot>
              <script type="text/javascript">
                  var product_quantity_discount_row = '<?php echo $product_quantity_discount_row; ?>';
                  function addProductQuantitydiscount(elem)
                  {
                      var from = $(elem).parent().parent().find('input[name=\'pd_from\']');
                      var to = $(elem).parent().parent().find('input[name=\'pd_to\']');
                      var percent = $(elem).parent().parent().find('input[name=\'pd_percent\']');

                      var html = '';
                      html += '<tr>';
                      html += '<td><input type="text" name="quantity_discount_values['+product_quantity_discount_row+'][from]" value="'+from.val()+'" /></td>';
                      html += '<td><input type="text" name="quantity_discount_values['+product_quantity_discount_row+'][to]" value="'+to.val()+'" /></td>';
                      html += '<td><input type="text" name="quantity_discount_values['+product_quantity_discount_row+'][percent]" value="'+percent.val()+'" /></td>';
                      html += '<td><div onclick="removeProductQuantitydiscount(this)"><img src="view/image/delete.png" /><?php echo $this->language->get('text_remove'); ?></div></td>';
                      html += '</tr>';

                      from.val('');
                      to.val('');
                      percent.val('');
                      $('#tb tbody').append(html);
                      product_quantity_discount_row++;
                  }

                  function removeProductQuantitydiscount(elem)
                  {
                      $(elem).parents('tr').remove();
                  }

              </script>
          </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>