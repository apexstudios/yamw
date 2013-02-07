<div class="Update">
    <div class="UpdateDate"><?php echo $_POST['date'] ?></div>
    <blockquote style="margin: 0; margin-left: 1em;">Comments: 0<br /><br /></blockquote>

    <div style="text-align: center; width: 100%;"><div class="UpdateText">
    <?php
    echo BBCode2HTML($_POST['text']);
    ?></div></div>
</div><?php
