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
  <div class="box login">
    <div class="content">			 <img src="<?php echo HTTP_CATALOG; ?>image/<?php echo $this->config->get('config_logo'); ?>" alt="<?php echo $this->config->get('config_name'); ?>"/>	    <h1><?php echo $heading_title; ?></h1>	
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="forgotten">
       <?/* <p><?php echo $text_email; ?></p>*/?>
        <table class="form">
          <tr>
            <td><input type="text" name="email" placeholder="<?php echo $entry_email; ?>" class="ui-widget-content" value="<?php echo $email; ?>" />			 			<div class="buttons"><a onclick="$('#forgotten').submit();" class="button"><?php echo $button_reset; ?> hasło</a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>						</td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>