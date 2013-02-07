<?php
namespace Yamw\Models;

use \Yamw\Lib\UAM\UAM;

class Blog extends IModel
{
    protected $table = 'mybb_posts';
    protected $id_column = 'pid';

    public function getTitle()
    {
        return $this->Get('subject');
    }

    public function getSlug()
    {
        return false;
    }

    public function getAuthor()
    {
        return $this->Get('username');
    }

    public function getAuthorId()
    {
        return $this->Get('uid');
    }

    public function getDate()
    {
        $date = $this->Get('edittime') ? $this->Get('edittime') : $this->Get('dateline');
        return (is_numeric($date)) ? date(DATE_ANH_NHAN, $date) : $date;
    }

    public function getText()
    {
        // Cached text does not exist or is not up2date
        if (
            !$this->Get('cached_content') ||
            (($this->Get('edittime') ? $this->Get('edittime') :
                $this->Get('dateline')) > ($this->Get('last_modified')))
        ) {
            $this->ForumCache($this->Get('message'));
        }
        
        return $this->Get('cached_content');
    }

    public function getRawText()
    {
        return $this->Get('message');
    }

    public function getPreviewText($length = 1600)
    {
        $ret = $this->getText();
        $ret = preg_replace('/<(?:(?!br|img|a|object|param).).*?>/si', '', $ret);

        useHelper('sf/Text');
        $ret = trim(
            truncate_text(
                $ret,
                $length,
                '&hellip;<br /><a href="blog/show/'.$this->getId().'">Read more&hellip;</a>',
                true
            )
        );

        return $ret;
    }
}
