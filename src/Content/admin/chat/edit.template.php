<form id="ChatForm" class="Form">
    <label>Author: <input type="text" name="author" id="author" disabled="disabled" value="<?= $chat->Name ?>" /></label>
    <label>Text: <input type="text" name="text" id="text" value="<?= $chat->RawText ?>" /></label>
    <input type="hidden" name="author_id" id="author_id" value="<?= $chat->Uid ?>" />
    <input type="submit" value="Submit" />
</form>

<script type="text/javascript">

$('#ChatForm').submit(function() {
        $.ajax({
            data: {    text: $('#text').val(),
                    author: $('#author_id').val() },
            url: absPath+'admin/chat/update/<?= $chat->Uid ?>',
            type: 'POST',
            success: function(a) {
                $('#res').html(a);
                $('#EditChat').slideUp('slow');
            }
        });
        
        return false;
});

</script>
