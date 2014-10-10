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
            <td><select name="category_discount_status">
                <?php if ($category_discount_status) { ?>
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
            <td><input type="text" name="category_discount_sort_order" value="<?php echo $category_discount_sort_order; ?>" size="1" /></td>
          </tr>
          <?php $category_row = 0; ?>
            <?php if(!empty($category_discount_discounts)){ ?>
          <?php foreach($category_discount_discounts as $discount){ ?>
            <tr class="hook">
                <td colspan="2">
                    <table>
                        <tr>
                            <td><?php echo $this->language->get('text_category'); ?></td>
                            <td><select name="category_discount_discounts[<?php echo $category_row; ?>][category_id]">
                                     <option ></option>
                                     <?php foreach($categories as $category){ ?>
                                            <option value="<?php echo $category['category_id']; ?>" <?php if($category['category_id'] == $discount['category_id']){ echo 'selected="selected"'; } ?> ><?php echo $category['name']; ?></option>
                                     <?php } ?>
                                </select>
                                </td>
                        </tr>
                        <tr>
                            <td><?php echo $this->language->get('text_discount_type'); ?></td>
                            <td>
                                <select name="category_discount_discounts[<?php echo $category_row; ?>][type]">
                                    <?php if ($discount['type'] == 'percent') { ?>
                                    <option value="percent" selected="selected"><?php echo $this->language->get('text_percent'); ?></option>
                                    <?php /* <option value="flat"><?php echo $this->language->get('text_flat'); ?></option> */ ?>
                                    <?php } else { ?>
                                    <option value="percent"><?php echo $this->language->get('text_percent'); ?></option>
                                    <?php /* <option value="flat" selected="selected"><?php echo $this->language->get('text_flat'); ?></option> */ ?>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo $this->language->get('text_value'); ?>
                            </td>
                            <td>
                                <input type="text" value="<?php echo $discount['value']; ?>" name="category_discount_discounts[<?php echo $category_row; ?>][value]" />
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" onclick="removeDiscount">
                                <div class="button"><?php echo $this->language->get('text_remove_discount'); ?>
                                <img src="view/image/delete.png" /></div>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
                <?php $category_row++; ?>
          <?php } ?>
            <?php } ?>
            <tfoot>

                            <tr style="height: 50px;">
                                <td style=" text-align: center;font-size: 20px;" colspan="2" onclick="addDiscount()" ><div style="padding: 10px;" class="button"><?php echo $this->language->get('text_add_discount'); ?></div><img src="view/image/add.png" /></td>
                            </tr>

            </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" >
    var category_row = '<?php echo $category_row; ?>';
    function removeDiscount(elem)
    {
          $(elem).parents('.hook').remove();
    }

    function addDiscount()
    {


        var html = '';

        html += '<tr class="hook">';
        html += '<td colspan="2">';
        html += '<table>';
        html += '<tr>';
        html += '<td><?php echo $this->language->get('text_category'); ?></td>';
        html += '<td><select name="category_discount_discounts['+category_row+'][category_id]">';
        html += '<option ></option>';
        <?php foreach($categories as $category){ ?>
        html += '<option value="<?php echo $category['category_id']; ?>"  ><?php echo $category['name']; ?></option>';
                <?php } ?>
        html += '</select>';
        html += '</td>';
        html += '</tr>';
        html += '<tr>';
        html += '<td><?php echo $this->language->get('text_discount_type'); ?></td>';
        html += '<td>';
        html += '<select name="category_discount_discounts['+category_row+'][type]">';

        html += '<option value="percent" ><?php echo $this->language->get('text_percent'); ?></option>';
       /* html += '<option value="flat"><?php echo $this->language->get('text_flat'); ?></option>'; */

        html += '</select>';
        html += '</td>';
        html += '</tr>';
        html += '<tr>';
        html += '<td>';
        html += '    <?php echo $this->language->get('text_value'); ?>';
        html += '</td>';
        html += '<td>';
        html += '<input type="text"  name="category_discount_discounts['+category_row+'][value]" />';
        html += '</td>';
        html += '</tr>';
        html += '<tr>';

        html += '<tr>';
        html += '<td colspan="2" onclick="removeDiscount">';
        html += '<div class="button" ><?php echo $this->language->get('text_remove_discount'); ?><img src="view/image/delete.png" /></div>';
        html += '</td>';
        html += '</tr>';
        html += '</table>';

        html += '</td>';
        html += '</tr>';

        $('tfoot').before(html);

        category_row++;

    }
</script>
<?php echo $footer; ?>