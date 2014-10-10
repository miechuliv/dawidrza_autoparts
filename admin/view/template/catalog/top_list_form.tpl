<?php echo $header; ?>
<div id="content">

  <div class="box">
    <div class="heading">

      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">

      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
         <div>
             <table class="form">
                    <tr>
                        <td><?php echo $this->language->get('column_sort_order'); ?></td>
                        <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" /></td>
                    </tr>
                 <tr>
                     <td><?php echo $this->language->get('column_limit'); ?></td>
                     <td><input type="text" name="limit" value="<?php echo $limit; ?>" /></td>
                 </tr>
                 <tr>
                     <td><?php echo $this->language->get('column_status'); ?></td>
                     <td>
                         <select name="active" >
                             <?php if($active){ ?>
                                <option value="1" selected="selected" ><?php echo $this->language->get('text_yes'); ?></option>
                             <option value="0"  ><?php echo $this->language->get('text_no'); ?></option>
                             <?php }else{ ?>
                             <option value="1" ><?php echo $this->language->get('text_yes'); ?></option>
                             <option value="0" selected="selected"  ><?php echo $this->language->get('text_no'); ?></option>
                             <?php } ?>

                         </select>
                     </td>
                 </tr>

                    <tr>
                        <td><?php echo $this->language->get('column_name'); ?></td>
                        <td>
                            <table>
                                <?php foreach($languages as $language){ ?>
                                    <tr>
                                        <td><?php echo $language['code']; ?></td>
                                        <td><input type="text" value="<?php if(isset($description[$language['language_id']])){ echo $description[$language['language_id']];  } ?>" name="description[<?php echo $language['language_id']; ?>][name]; ?>" /></td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $this->language->get('column_products'); ?></td>
                        <td>
                            <table id="p_tb">
                                <thead>
                                <tr>
                                    <td><?php echo $this->language->get('column_name'); ?></td>
                                    <td><?php echo $this->language->get('sort_order'); ?></td>
                                    <td>Akcja</td>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php $product_row = 0; ?>
                                    <?php foreach($products as $product){ ?>
                                    <tr>
                                        <td>
                                            <input type="hidden" value="<?php echo $product['product_id']; ?>" name="products[<?php echo $product_row; ?>][product_id]" />
                                            <?php echo $product['name']; ?><br/>

                                        </td>
                                        <td>
                                            <input type="text"  value="<?php echo $product['product_sort_order']; ?>" name="products[<?php echo $product_row; ?>][product_sort_order]" />
                                        </td>
                                        <td>
                                            <div onclick="$(this).parent().parent().remove();">
                                                Usuń
                                                <img src="view/image/delete.png" />
                                            </div>
                                        </td>
                                    </tr>

                                    <?php $product_row++; ?>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2">
                                            <input type="text" name="product_auto" />
                                        </td>
                                        <td>
                                            <div onclick="addTopListProduct();">
                                                Dodaj produkt
                                                <img src="view/image/add.png" />
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
             </table>
         </div>

      </form>
    </div>
  </div>
</div>

<script type="text/javascript" >
    var product_row = '<?php echo $product_row; ?>';
    $('input[name=\'product_auto\']').autocomplete({
        delay: 500,
        source: function(request, response) {
            $.ajax({
                url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
                dataType: 'json',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            label: item.name,
                            value: item.product_id
                        }
                    }));
                }
            });
        },
        select: function(event, ui) {

            var html = '';
            html += '<tr>';
            html += '<td>';
            html += '<input type="hidden" value="'+ui.item.value+'" name="products['+product_row+'][product_id]" />';
            html += '        '+ui.item.label+'<br/>';

            html += '        </td>';
            html += '        <td>';
            html += '<input type="text"   name="products['+product_row+'][product_sort_order]" />';
            html += '        </td>';
            html += '        <td>';
            html += '         <div onclick="$(this).parent().parent().remove();">';
            html += '         Usuń';
            html += '        <img src="view/image/delete.png" />';
            html += '        </div>';
            html += '        </td>';
            html += '</tr>';

            $('#p_tb tbody').append(html);
            product_row++;

            return false;
        },
        focus: function(event, ui) {
            return false;
        }
    });
</script>


<?php echo $footer; ?>