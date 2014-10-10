<?php if (count($languages) > 1) { ?> 
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  <div id="language" class="pseudoselect">
  <?php echo $text_language; ?>
	<div class="namer"><?php echo $this->language->get('lang_'.$this->config->get('config_language').''); ?></div>
	<div class="contentr">
    <?php foreach ($languages as $language) { ?>
		<div onclick="$('input[name=\'language_code\']').attr('value', '<?php echo $language['code']; ?>'); $(this).parent().parent().parent().submit();">
			<img src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>"  /> <?php echo $language['name']; ?>
		</div>
    <?php } ?>
	</div>
    <input type="hidden" name="language_code" value="" />
    <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
  </div>
</form>
<?php } ?>
