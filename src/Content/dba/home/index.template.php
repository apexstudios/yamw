<?php
set_slot('title', 'YAMW Database Administration');
?>
<style>
.body {padding: 0 !important;}

ul li {
    list-style: none;
}
</style>

<div>
    <!-- Ribbon Menu for each major section -->
    <div id="admin_screen">
        <div id="admin_selector" class="panel_selector">
            <a href="#" id="admin_tab_1" class="admin_selected selector admin_tab_1"><span>General</span></a>
            <a href="#" id="admin_tab_2" class="selector admin_tab_2"><span>MongoDB</span></a>
            <a href="#" id="admin_tab_3" class="selector admin_tab_3"><span>MySql</span></a>
            <a href="#" id="admin_tab_3" class="selector admin_tab_4"><span>Ign.</span></a>
            <a href="#" id="admin_tab_3" class="selector admin_tab_5"><span>Ign.</span></a>
        </div><br style="clear: both;" />
        
        <div id="admin_screen_panel">
        
        <!-- General -->
            <div id="admin_screen_1" class="admin_screen units_screen startscreen">
                <div class="admin_nbsp"><span>&nbsp;</span></div>
                
                <div>
                    <span>Purge (N/A)</span>
                    <ul>
                        <li><a onclick="adAnim('dba/purge/mongo/nt')"><span>Mongo</span></a></li>
                        <li class="admin_nbsp"><span>&nbsp;</span></li>
                        <li><a onclick="adAnim('dba/purge/mysql/nt')"><span>MySql</span></a></li>
                    </ul>
                </div>
                
                <div class="admin_nbsp"><span>&nbsp;</span></div>
                
                <div><a onclick="adAnim('dba/stats/nt')"><span class="fullmenu">Statistics</span></a></div>
                
                <div class="admin_nbsp"><span>&nbsp;</span></div><br style="clear: both;" />
            </div>
            
            <!-- MongoDB -->
            <div id="admin_screen_2" class="admin_screen units_screen hidden">
                <div class="admin_nbsp"><span>&nbsp;</span></div>
                
                <div>
                    <span>Links</span>
                    <ul>
                        <li><a onclick="adAnim('stats/weblink/origin/nt')"><span>Origin</span></a></li>
                        <li class="admin_nbsp"><span>&nbsp;</span></li>
                        <li><a onclick="adAnim('stats/weblink/destination/nt')"><span>Destination</span></a></li>
                    </ul>
                </div>
                
                <div class="admin_nbsp"><span>&nbsp;</span></div>
                
                <div><a onclick="adAnim('dba/mongo.index/nt')"><span class="fullmenu">Index</span></a></div>
                
                <div class="admin_nbsp"><span>&nbsp;</span></div><br style="clear: both;" />
            </div>
            
            <!-- MySql -->
            <div id="admin_screen_3" class="admin_screen units_screen hidden">
                <div class="admin_nbsp"><span>&nbsp;</span></div>
                
                <div>
                    <span>Links</span>
                    <ul>
                        <li><a onclick="adAnim('stats/weblink/origin/nt')"><span>Origin</span></a></li>
                        <li class="admin_nbsp"><span>&nbsp;</span></li>
                        <li><a onclick="adAnim('stats/weblink/destination/nt')"><span>Destination</span></a></li>
                    </ul>
                </div>
                
                <div class="admin_nbsp"><span>&nbsp;</span></div>
                
                <div><a onclick="adAnim('stats/weblink/trace/nt')"><span class="fullmenu">Trace</span></a></div>
                
                <div class="admin_nbsp"><span>&nbsp;</span></div><br style="clear: both;" />
            </div>
            
            <div id="admin_screen_4" class="admin_screen units_screen hidden">
                Panel 4<br style="clear: both;" />
            </div>
            
            <div id="admin_screen_5" class="admin_screen units_screen hidden">
                <div class="admin_nbsp"><span>&nbsp;</span></div>
                
                <div><a onclick="adAnim('admin/staff/nt')"><span class="fullmenu">Staff</span></a></div>
                
                <div class="admin_nbsp"><span>&nbsp;</span></div><br style="clear: both;" />
            </div><br style="clear: both;" />
        </div>
    </div><br style="clear: both;" />
    
<div id="AdminPanel">

<h1>Database Administration</h1>

<div id="AdminContent">
<p>Content comes here. Works like the admin panel</p>
<h1 style="color: #FF0000">Warning!</h1>
<p>Only click things when you know what you're doing!</p>
</div>
<div id="AdminMsg"></div>
</div>

<script>

var admin_last = 1;

$('#admin_screen #admin_selector a').each(function (key, item) {
    $(this).click(function() {
        if($(this).hasClass('admin_selected')) return false;
        $('#admin_screen_panel #admin_screen_'+admin_last).hide( 'fade', {direction: 'right'}, 200, function(){
            if(admin_last==5) {
                $('#admin_screen_5 p').css( 'display', 'none');
            }
            $('#admin_screen_panel #admin_screen_'+(key+1)).show( 'fade', 200);
        } );
        $('#admin_screen #admin_selector a').removeClass('admin_selected');
        $(this).addClass('admin_selected');
        admin_last = key+1;
        return false;
    });
});

</script>
<br style="clear: both;" />
