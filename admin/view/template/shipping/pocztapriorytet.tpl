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
      <h1><span class="fa fa-truck"></span> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">

          <tr>
            <td><?php echo $entry_tax; ?></td>
            <td><select name="pocztapriorytet_tax_class_id">
                <option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($tax_classes as $tax_class) { ?>
                <?php if ($tax_class['tax_class_id'] == $pocztapriorytet_tax_class_id) { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $this->language->get('text_add_zone'); ?></td>
            <td><select name="pocztapriorytet_geo_zone_id"  onclick="addZone(this)" >

                    <option>    </option>
                <?php foreach ($geo_zones as $geo_zone) { ?>

                <option  value="<?php echo $geo_zone['geo_zone_id']; ?>" ><?php echo $geo_zone['name']; ?></option>

                <?php } ?>
              </select></td>
          </tr>
          <tr>
              <td><?php echo $this->language->get('text_zones'); ?></td>
              <td id="zones">
                  <?php $zone_row = 0; ?>

                  <table>

                      <thead>
                      <tr>
                          <td><?php echo $this->language->get('text_zone_name'); ?></td>
                          <td><?php echo $this->language->get('text_zone_time'); ?></td>
                          <td><?php echo $this->language->get('text_zone_weight'); ?></td>
                          <td><?php echo $this->language->get('text_zone_remove'); ?></td>
                      </tr>
                      </thead>
                      <tbody id="zones_cont">
                      <?php if(!empty($pocztapriorytet_allowed_zones)){ ?>
                      <?php foreach($pocztapriorytet_allowed_zones as $zone){ ?>
                      <tr>
                          <td>
                              <label for="pocztapriorytet_allowed_zones[<?php echo $zone_row; ?>][zone_id]" ><?php echo $zone['name']; ?></label><br/>
                              <input type="hidden" name="pocztapriorytet_allowed_zones[<?php echo $zone_row; ?>][zone_id]" value="<?php echo $zone['zone_id']; ?>" />
                          </td>
                          <td>
                              <input type="text" name="pocztapriorytet_allowed_zones[<?php echo $zone_row; ?>][delivery_time]" value="<?php echo $zone['delivery_time']; ?>" />
                          </td>
                          <td>
                              <textarea name="pocztapriorytet_allowed_zones[<?php echo $zone_row; ?>][weight]" ><?php echo $zone['weight']; ?></textarea>
                          </td>
                          <td onclick="$(this).parent().remove();">
                              <img src="view/image/delete.png" />
                          </td>
                      </tr>
                      <?php $zone_row++; ?>
                      <?php } ?>
                      </tbody>
                      <?php } ?>
                  </table>

              </td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="pocztapriorytet_status">
                <?php if ($pocztapriorytet_status) { ?>
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
            <td><input type="text" name="pocztapriorytet_sort_order" value="<?php echo $pocztapriorytet_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" >
    var prev = false;
    var zone_row = <?php echo $zone_row; ?>;
    function addZone(elem)
    {
        var zone_id = $(elem).find('option:selected').val();

        if(!zone_id || zone_id == prev)
        {
            return false;
        }
        prev = zone_id;
        var zone_name = $(elem).find('option:selected').text();

        var html = '';

        html += '<tr>';
        html += '<td>';
        html += '<label for="pocztapriorytet_allowed_zones['+zone_row+'][zone_id]" >'+zone_name+'</label><br/>';
        html += '<input type="hidden" name="pocztapriorytet_allowed_zones['+zone_row+'][zone_id]" value="'+zone_id+'" />';
        html += '</td>';
        html += '<td>';
        html += '<input type="text" name="pocztapriorytet_allowed_zones['+zone_row+'][delivery_time]" value="" />';
        html += '</td>';
        html += '<td>';
        html += '<textarea name="pocztapriorytet_allowed_zones['+zone_row+'][weight]" >';

        html += '</textarea>';
        html += '</td>';

        html += '<td onclick="$(this).parent().remove();">';
        html += '<img src="view/image/delete.png" />';
        html += '</td>';
        html += '</tr>';

        console.log(html);

        $('#zones_cont').append(html);

        zone_row++;
    }
</script>
<?php echo $footer; ?>