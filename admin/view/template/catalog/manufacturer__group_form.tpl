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
    <div class="box">
        <div class="heading">
            <h1><img src="view/image/shipping.png" alt="" /> <?php echo $heading_title; ?></h1>
            <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
        </div>
        <div class="content">
            <div id="tabs" class="htabs"><a href="#tab-general"><?php echo $tab_general; ?></a></div>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <div id="tab-general">
                    <table class="form">
                        <tr>
                            <td><span class="required">*</span> <?php echo $entry_name; ?></td>
                            <td><input type="text" name="name" value="<?php echo $name; ?>" size="100" />
                                <?php if ($error_name) { ?>
                                <span class="error"><?php echo $error_name; ?></span>
                                <?php } ?></td>
                        </tr>

                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<?php echo $footer; ?>