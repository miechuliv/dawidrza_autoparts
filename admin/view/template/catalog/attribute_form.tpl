<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?>
        <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>  <div class="box">
        <div class="heading">
            <h1><span class="fa fa-cogs"></span> <?php echo $heading_title; ?></h1>
            <div class="buttons"><a onclick="$('#form').submit();" class="button">
                    <?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
        </div>
        <div class="content">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="form">
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_name; ?></td>
                        <td><?php foreach ($languages as $language) { ?>
                            <input type="text" name="attribute_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($attribute_description[$language['language_id']]) ? $attribute_description[$language['language_id']]['name'] : ''; ?>" />
                            <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                            <br />
                            <?php if (isset($error_name[$language['language_id']])) { ?>
                            <span class="error"><?php echo $error_name[$language['language_id']]; ?></span><br />
                            <?php } ?>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_attribute_group; ?></td>
                        <td><select name="attribute_group_id">
                                <?php foreach ($attribute_groups as $attribute_group) { ?>
                                <?php if ($attribute_group['attribute_group_id'] == $attribute_group_id) { ?>
                                <option value="<?php echo $attribute_group['attribute_group_id']; ?>" selected="selected"><?php echo $attribute_group['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $attribute_group['attribute_group_id']; ?>"><?php echo $attribute_group['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr >
                        <td><?php echo $this->language->get('entry_front'); ?></td>
                        <td><select name="front">
                                <?php if ($front) { ?>
                                <option value="1" selected="selected"><?php echo $this->language->get('text_enabled'); ?></option>
                                <option value="0"><?php echo $this->language->get('text_disabled'); ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $this->language->get('text_enabled'); ?></option>
                                <option value="0" selected="selected"><?php echo $this->language->get('text_disabled'); ?></option>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_sort_order; ?></td>
                        <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<?php echo $footer; ?>