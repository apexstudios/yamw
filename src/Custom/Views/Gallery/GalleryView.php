<?php
namespace Custom\Views\Gallery;

use Yamw\Lib\Builders\HtmlFactory;
use Yamw\Lib\Builders\Markup\MarkupContainer;
use Yamw\Lib\Builders\Markup\HtmlTag;
use Yamw\Lib\UAM\UAM;

/**
 * Handles the View of the Gallery
 *
 * @author AnhNhan <anhnhan@outlook.com>
 * @package Yamw
 * @subpackage Views
 */
class GalleryView implements \Yamw\Views\Interfaces\ViewInterface
{
    private $isFirst = true;
    private $images = array();

    public function __toString()
    {
        $container = new MarkupContainer;

        $bigpic = HtmlFactory::divTag(
            HtmlFactory::divTag($this->buildImages()
        )->setId('PictureWide'))->setId("BigPicture");

        $galpicctrl = HtmlFactory::divTag()
            ->setId('GalleryPicControl')
            ->appendContent(
                HtmlFactory::spanTag('(&lt;)')
                    ->addOption('onclick', "left();")
            )
            ->appendContent(
                HtmlFactory::spanTag('(HD)')
                    ->addOption('onclick', "enlarge();")
            )
            ->appendContent(
                HtmlFactory::spanTag('(&hearts;)')
                    ->addOption('onclick', "fav();")
            )
            ->appendContent(
                HtmlFactory::spanTag('(&gt;)')
                    ->addOption('onclick', "right();")
            );

        $container->push(
            HtmlFactory::divTag($bigpic)->setId('GalleryPic')
                ->appendContent($galpicctrl)
        );

        $thumbs = HtmlFactory::divTag()->setId("GalleryThumbs")
            ->appendContent(
                HtmlFactory::divTag()->setId('up1')
                    ->setContent(HtmlFactory::spanTag("(up)"))
            )->appendContent(
                $this->buildThumbnails()
            )->appendContent(
                HtmlFactory::divTag()->setId('down1')
                    ->setContent(HtmlFactory::spanTag("(down)"))
            );

        $container->push($thumbs);

        $firstPic = $this->images[0];

        $details = HtmlFactory::divTag()->setId('ImageDetails')
            ->appendContent(
                HtmlFactory::divTag('Favs: ')
                    ->addOption('style', 'float: right;')
                    ->appendContent($firstPic['favs'])
            )->appendContent(
                new HtmlTag('h1', $firstPic['title'])
            )->appendContent(
                new HtmlTag('p', $firstPic['description'])
            );

        $comments = HtmlFactory::divTag()->setId('ImageComments')
            ->appendContent(
                HtmlFactory::divTag()->setId('PostComment')
                    ->appendContent(
                        new HtmlTag(
                            'textarea',
                            'Comment here',
                            array(
                                'rows' => 4,
                                'cols' => '30',
                                'name' => 'text',
                                'id' => 'commenttext'
                            )
                        )
                    )->appendContent(
                        new HtmlTag(
                            'input',
                            null,
                            array(
                                'type' => 'submit',
                                'id' => 'postcomment_submit',
                                'value' => 'Comment',
                                'class' => 'submitbutton',
                            )
                        )
                    )->appendContent(
                        new HtmlTag(
                            'span',
                            140,
                            array(
                                'class' => 'limit',
                            )
                        )
                    )
            )->appendContent(
                HtmlFactory::divTag($this->buildComments())
                    ->setId('PostComments')
            );

        $container->push(
            HtmlFactory::divTag()->setId('ImageExtra')
                ->appendContent($details)
                ->appendContent($comments)
        );

        $container->push($this->buildJs());

        return $container->__toString();
    }

    public function buildImages()
    {
        $markup = '';

        foreach ($this->images as $image) {
            $markup .= $this->buildImgTag(
                $image['_id'],
                TN_BIG_WIDTH
            );
        }

        return $markup;
    }

    public function buildThumbnails()
    {

        $markup = new MarkupContainer();

        foreach ($this->images as $image) {
            $markup->push(
                new HtmlTag(
                    'li',
                    new HtmlTag(
                        'a',
                        $this->buildImgTag(
                            $image['_id'],
                            TN_SMALL_WIDTH
                        ),
                        array('href' => 'files/gallery/index/'.$image['_id'])
                    )
                )
            );
        }

        return new HtmlTag('ul', $markup, array('id' => 'thumbs'));
    }

