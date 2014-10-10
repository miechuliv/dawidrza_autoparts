<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">	<a href="./index.php?route=common/home&token=<?php echo $this->session->data['token']; ?>">Strona główna</a> 	::	<a href="http://<?php echo $_SERVER['HTTP_HOST']; echo $_SERVER['REQUEST_URI']; ?>">Kampanie</a>	  </div>
<div class="box">
<div class="heading">
    <h1><span class="fa fa-pencil"></span> Kampania
        <?php if($release_status){ ?>
        <small>(<?php echo $release_status; ?>)</small>
        <?php } ?></h1>

    <div class="buttons"><a onclick="$('#form').submit();" class="button action"><?php echo $this->language->get('button_save'); ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $this->language->get('button_cancel'); ?></a></div>
</div>
<div class="content">
<div id="tabs" class="htabs"><a href="#tab-general"><?php echo $this->language->get('tab_general'); ?></a><a href="#tab-image"><?php echo $this->language->get('tab_image'); ?> <strong>(mockup)</strong></a><a href="#tab-products"><?php echo $this->language->get('tab_product'); ?></a>
    <a href="#tab-settings"><?php echo $this->language->get('tab_settings'); ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
<div id="tab-general">
    <div id="languages" class="htabs">
        <?php foreach ($languages as $language) { ?>
        <a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
        <?php } ?>
    </div>
    <?php foreach ($languages as $language) { ?>
    <div id="language<?php echo $language['language_id']; ?>">
        <div class="error" ><?php echo $error_name; ?></div>
        <table class="form">
            <tr>
                <td><span class="required">*</span> <?php echo $this->language->get('entry_name'); ?></td>
                <td><input type="text" class="product-name" name="campaign_description[<?php echo $language['language_id']; ?>][name]" size="100" value="<?php echo isset($campaign_description[$language['language_id']]) ? $campaign_description[$language['language_id']]['name'] : ''; ?>" />
                    <?php if (isset($error_name[$language['language_id']])) { ?>
                    <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
                    <?php } ?></td>
            </tr>
            <tr >
                <td><?php echo $this->language->get('entry_meta_description'); ?></td>
                <td><textarea name="campaign_description[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="5"><?php echo isset($campaign_description[$language['language_id']]) ? $campaign_description[$language['language_id']]['meta_description'] : ''; ?></textarea></td>
            </tr>
            <tr >
                <td><?php echo $this->language->get('entry_meta_keyword'); ?></td>
                <td><textarea name="campaign_description[<?php echo $language['language_id']; ?>][meta_keyword]" cols="40" rows="5"><?php echo isset($campaign_description[$language['language_id']]) ? $campaign_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea></td>
            </tr>
            <tr >
                <td><?php echo $this->language->get('entry_description'); ?></td>
                <td><textarea name="campaign_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($campaign_description[$language['language_id']]) ? $campaign_description[$language['language_id']]['description'] : ''; ?></textarea></td>
            </tr>
            <tr>
                <td><?php echo $this->language->get('entry_tag'); ?></td>
                <td><input type="text" name="campaign_description[<?php echo $language['language_id']; ?>][tag]" value="<?php echo isset($campaign_description[$language['language_id']]) ? $campaign_description[$language['language_id']]['tag'] : ''; ?>" size="80" /></td>
            </tr>
        </table>
    </div>
    <?php } ?>
