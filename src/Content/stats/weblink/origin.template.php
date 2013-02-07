<h1>Where do you come from?</h1>
<h2>Web-Links</h2>
<p>Time to generate this data: <?= $weblink_mr['timeMillis']/1000 ?>s<br />
Number of processed records: <?= $weblink_mr['counts']['input'] ?> records<br />
Number of generated records: <?= $weblink_mr['counts']['output'] ?> records<br />
Number of loaded records: <?= $weblink->count() ?> records</p>

<ul>
<?php foreach ($weblink as $page) { ?><li><?= $page['_id'] ?>
<ul>
<?php
$c = 0;
if (isset($page['value']['referer'][0])) {
foreach ($page['value']['referer'] as $ref) {
    $c += $ref['count'];
    ?>
    <li><?= ($ref['count'] ? $ref['count'] : $page['value']['count']-$c).' * '.($ref['page'] ? $ref['page'] : '{entry}').' '.round(($ref['count'] ? $ref['count'] : $page['value']['count']-$c)/$page['value']['count']*100, 1).'%' ?></li>
    <?php }}
else {
?>
<li><?= ($page['value']['referer']['count'] ? $page['value']['referer']['count'] : $page['value']['referer']['value']['count']-$c).' * '.($page['value']['referer']['page'] ? $page['value']['referer']['page'] : '{entry}').' '.round(($page['value']['referer']['count'] ? $page['value']['referer']['count'] : $page['value']['referer']['value']['count']-$c)/$page['value']['count']*100, 1).'%' ?></li>
<?php
}
    ?></ul>
</li>
<?php } ?>
</ul>