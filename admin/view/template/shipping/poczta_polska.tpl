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
          <td><?php echo $entry_poczta_polska_ekonom; ?></td>          
          <td><?php if ($poczta_polska_ekonom) { ?>
              <input type="radio" name="poczta_polska_ekonom" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="poczta_polska_ekonom" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="poczta_polska_ekonom" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="poczta_polska_ekonom" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?>
          </td>        
          <td><textarea name="poczta_polska_ekonom_rate" cols="40" rows="5"><?php echo $poczta_polska_ekonom_rate; ?></textarea></td>                  
        </tr>
        <tr>        
          <td><?php echo $entry_poczta_polska_prio; ?></td>          
          <td><?php if ($poczta_polska_prio) { ?>
              <input type="radio" name="poczta_polska_prio" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="poczta_polska_prio" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="poczta_polska_prio" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="poczta_polska_prio" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?>
          </td>        
          <td><textarea name="poczta_polska_prio_rate" cols="40" rows="5"><?php echo $poczta_polska_prio_rate; ?></textarea></td>                  
        </tr>
        <tr>        
          <td><?php echo $entry_poczta_polska_pobranie_ekonom; ?></td>          
          <td><?php if ($poczta_polska_prio) { ?>
              <input type="radio" name="poczta_polska_pobranie_ekonom" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="poczta_polska_pobranie_ekonom" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="poczta_polska_pobranie_ekonom" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="poczta_polska_pobranie_ekonom" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?>
          </td>        
          <td><textarea name="poczta_polska_pobranie_ekonom_rate" cols="40" rows="5"><?php echo $poczta_polska_pobranie_ekonom_rate; ?></textarea></td>                  
        </tr>
        <tr>        
          <td><?php echo $entry_poczta_polska_pobranie_prio; ?></td>         
          <td><?php if ($poczta_polska_prio) { ?>
              <input type="radio" name="poczta_polska_pobranie_prio" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="poczta_polska_pobranie_prio" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="poczta_polska_pobranie_prio" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="poczta_polska_pobranie_prio" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?>
          </td>        
          <td><textarea name="poczta_polska_pobranie_prio_rate" cols="40" rows="5"><?php echo $poczta_polska_pobranie_prio_rate; ?></textarea></td>                  
        </tr>
        <tr>        
          <td><?php echo $entry_poczta_polska_polecony_ekonom; ?></td>          
          <td><?php if ($poczta_polska_polecony_ekonom) { ?>
              <input type="radio" name="poczta_polska_polecony_ekonom" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="poczta_polska_polecony_ekonom" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="poczta_polska_polecony_ekonom" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="poczta_polska_polecony_ekonom" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?>
          </td>        
          <td><textarea name="poczta_polska_polecony_ekonom_rate" cols="40" rows="5"><?php echo $poczta_polska_polecony_ekonom_rate; ?></textarea></td>                  
        </tr>
        <tr>        
          <td><?php echo $entry_poczta_polska_polecony_prio; ?></td>          
          <td><?php if ($poczta_polska_polecony_prio) { ?>
              <input type="radio" name="poczta_polska_polecony_prio" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="poczta_polska_polecony_prio" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="poczta_polska_polecony_prio" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="poczta_polska_polecony_prio" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?>
          </td>        
          <td><textarea name="poczta_polska_polecony_prio_rate" cols="40" rows="5"><?php echo $poczta_polska_polecony_prio_rate; ?></textarea></td>                  
        </tr>
        <tr>        
          <td><?php echo $entry_max_size_envelope; ?></td>          
          <td>Wymiary: <input type="text" name="max_size_envelope_x" value="<?php echo $max_size_envelope_x; ?>" size="3" />
          x<input type="text" name="max_size_envelope_y" value="<?php echo $max_size_envelope_y; ?>" size="3" />
          x<input type="text" name="max_size_envelope_z" value="<?php echo $max_size_envelope_z; ?>" size="3" />cm</td>
          <td>Waga: <input type="text" name="max_weight_envelope" value="<?php echo $max_weight_envelope; ?>" size="3" />kg</td>
          <td>Ilość: <input type="text" name="max_items_envelope" value="<?php echo $max_items_envelope; ?>" size="3" /></td>
        </tr>        
        <tr>        
          <td><?php echo $entry_poczta_polska_packing_cost; ?></td>         
          <td><input type="text" name="poczta_polska_packing_cost" value="<?php echo $poczta_polska_packing_cost; ?>" size="3" /></td>
        </tr>
        <tr>        
          <td><?php echo $entry_poczta_polska_add_value_fee; ?></td>         
          <td><input type="text" name="poczta_polska_add_value_fee" value="<?php echo $poczta_polska_add_value_fee; ?>" size="3" /></td>
        </tr>
        <tr>        
          <td><?php echo $entry_poczta_polska_display_weight; ?></td>          
          <td><?php if ($poczta_polska_display_weight) { ?>
              <input type="radio" name="poczta_polska_display_weight" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="poczta_polska_display_weight" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="poczta_polska_display_weight" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="poczta_polska_display_weight" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?>
          </td>        
        </tr>
        <tr>
          <td><?php echo $entry_tax; ?></td>
          <td><select name="poczta_polska_tax_class_id">
              <option value="0"><?php echo $text_none; ?></option>
              <?php foreach ($tax_classes as $tax_class) { ?>
              <?php if ($tax_class['tax_class_id'] == $poczta_polska_tax_class_id) { ?>
              <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_geo_zone; ?></td>
          <td><select name="poczta_polska_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $poczta_polska_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="poczta_polska_status">
              <?php if ($poczta_polska_status) { ?>
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
          <td><input type="text" name="poczta_polska_sort_order" value="<?php echo $poczta_polska_sort_order; ?>" size="1" /></td>
        </tr>
            </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 