<?php echo $header; ?>
<?php echo $debaysellermenu; ?>
<div id="lista">
    <?php if(!$success){ ?>
    <form method="post" action="<?php  echo $action; ?>" >
         <select name="EndingReason" >
             <?php foreach($EndingReason as $code => $reason){ ?>
                 <option value="<?php echo $code; ?>" ><?php echo $reason; ?></option>
             <?php } ?>
         </select>
         <input type="hidden" value="<?php echo $ItemID; ?>" name="ItemID" />
         <input type="submit" value="Zakończ aukcję" />
    </form>
    <?php }else{ ?>
          Zakończono aukcję
    <?php } ?>

</div>

<?php echo $footer; ?>