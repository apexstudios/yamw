<?php
set_slot('title', 'About us');
?>
<div class="AboutBox">
<div class="AboutHeader">Staff</div>
<div class="AboutEntries">
<?php foreach ($this->about as $entry): ?>
    <div class="AboutEntry">
        <div class="AboutMeta">
            <div class="AboutName"><?= $entry->getName() ?></div>
        </div>
        <div class="AboutContent"><?php if ($entry->getImage(false)): ?>
            <div class="AboutImg"><?php $entry->getImage() ?></div><?php endif; ?>
            <div class="AboutPosition">Position: <?= $entry->getPosition() ?></div>
            <div class="AboutDesc">About him:<br />
            <?= $entry->getDesc() ?></div>
        </div>
    </div>
<?php endforeach; ?>
</div>
</div>
