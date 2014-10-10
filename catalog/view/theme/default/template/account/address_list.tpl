<?php echo $header; ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="mobilehide"><?php echo $column_left; ?></div><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>

  <?php foreach ($addresses as $result) { ?>
  <div class="content" style="margin-top:20px;">
    <table style="width: 100%;">
      <tr>
        <td style="font-weight:bold"><?php echo $result['address']; ?></td>
        <td style="text-align: right;"><a href="<?php echo $result['update']; ?>" class="button"><?php echo $button_edit; ?></a> &nbsp; <a href="<?php echo $result['delete']; ?>" class="button"><?php echo $button_delete; ?></a></td>
      </tr>
    </table>
  </div>
  <?php } ?>
  <div class="buttons" style="margin-top:20px;">
    <div class="right"><a href="<?php echo $insert; ?>" class="button action"><?php echo $button_new_address; ?></a></div>
  </div>
  <?php echo $content_bottom; ?></div><div class="mobileshow mobilebox"><?php echo $column_left; ?></div>
<?php echo $footer; ?>