<div id="ChatContainer" style="margin-bottom: 2em;">
<h2>Select a chat entry to edit it</h2>
<?php
foreach($this->chat as $chat):
?>
<div class="ChatDelete AdminDelete" id="deleteChat<?= $chat->Uid ?>" onclick="deleteChat(<?= $chat->Uid ?>)">Delete</div>
<div id="Chat<?php echo $chat->Id ?>" onclick="selectChat(<?= $chat->Uid ?>)">
    <h3><?= $chat->Text ?></h3><span style="font-size: 0.7em;"> by <b><?= $chat->Name ?></b></span>
</div>
<?php
endforeach;
?>
</div>
<div id="EditChat"></div>

<script type="text/javascript">
function selectChat(id) {
    $('#EditChat').slideUp(800, function() {
        $('#EditChat').load('admin/chat/edit/' + id,
            function(a,b,c){
                $('#EditChat').slideDown(800);
        });
    });
}

function deleteChat(id) {
    $('#res').load('admin/chat/delete', {chat_id: id});
    $('#Chat' + id).slideUp();
    $('#deleteChat' + id).slideUp();
}
</script>