    public function buildComments()
    {
        $comments = & $this->images[0]['comments'];
        if (count($comments)) {
            $container = new MarkupContainer();

            foreach (array_reverse($comments) as $comment) {
                $time = new HtmlTag(
                    'div',
                    getTimeLabel($comment['time']->sec),
                    array('class' => 'ImageCommentTime')
                );

                $author_span = new HtmlTag(
                    'span',
                    UAM::getInstance()->Users()
                        ->getUserNameById($comment['author'])
                );
                $author = new HtmlTag(
                    'div',
                    $author_span,
                    array('class' => 'ImageCommentAuthor')
                );

                $text = new HtmlTag(
                    'div',
                    $comment['text'],
                    array('class' => 'ImageCommentContent')
                );

                $container->push(
                    new HtmlTag(
                        'div',
                        $time.$author.$text,
                        array('class' => 'ImageComment')
                    )
                );
            }

            return $container->__toString();
        } else {
            return "<p>Sorry, there are no comments for this image.</p>";
        }
    }

    protected function buildImgTag($id, $size)
    {
        return HtmlFactory::imgTag(
            "files/thumbs/index/$id/$size",
            $size,
            (int)($size / 1.618)
        )->addOption('oncontextmenu', "return false;");
    }

    public function addImage($image)
    {
        $object = array(
            '_id' => $image->file['_id']
        );

        if ($this->isFirst) {
            $object['favs'] = $image->file['metadata']['favs']['count'];

            $object['title'] = !empty($image->file['metadata']['title']) ?
            $image->file['metadata']['title'] : $image->getFilename();

            $object['description'] = !empty($image->file['metadata']['description']) ?
            $image->file['metadata']['description'] : 'No Description';

            $object['comments'] = $image->file['metadata']['comments'];

            $this->isFirst = false;
        }

        $this->images[] = $object;
    }

    public function addElement($name, $element)
    {
        return "Not used.";
    }

