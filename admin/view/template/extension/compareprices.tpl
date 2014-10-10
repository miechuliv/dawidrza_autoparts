<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <?php if ($error) { ?>
  <div class="warning"><?php echo $error; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/total.png" alt="" /> <?php echo $heading_title; ?></h1>
    </div>
    <div class="content">
      <table class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $column_name; ?></td>
            <td class="left"><?php echo $column_status; ?></td>
            <td class="right"><?php echo $column_address_xml; ?></td>
			<td class="right"><?php echo $column_data_export; ?></td>
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($compare) { ?>
          <?php foreach ($compare as $row) { ?>
          <tr>
            <td class="left"><?php echo $row['name']; ?></td>
            <td class="left"><?php echo $row['status'] ?></td>
            <td class="right"><textarea wrap="on" onclick="this.select();" style="width: 300px; height: 50px;"><?php echo $row['address_xml']; ?></textarea></td>
			<td class="right"><?php echo $row['data_export']; ?></td>
            <td class="right"><?php foreach ($row['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php echo $footer; ?>