<div id="UpdateContainer" style="margin-bottom: 2em;">
<span onclick="NewUpdate()" class="NewButton">Add a new Weekly Update</span>

<h2>Select an update to edit</h2>
<?php
foreach($this->updates as $update):
?>
<div class="TabbedPanelsContent" id="Update<?php echo $update->getId() ?>"
    onclick="editUpdate(<?php echo $update->getId() ?>)">
        <h4><?php echo $update->getDate() ?></h4></div>
<?php
endforeach;
?>
</div>
<div id="EditUpdate"></div>

<script>
/* <![CDATA[ */
function editUpdate(id) {
    updateLoad('update/edit/' + id);
}

function NewUpdate() {
    updateLoad('update/new');
}

function updateLoad(target) {
    adLoad(target, 'UpdateContainer', 'EditUpdate');
}

function UnselectUpdate() {
    $('#EditUpdate').slideUp(800);
    $('#UpdateContainer').slideDown(800);
}

function previewUpdate() {
    $.ajax({
        data: {
            text: $('#text').html(),
            date: $('#date').val()
        },
        url: 'update/preview',
        type: 'POST',
        success: function(a) {
            statusMessage(a);
        }
    });
}
/* ]]> */
</script>