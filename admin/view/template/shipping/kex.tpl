<?php echo $header; ?>
<?php if (isset($breadcrumbs)) { //v15x ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php } ?>
<?php if (!empty($error_warning)) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
	
	<?php if (!isset($breadcrumbs)) { //v14x ?>
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('view/image/<?php echo !empty($extension_class) ? $extension_class : 'module' ?>.png');"><?php echo $heading_title; ?></h1>
		<div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
	</div>
	<?php } else { //v15x ?>
	<div class="heading">
      <h1><img src="view/image/<?php echo !empty($extension_class) ? $extension_class : 'module' ?>.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
	<?php } ?>
	
	<div class="content">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			
			</div>
<?php $i=0; ?>

			<div class="page">
				<table class="form">
<?php foreach ($fields as $field) { ?>
<?php if ((empty($field['tab']) && $i == 0) || (!empty($field['tab']) && $field['tab'] == $tab['id'])) { ?>
					<tr class="field field-<?php echo $field['type']; ?>">
						<td><?php echo ((!empty($field['required']) ) ? '<span class="required">*</span>' : '') . $field['entry']; ?></td>
						<td>
<?php if ($field['type'] == 'select') { ?>
							<select name="<?php echo $field['name']; ?>" <?php echo (isset($field['multiple']) && $field['multiple']) ? 'multiple="multiple"' : ''?> <?php echo (isset($field['size']) && $field['size']) ? 'size="' . $field['size'] . '"' : ''?>>
<?php foreach ($field['options'] as $key => $value) : ?>
								<option value="<?php echo $key; ?>"<?php if((is_array($field['value']) && in_array($key, $field['value'])) || ($field['value'] == $key)) echo ' selected="selected"'?>><?php echo $value; ?></option>
<?php endforeach; ?>
							</select>
<?php } elseif ($field['type'] == 'radio') {?>
<?php foreach($field['options'] as $key => $value) : ?>
							<input type="radio" name="<?php echo $field['name']; ?>" id="<?php echo $field['name']; ?>" value="<?php echo $key; ?>"<?php if($field['value'] == $key) echo ' checked="checked"'; ?> /><label for="<?php echo $field['name']; ?>"><?php echo $value; ?></label>
<?php endforeach; ?>
<?php } elseif ($field['type'] == 'text') {?>
							<input type="text" name="<?php echo $field['name']; ?>" value="<?php echo $field['value']; ?>" <?php echo (isset($field['size']) && $field['size']) ? 'size="' . $field['size'] . '"' : ''?>/>
<?php } elseif ($field['type'] == 'password') {?>
							<input type="password" name="<?php echo $field['name']; ?>" value="<?php echo $field['value']; ?>" <?php echo (isset($field['size']) && $field['size']) ? 'size="' . $field['size'] . '"' : ''?>/>
<?php } elseif ($field['type'] == 'checkbox') {?>
							<input type="checkbox" name="<?php echo $field['name']; ?>" id="<?php echo $field['name']; ?>" value="1"<?php if($field['value']) echo 'checked="checked"'; ?> />
<?php } elseif ($field['type'] == 'file') {?>
							<input type="file" name="<?php echo $field['name']; ?>" value="" <?php echo (isset($field['size']) && $field['size']) ? 'size="' . $field['size'] . '"' : ''?> />
<?php } elseif ($field['type'] == 'textarea') {?>
							<textarea name="<?php echo $field['name']; ?>" cols="<?php echo $field['cols']; ?>" rows="<?php echo $field['rows']; ?>"><?php echo $field['value']; ?></textarea>
<?php } elseif ($field['type'] == 'hidden') {?>
							<input type="hidden" name="<?php echo $field['name']; ?>" value="<?php echo $field['value']; ?>" />
<?php } ?>
<?php if (!empty($field['help'])) { ?>
							<span class="help"><?php echo $field['help']; ?></span><br />
<?php } ?>
<?php if (!empty($field['error'])) { ?>
							<span class="error"><?php echo $field['error']; ?></span>
<?php } ?>
						</td>
					</tr>
<?php } // end if field tab ?>
<?php } // end foreach fields ?>
				</table>
			</div>
<?php $i++; ?>

		</form>
	</div>
</div>
<?php if (isset($breadcrumbs)) { //v15x ?></div><?php } ?>
<script type="text/javascript"><!--
$.tabs('#tabs a');
//--></script>
<?php echo $footer; ?>