<?php
use Yamw\Lib\Core;
use Yamw\Lib\Meta;
?>
<style>
.body {
    padding: 0!important;
}
</style>
<div id="home_screen" class="HomePanel">
    <div id="screen_selector">
        <a href="#" id="tab_1" class="selected"><span><?= Meta::getInstance()->caw_home_box_panel1_desc['title'] ?></span></a>
        <a href="#" id="tab_2"><span><?= Meta::getInstance()->caw_home_box_panel2_desc['title'] ?></span></a>
        <a href="#" id="tab_3"><span><?= Meta::getInstance()->caw_home_box_panel3_desc['title'] ?></span></a>
        <a href="#" id="tab_4"><span><?= Meta::getInstance()->caw_home_box_panel4_desc['title'] ?></span></a>
    </div>

    <div id="screen_panel">
        <div id="screen_1" class="HomeText screen startscreen">
            <p><?= Meta::getInstance()->caw_home_box_panel1_desc['cached_content'] ?></p>
        </div>

        <div id="screen_2" class="hidden screen">
            <p><?= Meta::getInstance()->caw_home_box_panel2_desc['cached_content'] ?></p>
        </div>

        <div id="screen_3" class="hidden screen">
            <p><?= Meta::getInstance()->caw_home_box_panel3_desc['cached_content'] ?></p>
        </div>

        <div id="screen_4" class="hidden screen">
            <p><?= Meta::getInstance()->caw_home_box_panel4_desc['cached_content'] ?></p>
        </div>
    </div>
</div>
<span style="clear: both;"></span>

<script>

$('#home_screen #screen_selector a').each(function (key, item) {
    $(this).click(function() {
        if($(this).hasClass('selected')) return false;
        $('#screen_panel .screen').css('display', 'none');
        $('#screen_panel #screen_'+(key+1)).fadeIn(800);
        if (!$('#home_screen #screen_selector a').hasClass('auto_change'))
            clearInterval(panelRound);
        $('#home_screen #screen_selector a').removeClass('selected').removeClass('auto_change');
        $(this).addClass('selected');
        return false;
    });
});

var panelRound = setInterval( function () {
    var $next = $('#home_screen #screen_selector a.selected').next();
    if (!$next.length)
        $next = $('#home_screen #screen_selector a:first-child');
    $next.addClass('auto_change').click();
}, 15000);

</script>


<!-- Blog and About sections -->
<div id="HomeBox">
    <div id="HomeBox_left">
        <?= Core::getInstance()->getModule('blog') ?>
    </div>

    <div id="HomeBox_right">
        <?= Core::getInstance()->getModule('about') ?>
    </div>
<br style="clear: both;" />
</div>


<?php
set_slot('title', 'Home');
?>
