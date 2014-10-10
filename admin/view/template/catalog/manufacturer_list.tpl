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
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><span class="fa fa-barcode"></span> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'sort_order') { ?>
                <a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
          <?php if ($manufacturer_groups) { ?>
          <?php foreach ($manufacturer_groups as $group) { ?>
          <?php if ($group['manufacturers']) { ?>
            <tr onclick="$(this).next().toggle('slow',function(){ });">
                <td></td>
                <td style="height:30px;font-weight: bold;"><?php echo $group['name']; ?></td>
                <td></td>
                <td></td>
            </tr>


          <?php foreach ($group['manufacturers'] as $manufacturer) { ?>
          <tr style="display:none">
              <td style="text-align: center;"><?php if ($manufacturer['selected']) { ?>
                  <input type="checkbox" name="selected[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" />
                  <?php } else { ?>
                  <input type="checkbox" name="selected[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
                  <?php } ?></td>
              <td class="left" style="padding-left: 30px;"><?php echo $manufacturer['name']; ?></td>
              <td class="right"><?php echo $manufacturer['sort_order']; ?></td>
              <td class="right"><?php foreach ($manufacturer['action'] as $action) { ?>
                  [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                  <?php } ?></td>
          </tr>
          <?php } ?>

          <?php } ?>

          <?php } ?>
          <?php } ?>

          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>