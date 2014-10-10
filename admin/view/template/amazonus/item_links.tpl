<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>

    <div class="box">
        <div class="heading">
            <h1><?php echo $lang_title; ?></h1>
            <div class="buttons">
                <a class="button" onclick="location = '<?php echo $link_overview; ?>';" ><span><?php echo $lang_btn_return; ?></span></a>
            </div>
        </div>

        <div class="content">
            <table class="form">
                <h2><?php echo $lang_link_items; ?></h2>
                <tbody>
                    <p><?php echo $lang_desc1; ?></p>
                    <p><?php echo $lang_desc2; ?></p>
                    <a class="button" onclick="loadUnlinked(this)" ><span><?php echo $lang_load_btn; ?></span></a>
                </tbody>
            </table>

            <table align="left" class="list" id="linkListTable">
                <thead id="tableThread1">
                    <tr>
                        <td class="center" colspan="3"><?php echo $lang_new_link; ?></td>
                    </tr>
                </thead>
                <thead id="tableThread2">
                    <tr>
                        <td class="right" width="45%"><?php echo $lang_autocomplete_product; ?></td>
                        <td class="left" width="45%"><?php echo $lang_amazonus_sku; ?></td>
                        <td class="center" width="10%"><?php echo $lang_action; ?></td>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <tr>
                        <td class="right">
                            <input id="newProduct" type="text">
                            <input type="hidden" id="newProductId">
                        </td>
                        <td>
                            <input id="newamazonusSku" type="text">
                        </td>
                        <td class="center">
                            <a class="button" id="addNewButton" onclick="addNewLinkAutocomplete()" ><span><?php echo $lang_add; ?></span></a>
                        </td>
                    </tr>
                </tbody>
            </table>


            <table align="left" class="list" id="linkListTable">
                <thead>
                    <tr>
                        <td class="center" colspan="6"><?php echo $lang_linked_items; ?></td>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <td width="18%"><?php echo $lang_name; ?></td>
                        <td width="18%"><?php echo $lang_model; ?></td>
                        <td width="18%"><?php echo $lang_combination; ?></td>
                        <td width="18%"><?php echo $lang_sku; ?></td>
                        <td width="18%"><?php echo $lang_amazonus_sku; ?></td>
                        <td class="center" width="10%"><?php echo $lang_action; ?></td>
                    </tr>
                </thead>
                <tbody id="linkedItems">
                </tbody>
            </table>
        </div>

<script type="text/javascript"><!--

$(document).ready(function() {
   loadLinks();
});

function loadLinks() {
    $.ajax({
            url: '<?php echo html_entity_decode($loadLinks); ?>',
            type: 'get',
            dataType: 'json',
            data: 'product_id=' + encodeURIComponent($('#newProductId').val()) + '&amazonus_sku=' + encodeURIComponent($('#newamazonusSku').val()),
            success: function(json) {
                
                var rows = '';
                for(i in json) {
                    rows += '<tr>';
                    rows += '<td class="left">' + json[i]['product_name'] + '</td>';
                    rows += '<td class="left">' + json[i]['model'] + '</td>';
                    rows += '<td class="left">' + json[i]['combi'] + '</td>';
                    rows += '<td class="left">' + json[i]['sku'] + '</td>';
                    rows += '<td class="left">' + json[i]['amazonus_sku'] + '</td>';
                    rows += '<td class="center"><a class="button" onclick="removeLink(this, \'' + json[i]['amazonus_sku'] + '\')" ><span><?php echo $lang_remove; ?></span></a></td>';
                    rows += '</tr>';
                }
                
                 $('#linkedItems').html(rows);  
            },
            error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
    });	
}

