<?php echo $header; ?>
<div id="content">
    <div class="box">
        <div class="heading">
            <h1><img src="view/image/setting.png" alt="" /> Konfiguracja allegro</h1>
            <div class="buttons"><a onclick="$('#form').submit();" class="button">zapisz</a><a href="<?php echo $cancel; ?>" class="button">Anuluj</a></div>
        </div>
        <div class="content">
            <form method="post" action="<?php echo $action; ?>" id="form"  >
                <table class="form">

                   <?php /* <tr>
                        <td><?php echo $entry_config_allegro_id; ?></td>
                        <td><input type="text" name="config_allegro_id"  value="<?php echo $config_allegro_id; ?>" /></td>
                    </tr> */ ?>
                    <tr>
                        <td><?php echo $entry_config_allegro_login; ?></td>
                        <td><input type="text" name="config_allegro_login" value="<?php echo $config_allegro_login; ?>" /></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_config_allegro_pass; ?></td>
                        <td><input type="text" name="config_allegro_pass" value="<?php echo $config_allegro_pass; ?>" /></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_config_allegro_webapi; ?></td>
                        <td><input type="text" name="config_allegro_webapi" value="<?php echo $config_allegro_webapi; ?>" /></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<?php echo $footer; ?>