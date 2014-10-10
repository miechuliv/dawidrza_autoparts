<?php echo $header; ?>

<div id="content">

    <?php if(isset($error)){ ?>
      <h2><?php echo $error; ?></h2>
    <?php } ?>

    <?php if(isset($success)){ ?>
   <h2> Aukcja została pomyslnie wystawiona!</h2>
    <table>
        <tr>
            <td>Id aukcji</td>
			<td>Nazwa</td>
            <td>Opłaty</td>
			<td>Łącznie</td>
        </tr>
        <?php foreach($fees as $fee){ ?>

        <tr>
			<td><?php echo $auction_id; ?><td>
			<td><?php echo $fee['name']; ?></td>
            <td><?php echo $fee['amount']; ?></td>
        <?php } ?>

            <td><?php echo $total; ?></td>
        </tr>

    </table>


    <?php }elseif(isset($error)){ ?>

    <div><?php echo $error; ?></div>

    <?php }else{ ?>

      <h2>Wystaw przedmiot ponownie</h2>
      <form method="post" action="<?php echo  $action; ?>" >
          <label for="Quantity" >Ilość</label>
          <input type="text" value="1" name="Quantity" />
          <input type="hidden" value="<?php echo $item_id; ?>" name="item_id"/>
          <input type="submit" value="Wystaw ponownie" />
      </form>



    <?php } ?>

    <div class="button">
        <a href="<?php echo $return_action; ?>" >Powrót do panelu sprzedawcy</a>
    </div>
</div>


<?php echo $footer; ?>