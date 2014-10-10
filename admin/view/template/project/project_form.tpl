<?php echo $header; ?>

<div id="content" class="proj-show">  <div class="breadcrumb">	<a href="./index.php?route=common/home&token=<?php echo $this->session->data['token']; ?>">Strona główna</a> 	::	<a href="http://<?php echo $_SERVER['HTTP_HOST']; echo $_SERVER['REQUEST_URI']; ?>">Projekty</a>	  </div><div class="box">    <div class="heading">        <h1><span class="fa fa-pencil"></span> Projekty</h1>		<div class="buttons">  <a class="button" target="_blank" href="<?php echo $campaign($project->ID); ?>" ><?php echo $this->language->get('text_campaign'); ?></a></div>    </div><div class="content">
   <div style="padding:10px">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">

                <table>
                    <tr>
                        <td><label for="author" ><?php echo $this->language->get('text_author'); ?></label></td>
                        <td>
                            <input disabled="disabled" type="text" name="author" value="<?php echo $project->author; ?>" />

                        </td>
                    </tr>
                    <tr>
                        <td><label for="email" ><?php echo $this->language->get('text_email'); ?></label></td>
                        <td>
                            <input disabled="disabled" type="text" name="email" value="<?php echo $project->email; ?>" />

                        </td>
                    </tr>
                    <tr>
                        <td><label for="title" ><?php echo $this->language->get('text_title'); ?></label></td>
                        <td>
                            <input type="text" name="title" value="<?php echo $title; ?>" />
                            <div><?php echo $error_title; ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="description" ><?php echo $this->language->get('text_description'); ?></label></td>
                        <td>
                            <textarea  name="description" ><?php echo $description; ?></textarea>
                            <div><?php echo $error_description; ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="inspiration" ><?php echo $this->language->get('text_inspiration'); ?></label></td>
                        <td>
                            <input type="text" name="inspiration" value="<?php echo $inspiration; ?>" />
                            <div><?php echo $error_inspiration; ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="design" ><?php echo $this->language->get('text_design'); ?></label></td>
                        <td>
                            <img src="<?php echo $prepare_image($project->design); ?>" />

                        </td>
                    </tr>
                    <tr>
                        <td><label for="colors" ><?php echo $this->language->get('text_colors'); ?></label></td>
                        <td>
                            <?php echo generateDropDown(array(1,2,3,4),false,false,false,$colors,'colors'); ?>

                        </td>
                    </tr>

                    <tr>
                        <td><label for="prev_release" ><?php echo $this->language->get('text_prev_release'); ?></label></td>
                        <td>
                            <input type="text" name="prev_release" value="<?php echo $prev_release; ?>" />
                            <div><?php echo $error_prev_release; ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="portfolio" ><?php echo $this->language->get('text_portfolio'); ?></label></td>
                        <td>
                            <input type="text" name="portfolio" value="<?php echo $portfolio; ?>" />
                            <div><?php echo $error_portfolio; ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="status" ><?php echo $this->language->get('text_status'); ?></label></td>
                        <td>
                            <?php echo generateDropDown($statuses->rows,'project_status_id','name',false,$status,'status',true); ?>
                            <div><?php echo $error_status; ?></div>
                        </td>
                    </tr>

                </table>

                <p><?php echo $this->language->get('text_notes'); ?></p>
                <table id="notes" >
                    <tbody>
                    <?php foreach($notes as $note){ ?>
                           <tr class="root">


                                           <td>

                                               <textarea id="note-<?php echo $note['project_note_id']; ?>" style="width: 500px; height: 200px;"><?php echo $note["note"]; ?></textarea>
                                           </td>

                                    <td>
                                               <a class="button" onclick="deleteNote(this,<?php echo $note["project_note_id"]; ?>)" ><?php echo $this->language->get('text_delete_note'); ?></a><br/>
                                               <a class="button" onclick="updateNote(this,<?php echo $note["project_note_id"]; ?>)" ><?php echo $this->language->get('text_update_note'); ?></a>
                                   </td>


                           </tr>


                    <?php } ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="2"
                            >
                            <?php echo $this->language->get('text_new_note'); ?>
                            </td>
                    </tr>
                       <tr>
                           <td>

                               <textarea id="note-new" style="width: 500px; height: 200px;">

                               </textarea>
                           </td>
                           <td>
                               <a class="button" onclick="addNote(this,<?php echo $project->ID; ?>)" ><?php echo $this->language->get('text_add_note'); ?></a>
                           </td>
                       </tr>


                    </tfoot>
                </table>

                <div class="buttons" style="padding:5px 5px 10px; float:left;">
                    <div class="left"> <input type="submit" class="button action" value="<?php echo $button_continue; ?>"/></div>

                </div>
            </form>
</div>
        </div>
    </div>
</div>



<script type="text/javascript">

    function deleteNote(elem,note_id)
    {
        $.ajax({
            url: 'index.php?route=project/project/deleteNote&token=<?php echo $this->session->data["token"]; ?>',
            type: 'post',
            data: {
               note_id: note_id
            },
            success: function()
            {
                $(elem).parents('.root').remove();
            }
        })
    }

    function editNote()
    {

    }

    function updateNote(elem,note_id)
    {
        var note = $(elem).parents('.root').find('textarea').val();
        $.ajax({
            url: 'index.php?route=project/project/updateNote&token=<?php echo $this->session->data["token"]; ?>',
            type: 'post',
            data: {
                note_id: note_id,
                note: note
            }

        })
    }

    function addNote(elem,project_id)
    {
        var note = $(elem).parents('tr').find('textarea').val();

        $.ajax({
            url: 'index.php?route=project/project/addNote&token=<?php echo $this->session->data["token"]; ?>',
            type: 'post',
            dataType: 'text',
            data: {
                project_id: project_id,
                note: note
            },
            success: function(text)
            {
                var html = '';
                html += '<tr class="root">';

                html += '<td>';
                html += '<textarea id="note-'+text+'" style="width: 500px; height: 200px;">';
                html += note;
                html += '</textarea>';
                html += '</td>';


                html += '<td>';
                html += '<a class="button" onclick="deleteNote(this,'+text+')" ><?php echo $this->language->get('text_delete_note'); ?></a><br/>';
                html += '<a class="button" onclick="updateNote(this,'+text+')" ><?php echo $this->language->get('text_delete_note'); ?></a>';
                html += '</td>';


                html += '</tr>';

                $('#notes tbody').append(html);

            }
        })
    }

</script>


<?php echo $footer; ?>