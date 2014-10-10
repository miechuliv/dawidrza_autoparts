<div class="box mobilehide" id="mobilekategorie">
    <ul class="box-category <?php if(array_key_exists('route',$this->request->get)) { ?> normal <?php } ?>">
      <?php foreach ($categories as $category) { ?>
      <li <?php if ($category['category_id'] == $category_id) { ?>class="active"<?php } ?>>

        <a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>

        <?php if ($category['children']) { ?>
        <ul>
          <?php foreach ($category['children'] as $child) { ?>
          <li>
            <?php if ($child['category_id'] == $child_id) { ?>
            <a href="<?php echo $child['href']; ?>" class="active"><?php echo $child['name']; ?></a>
            <?php } else { ?>
            <a href="<?php echo $child['href']; ?>" ><?php echo $child['name']; ?></a>
            <?php } ?>
          </li>
          <?php } ?>
        </ul>
        <?php } ?>
      </li>
      <?php } ?>
    </ul>
</div>

<script>
    $('.option_left').click(function() {
        document.forms['miechu_form'].submit();
    });

    function clearSearch(){
        $('#searchClear').val('true');
        document.forms['miechu_form'].submit();
    };
</script>