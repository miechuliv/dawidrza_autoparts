<?php echo $header; ?>
<div id="content" >
    <table class="startup"><tr><td>
                <?php echo $debaysellermenu; ?>
            </td>

            <td>
               <div class="message">
                     <div id="msg-info" >
                     <h2>Temat:    <?php echo $message['Subject']; ?></h2> <br/>
                      Od:   <?php echo $message['Sender']; ?> <br/>
                      Data otrzymania:    <?php echo $message['ReceiveDate']; ?>
                     </div>
                     <div id="msg-cont">
                         <?php echo $message['Text']; ?>
                     </div>
               </div>
            </td></tr></table>

</div>
<?php echo $footer; ?>