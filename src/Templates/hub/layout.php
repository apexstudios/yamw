<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{TITLE} - .:Halo Mod Hub:.</title>
<base href="{ROOT}" />
<link rel="shortcut icon" type="image/x-icon" href="img/icons/favicon.ico" />
{META}
{STYLESHEETS}
{STYLES}
<script>
var absPath = '{ROOT}';
var cur_uid = '{CURUSER_ID}';
var cur_uname = '{CURUSER_NAME}';
</script>
</head>
<body>
    <div class="container" id="container">
    <div class="header" id="header">
        <div class="menu_top">
            {MENU_TOP}
        </div>
        <div id="header_img"><a href="{ROOT}"><img id="logo" src="img/logo3.png" alt="Halo: Covenant at War" /></a></div>
        <div class="menu" id="menu">
            {MENU_MAIN}
        </div>

        <div class="header_bottom">
            {MENU_USER}
    </div>
    </div>
{JS_FILES}
        <div class="body" id="body">
            <div id="res"></div>
            {CONTENT}
        </div>

        <div class="footer" id="footer">
            <p>Covenant at War, Sins of the Prophets and Homefront were created under Microsoft's <i>&ldquo;Game Content Usage Rules&rdquo;</i> using assets from Halo. Halo is &copy; Microsoft Corporation.</p>
            <p>NOTICE: These fan based modifications are in no way affiliated with Sci-Fi, Sky One, Universal Studios,
            Lucasarts or Petroglyph, Microsoft, Bungie. All trademarks, logos, sounds and videos are property of their
            respective owners.</p>
            <p>This page has been generated within {GENTIME} seconds with a total number of {NUM_QUERIES} querie(s)
            <br />Maximum Memory Usage: {PEAKMEM}MB</p>

            <p>This website is powered by {VERSION}, made by Anh Nhan Nguyen.</p>
            <p style="display: none;"><a href="http://apycom.com/">Apycom jQuery Menus</a></p>
        </div>
    </div>

    <div class="{title:'Chat'}" id="chat">
        <div class="content" id="content">{CHAT}</div>
    </div>
{JS_END}
{JS_NOTICES}
</body>
</html>