function loadUnlinked(button) {
    $.ajax({
            url: '<?php echo html_entity_decode($loadUnlinked); ?>',
            type: 'get',
            dataType: 'json',
            beforeSend: function() {
               $(button).after('<span class="wait"><img src="view/image/loading.gif" alt="" /></span>');  
               $(button).hide();
            },
            complete: function() {
                $(".wait").remove();
                $(button).show();
            },
            success: function(json) {
                
                var thread1 = '';
                thread1 += '<tr>';
                thread1 += '<td class="center" colspan="5"><?php echo $lang_unlinked_items; ?></td>';
                thread1 += '</tr>';
                $('#tableThread1').html(thread1);
                
                var thread2 = '';
                thread2 += '<tr>';
                thread2 += '<td width="22.5%"><?php echo $lang_name; ?></td>';
                thread2 += '<td width="22.5%"><?php echo $lang_model; ?></td>';
                thread2 += '<td width="22.5%"><?php echo $lang_sku; ?></td>';
                thread2 += '<td width="22.5%"><?php echo $lang_amazonus_sku; ?></td>';
                thread2 += '<td class="center" width="10%"><?php echo $lang_action; ?></td>';
                $('#tableThread2').html(thread2);
                
                var rows = '';
                for(i in json) {
                    rows += '<tr id="productRow_' + json[i]['product_id'] + '">';
                    rows += '<td class="left">' + json[i]['product_name'] + '</td>';
                    rows += '<td class="left">' + json[i]['model'] + '</td>';
                    rows += '<td class="left">' + json[i]['sku'] + '</td>';
                    
                    rows += '<td class="left">';
                    rows += '<div class="amazonusSkuDiv_' + json[i]['product_id'] + '">';
                    rows += '<input class="amazonusSku_' + json[i]['product_id'] + '"  type="text">';
                    rows += '<a onclick="addNewSkuField(' + json[i]['product_id'] + ')"><img src="view/image/add.png" alt="<?php echo $lang_add_sku_tooltip; ?>" title="<?php echo $lang_add_sku_tooltip; ?>"></a>';
                    rows += '</div>';
                    rows += '</td>';
                    
                    rows += '<td class="center"><a class="button" onclick="addNewLink(this, \'' +  json[i]['product_id'] + '\')"><span><?php echo $lang_add; ?></span></a></td>';
                    rows += '</tr>';
                }
                
                 $('#tableBody').html(rows);  
                 
            },
            error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
    });	
}

function addLink(button, product_id, amazonus_sku) {
    $.ajax({
            url: '<?php echo html_entity_decode($addLink); ?>',
            type: 'get',
            dataType: 'json',
            async: 'false',
            data: 'product_id=' + encodeURIComponent(product_id) + '&amazonus_sku=' + encodeURIComponent(amazonus_sku),
            beforeSend: function() {
               $(button).after('<span class="wait"><img src="view/image/loading.gif" alt="" /></span>');  
               $(button).hide();
            },
            complete: function() {
                $('.wait').remove();
                $(button).show();
            },
            success: function(json) {
                //alert(json);
                loadLinks();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
    });	
}

function removeLink(button, amazonus_sku) {
    $.ajax({
            url: '<?php echo html_entity_decode($removeLink); ?>',
            type: 'get',
            dataType: 'json',
            data: 'amazonus_sku=' + encodeURIComponent(amazonus_sku),
            beforeSend: function() {
               $(button).after('<span class="wait"><img src="view/image/loading.gif" alt="" /></span>');  
               $(button).hide();
            },
            success: function(json) {
                //alert(json);
                loadLinks();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
    });	
}

function addNewSkuField(product_id) {
    var newField = '';
    newField += '<div class="amazonusSkuDiv_' + product_id + '">';
    newField += '<input class="amazonusSku_' + product_id + '"  type="text">';
    newField += '<a class="removeSkuIcon_' + product_id + '" onclick="removeSkuField(this, \'' + product_id + '\')"><img src="view/image/delete.png" alt=""></a>';
    newField += '</div>';
    
    $(".amazonusSkuDiv_" + product_id).last().after(newField);
}

function removeSkuField(icon, product_id) {
    var removeIndex = $('.removeSkuIcon_' + product_id).index($(icon)) + 1;
    $(".amazonusSkuDiv_" + product_id + ":eq(" + removeIndex + ")").remove();
}

function addNewLink(button, product_id) {
    var errors = 0;
    $(".amazonusSku_" + product_id).each(function(index) {
        if($(this).val() == '') {
            errors ++;
        }
    });
    if(errors > 0) {
        alert('<?php echo $lang_sku_empty_warning; ?>');
        return;
    }
    
    $(".amazonusSku_" + product_id).each(function(index) {
            addLink(button, product_id, $(this).val());
    });
    
    $("#productRow_" + product_id).remove();
}

function addNewLinkAutocomplete() {
    if($('#newProduct').val() == "") {
        alert('<?php echo $lang_name_empty_warning; ?>');
        return;
    }
    
    if($('#newProductId').attr('label') != $('#newProduct').val()) {
        alert('<?php echo $lang_product_warning; ?>');
        return;
    }
    
    if($('#newamazonusSku').val() == "") {
        alert('<?php echo $lang_sku_empty_warning; ?>');
        return;
    }

    var product_id = $('#newProductId').val();
    var amazonus_sku = $('#newamazonusSku').val();
    
    $('#newProduct').val('');
    $('#newamazonusSku').val('');
    $('#newProductId').val('');
    $('#newProductId').attr('label', '');
    
    addLink('#addNewButton', product_id, amazonus_sku);
}

$('#newProduct').autocomplete({
    delay: 0,
    source: function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
            dataType: 'json',
            success: function(json) {		
                    response($.map(json, function(item) {
                            return {
                                    id: item.product_id,
                                    label: item.name
                            }
                    }));
            },
            error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }, 
    select: function(event, ui) {
        $('#newProductId').val(ui.item.id);
        $('#newProductId').attr('label', ui.item.label);
    }
});   
//--></script> 