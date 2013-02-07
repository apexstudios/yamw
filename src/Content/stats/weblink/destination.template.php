<h1>Quo vadis?</h1>
<h2>Web-Links (reverse)</h2>
<p>Time to generate this data: <?= $weblink_mr['timeMillis']/1000 ?>s<br />
Number of processed records: <?= $weblink_mr['counts']['input'] ?> records<br />
Number of generated records: <?= $weblink_mr['counts']['output'] ?> records<br />
Number of loaded records: <?= $weblink->count() ?> records</p>

<ul>
<?php foreach ($weblink as $page) { ?><li><?php echo $page['_id'] ?>
<ul>
<?php
$c = 0;
if (isset($page['value']['goto'][0])) {
foreach ($page['value']['goto'] as $ref) {
    $c += $ref['count'];
    ?>
    <li><?= ($ref['count'] ? $ref['count'] : $page['value']['count']-$c).' * '.($ref['page'] ? $ref['page'] : '{entry}').' '.round(($ref['count'] ? $ref['count'] : $page['value']['count']-$c)/$page['value']['count']*100, 1).'%' ?></li>
    <?php }}
else {
?>
<li><?= ($page['value']['goto']['count'] ? $page['value']['goto']['count'] : $page['value']['goto']['value']['count']-$c).' * '.($page['value']['goto']['page'] ? $page['value']['goto']['page'] : '{entry}').' '.round(($page['value']['goto']['count'] ? $page['value']['goto']['count'] : $page['value']['goto']['value']['count']-$c)/$page['value']['count']*100, 1).'%' ?></li>
<?php
}
    ?></ul>
</li>
<?php } ?>
</ul>