// General Area

function statusMessage(data) {
    if($('#res').html()) {
        $('#res').slideUp(200, function() {
            $('#res').html(data);
            $('#res').slideDown(600);
        });
    } else {
        $('#res').html(data);
        $('#res').slideDown(600);
    }
}

function ajax_error_handling (x, e) {
                    if(x.status == 0)
                        $.noticeAdd({text: "You are offline! Please check your network!"
                        , type: "error"});
                    else if (x.status == 404)
                        $.noticeAdd({text: "Requested URL not found!", type: "error"});
                    else if (x.status == 403)
                        $.noticeAdd({text: "You did not have sufficient permission" +
                        " to do what you wanted...", type: "error"});
                    else if (x.status == 500)
                        $.noticeAdd({text: "Server-side error!", type: "error"});
                    else if (x.status == "parseerror")
                        $.noticeAdd({text: "Error parsing response from server!", type: "error"});
                    else if(x.status == "timeout")
                        $.noticeAdd({text: "Network timeout!", type: "error"});
                    else
                        $.noticeAdd({text: "Unknown Error! "+x.responseText, type: "error"});
                }

//Admin Area

/**
 * This function handles the loading for the Admin Panel, loading the sup-panels
 */
function adAnim(hi) {
    $('#AdminMsg').html('<font color="#3377ff">Loading...</font>').slideDown(400);
    $('#AdminContent').slideUp(400, function(){$('#AdminContent').load(hi, function () {$('#AdminContent').slideDown(400);$('#AdminMsg').slideUp(400);});});
}

function adLoad(target, container, area) {
    $('#AdminMsg').html('<font color="#3377ff">Loading...</font>').slideDown(400);
    $('#'+area).slideUp(800, function() {
        $('#'+area).load(absPath+target,
            function(a,b,c){
                $('#'+area).slideDown(800);
                $('#'+container).slideUp(800);
                $('#AdminMsg').slideUp(400);
            }
        );
    });
}

/**
 * Re-Issue thumbnails
 */
function reissueThumbnails(filename, sizes) {
    if(sizes == "default")
        sizes = [150, 900];

    $.noticeAdd({
        text: sizes.length + " thumbnail generation tasks have been" +
            " issued! You should get the notices within the next few minutes.",
        type: "info"
    });

    $.each(sizes, function (k, v) {
        $.ajax({
            url: 'api/thumbs/regenerate/'+filename+'/'+v,
            type: 'GET',
            data: 'sharpen=true',
            dataType: 'json',
            error: function (x,e) {ajax_error_handling(x,e);},
            success: function (data) {
                    if (data.result === 'ok') {
                        text = "Successfully generated thumbnail for <b>" +
                            data.filename + "</b> @ " + data.new_size;
                    } else {
                        text = "Error while generating thumbnail for <b>" +
                            data.filename + "</b> @ " + data.new_size + "; Please" +
                            " contact an admin!";
                    }

                    $.noticeAdd({text: text, type: data.result === 'ok' ? 'success' : 'error'});
                }
            });
    });
}


//Chat Area
function chatSend() {
    $.ajax({
        data: { name: $('#ChatName').val(), text: $('#ChatText').val() },
        url: 'api/chat/add',
        type: 'POST',
        dataType: 'json',
        error: function (x,e) {ajax_error_handling(x,e);},
        beforeSend: function() {
                if($('#ChatText').val() == '' || $('#ChatText').val() == 'Message') {
                    $('#ChatResponse').html('<font color="#FF8888">Please supply a message you want to send!</font>');
                    return false;
                }
                if($('#ChatText').val().length > 512) {
                    $('#ChatResponse').html('<font color="#FF8888">Your message is too long!</font>');
                    return false;
                }
            },
        success: function(a) {
            if (a.result === 'ok') {
                $.noticeAdd({text: "Your message was successfully sent!", type: "success"});
            } else {
                $.noticeAdd({text: "There was an error while sending your message!", type: "error"});
            }

            chatUpdate();
            resetChatForm();
        }
    });
}

function resetChatForm() {
    $('#ChatText').removeClass('clicked')
        .addClass('notClicked')
        .val('')
        .blur().keyup();
}

function chatUpdate() {
    $.ajax({
        type: "GET",
        url: "api/chat/refresh/"+last_chat_update,
        dataType: "json",
        success: function(data) {
                last_chat_update = data.last_chat_update;

                var i = 0;
                $.each(data.entries, function(k, v){
                        i++;
                        time = v.time;
                        author = v.author;
                        text = v.text;

                        insert = '<div id="'+last_chat_update+'_'+i+'" class="ChatEntry">';
                        insert += '<div class="ChatTime">'+time+'</div>';
                        insert += '<div class="ChatAuthor">'+author+'</div>';
                        insert += '<div class="ChatText">'+text+'</div>';
                        insert += '</div>';

                        $('#Chat').prepend(insert);
                        $('#'+last_chat_update+'_'+i + ', .flapLabel').effect('pulsate', 150);
                });
        }
    });
}
