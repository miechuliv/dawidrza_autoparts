<?php echo $header; ?>

    <?php if($success){ echo 'Uaktualniono baze danych!</br>'; } ?>

    <?php if($error){ echo 'Błąd: ' .$error.'</br>';} ?>

    Uaktualnij dane kategorii, wysyłki itp.

    <form method="post" action="<?php echo $action; ?>" >
        <input type="checkbox" name="country" value="on"/>Kody krajów
        <input type="checkbox" name="currency" value="on"/>Kody walut
        <input type="checkbox" name="dispatch" value="on"/>Czasy wysyłki
        <input type="checkbox" name="details" value="on"/>Rózne
        <input type="checkbox" name="carrier" value="on"/>Kurierzy
        <input type="checkbox" name="location" value="on"/>Lokalizacje
        <input type="checkbox" name="package" value="on"/>Paczki
        <input type="checkbox" name="service" value="on"/>Usługi
        <input type="checkbox" name="category" value="on"/>Kategorie
        <input type="submit" value="uaktualnij"/>
    </form>

<?php echo $footer; ?>