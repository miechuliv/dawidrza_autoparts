<?php echo $header; ?>
<div id="content"><?php echo $content_top; ?>
<?/*
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  */?>

 
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  <div id="kontakt">
  <div>
    <h2 style="margin-top:10px;"><?php echo $text_location; ?></h2>
    <div class="contact-info" style="padding:0 0 10px;">
      <div class="content" style="border:none; padding:0;"><div class="left">
        <?php echo $store; ?><br />
        <?php // echo $address; ?><br/></div>
      <div class="right">
        <?php if ($telephone) { ?>
        <strong><?php echo $text_telephone; ?></strong><br />
        <?php echo $telephone; ?><br /><br/>
		<strong><?php echo $entry_email; ?></strong><br />
		<?php echo $this->config->get('config_email'); ?>
        <br />
        <?php } ?>
        <?php if ($fax) { ?><br/>
        <strong><?php echo $text_fax; ?></strong><br />
        <?php echo $fax; ?>
        <?php } ?>
      </div>
    </div>
    </div>
	</div>
	<div>
    <h2><?php echo $text_contact; ?></h2>
    <div class="content">
    <strong><?php echo $entry_name; ?></strong><br />
    <input type="text" name="name" value="<?php echo $name; ?>" />
    <br />
    <?php if ($error_name) { ?>
    <span class="error"><?php echo $error_name; ?></span>
    <?php } ?>
    <br />
    <strong><?php echo $entry_email; ?></strong><br />
    <input type="text" name="email" value="<?php echo $email; ?>" />
    <br />
    <?php if ($error_email) { ?>
    <span class="error"><?php echo $error_email; ?></span>
    <?php } ?>
    <br />
    <strong><?php echo $entry_enquiry; ?></strong><br />
    <textarea name="enquiry" cols="40" rows="10"><?php echo $enquiry; ?></textarea>
    <br />
    <?php if ($error_enquiry) { ?>
    <span class="error"><?php echo $error_enquiry; ?></span>
    <?php } ?>
    <br />
	<?/*
    <strong><?php echo $entry_captcha; ?></strong><br />
    <input type="text" name="captcha" value="<?php echo $captcha; ?>" />
    <br />
    <img src="index.php?route=information/contact/captcha" alt="" />
    <?php if ($error_captcha) { ?>
    <span class="error"><?php echo $error_captcha; ?></span>
    <?php } ?>
	*/?>
    </div>
    <div class="buttons">
      <div class="right"><input type="submit" value="<?php echo $this->language->get('submitto'); ?>" class="button action" /></div>
    </div>
	</div>
	</div>
  </form>
  <?php echo $content_bottom; ?></div>
  <div id="panel-right">

  </div>

<div id="informcoulmn">  
<?php echo $column_left; ?>
</div>
<?php echo $column_right; ?>
<?php echo $footer; ?>