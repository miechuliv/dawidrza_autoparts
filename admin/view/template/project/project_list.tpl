<?php echo $header; ?>

<div id="content">
  <div class="breadcrumb">
	<a href="./index.php?route=common/home&token=<?php echo $this->session->data['token']; ?>">Strona główna</a> 
	::
	<a href="http://<?php echo $_SERVER['HTTP_HOST']; echo $_SERVER['REQUEST_URI']; ?>">Projekty</a>	
  </div>
<div class="box">
    <div class="heading">
        <h1><span class="fa fa-pencil"></span> Projekty</h1>
    </div>
<div class="content">

    <table class="list projekty procent20">
        <thead>
            <tr>
                <td class="left"><?php echo $this->language->get('text_title'); ?></td>

                <td class="left"><?php echo $this->language->get('text_design'); ?></td>

                <td class="left"><?php echo $this->language->get('text_date_added'); ?></td>

                <td class="left"><?php echo $this->language->get('text_status'); ?></td>

                <td class="left"><?php echo $this->language->get('text_author'); ?></td>

                <td class="left"><?php echo $this->language->get('text_action'); ?></td>
            </tr>
		</thead>
		<tbody>
        <tr class="filter">
            <td>

            </td>
            <td> </td>

            <td class="date">
				<div style="display:table; width:100%;">
					<div style="display:table-cell; width:50%;">
						<input type="text" name="date_start" value="<?php echo $date_start; ?>" placeholder="<?php echo $this->language->get('text_date_start'); ?>" />
					</div>
					<div style="display:table-cell; width:50%;">
						<input type="text" name="date_end" value="<?php echo $date_end; ?>" placeholder="<?php echo $this->language->get('text_date_end'); ?>" />
					</div>
				</div>
			</td>

            <td><?php echo generateDropDown($statuses->rows,'project_status_id','name',false,$status,'status',true); ?> </td>

            <td><input type="text" name="author" value="<?php echo $author; ?>" placeholder="<?php echo $this->language->get('text_author'); ?>" /></td>

            <td><a class="button action" onclick="filter();return false" ><?php echo $this->language->get('text_filter'); ?></a></td>
        </tr>

        <?php if(!empty($projects)){ ?>
        <?php foreach($projects as $project){ ?>
        <tr>
            <td class="left"><?php echo $project->title; ?></td>

            <td class="right">
                <a href="<?php echo $prepare_image($project->design); ?>" target="_blank"><img src="<?php echo $prepare_image($project->design); ?>" style="width:50%;"/></a>
            </td>

            <td class="left"><?php echo $project->date_added; ?></td>

            <td class="left"><?php echo $project->status; ?></td>

            <td class="left"><strong><?php echo $project->author; ?></strong><br/>
                <?php echo $project->email; ?></td>

            <td class="left">
             <a href="<?php echo $edit($project->ID); ?>" class="button action clear"><?php echo $this->language->get('text_edit'); ?></a>
             <a href="<?php echo $delete($project->ID); ?>" class="button" ><?php echo $this->language->get('text_delete'); ?></a>
             <a target="_blank" href="<?php echo $campaign($project->ID); ?>" class="button" ><?php echo $this->language->get('text_create_campaign'); ?></a>
            </td>
        </tr>
        <?php } ?>
        <?php } ?>
        </tbody>

    </table>

    <div class="pagination"><?php echo $pagination; ?></div>

    </div>
</div>
</div>


<script type="text/javascript"><!--
    function filter() {
        url = 'index.php?route=project/project/showList&token=<?php echo $this->session->data["token"]; ?>';


        var date_start = $('input[name=\'date_start\']').attr('value');

        if (date_start) {
            url += '&date_start=' + encodeURIComponent(date_start);
        }

        var date_end = $('input[name=\'date_end\']').attr('value');

        if (date_end) {
            url += '&date_end=' + encodeURIComponent(date_end);
        }

        var status = $('select[name=\'status\'] option:selected').attr('value');

        if (status) {
            url += '&status=' + encodeURIComponent(status);
        }

        var author = $('input[name=\'author\']').attr('value');

        if (author) {
            url += '&author=' + encodeURIComponent(author);
        }


        location = url;
    }

    $('input[name=\'date_start\']').datepicker({dateFormat: 'yy-mm-dd'});
    $('input[name=\'date_end\']').datepicker({dateFormat: 'yy-mm-dd'});
    //--></script>
<?php echo $footer; ?>