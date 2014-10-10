<?php echo $header; ?><div class="mobilehide"><?php echo $column_left; ?></div><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <?php echo $text_message; ?>
  
  <?php echo $content_bottom; ?></div><div class="mobileshow mobilebox"><?php echo $column_left; ?></div>
<?php echo $footer; ?>