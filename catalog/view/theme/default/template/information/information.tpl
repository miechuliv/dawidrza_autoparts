<?php echo $header; ?>
<div id="content" class="cont-white"><?php echo $content_top; ?>
  <?/*<div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>*/?>
  <h1><?php echo $heading_title; ?></h1>
  <?php echo $description; ?>
  <?php echo $content_bottom; ?></div>

<div id="informcoulmn">  
<?php echo $column_left; ?>
</div>
<?php echo $column_right; ?>
<?php echo $footer; ?>