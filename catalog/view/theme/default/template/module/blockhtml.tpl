<?php if($show_title) { ?>
	<div class="box">
		<div class="box-heading"><?php echo $title; ?></div>
	<div class="box-content">
		<div class="box-html">
			<?php echo $html; ?>
		</div>
	</div>
	</div>
<?php } else { ?>
	<div class="box-html">
		<?php echo $html; ?>
	
	</div>
<?php } ?>
