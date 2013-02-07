<?php
use Yamw\Lib\UAM\UAM;

set_slot('title', 'Blog');
?>
<style>
.body {
    padding: 0!important;
}
</style>
<div class="BlogBox">
<div class="BlogHeader">Blog</div>
<div class="BlogEntries">
<?php foreach ($blog as $entry): ?>
<div class="BlogEntry">
    <div class="BlogMeta">
        <div class="BlogTitle">
            <?php link_to('caw/blog/show/'.$entry->getId(), $entry->getTitle()) ?>
        </div>
    </div>

    <div class="BlogContent">
        <div class="BlogAuthor">
            Written by <b><?php UAM::getInstance()->linkToUser($entry->getAuthorId()) ?></b> on <?= $entry->getDate() ?>
        </div>
        <?= $entry->getPreviewText() ?>
    </div>
</div>
<?php endforeach; ?>
</div>
</div>
