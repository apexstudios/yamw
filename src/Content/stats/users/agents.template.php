<h2>User Agents</h2>
<table>
    <tr>
        <th>User Agent</th>
        <th>Percentage</th>
        <th>Count</th>
    </tr>
    <?php
    for ($i = 0; isset($avgmem['retval'][$i]); $i++) { ?>
    <tr>
        <td><?= $avgmem['retval'][$i]['global_server.HTTP_USER_AGENT'] ?></td>
        <td><?= round($avgmem['retval'][$i]['count']/$avgmem['count'], 2)*100 ?>%</td>
        <td><?= $avgmem['retval'][$i]['count'] ?></td>
    </tr>
    <?php } ?>
</table>
