<?php echo $header; ?>

<div id="content">
    <div class="box">
        <div class="heading">
            <h1><img src="view/image/product.png" alt="" /> <?php echo $this->language->get('heading_title'); ?></h1>
        </div>
        <div class="content">

            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">

                <table>
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
                        <td><label for="colors" ><?php echo $this->language->get('text_colors'); ?></label></td>
                        <td>
                            <?php echo generateDropDown(array(1,2,3,4),false,false,false,$colors,'colors'); ?>

                        </td>
                    </tr>
                    <tr>
                        <td><label for="file" ><?php echo $this->language->get('text_design'); ?></label></td>
                        <td>
                            <input type="file" name="file"  />
                            <div><?php echo $error; ?></div>
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
                        <td><label for="confirm" ><?php echo $this->language->get('text_confirm'); ?></label></td>
                        <td>
                            <input type="checkbox" name="confirm" value="1" />
                            <div><?php echo $error_confirm; ?></div>
                        </td>
                    </tr>

                </table>

                <div class="buttons">
                    <div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
                    <div class="right">
                        <input type="submit" value="<?php echo $button_continue; ?>" class="button" />
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>


<?php echo $footer; ?>