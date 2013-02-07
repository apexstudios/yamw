<style>
.body {
    padding: 0 !important;
}
</style>
<?php
foreach($this->update as $update):
set_slot('title', 'Update of the '.$update->getDate());
?>
<div class="Update">
    <div class="UpdateDate"><?php echo $update->getDateText() ?></div>

    <div style="text-align: center; width: 100%;" class="UpdateContent">
        <div class="UpdateText"><?php echo $update->getText() ?></div>
    </div>
</div>
<p style="margin-top: 2em;"><a href="feeds/updates.rss">Follow us on our RSS Feed!</a></p>
<?php
endforeach;
?>