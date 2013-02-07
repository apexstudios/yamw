<?php
set_slot('title', 'CaW Media');
$vid = $vid->getNext();
?>

<style>
.body {padding: 0}
</style>

<video id="vid" src="<?php echo getAbsPath() . "files/media/index/" . urldecode($vid->getFilename()) ?>"
 width="900" height="506" autobuffer controls preload="auto" poster="img/bgs/UNSC_attacks_by_AnhNhan_size.png">
<p>If you are reading this, it is because your browser does not support the HTML5 video element.</p>
</video>
