<?php
set_slot('title', 'Admin Panel');
?>
<style>
.body {padding: 0 !important;}
</style>
<div>
    <!-- Ribbon Menu for each major section -->
    <div id="admin_screen">
        <div id="admin_selector" class="panel_selector">
            <a href="#" id="admin_tab_1" class="admin_selected selector admin_tab_1"><span>General</span></a>
            <a href="#" id="admin_tab_2" class="selector admin_tab_2"><span>CaW</span></a>
            <a href="#" id="admin_tab_3" class="selector admin_tab_3"><span>SotP</span></a>
            <a href="#" id="admin_tab_3" class="selector admin_tab_4"><span>HF</span></a>
            <a href="#" id="admin_tab_3" class="selector admin_tab_5"><span>Other</span></a>
        </div><br style="clear: both;" />

        <div id="admin_screen_panel">
            <!-- General tab - All things in here -->
            <div id="admin_screen_1" class="admin_screen units_screen startscreen">
                <div class="admin_nbsp"><span>&nbsp;</span></div>

                <div><a onclick="adAnim('admin/meta/nt')"><span class="fullmenu">Content Text</span></a></div>

                <div class="admin_nbsp"><span>&nbsp;</span></div>

                <div><a onclick="adAnim('admin/staff/nt')"><span class="fullmenu">Staff</span></a></div>

                <div class="admin_nbsp"><span>&nbsp;</span></div>

                <div><a onclick="adAnim('admin/menu/nt')"><span class="fullmenu">Menu Management</span></a></div>

                <div class="admin_nbsp"><span>&nbsp;</span></div>

                <div><a onclick="adAnim('admin/chat/select/nt')"><span class="fullmenu">Chat</span></a></div>

                <div class="admin_nbsp"><span>&nbsp;</span></div><br style="clear: both;" />
            </div>

            <!-- CaW Tab - Put CaW-related things in here (updates?, units ground/space, images/media) -->
            <div id="admin_screen_2" class="admin_screen units_screen hidden">
                <div class="admin_nbsp"><span>&nbsp;</span></div>

                <div><span>Units</span>
                    <ul>
                        <li><a onclick="adAnim('admin/units.caw.space/nt')"><span>Space</span></a></li>
                        <li class="admin_nbsp"><span>&nbsp;</span></li>
                        <li><a onclick="adAnim('admin/units.caw.ground/nt')"><span>Ground</span></a></li>
                    </ul>
                </div>

                <div class="admin_nbsp"><span>&nbsp;</span></div>

                <div>
                    <span>Media</span>
                    <ul>
                        <li><a onclick="adAnim('admin/gallery.caw/nt')"><span>Images</span></a></li>
                        <li class="admin_nbsp"><span>&nbsp;</span></li>
                        <li><a onclick="adAnim('admin/media.caw/nt')"><span>Media</span></a></li>
                    </ul>
                </div>

                <div class="admin_nbsp"><span>&nbsp;</span></div>

                <div><a onclick="adAnim('admin/updates/select/nt')"><span class="fullmenu">Update</span></a></div>

                <div class="admin_nbsp"><span>&nbsp;</span></div><br style="clear: both;" />
            </div>

            <!-- SotP tab -->
            <div id="admin_screen_3" class="admin_screen units_screen hidden">
                Panel 3<br style="clear: both;" />
            </div>

            <!-- Homefront tab -->
            <div id="admin_screen_4" class="admin_screen units_screen hidden">
                Panel 4<br style="clear: both;" />
            </div>

            <!-- Tab for other mods and related content -->
            <div id="admin_screen_5" class="admin_screen units_screen hidden">
                <div class="admin_nbsp"><span>&nbsp;</span></div>

                <div><a onclick="adAnim('admin/files/add/nt')"><span class="fullmenu">Upload</span></a></div>

                <div class="admin_nbsp"><span>&nbsp;</span></div>

                <div>
                    <span>Files</span>
                    <ul>
                        <li><a onclick="adAnim('admin/files/list/nt')"><span>List</span></a></li>
                        <li class="admin_nbsp"><span>&nbsp;</span></li>
                        <li><a onclick="adAnim('admin/files/add/nt')"><span>Upload</span></a></li>
                    </ul>
                </div>

                <div class="admin_nbsp"><span>&nbsp;</span></div><br style="clear: both;" />
            </div><br style="clear: both;" />
        </div>
    </div><br style="clear: both;" />

<div id="AdminPanel">
<div id="AdminContent">

<h1>Admin Panel</h1>
<h2>A little help from my side</h2>
<p>This, Captain Obvious, is the admin panel, from where you will be able to administrate big parts of {VERSION}.</p>

<p>Above you can see the menus to access the specific sections of the admin panel, each providing you with the tools required to fill this website with content. Feel free to discover the tools provided to you, but please submit every change with a sharp mind and maybe with a second thought, as currently every member who has administrative access to this panel can change content from other groups' sections. So please act with care and have respect for the work of others.</p>

<p>Once you have clicked on a menu point above, this text should disappear and a list or a table presenting the content entries you can change. Click on an entry from the list or table to start editing the entry itself. Feel free to write anything as you please (jk). Press submit to apply the changes, or on 'Back' (there should be such a line in red in the upper right corner once you are editing an entry) to discard the changes and return to the list.<br />
On some pages a green button will be available for you to add new entries to the specific content pages. This just works like editing existing entries.</p>

<p>Known Issues:
<ul>
<li>Files:
    <ul>
        <li>File name have to be unique across all sections within a target (left select group on the upload page)</li>
    </ul>
</li>
</ul>
</p>

<p>Some plans for the future:
<ul>
    <li>Statistics
        <ul>
            <li>Analytics presented in graphical form</li>
        </ul>
    </li>
</ul>
</p>

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