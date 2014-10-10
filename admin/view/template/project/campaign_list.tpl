<?php echo $header; ?>

<div id="content">
  <div class="breadcrumb">
	<a href="./index.php?route=common/home&token=<?php echo $this->session->data['token']; ?>">Strona główna</a> 
	::
	<a href="http://<?php echo $_SERVER['HTTP_HOST']; echo $_SERVER['REQUEST_URI']; ?>">Kampanie</a>	
  </div>

  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
    <?php if ($error) { ?>
    <div class="error"><?php echo $error; ?></div>
    <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><span class="fa fa-pencil"></span> Kampanie</h1>
      <div class="buttons">
          <?php /* <a href="<?php echo $insert; ?>" class="button"><?php echo $this->language->get('button_insert'); ?></a> */ ?>
          <a onclick="$('form').submit();" class="button"><?php echo $this->language->get('button_delete'); ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list projekty">
          <thead>
            <tr>
                <td>Status <br/>
					<small style="color:#1aca26">właśnie trwa</small>, <small style="color:#d9ce06">ostatnia szansa</small>, <small style="color:#f50d07">zakończona</small>
                </td>
              <td class="right"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>

                <?php /* <td class="center"><?php echo $this->language->get('column_image'); ?></td> */ ?>

                <td class="left"><?php echo $this->language->get('column_name'); ?></td>
                <td class="left"><?php echo $this->language->get('column_author'); ?></td>
                <td class="left"><?php echo $this->language->get('column_project'); ?></td>

                <td class="left"><?php echo $this->language->get('column_date_start'); ?></td>
                <td class="left"><?php echo $this->language->get('column_date_end'); ?></td>
				<td class="left"><?php echo $this->language->get('column_sold'); ?></td>

               <td class="right">Akcja</td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
              <td></td>
                <td></td>
                <?php /* <td></td> */ ?>

                <td class="left">
                    <input name="filter_name" type="text" value="<?php echo $filter_name; ?>" />
                </td>
                <td class="left">
                    <input name="filter_author" type="text" value="<?php echo $filter_author; ?>" />
                </td>
                <td class="left">

                </td>
                <td class="left">
                    <input class="datetime" type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" />
                </td>
                <td class="left">
                    <input class="datetime" type="text" name="filter_date_stop" value="<?php echo $filter_date_stop; ?>" />
                </td>
			<td></td>

              <td align="left"><a onclick="filter();" class="button action"><?php echo $this->language->get('button_filter'); ?></a></td>
            </tr>
            <!-- dodatkowe wyszukiwanie np: opcje, atrybuty -->


            <?php if ($campaigns) { ?>
            <?php foreach ($campaigns as $campaign) { ?>
            <tr>
              <td class="left" ><img src="<?php echo $campaign['status']; ?>" class="camp-stat"/></td>
              <td style="text-align: center;"><?php if ($campaign['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $campaign['campaign_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $campaign['campaign_id']; ?>" />
                <?php } ?></td>
                <?php /* <td class="center"><img src="<?php echo $campaign['image']; ?>" alt="<?php echo $campaign['name']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" /></td> */ ?>
              <td class="left" ><?php echo $campaign['name']; ?></td>
                <td class="left" ><a target="_blank" href="<?php echo $campaign['edit_author']; ?>" ><?php echo $campaign['author']; ?></a></td>
                <td class="left" ><?php echo $campaign['project']; ?></td>
                <td class="left" ><?php echo $campaign['date_start']; ?></td>
                <td class="left" ><?php echo $campaign['date_end']; ?></td>
				<td class="left" ><?php echo $campaign['sold']; ?></td>

              <td id="action-td" class="left">
                 <a href="<?php echo $campaign['edit']; ?>"  class="button action"><?php echo $this->language->get('text_edit'); ?></a><br/>
                  <a target="_blank" href="<?php echo $campaign['preview_link']; ?>"  class="button" ><?php echo $this->language->get('text_preview'); ?></a><br/>				  				  
				  <a  href="<?php echo $campaign['short_report']; ?>"  class="button" ><?php echo $this->language->get('text_short_report'); ?></a>
              </td>
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
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter(mass_edit) {
	url = 'index.php?route=project/campaign/showList&token=<?php echo $token; ?>';



        var selected = $('input[name=\'selected[]\']:checked');


        $(selected).each(function(key,elem){

            var id = $(elem).val();



            if (id) {
                url += '&selected[]=' + encodeURIComponent(id);
            }
        });




	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

    var filter_author = $('input[name=\'filter_author\']').attr('value');

    if (filter_author) {
        url += '&filter_author=' + encodeURIComponent(filter_author);
    }

    var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');

    if (filter_date_start) {
        url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
    }

    var filter_date_stop = $('input[name=\'filter_date_stop\']').attr('value');

    if (filter_date_stop) {
        url += '&filter_date_stop=' + encodeURIComponent(filter_date_stop);
    }




	location = url;
}
//--></script> 
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter(false);
	}
});
//--></script>

<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
            $('.date').datepicker({dateFormat: 'yy-mm-dd'});
    $('.datetime').datetimepicker({
        dateFormat: 'yy-mm-dd',
        timeFormat: 'h:m'
    });
    $('.time').timepicker({timeFormat: 'h:m'});


    //--></script>

<?php echo $footer; ?>