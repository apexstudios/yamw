<?php
use Yamw\Lib\Meta;

set_slot('title', 'Space Units');
?>
<noscript><h1>For this page you need JavaScript enabled in order to benefit from all features!</h1></noscript>
<style>.body {padding: 0 !important;}</style>
<div id="aff_screen">
    <div id="aff_selector" class="panel_selector">
        <a href="units#" id="aff_tab_1" class="aff_selected selector aff_tab_1"><span>Covenant</span></a>
        <a href="units#" id="aff_tab_2" class="selector aff_tab_2"><span>Unsc</span></a>
        <a href="units#" id="aff_tab_3" class="selector aff_tab_3"><span>Flood</span></a>
    </div>

    <div id="aff_screen_panel">
        <div id="aff_screen_1" class="aff_screen units_screen startscreen">
                <div id="cov_screen">
                    <div id="cov_selector" class="panel_selector">
                        <?php
                        $i = 0; foreach ($this->covenant_list as $unit): $i++; ?>
                        <a href="units#" id="cov_tab_<?php echo $i; ?>" class="<?php
                        if ($i == 0) echo "cov_selected ";  ?>selector">
                        <span><?= $unit->getName() ?></span></a>
                        <?php endforeach; ?>
                    </div>

                    <div id="cov_screen_panel">
                        <?php
                        $i = 0; foreach ($this->covenant_list as $unit): $i++; ?>
                        <div id="cov_screen_<?= $i; ?>" class="cov_screen units_screen<?php
                        if ($i == 1) {echo " startscreen";} else {echo " hidden";}  ?>">
                            <div class="UnitsName"><h1><?= $unit->getName() ?></h1></div>
                            <div class="UnitsImage"><?php $unit->getImage() ?></div>
                            <div class="UnitsLayer"><h2><?= Meta::getInstance()
                            ->caw_units_space_layer_label['title'] ?></h2> <?= $unit->getLayer() ?></div>
                            <div class="UnitsDescription"><h2><?= Meta::getInstance()
                            ->caw_units_space_text_label['title'] ?></h2><?= $unit->getDescription() ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <br style="clear: both;" />
        </div>

        <div id="aff_screen_2" class="hidden aff_screen units_screen">
                <div id="unsc_screen">
                    <div id="unsc_selector" class="panel_selector">
                        <?php
                        $i = 0; foreach ($this->unsc_list as $unit):
                        $i++; ?>
                        <a href="units#" id="unsc_tab_<?= $i; ?>" class="<?php
                        if ($i == 1) {echo "unsc_selected ";} ?>selector"><span><?= $unit->getName() ?></span></a>
                        <?php endforeach; ?>
                    </div>

                    <div id="unsc_screen_panel">
                        <?php
                        $i = 0; foreach ($this->unsc_list as $unit):
                        $i++; ?>
                        <div id="unsc_screen_<?= $i; ?>" class="unsc_screen units_screen<?php
                        if ($i == 1) {echo " startscreen";} else {echo " hidden";}  ?>">
                            <div class="UnitsName"><h1><?= $unit->getName() ?></h1></div>
                            <div class="UnitsImage"><?php $unit->getImage() ?></div>
                            <div class="UnitsLayer"><h2><?= Meta::getInstance()
                            ->caw_units_space_layer_label['title'] ?></h2> <?= $unit->getLayer() ?></div>
                            <div class="UnitsDescription"><h2><?= Meta::getInstance()
                            ->caw_units_space_text_label['title'] ?></h2><?= $unit->getDescription() ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <br style="clear: both;" />
        </div>

        <div id="aff_screen_3" class="hidden aff_screen units_screen">
            <?= Meta::getInstance()->caw_units_space_flood_message['content'] ?>
        </div>
    </div>
</div>
<br style="clear: both;" />

<script>

var aff_last = 1;
var varEasterTimeout;

$('#aff_screen #aff_selector a').each(function (key, item) {
    $(this).click(function() {
        if($(this).hasClass('aff_selected')) return false;
        $('#aff_screen_panel #aff_screen_'+aff_last).hide( 'slide', {direction: 'right'}, 400, function(){
            if(aff_last==3) {
                $('#aff_screen_3 p').css( 'display', 'none');
            }
            $('#aff_screen_panel #aff_screen_'+(key+1)).show( 'slide', 400, function() {
                if(key==2) {
                    $('#flood_security').effect( 'pulsate', 200);
                    varEasterTimeout = setTimeout(function() {
                        $('#flood_security').slideUp(1200);
                        $('#flood_message').slideDown(1200, function() {
                            $('#flood_message').effect('pulsate', 200);
                            clearTimeout(varEasterTimeout);
                        });
                    }, 15000);
                }
            } );
        } );
        $('#aff_screen #aff_selector a').removeClass('aff_selected');
        $(this).addClass('aff_selected');
        aff_last = key+1;
        return false;
    });
});

$('#cov_screen #cov_selector a').each(function (key, item) {
    $(this).click(function() {
        if($(this).hasClass('cov_selected')) return false;
        $('#cov_screen_panel .cov_screen').css('display', 'none');
        $('#cov_screen_panel #cov_screen_'+(key+1)).fadeIn(400);
        $('#cov_screen #cov_selector a').removeClass('cov_selected');
        $(this).addClass('cov_selected');
        return false;
    });
});

$('#unsc_screen #unsc_selector a').each(function (key, item) {
    $(this).click(function() {
        if($(this).hasClass('unsc_selected')) return false;
        $('#unsc_screen_panel .unsc_screen').css('display', 'none');
        $('#unsc_screen_panel #unsc_screen_'+(key+1)).fadeIn(400);
        $('#unsc_screen #unsc_selector a').removeClass('unsc_selected');
        $(this).addClass('unsc_selected');
        return false;
    });
});

</script>
