<?php
set_slot('title', 'Meta Editing')
?>

<style>

table {
    margin: 0.5em;
    padding: 0;
    background: url("img/bgs/transparent_black_20.png");
}

table tr {
    border-bottom: 1px #CCCCCC solid;
    border-top: 1px #CCCCCC solid;
}

table tr:hover {
    background: url(img/bgs/transparent_white.png);
}

table td {
    border-left: 1px #AAAAAA solid;
    border-right: 1px #AAAAAA solid;
    border-collapse: collapse;
}

</style>

<div id="ListMeta">
<table>
    <tr><th>Id</th><th>Name</th><th>Description</th><th>Title (optional)</th><th>Content</th></tr>
<?php foreach($this->meta_list as $key => $meta): ?>
    <tr id="Meta<?php echo $meta->getId() ?>" onclick="selectMeta(<?php echo $meta->getId() ?>)"><td><?php echo $meta->getId() ?></td><td><?php echo $meta->getName() ?></td><td><?php echo $meta->getDescription() ?></td>
        <td><?php echo $meta->getTitle() ?></td><td><?php echo $meta->getContent() ?></td></tr>
<?php endforeach; ?>
</table>
</div>
<div id="EditMeta"></div>

<script type="text/javascript">

function selectMeta(id) {
    adLoad('admin/meta/edit/' + id, 'ListMeta', 'EditMeta');
}

function backButton() {
    $('#EditMeta').slideUp(800);
    $('#ListMeta').slideDown(800);
}

</script>
