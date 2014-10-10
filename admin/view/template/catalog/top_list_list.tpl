<?php echo $header; ?>

<div id="content">


  <div class="box">
    <div class="heading">
      <h1><span class="fa fa-folder-o"></span></h1>
      <div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td class="right"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php echo $this->language->get('column_name'); ?></td>
                <td class="left"><?php echo $this->language->get('column_sort_order'); ?></td>
                <td class="left"><?php echo $this->language->get('column_status'); ?></td>

                <td class="left">Akcja</td>
            </tr>
          </thead>
          <tbody>


            <?php if ($top_lists) { ?>
            <?php foreach ($top_lists as $top_list) { ?>
            <tr>
              <td style="text-align: center;">
                <input type="checkbox" name="selected[]" value="<?php echo $top_list['top_list_id']; ?>" />
                </td>

              <td class="left filter_name" ><?php echo $top_list['name']; ?></td>
                <td class="left filter_name" ><?php echo $top_list['sort_order']; ?></td>


                <td class="left"><?php echo $top_list['active']; ?></td>

              <td id="action-td" class="right"><?php foreach ($top_list['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
               <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="8"><?php echo $this->language->get('text_no_results'); ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>

    </div>
  </div>
</div>
<?php echo $footer; ?>