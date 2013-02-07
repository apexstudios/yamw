<?php
namespace Modules\Chat;

use \Yamw\Lib\Builders\Markup\MarkupContainer;
use \Yamw\Lib\Builders\Markup\HtmlTag;

class ChatBuilder
{
    public static function push($varcontent)
    {
        $container = new MarkupContainer();

        foreach ($varcontent as $value) {
            $chatentry = new HtmlTag('div', null, array('class' => 'ChatEntry'));
            $chatentry->appendContent(new HtmlTag('div', (string)$value->Time, array('class' => 'ChatTime')));
            $chatentry->appendContent(new HtmlTag('div', (string)$value->Name, array('class' => 'ChatAuthor')));
            $chatentry->appendContent(new HtmlTag('div', (string)$value->Text, array('class' => 'ChatText')));

            $container->push($chatentry);
        }

        // Generate the form
        $form_container = new MarkupContainer();

        $form_container->push(
            new HtmlTag('span', 'Name: <b>{CURUSER_NAME}</b><br />')
        );
        $form_container->push(
            new HtmlTag('input', null, array(
                'type' => 'text',
                'name' => 'text',
                'id' => 'ChatText',
                'class' => 'notClicked',
                'value' => 'Message'
                ))
        );
        $form_container->push(
            new HtmlTag('input', null, array(
                'type' => 'submit',
                'value' => 'Send',
                'class' => 'submitbutton'
                ))
        );
        $form_container->push(new HtmlTag('span', '140', array('id' => 'ChatText_limit')));

        $form_options = array(
            'id' => 'chatForm',
            'class' => 'Chat',
            'onsubmit' => 'chatSend(); return false;'
        );
        $form = new HtmlTag('form', $form_container, $form_options);

        // Add the final container
        $options = array('id' => 'Chat');
        $chat = new HtmlTag('div', $container, $options);

        $options = array('id' => 'ChatSupz', 'class' => 'hidden');
        $chatsupz = new HtmlTag('div', $chat, $options);
        $chatsupz->appendContent($form);
        echo $chatsupz;

        $chattimeout = \Yamw\Lib\Config::get('chat.autoupdate');
        $chatupdate = $chattimeout ?
            "var chatUpdateInterval = setInterval('chatUpdate()', {$chattimeout});" :
            null;
        $time = time();

        // Register Js
        use_partialJs("$('#ChatText').focus(function() {
    if($(this).hasClass('clicked')) {
        // Do nothing
    } else {
        $(this).removeClass('notClicked')
            .addClass('clicked')
            .val('');
    }
});

resetChatForm();

{$chatupdate}
var last_chat_update = {$time};

$('#ChatText').keyup(function(){
    var limit = 140;
    var currentLength = $(this).val().length;
    var charsLeft = limit - currentLength;
    $('span#ChatText_limit').html(charsLeft);

    if(currentLength >= limit){
        $(this).val($(this).val().substring(0, limit));
        $('span#ChatText_limit').html('0');
    }
});

$(document).ready(function () {
    $('#chat')
        .buildMbExtruder({
            position:'left',
            flapDim:140,
            sensibility:3000,
            width:270,
            extruderOpacity:1,
            textOrientation:'tb',
            slideTimer:500,
            onClose:function(){},
            onContentLoad:function(){}
        });

    $('#ChatSupz').fadeIn();
});");
    }
}
