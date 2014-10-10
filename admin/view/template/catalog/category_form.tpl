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
      <h1><img src="view/image/category.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs"><a href="#tab-general">
              <?php echo $tab_general; ?></a>
          <a href="#tab-data"><?php echo $tab_data; ?>
          </a><a href="#tab-design"><?php echo $tab_design; ?></a>
          <a href="#tab-quantity-discount"><?php echo $this->language->get('text_quantity_discount'); ?></a>


          <a href="#tab-extended-filter"><?php echo $this->language->get('tab_extended_filter'); ?></a>
      </div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
          <div id="languages" class="htabs">
            <?php foreach ($languages as $language) { ?>
            <a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
            <?php } ?>
          </div>
          <?php foreach ($languages as $language) { ?>
          <div id="language<?php echo $language['language_id']; ?>">
            <table class="form">
              <tr>
                <td><span class="required">*</span> <?php echo $entry_name; ?></td>
                <td><input type="text" name="category_description[<?php echo $language['language_id']; ?>][name]" size="100" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['name'] : ''; ?>" />
                  <?php if (isset($error_name[$language['language_id']])) { ?>
                  <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><?php echo $entry_meta_description; ?></td>
                <td><textarea name="category_description[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="5"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_description'] : ''; ?></textarea></td>
              </tr>
              <tr>
                <td><?php echo $entry_meta_keyword; ?></td>
                <td><textarea name="category_description[<?php echo $language['language_id']; ?>][meta_keyword]" cols="40" rows="5"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea></td>
              </tr>
              <tr>
                <td><?php echo $entry_description; ?></td>
                <td><textarea name="category_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['description'] : ''; ?></textarea></td>
              </tr>
            </table>
          </div>
          <?php } ?>
        </div>
        <div id="tab-data">
          <table class="form">
            <tr>
              <td><?php echo $entry_parent; ?></td>
              <td><input type="text" name="path" value="<?php echo $path; ?>" size="100" />
                <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_filter; ?></td>
              <td><input type="text" name="filter" value="" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><div id="category-filter" class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($category_filters as $category_filter) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="category-filter<?php echo $category_filter['filter_id']; ?>" class="<?php echo $class; ?>"><?php echo $category_filter['name']; ?><img src="view/image/delete.png" alt="" />
                    <input type="hidden" name="category_filter[]" value="<?php echo $category_filter['filter_id']; ?>" />
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
            <tr>
              <td><?php echo $entry_store; ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'even'; ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array(0, $category_store)) { ?>
                    <input type="checkbox" name="category_store[]" value="0" checked="checked" />
                    <?php echo $text_default; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="category_store[]" value="0" />
                    <?php echo $text_default; ?>
                    <?php } ?>
                  </div>
                  <?php foreach ($stores as $store) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($store['store_id'], $category_store)) { ?>
                    <input type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                    <?php echo $store['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" />
                    <?php echo $store['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
              <tr>
                  <td><?php echo $entry_keyword; ?></td>

                  <td>
                      <table>


                          <?php foreach($languages as $key => $language){ ?>

                          <tr><td><?php echo $key; ?>: <input style="width:600px;" type="text" name="keyword[<?php echo $key; ?>]" value="<?php if(isset($keyword[$key])){ echo  $keyword[$key];  } ?>" /></td></tr>
                          <?php } ?>

                      </table>
                  </td>
              </tr>
            <tr>
              <td><?php echo $entry_image; ?></td>
              <td valign="top"><div class="image"><img src="<?php echo $thumb; ?>" alt="" id="thumb" />
                  <input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
                  <br />
                  <a onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
            </tr>
            <tr>
              <td><?php echo $entry_top; ?></td>
              <td><?php if ($top) { ?>
                <input type="checkbox" name="top" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="top" value="1" />
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_column; ?></td>
              <td><input type="text" name="column" value="<?php echo $column; ?>" size="1" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_sort_order; ?></td>
              <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="status">
                  <?php if ($status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
              <tr>
                  <td>Kategoria wirtualna?</td>
                  <td><select name="virtual">
                          <?php if ($virtual) { ?>
                          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                          <option value="0"><?php echo $text_disabled; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_enabled; ?></option>
                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                          <?php } ?>
                      </select></td>
              </tr>
          </table>
        </div>
        <div id="tab-quantity-discount" style="display: none;">
            <table>
                <thead>
                <tr>
                    <td><?php echo $this->language->get('column_from'); ?></td>

                    <td><?php echo $this->language->get('column_to');  ?></td>

                    <td><?php echo $this->language->get('text_percent');  ?></td>

                    <td><?php echo $this->language->get('column_action');  ?></td>
                </tr>
                </thead>
                <tbody>
                <?php $category_quantity_discount_row = 0; ?>
                <?php if($category_quantity_discount){ ?>
                <?php foreach($category_quantity_discount as $discount){ ?>
                <tr class="remuwator">
                    <td><input type="text" name="category_quantity_discount[<?php echo $category_quantity_discount_row; ?>][from]" value="<?php echo $discount['from'] ?>" /></td>
                    <td><input type="text" name="category_quantity_discount[<?php echo $category_quantity_discount_row; ?>][to]" value="<?php echo $discount['to'] ?>" /></td>
                    <td><input type="text" name="category_quantity_discount[<?php echo $category_quantity_discount_row; ?>][percent]" value="<?php echo $discount['percent'] ?>" /></td>
                    <td><div onclick="removeCategoryQuantitydiscount(this)"><img src="view/image/delete.png" /><?php echo $this->language->get('text_remove'); ?></div></td>
                </tr>
                <?php $category_quantity_discount_row++; ?>
                <?php } ?>

                <?php } ?>

                </tbody>
                <tfoot>

                <tr>
                    <td><input type="text" name="pd_from" /></td>
                    <td><input type="text" name="pd_to" /></td>
                    <td><input type="text" name="pd_percent" /></td>
                    <td><div onclick="addCategoryQuantitydiscount(this)"><img src="view/image/add.png" /><?php echo $this->language->get('text_add'); ?></div></td>
                </tr>
                </tfoot>
                <script type="text/javascript">
                    var category_quantity_discount_row = '<?php echo $category_quantity_discount_row; ?>';
                    function addCategoryQuantitydiscount(elem)
                    {
                        var from = $(elem).parent().parent().find('input[name=\'pd_from\']');
                        var to = $(elem).parent().parent().find('input[name=\'pd_to\']');
                        var percent = $(elem).parent().parent().find('input[name=\'pd_percent\']');

                        var html = '';
                        html += '<tr class="remuwator" >';
                        html += '<td><input type="text" name="category_quantity_discount['+category_quantity_discount_row+'][from]" value="'+from.val()+'" /></td>';
                        html += '<td><input type="text" name="category_quantity_discount['+category_quantity_discount_row+'][to]" value="'+to.val()+'" /></td>';
                        html += '<td><input type="text" name="category_quantity_discount['+category_quantity_discount_row+'][percent]" value="'+percent.val()+'" /></td>';
                        html += '<td><div onclick="removeCategoryQuantitydiscount(this)"><img src="view/image/delete.png" /><?php echo $this->language->get('text_remove'); ?></div></td>';
                        html += '</tr>';

                        from.val('');
                        to.val('');
                        percent.val('');
                        $('#tab-quantity-discount tbody').append(html);
                        category_quantity_discount_row++;
                    }

                    function removeCategoryQuantitydiscount(elem)
                    {
                        $(elem).parents('.remuwator').remove();
                    }

                </script>
            </table>
        </div>
        <div id="tab-design">
          <table class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $entry_store; ?></td>
                <td class="left"><?php echo $entry_layout; ?></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="left"><?php echo $text_default; ?></td>
                <td class="left"><select name="category_layout[0][layout_id]">
                    <option value=""></option>
                    <?php foreach ($layouts as $layout) { ?>
                    <?php if (isset($category_layout[0]) && $category_layout[0] == $layout['layout_id']) { ?>
                    <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                        <?php } elseif($layout['layout_id'] == 3) { ?>
                        <option value="<?php echo $layout['layout_id']; ?>" selected="selected" ><?php echo $layout['name']; ?></option>

                    <?php } else { ?>
                    <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
            </tbody>
            <?php foreach ($stores as $store) { ?>
            <tbody>
              <tr>
                <td class="left"><?php echo $store['name']; ?></td>
                <td class="left"><select name="category_layout[<?php echo $store['store_id']; ?>][layout_id]">
                    <option value=""></option>
                    <?php foreach ($layouts as $layout) { ?>
                    <?php if (isset($category_layout[$store['store_id']]) && $category_layout[$store['store_id']] == $layout['layout_id']) { ?>
                    <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                        <?php } elseif($layout['layout_id'] == 3) { ?>
                        <option value="<?php echo $layout['layout_id']; ?>" selected="selected" ><?php echo $layout['name']; ?></option>

                        <?php } else { ?>
                    <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
            </tbody>
            <?php } ?>
          </table>
        </div>
        <div id="tab-extended-filter">
            <table id="ext_tb" class="list">
                <thead>
                    <tr>
                        <td><?php echo $this->language->get('text_filter_type'); ?></td>
                        <td><?php echo $this->language->get('text_filter_name'); ?></td>
                        <td><?php echo $this->language->get('text_sort_order'); ?></td>
                        <td><?php echo $this->language->get('text_attribute_or_option_id'); ?></td>
                        <td><?php echo $this->language->get('text_active'); ?></td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                <?php $extended_filter_row = 0; ?>
                        <?php foreach($category_extended_filter as $filter){ ?>
                        <tr>
                            <td><input type="hidden" name="category_extended_filter[<?php echo $extended_filter_row; ?>][type]" value="<?php echo $filter['type']; ?>" /><?php echo $filter['type']; ?></td>
                            <td>
                                <?php foreach($languages as $language){ ?>
                                <input type="text" name="category_extended_filter[<?php echo $extended_filter_row; ?>][description][<?php echo $language['language_id']; ?>][name]" value="<?php echo $filter['description'][$language['language_id']]['name']; ?>" />
                                <?php } ?>
                            </td>
                            <td><input type="text" name="category_extended_filter[<?php echo $extended_filter_row; ?>][sort_order]" value="<?php echo $filter['sort_order']; ?>" /></td>

                            <td>
                                <?php if($filter['type'] == 'option'){ ?>
                                        <select name="category_extended_filter[<?php echo $extended_filter_row; ?>][attribute_or_option_id]" >
                                                <option></option>
                                                <?php foreach($options as $option){ ?>
                                                    <option <?php if($option['option_id'] == $filter['attribute_or_option_id']){  echo 'selected="selected"';  }  ?> value="<?php echo $option['option_id']; ?>" ><?php echo $option['name']; ?></option>
                                                <?php } ?>
                                        </select>
                                <?php }elseif($filter['type'] == 'attribute'){ ?>
                                        <select name="category_extended_filter[<?php echo $extended_filter_row; ?>][attribute_or_option_id]" >
                                            <option></option>
                                            <?php foreach($attributes as $attribute){ ?>
                                            <option <?php if($attribute['attribute_id'] == $filter['attribute_or_option_id']){  echo 'selected="selected"';  }  ?> value="<?php echo $attribute['attribute_id']; ?>" ><?php echo $attribute['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                <?php }else{ ?>
                                        <input type="hidden" name="category_extended_filter[<?php echo $extended_filter_row; ?>][attribute_or_option_id]" value="" /><?php echo $this->language->get('text_na'); ?>
                                <?php } ?>
                            </td>
                            <td>
                                <select name="category_extended_filter[<?php echo $extended_filter_row; ?>][active]">
                                    <?php if ($filter['active']) { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>
                                <div onclick="$(this).parent().parent().remove();">
                                    <img src="view/image/delete.png" /> <?php echo $this->language->get('text_remove'); ?>
                                </div>
                            </td>
                        </tr>
                <?php $extended_filter_row++; ?>
                        <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">
                            <select name="filter_type" >
                                <option ></option>
                                <?php foreach($extended_filter_types as $type){ ?>
                                        <option value="<?php echo $type; ?>" ><?php echo $type; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td colspan="3">
                            <div onclick="addExtendedFilter(this)">
                                <img src="view/image/add.png" /> <?php echo $this->language->get('text_add'); ?>
                            </div>
                        </td>
                    </tr>
                </tfoot>
                <script type="text/javascript" >
                    var extended_filter_row = '<?php echo $extended_filter_row; ?>';
                    function addExtendedFilter(elem)
                    {
                        var type = $(elem).parent().parent().find('option:selected').val();

                        var  html = '';

                    html +='<tr>';
                        html +='<td><input type="hidden" name="category_extended_filter['+extended_filter_row+'][type]" value="'+type+'" />'+type+'</td>';
                        html +='<td>';
                    <?php foreach($languages as $language){ ?>
                        html +='<input type="text" name="category_extended_filter['+extended_filter_row+'][description][<?php echo $language['language_id']; ?>][name]" value="" />';
                    <?php } ?>
                        html +='</td>';
                        html +='<td><input type="text" name="category_extended_filter['+extended_filter_row+'][sort_order]" value="0" /></td>';

                        html +='<td>';
                        if(type=='option'){
                        html +='<select name="category_extended_filter['+extended_filter_row+'][attribute_or_option_id]" >';
                        html +='<option></option>';
                                <?php foreach($options as $option){ ?>
                        html +='<option  value="<?php echo $option['option_id']; ?>" ><?php echo $option['name']; ?></option>';
                                    <?php } ?>
                        html +='</select>';
                        }
                        if(type=='attribute'){
                            html +='<select name="category_extended_filter['+extended_filter_row+'][attribute_or_option_id]" >';
                        html +='<option></option>';
                                <?php foreach($attributes as $attribute){ ?>
                        html +='<option  value="<?php echo $attribute['attribute_id']; ?>" ><?php echo $attribute['name']; ?></option>';
                                    <?php } ?>
                        html +='</select>';
                        }
                        if(type=='price'){
                        html +='<input type="hidden" name="category_extended_filter['+extended_filter_row+'][attribute_or_option_id]" value="" /><?php echo $this->language->get('text_na'); ?>';
                        }
                        html +='</td>';
                        html +='<td>';
                        html +='<select name="category_extended_filter['+extended_filter_row+'][active]">';

                        html +='<option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
                        html +='<option value="0"><?php echo $text_disabled; ?></option>';

                        html +='</select>';
                        html +='</td>';
                        html +='<td>';
                        html +='<div onclick="$(this).parent().parent().remove();">';
                        html +='<img src="view/image/delete.png" />Usuń';
                        html +='</div>';
                        html +='</td>';
                        html +='</tr>';


                        extended_filter_row++;
                        $('#ext_tb tbody').append(html);
                    }
                </script>
            </table>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>
//--></script> 
<script type="text/javascript"><!--
$('input[name=\'path\']').autocomplete({
	delay: 500,
	source: function(request, response) {		
		$.ajax({
			url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				json.unshift({
					'category_id':  0,
					'name':  '<?php echo $text_none; ?>'
				});
				
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.category_id
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'path\']').val(ui.item.label);
		$('input[name=\'parent_id\']').val(ui.item.value);
		
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});
//--></script> 
<script type="text/javascript"><!--
// Filter
$('input[name=\'filter\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/filter/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.filter_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('#category-filter' + ui.item.value).remove();
		
		$('#category-filter').append('<div id="category-filter' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="category_filter[]" value="' + ui.item.value + '" /></div>');

		$('#category-filter div:odd').attr('class', 'odd');
		$('#category-filter div:even').attr('class', 'even');
				
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});

$('#category-filter div img').live('click', function() {
	$(this).parent().remove();
	
	$('#category-filter div:odd').attr('class', 'odd');
	$('#category-filter div:even').attr('class', 'even');	
});
//--></script> 
<script type="text/javascript"><!--
function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script> 
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
$('#languages a').tabs();
//--></script> 
<?php echo $footer; ?>