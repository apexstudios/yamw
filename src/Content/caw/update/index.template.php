<?php
set_slot('title', 'Updates');
?>
<style>
.body {
    padding: 0 !important;
}
</style>
<div class="Updates">
<?php foreach ($this->updates as $update): ?>
    <div class="UpdateEntry">
        <div class="UpdateDate"><a href="caw/update/show/<?php echo $update->getId() ?>"><?= $update->getDateText()?></a></div>

        <div class="UpdateContent">
            <div class="UpdateDescription">
                <?= $update->getDescription() ?><br />
                <?php link_to('caw/update/show/'.$update->getId(), 'Read more...')?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
<p><a href="feeds/updates.rss">Follow us on our RSS Feed!</a></p>
