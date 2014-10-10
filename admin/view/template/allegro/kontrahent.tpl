<?php echo $header; ?>

<?php echo $sellermenu; ?>

<?php if(isset($error_msg)){ ?>
<p>Wystąpił błąd:</p>
<p><?php echo $error_msg ?></p>
<?php }else{ ?>
<div class="item_list">
<h1>Sprawdź dane swojego kontrahenta</h1>
<?php if(!isset($results)){ ?>
<div>Wpisz nr aukcji aby wyświetlić informacje o sprzedawcy:<br/><br/>
<form action="<?php echo $form_action; ?>" method="post">
    <input type="text" name="auction_id" />
    <input type="submit" value="Sprawdz" />
</form>
</div>
<?php }else{ ?>
      <div>
          <table>
              <tr>
                  <td>
                      Nazwa użytkownika: <?php echo $results['dane']->{'user-login'}; ?>
                  </td>

              </tr>
              <tr>

                  <td>
                      <?php echo $results['dane']->{'user-first-name'}.' '.$results['dane']->{'user-last-name'}; ?>
                  </td>

              </tr>
              <tr>
                  <td>
                      <?php echo $results['dane']->{'user-city'}; ?>
                  </td>

              </tr>
              <tr>
                  <td>
                      <?php echo $results['dane']->{'user-postcode'}; ?>
                  </td>

              </tr>
              <tr>
                  <td>
                      <?php echo $results['dane']->{'user-address'}; ?>
                  </td>

              </tr>
              <tr>
                  <td>
                      <?php echo $results['dane']->{'user-email'}; ?>
                  </td>

              </tr>
              <tr>
                  <td>
                     <?php echo $results['dane']->{'user-phone'}.' '.$results['dane']->{'user-phone2'}; ?>
                  </td>
              </tr>

          </table>

      </div>
<?php } ?>
</div>

<?php } ?>





<?php echo $footer; ?>