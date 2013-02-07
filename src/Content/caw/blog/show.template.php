<?php
use Yamw\Lib\UAM\UAM;

set_slot('title', $blog->getTitle());
?>
<style>
.body {
    padding: 0!important;
}
</style>
<div class="BlogBox" style="background: url(../img/bgs/transparent_black_40.png)">
<div class="BlogEntries">
<div class="BlogEntry">
    <div class="BlogMeta" style="width: 556.22px; padding-top: 0.75em; padding-bottom: 0.75em;">
        <div class="BlogTitle">
            <?= $blog->getTitle() ?>
        </div>
    </div>

    <div class="BlogContent">
        <div class="BlogAuthor">
            Written by <b><?php UAM::getInstance()->linkToUser($blog->getAuthorId()) ?></b> on the <?= $blog->getDate() ?>
        </div>
        <?= $blog->getText() ?>
    </div>
</div>
</div>
</div>
