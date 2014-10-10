<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
<div class="login-content">
    

		<div class="left">
			<div class="marginy">
				<form action="" method="post" enctype="multipart/form-data">
					<h2><?php echo $this->language->get('print_head'); ?></h2>
					<div>					
						<textarea name="comment" rows="8"><?php echo $comment; ?></textarea>
					</div>
					<div class="checkbox">
						<input type="checkbox" id="filecheckbox" name="file_checkbox" value="1" <?php if($file_checkbox){ echo 'checked="checked"'; } ?> /><label for="filecheckbox" ><?php echo $this->language->get('print_have_file'); ?></label>
						<?php if($error){ ?>
							<div><?php echo $error; ?></div>
						<?php } ?>
					</div>
					<div id="filesend">
						<strong><?php echo $this->language->get('print_file'); ?></strong><br/><br/>
						<input type="file" name="file" />
					</div>
					<div>
						<input type="submit" class="button ultra" value="<?php echo $this->language->get('print_submit'); ?>" />
						
					</div>
				</form>
			</div>
				<h2 id="skip"><?php echo $this->language->get('print_skip'); ?></h2>
		</div>
		<div class="right">
			<div>
				<?php echo $this->language->get('print_info'); ?>
			</div>
		</div>




    <?php echo $content_bottom; ?>
    </div>
</div>	
	<script>
		
		$(document).ready(function(){
			
			var filediv = $('#filesend');
			filediv.hide();
		
			$('#filecheckbox').click(function(){
				if (this.checked) {
					filediv.show();
				} else {
					filediv.hide();
				}
			});
			
		});
		
	</script>
	
<?php echo $footer; ?>