    public function buildJs()
    {
        return new \Yamw\Lib\Builders\Markup\HtmlTag(
            'script',
            "var current_page = 0;
var current_page_horz = 0;
var pagelength = 18;
var pagelength_horz = 1;
var size = 92;
var size_horz = 900;

var button_up = $('#up1');
var button_down = $('#down1');
var button_left;
var button_right;

function down() {
    if(current_page > ($('#thumbs li').length / pagelength)-1) {
        button_up.addClass('activeUpDown');
        button_down.removeClass('activeUpDown');
        return false;
    }

    $('#thumbs li').animate({bottom: '+=' + (pagelength/3*size)});
    button_down.addClass('activeUpDown');
    current_page++;
    $('#thumbs li:eq('+(current_page*pagelength)+') a').click();

    if(current_page > ($('#thumbs li').length / pagelength)-1) {
        button_up.addClass('activeUpDown');
        button_down.removeClass('activeUpDown');
        return false;
    }
}

function up() {
    if(current_page <= 0) {
        button_down.addClass('activeUpDown');
        button_up.removeClass('activeUpDown');
        return false;
    }

    $('#thumbs li').animate({bottom: '-=' + (pagelength/3*size)});
    button_up.addClass('activeUpDown');
    current_page--;
    $('#thumbs li:eq('+(current_page*pagelength)+') a').click();

    if(current_page <= 0) {
        button_down.addClass('activeUpDown');
        button_up.removeClass('activeUpDown');
        return false;
    }
}

function left() {
    moveTo(current_page_horz - 1);
}

function right() {
    moveTo(current_page_horz + 1);
}

/**
 * Zero-based index \$i
 */
function moveTo(i) {
    if (i < 0) {
        // Too far left, go to the right-most
        moveTo($('#PictureWide img').length - 1);
        return;
    }

    if (i >= $('#PictureWide img').length) {
        // Too far right, go to the left-most
        moveTo(0);
        return;
    }

    if (current_page_horz > i) {
        // We have to go left
        diff = current_page_horz - i;
        dir = '+';
    } else {
        // We are going right
        diff = i - current_page_horz;
        dir = '-';
    }

    size = pagelength_horz * size_horz;
    time = 600 * diff * Math.pow(0.93, diff);

    $('#PictureWide').animate(
        {left: dir+'=' + size * diff},
        {
            duration: time,
            complete: function () {
                $('#thumbs li').removeClass('activeSlide');
                $('#thumbs li').eq(i).addClass('activeSlide');
                loadDetails();
            },
            easing: 'easeInOutQuart'
        }
    );
    current_page_horz = i;

}

function loadDetails() {
    var filename = $('#thumbs li.activeSlide a').attr('href').replace('files/gallery/index/', '');

    $.ajax({
        url: 'api/details/retrieve/'+filename+'/gallery',
        dataType: 'json',
        error: function (x,e) {ajax_error_handling(x,e);},
        success: function(data) {
            // Update comments
            $('#PostComments').hide().empty();
            $.each(data.comments, function (k, v) {
                insertComment(v.author, v.text, v.time);
            });

            if (data.comments.length == 0) {
                $('#PostComments').html('<p>Sorry, there are no comments for this image.</p>');
            }

            $('#PostComments').fadeIn();

            // Update some details
            $('#ImageDetails').fadeOut(function () {
                $('#ImageDetails > div').empty().html('Favs: ' + data.favs);
                $('#ImageDetails > h1').empty().html(data.title ? data.title : data.filename);
                $('#ImageDetails > p').empty().html(data.description ? data.description : 'No Description');
                $('#ImageDetails').fadeIn();
            });
        }
    });
}

$(document).ready(function () {
    $('#thumbs').css('overflow', 'hidden').animate({height: '552px'}, 500, function () {
        $('#up1, #down1').slideDown(50);
        $('#thumbs').animate({bottom: '30px'}, 50);
    });

    // Register the click-event
    $('#thumbs li').click(function () {
        var index = $(this).index();
        if (index != current_page_horz) {
            moveTo(index);
        }
        return false;
    });

    $('#thumbs li:eq(0)').addClass('activeSlide');
});

/**
 * Inserts a comment to the current selected picture
 */
$('#postcomment_submit').click(function () {
    var text = $('#commenttext').val();

    if(text.length < 1 || text == 'Comment here') {
        $.noticeAdd({text: 'Please type in your comment!', type: 'error'});
         return false;
    }

    if(text.length > 140) {
        $.noticeAdd({text: 'Your comment is too long!', type: 'error'});
        return false;
    }

    $('#commenttext').empty().keyup();

    var filename = $('#thumbs li.activeSlide a').attr('href')
            .replace('files/gallery/index/', '');

    $.ajax({
        type: 'POST',
        data: 'text='+text,
        dataType: 'json',
        url: '{ROOT}api/comments/add/'+filename+'/gallery',
        error: function (x,e) {ajax_error_handling(x,e);},
        success: function(d) {

            if (d.result === 'ok') {
                $.noticeAdd({text: 'Comment successfully added! Thanks for the feedback!', type: 'success'});
                insertComment(cur_uname, text, 'Just right now');
            } else if (d.result === 'login') {
                $.noticeAdd({text: 'We\'re sorry, but you have to log in first before you comment this image!', type: 'error'});
            }
        }
    });
});

/**
 * Inserts comment markup
 */
function insertComment(name, text, time) {
    insert = '<div class=\"ImageComment\"><div class=\"ImageCommentTime\">'
        +time+'</div><div class=\"ImageCommentAuthor\">';
    insert += '<span>' + name + '</span>';
    insert += '</div><div class=\"ImageCommentContent\"><p>';
    insert += text;
    insert += '</p></div></div>';

    $('#PostComments').prepend(insert);
}

// Put a lightbox here
var wi;

function enlarge() {
    wi = window.open('{ROOT}'+$('#thumbs li.activeSlide a').attr('href'),
            'largeWindow', 'width=900,height=506,status=no,scrollbars=yes,resizable=yes');
    wi.focus();
}

/**
 * Adds a fav to the current selected picture
 */
function fav() {
    var filename = $('#thumbs li.activeSlide a').attr('href').replace('files\/gallery\/index\/', '');

    $.ajax({
        type: 'GET',
        url: '{ROOT}api/fav/add/'+filename+'/gallery',
        dataType: 'json',
        error: function (x,e) {ajax_error_handling(x,e);},
        success: function(d) {
            if (d.result === 'ok') {
                $.noticeAdd({text: 'Fav successfully added! We love that!', type: 'success'});
            } else if (d.result === 'exist') {
                $.noticeAdd({text: 'We\'re sorry, but you have already faved it!', type: 'error'});
            } else if (d.result === 'login') {
                $.noticeAdd({text: 'We\'re sorry, but you have to log in first before you fav!', type: 'error'});
            }

            $('#ImageDetails > div').fadeOut(function () {
                $('#ImageDetails > div').empty().html('Favs: ' + d.count);
                $('#ImageDetails > div').fadeIn();
            });
        }
    });
}

/**
 * This function is responsible for updating the text size & limit
 */
$('#commenttext').keyup(function(){
    var limit = 140;
    var currentLength = $(this).val().length;
    var charsLeft = limit - currentLength;
    $('span.limit').html(charsLeft);

    if(currentLength >= limit){
        $(this).val($(this).val().substring(0, limit));
        $('span.limit').html('0');
        var textarea = document.getElementById('commenttext');
        textarea.scrollTop = textarea.scrollHeight + 9999;
    }
});"
        );
    }
}