</div>

    <div id="tab-image" style="display: none;">
        <table id="images" class="list">
            <thead>
            <tr>
                <td class="left"><?php echo $this->language->get('entry_image'); ?></td>
                <td class="right"><?php echo $this->language->get('entry_sort_order'); ?></td>
                <td></td>
            </tr>
            </thead>
            <?php $image_row = 0; ?>
            <?php foreach ($campaign_images as $campaign_image) { ?>
            <tbody id="image-row<?php echo $image_row; ?>">
            <tr>
                <td class="left"><div class="image"><img src="<?php echo $campaign_image['thumb']; ?>" alt="" id="thumb<?php echo $image_row; ?>" />
                        <input type="hidden" name="campaign_image[<?php echo $image_row; ?>][image]" value="<?php echo $campaign_image['image']; ?>" id="image<?php echo $image_row; ?>" />
                        <br />
                        <a onclick="image_upload('image<?php echo $image_row; ?>', 'thumb<?php echo $image_row; ?>');"><?php echo $this->language->get('text_browse'); ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $image_row; ?>').attr('value', '');"><?php echo $this->language->get('text_clear'); ?></a></div></td>
                <td class="right"><input type="text" name="campaign_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $campaign_image['sort_order']; ?>" size="2" /></td>
                <td class="left"><a onclick="$('#image-row<?php echo $image_row; ?>').remove();" class="button"><?php echo $this->language->get('button_remove'); ?></a></td>
            </tr>
            </tbody>
            <?php $image_row++; ?>
            <?php } ?>
            <tfoot>
            <tr>
                <td colspan="2"></td>
                <td class="left"><a onclick="addImage();" class="button"><?php echo $this->language->get('button_add_image'); ?></a></td>
            </tr>
            </tfoot>
        </table>
    </div>


    <div id="tab-products" style="display: none;">
        <div class="error" ><?php echo $error_products; ?></div>
        <table id="products" class="list">
            <thead>
            <tr>
                <td class="left"><?php echo $this->language->get('entry_product_template'); ?></td>

                <td></td>
            </tr>
            </thead>
            <?php $product_row = 0; ?>
            <?php foreach ($campaign_products as $id => $campaign_product) { ?>

            <tbody id="product-row<?php echo $product_row; ?>" class="haka">
            <tr>
                <td class="left" >
                    <input type="hidden" class="idek" name="campaign_products[<?php echo $product_row; ?>][product_id]" value="<?php echo $campaign_product['product_id']; ?>" />
                    <input type="hidden" name="campaign_products[<?php echo $product_row; ?>][parent_product]" value="<?php echo $campaign_product['parent_product']; ?>" />
                    <?php echo $product_templates[$campaign_product['parent_product']]['name']; ?>
                </td>
                <td class="left" >
                    <a class="button" target="_blank" href="<?php echo $campaign_product['edit']; ?>" ><?php echo $this->language->get('button_product_edit'); ?></a>
                  <?php /*  <a class="button" target="_blank" href="<?php echo $campaign_product['show']; ?>" ><?php echo $this->language->get('button_product_show'); ?></a><br/> */ ?>
                    <a class="button" onclick="productDelete(this);return false;" ><?php echo $this->language->get('button_product_delete'); ?></a>
                </td>
            </tr>
            </tbody>
            <?php $product_row++; ?>
            <?php } ?>
            <tfoot>
            <tr>
               <td class="left" ><select id="parent_id" name="parent_id" >
                   <?php foreach($product_templates as $template){ ?>
                         <option value="<?php echo $template["product_id"]; ?>" ><?php echo $template["name"]; ?></option>';
                   <?php } ?>
                </select></td>
                <td class="left"><a onclick="addProduct(this);" class="button action"><?php echo $this->language->get('button_add_product'); ?></a></td>
            </tr>
            </tfoot>
        </table>
    </div>
    <div id="tab-settings">
        <table id="settings" class="list">
            <tr>
                <td style="padding:20px 10px;">
                    <?php echo $this->language->get('text_show_archiwe'); ?>
                </td>
                <td style="padding:0 10px;">
                    <select name="show_archiwe" >
                         <?php if($show_archiwe){ ?>
                                <option value="1" selected="selected" ><?php echo $this->language->get('text_yes'); ?></option>
                                <option value="0"  ><?php echo $this->language->get('text_no'); ?></option>
                         <?php }else{ ?>
                        <option value="1"  ><?php echo $this->language->get('text_yes'); ?></option>
                        <option value="0" selected="selected"  ><?php echo $this->language->get('text_no'); ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td style="padding:20px 10px;">
                    <?php echo $this->language->get('text_date_start'); ?>
                </td>
               <td style="padding:0 10px;">
                    <input class="datetime"  type="text" name="date_start" value="<?php echo $date_start; ?>" />
                    <div class="error" ><?php echo $error_date_start; ?></div>
                </td>
            </tr>

            <tr style="display: none;">
                <td>

                </td>
                <td>
                    <input  type="text" name="project_id" value="<?php echo $project_id; ?>" />
                </td>
            </tr>

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



    function image_upload(field, thumb) {



        $('#dialog').remove();

        $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

        $('#dialog').dialog({
            title: '<?php echo $this->language->get("text_image_manager"); ?>',
            close: function (event, ui) {
                if ($('#' + field).attr('value')) {
                    $.ajax({
                        url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
                        dataType: 'text',
                        success: function(text) {
                            $('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
                        }
                    });
                }
            },
            bgiframe: false,
            width: 1000,
            height: 800,
            resizable: false,
            modal: false
        });
    };
    //--></script>
<script type="text/javascript" >
    var image_row = <?php echo $image_row; ?>;

    function addImage() {

        html = '<tbody id="image-row' + image_row + '">';
        html += '  <tr>';
        html += '    <td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + image_row + '" /><input type="hidden" name="campaign_image[' + image_row + '][image]" value="" id="image' + image_row + '" /><br /><a onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\');"><?php echo $this->language->get("text_browse"); ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $this->language->get("text_clear"); ?></a></div></td>';
        html += '    <td class="right"><input type="text" name="campaign_image[' + image_row + '][sort_order]" value="" size="2" /></td>';
        html += '    <td class="left"><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="button"><?php echo $this->language->get("button_remove"); ?></a></td>';
        html += '  </tr>';
        html += '</tbody>';

        $('#images tfoot').before(html);

        image_row++;
    }
</script>
<script type="text/javascript"><!--




    var product_row = <?php echo $product_row; ?>;
    var product_id = false;
    var product_edit = false;
    var product_show = false;
    var  parent = false;



    function addProduct(elem) {


        // najpierw stowrzyc pusty produkt ajaxem

        var parent_id = $('#parent_id option:selected').val();

        $.ajax({
            type: 'post',
            dataType: 'json',
            data: {
               parent_id: parent_id
            },
            url: 'index.php?route=project/campaign/addProduct&token=<?php echo $this->session->data["token"]; ?>',
            success: function(json)
            {

                product_id = json['product_id'];
                parent = json['parent'];
                product_edit = json['product_edit'];
                product_show = json['product_show'];


                //html += '<input type="hidden" name="campaign_products['+product_row+'][parent_id]" value="" />';
                html = '<tbody class="haka" id="product-row' + product_row + '">';
                html += '  <tr>';
                html += '<td class="left">'+parent;
                html += '<input type="hidden" name="campaign_products['+product_row+'][product_id]" value="'+product_id+'" />';
                html += '<input type="hidden" name="campaign_products['+product_row+'][parent_product]" value="'+parent_id+'" />';
                html += '</td>';
                html +=    '<td class="left"><a class="button" target="_blank" href="'+product_edit+'" ><?php echo $this->language->get("button_product_edit"); ?></a>';
               // html +=    '<a class="button" target="_blank" href="'+product_show+'" ><?php echo $this->language->get("button_product_show"); ?></a><br/>';
                html +=    '<a class="button" onclick="productDelete(this);return false;" ><?php echo $this->language->get("button_product_delete"); ?></a></td>';
                html += '  </tr>';
                html += '</tbody>';

                console.log(html);


                $('#products tfoot').before(html);

                product_row++;
            }

        });




    }

    function productDelete(elem)
    {


        var product_id = $(elem).parents('tbody').find('.idek').val();

        $.ajax({
            type: 'post',
            dataType: 'text',
            data: {
                product_id: product_id
            },
            url: 'index.php?route=project/campaign/deleteProduct&token=<?php echo $this->session->data["token"]; ?>',
            success: function(text)
            {

                product_row--;
            }

        });

        $(elem).parents('.haka').remove();
        return false;
    }


    //--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
    $('.date').datepicker({dateFormat: 'yy-mm-dd'});
    $('.datetime').datetimepicker({
        dateFormat: 'yy-mm-dd',
        timeFormat: 'h:m'
    });
    $('.time').timepicker({timeFormat: 'h:m'});

    $('#tabs a').tabs();
    $('#languages a').tabs();
    //--></script>
<?php
if(isset($this->session->data['error'])){ unset($this->session->data['error']); }
if(isset($this->session->data['success'])){ unset($this->session->data['success']); }


 ?>
<?php echo $footer; ?>