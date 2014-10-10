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
			<h1><span class="fa fa-book"></span> <?php echo $heading_title; ?></h1>
			<div class="buttons">
				<a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
				<a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a>
			</div>
		</div>
		<div class="content">
			<div id="tabs" class="htabs">
				<a href="#tab-general"><?php echo $tab_general; ?></a>
				<a href="#tab-data"><?php echo $tab_data; ?></a>
			</div>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<div id="tab-general">
					<div id="languages" class="htabs">
						<?php foreach ($languages as $language) { ?>
							<a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
						<?php } ?>
					</div>
					<?php foreach ($languages as $language) { ?>
						<div id="language<?php echo $language['language_id']; ?>">
							<table class="form">
								<tr>
									<td><span class="required">*</span> <?php echo $entry_title; ?></td>
									<td>
										<input type="text" name="blog_description[<?php echo $language['language_id']; ?>][title]" size="100" value="<?php echo isset($blog_description[$language['language_id']]) ? $blog_description[$language['language_id']]['title'] : ''; ?>" />
										<?php if (isset($error_title[$language['language_id']])) { ?>
											<span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
										<?php } ?>
									</td>
								</tr>
								<tr>
									<td><span class="required">*</span> <?php echo $entry_intro_text; ?></td>
									<td>
										<textarea name="blog_description[<?php echo $language['language_id']; ?>][intro_text]" id="intro_text<?php echo $language['language_id']; ?>"><?php echo isset($blog_description[$language['language_id']]) ? $blog_description[$language['language_id']]['intro_text'] : ''; ?></textarea>
										<?php if (isset($error_intro_text[$language['language_id']])) { ?>
											<span class="error"><?php echo $error_intro_text[$language['language_id']]; ?></span>
										<?php } ?>
									</td>
								</tr>
								<tr>
									<td><span class="required">*</span> <?php echo $entry_text; ?></td>
									<td>
										<textarea name="blog_description[<?php echo $language['language_id']; ?>][text]" id="text<?php echo $language['language_id']; ?>"><?php echo isset($blog_description[$language['language_id']]) ? $blog_description[$language['language_id']]['text'] : ''; ?></textarea>
										<?php if (isset($error_text[$language['language_id']])) { ?>
											<span class="error"><?php echo $error_text[$language['language_id']]; ?></span>
										<?php } ?>
									</td>
								</tr>
								<tr>
									<td><?php echo $entry_meta_description; ?></td>
									<td>
										<textarea name="blog_description[<?php echo $language['language_id']; ?>][meta_description]" id="meta_description<?php echo $language['language_id']; ?>" cols="40" rows="5"><?php echo isset($blog_description[$language['language_id']]) ? $blog_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
										<?php if (isset($error_meta_description[$language['language_id']])) { ?>
											<span class="error"><?php echo $error_meta_description[$language['language_id']]; ?></span>
										<?php } ?>
									</td>
								</tr>
								<tr>
									<td><?php echo $entry_meta_keyword; ?></td>
									<td>
										<textarea name="blog_description[<?php echo $language['language_id']; ?>][meta_keyword]" id="meta_keyword<?php echo $language['language_id']; ?>" cols="40" rows="5"><?php echo isset($blog_description[$language['language_id']]) ? $blog_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
										<?php if (isset($error_meta_keyword[$language['language_id']])) { ?>
											<span class="error"><?php echo $error_meta_keyword[$language['language_id']]; ?></span>
										<?php } ?>
									</td>
								</tr>
							</table>
						</div>
					<?php } ?>
				</div>
				<div id="tab-data">
					<table class="form">
						<tr>
							<td><span class="required">*</span> <?php echo $entry_date; ?></td>
							<td>
								<input type="text" name="date" value="<?php echo $date; ?>" class="date" />
								<?php if ($error_date) { ?>
									<span class="error"><?php echo $error_date; ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td><?php echo $entry_status; ?></td>
							<td>
								<select name="status">
									<?php if ($status) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
									<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo $entry_sort_order; ?></td>
							<td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="2" /></td>
						</tr>
					</table>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 

<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('intro_text<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
CKEDITOR.replace('text<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>
//--></script> 
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
$('#languages a').tabs();
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
//--></script> 
<?php echo $footer; ?>