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

	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>

	<div class="box">
		<div class="heading">
			<h1><span class="fa fa-book"></span> <?php echo $heading_title; ?></h1>
			<div class="buttons">
				<a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a>
				<a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a>
			</div>
		</div>

		<div class="content">
			<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td class="left">
								<?php if ($sort == 'bd.title') { ?>
									<a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_title; ?></a>
								<?php } else { ?>
									<a href="<?php echo $sort_title; ?>"><?php echo $column_title; ?></a>
								<?php } ?>
							</td>
							<td class="right">
								<?php if ($sort == 'b.date') { ?>
									<a href="<?php echo $sort_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date; ?></a>
								<?php } else { ?>
									<a href="<?php echo $sort_date; ?>"><?php echo $column_date; ?></a>
								<?php } ?>
							</td>
							<td class="right">
								<?php if ($sort == 'b.sort_order') { ?>
									<a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
								<?php } else { ?>
									<a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
								<?php } ?>
							</td>
							<td class="right">
								<?php if ($sort == 'b.create_time') { ?>
									<a href="<?php echo $sort_create_time; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_create_time; ?></a>
								<?php } else { ?>
									<a href="<?php echo $sort_create_time; ?>"><?php echo $column_create_time; ?></a>
								<?php } ?>
							</td>
							<td class="right">
								<?php if ($sort == 'b.update_time') { ?>
									<a href="<?php echo $sort_update_time; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_update_time; ?></a>
								<?php } else { ?>
									<a href="<?php echo $sort_update_time; ?>"><?php echo $column_update_time; ?></a>
								<?php } ?>
							</td>
							<td class="right">
								<?php echo $column_action; ?>
							</td>
						</tr>
					</thead>
					<tbody>
						<?php if ($posts) { ?>
							<?php foreach ($posts as $post) { ?>
							<tr>
								<td style="text-align: center;">
									<?php if ($post['selected']) { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $post['blog_id']; ?>" checked="checked" />
									<?php } else { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $post['blog_id']; ?>" />
									<?php } ?>
								</td>
								<td class="left"><?php echo $post['title']; ?></td>
								<td class="right"><?php echo $post['date']; ?></td>
								<td class="right"><?php echo $post['sort_order']; ?></td>
								<td class="right"><?php echo $post['create_time']; ?></td>
								<td class="right"><?php echo $post['update_time']; ?></td>
								<td class="right">
									<?php foreach ($post['action'] as $action) { ?>
									[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
						<?php } else { ?>
							<tr>
								<td class="center" colspan="7"><?php echo $text_no_results; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</form>
			<div class="pagination"><?php echo $pagination; ?></div>
		</div>
	</div>
</div>
<?php echo $footer; ?>