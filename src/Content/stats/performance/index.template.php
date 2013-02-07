<h2>Average Statistics</h2>
<p>Time to generate this data: <?= $avgmem['timeMillis']/1000 ?>s<br />
Number of processed records: <?= $avgmem['counts']['input'] ?> records<br />
Number of generated records: <?= $avgmem['counts']['output'] ?> records<br />
Number of loaded records: <?= $stats->count() ?> records</p>
<?php  ?>
<table>
<tr>
<th>Page</th>
<th>Avg. Peak Mem.</th>
<th>Avg. Page Gen.time</th>
<th>Avg. Sql Queries</th>
<th>Percentage</th>
<th>Calls</th>
</tr>
<?php
foreach ($stats as $val) {
    ?>
    <tr>
        <td><?= $val['_id']?></td>
        <td><?= round($val['value']['avg_mem'], 3) ?>MB</td>
        <td><?= round($val['value']['avg_time'], 4) ?>s</td>
        <td><?= round($val['value']['avg_queries'], 1) ?></td>
        <td><?= round($val['value']['count']/$avgmem['counts']['input'], 2)*100 ?>%</td>
        <td><?= $val['value']['count'] ?></td>
    </tr>
    <?php
}
?>
</table>
