<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/product.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $button_insert; ?></span></a><a onclick="$('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left">Nazwa</td>
            <td class="left">Tytuł</td>
            <td class="right">Akcja</td>
          </tr>
        </thead>
        <tbody>
          <?php if ($szablony) { ?>
          <?php foreach ($szablony as $szablon) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($szablon['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $szablon['szablon_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $szablon['szablon_id']; ?>" />
              <?php } ?></td>
            <td class="left"><?php echo $szablon['name']; ?></td>
            <td class="left"><?php echo $szablon['title']; ?></td>
            <td class="right"><?php foreach ($szablon['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>