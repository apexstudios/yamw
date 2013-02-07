<?php dump_var($weblink_mr) ?><ul>
<table>
<?php
foreach ($weblink as $user) {
    ?>
    <tr>
        <td><?= $user['_id'] ?></td>
    </tr>
            <?php foreach ($user['value'] as $t) {
    foreach ($t as $z) { ?>
        <tr>
        <td>&nbsp;&nbsp;&nbsp;</td>
            <?php $time = $z['time']; echo '<td>'.$z['page'].'</td><td> - </td><td>'.date(DATE_ANH_NHAN, $time->sec).'</td>' ?>
        </tr>
            <?php }
} ?>
<?php
}
?>
</table>