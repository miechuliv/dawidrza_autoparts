<?php echo $header; ?>
<div id="content" >
<table class="startup"><tr><td>
            <?php echo $debaysellermenu; ?>
        </td>

        <td>
            <div id="lista" class="procent15">
                <table>
                    <thead>
                    <tr class="scalone">
                        <td>
                            Nadawca
                        </td>

                        <td>
                            Temat
                        </td>
                        <td>
                            Data otrzymania
                        </td>
                    </tr>
                    </thead>
                    <?php if($messages){ ?>
                    <?php foreach($messages as $message){ ?>

                    <tr onclick="document.location = '<?php echo $message["view"]; ?>';"  class="tr-link"  >

                        <td <?php if($message['Read']){ echo 'style="color:gray;"'; } ?>>
                            <?php  echo $message['Sender']; ?>

                        </td>

                        <td <?php if($message['Read']){ echo 'style="color:gray;"'; } ?>>
                            <?php  echo $message['Subject']; ?>
                        </td>
                        <td <?php if($message['Read']){ echo 'style="color:gray;"'; } ?>>
                            <?php  echo $message['ReceiveDate']; ?>
                        </td>

                    </tr>
                    <?php } ?>
                    <?php } ?>


                </table>
            </div>
        </td></tr></table>
<div class="pagination" style="padding-bottom: 6px; margin-top:10px;">
    <?php echo $pagination; ?>
</div>
</div>
<?php echo $footer; ?>