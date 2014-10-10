<form action="<?=$action; ?>" method="post" id="payment">  
<input type="hidden" name="cartId" value="<?=$order_id;?>" />
</form>
  <div class="buttons">
    <table>
      <tr>  
        <td align="right"><a onclick="$('#payment').submit();" class="button"><span><?=$button_confirm; ?></span></a></td>
      </tr>
    </table>
  </div>
