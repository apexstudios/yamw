<?php
use Yamw\Lib\UAM\UAM;
?>
<table style="width: 100%;">
<tr>
    <th>Filename</td>
    <th>Preview</th>
    <th>Tasks</th>
</tr>
<?php
foreach($gallery as $file) {
    $entryId = $file->file['_id'];
    $entryName = $file->getFilename();
    $linkPath = 'files/gallery/index/' . $entryId;
    $entrySize = round($file->getSize()/1024/1024, 3);
    $authorName = UAM::getInstance()->Users()->getUserNameById($file->file['metadata']['uploaded_by']['id']);
    $entryDate = date(DATE_ANH_NHAN, $file->file['uploadDate']->sec);
    $numDownloads = $file->file['metadata']['downloads'];
    ?>
    <tr>
        <td>
            <div style="padding: 12px;">
                <div style="float: right;">
                    <?= $entryDate ?>
                </div>
                <div style="font-weight: bold; font-size: 1.2em;">
                    <a href="<?= $linkPath ?>"><?= $entryName ?></a>
                </div>
                <div style="float: right;">
                    <?= $authorName ?>
                </div>
                <div>
                    <?= $entrySize ?>MB - <?= $numDownloads ?> downloads
                </div>
            </div>
        </td>
        <td><img src="files/thumbs/index/<?= $entryId ?>/150" /></td>
        <td>
            <div class="submitbutton" onclick="reissueThumbnails('<?= $file->file['_id'] ?>', 'default');">Regenerate</div>
            <div class="submitbutton" onclick="edit(this);">Edit</div>
        </td>
    </tr>
<?php } ?>
</table>

<div id="editArea"></div>

<script>

function edit(obj) {
    $('#editArea').load({url: 'admin/files/edit/'+obj.parent().parent().find('a').src.replace('files\/gallery\/index\/', '')});
}

</script>
