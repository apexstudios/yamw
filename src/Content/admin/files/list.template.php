<?php
use Yamw\Lib\UAM\UAM;
?>
<table>
<tr>
    <th>Gallery</td>
</tr>
<tr>
    <th>Filename</td>
    <th>Size</td>
    <th>Uploaded By</td>
    <th>On</td>
    <th>Downloads</td>
    <th>Preview</th>
    <th>Tasks</th>
</tr>
<?php
foreach($gallery as $file) { ?>
    <tr>
        <td><a href="files/gallery/index/<?= $file->file['_id'] ?>"><?= $file->getFilename() ?></a></td>
        <td><?= round($file->getSize()/1024/1024, 3) ?>MB</td>
        <td><?= UAM::getInstance()->Users()->getUserNameById($file->file['metadata']['uploaded_by']['id']) ?></td>
        <td><?= date(DATE_ANH_NHAN, $file->file['uploadDate']->sec) ?></td>
        <td><?= $file->file['metadata']['downloads'] ?></td>
        <td><img src="files/thumbs/index/<?= $file->file['_id'] ?>/150" /></td>
        <td>
            <div class="submitbutton" onclick="reissueThumbnails('<?= $file->file['_id'] ?>', 'default');">Regenerate</div>
            <div class="submitbutton" onclick="edit(this);">Edit</div>
        </td>
    </tr>
<?php } ?>
<tr><td>&nbsp;</td></tr>
<tr>
    <th>Media</td>
</tr>
<tr>
    <th>Filename</td>
    <th>Size</td>
    <th>Uploaded By</td>
    <th>On</td>
    <th>Downloads</td>
</tr>
<?php
foreach($media as $file) { ?>
    <tr>
        <td><?= $file->getFilename() ?></td>
        <td><?= round($file->getSize()/1024/1024, 3) ?>MB</td>
        <td><?= UAM::getInstance()->Users()->getUserNameById($file->file['metadata']['uploaded_by']['id']) ?></td>
        <td><?= date(DATE_ANH_NHAN, $file->file['uploadDate']->sec) ?></td>
        <td><?= $file->file['metadata']['downloads'] ?></td>
    </tr>
<?php } ?>
</table>

<div id="editArea"></div>

<script>

function edit(obj) {
    $('#editArea').load({url: 'admin/files/edit/'+obj.parent().parent().find('a').src.replace('files\/gallery\/index\/', '')});
}

</script>
