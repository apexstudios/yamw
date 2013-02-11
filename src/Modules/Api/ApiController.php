<?php
/**
 * This file is part of the Yamw Package
 */
namespace Modules\Api;

use Yamw\Lib\ImageProcesser;
use Yamw\Lib\Request;
use Yamw\Lib\Mongo\AdvMongo;
use Yamw\Lib\Mongo\Gallery;
use Yamw\Lib\MySql\AdvMySql;
use Yamw\Lib\UAM\UAM;
use Yamw\Modules\RootController;

/**
 *
 * @author AnhNhan <anhnhan@outlook.com>
 * @package Yamw
 * @subpackage Modules
 */
class ApiController extends RootController
{
    private $result = array();

    public function indexAction()
    {
        noTemplate();
        statGroup('api');
        $this->id = Request::get('id');
        forward404Unless(Request::exists('id') ^ ($this->module == 'chat' && $this->action == 'add'));

        switch ($this->module) {
            case 'fav':
                $this->fav();
                break;
            case 'details':
                $this->details();
                break;
            case 'thumbs':
                $this->thumbs();
                break;
            case 'comments':
                $this->comments();
                break;
            case 'chat':
                $this->chat();
                break;
            default:
                forward404();
                break;
        }

        $builder = new ApiBuilder();
        $builder->pushResult($this->result);
        $builder->outputMarkup();
    }

    public function __call($name, $params)
    {
        if (preg_match('/^[a-z]+Action$/i', $name)) {
            $this->indexAction();
        } else {
            throw new \RuntimeException("Action does not exist!");
        }
    }

    public function chat()
    {
        if ($this->action == 'add') {
            $this->chatAdd();
        } else {
            $this->chatRefresh();
        }
    }

    public function chatAdd()
    {
        useHelper('BBCode');
        useHelper('sf/Text');

        Request::populateFromPost(array('text'));

        forward404Unless(Request::get('post-text'));

        $text = trim(substr(Request::get('post-text'), 0, 140));
        $uid = UAM::getInstance()->getCurUserId();

        $result = AdvMySql::insertTable('chat')->insertData('time', time())
            ->insertData('name', $uid, 'string')
            ->insertData('text', auto_link_text(htmlspecialchars($text)), 'string')->execute();

        if ($result) {
            $msg = 'ok';
        } else {
            $msg = 'error';
        }

        $this->result = array("result" => $msg);
    }

    public function chatRefresh()
    {
        $query = AdvMySql::getTable('chat')->orderby('id', ASC)
            ->where('time', $this->id, '>')->setModel('None')->execute();

        $this->result = array();

        foreach ($query as $key => $val) {
            $this->result[$key]['time'] = getTimeLabel($val['time']);
            $this->result[$key]['author'] = UAM::getInstance()
                ->Users()->getUserNameById($val['name']);
            $this->result[$key]['text'] = $val['cached_text'] ?
                $val['cached_text'] : BBCode2HTML($val['text']);
        }

        $this->result = array(
            'last_chat_update' => time(),
            'entries' => $this->result
        );
    }

    public function comments()
    {
        if ($this->action == 'add') {
            $this->commentsAdd();
        } else {
            $this->commentsRetrieve();
        }
    }

    public function commentsAdd()
    {
        useHelper('sf/Text');
        Request::populateFromPost(array('text'));

        if (!Request::exists('post-text')) {
            throw new HttpErrorException('Missing Message!', 404);
        }

        $type = Request::get('type').'.files';
        $text = trim(substr(Request::get('post-text'), 0, 140));
        $uid = UAM::getInstance()->getCurUserId();

        if ($uid) {
            $result = AdvMongo::getConn()->$type->update(
                array(
                    '_id' => new \MongoId($this->id)
                ),
                array(
                    '$push' => array(
                        'metadata.comments' => array(
                            'author' => $uid,
                            'time' => new \MongoDate(time()),
                            'text' => auto_link_text(preg_replace('/<(.*?)>/', '&lt;$1&gt;', trim($text)))
                        )
                    )
                ));

            if ($result) {
                $msg = 'ok';
                getLogger()->addNotice('Comment added', array('uid' => $uid));
            } else {
                $msg = 'error';
            }
        } else {
            $msg = 'login';
        }

        $this->result = array("result" => $msg);
    }

    public function commentsRetrieve()
    {
        forward404Unless(Request::exists('type'));

        $type = Request::get('type').'.files';

        $this->result = AdvMongo::getConn()->$type->findOne(
            array(
                '_id' => new \MongoId($this->id)
            ),
            array(
                '_id' => false,
                'metadata.comments' => true
            )
        );

        $this->result = $this->result['metadata']['comments'];

        foreach ($this->result as $key => $val) {
            $this->result[$key]['author'] = UAM::getInstance()
                ->Users()->getUserNameById($val['author']);
            $this->result[$key]['time'] = getTimeLabel($val['time']->sec);
        }
    }

    public function details()
    {
        $this->detailsRetrieve();
    }

    public function detailsRetrieve()
    {
        forward404Unless(Request::exists('type'));

        $type = Request::get('type').'.files';

        $query = AdvMongo::getConn()->$type->find(
            array('_id' => new \MongoId($this->id)),
            array('_id' => false)
        );

        if (!$query->count()) {
            throw new \Yamw\Lib\Exceptions\HttpErrorException('nothing found', 404);
        }
        $query = $query->getNext();

        $meta = $query['metadata'];

        foreach ($meta['comments'] as $key => $val) {
            $meta['comments'][$key]['author'] = UAM::getInstance()
                ->Users()->getUserNameById($val['author']);
            $meta['comments'][$key]['time'] = getTimeLabel($val['time']->sec);
        }

        $this->result = array(
            'filename' => $query['filename'],
            'date_uploaded' => $meta['date_created']->sec,
            'date_edited' => $meta['date_edited']->sec,
            'md5' => $query['md5'],
            'size' => $query['length'],
            'type' => $meta['type'],
            'favs' => $meta['favs']['count'],
            'downloads' => $meta['downloads'],
            'section' => $meta['section'],
            'uploaded_by' => $meta['uploaded_by']['name'],
            'comments' => $meta['comments']
        );

        if (isset($meta['title'])) {
            $this->result['title'] = $meta['title'];
        }

        if (isset($meta['description'])) {
            $this->result['description'] = $meta['description'];
        }
    }

    public function fav()
    {
        $this->favAdd();
    }

    public function favAdd()
    {
        forward404Unless(Request::exists('type'));
        $type = Request::get('type').'.files';

        if (UAM::getInstance()->isLoggedIn()) {
            $uid = UAM::getInstance()->getCurUserId();

            $query = AdvMongo::getConn()->$type->find(array(
                '_id' => new \MongoId($this->id),
                'metadata.favs.by' => array('$in' => array($uid)))
            );

            $result = !$query->count() ? AdvMongo::getConn()->$type->update(
                array(
                    '_id' => new \MongoId($this->id)
                ),
                array(
                    '$push' => array(
                        'metadata.favs.by' => $uid
                    ),
                    '$inc' => array('metadata.favs.count' => 1)
                )) : null;

                    if ($result) {
                        getLogger()->addNotice('Fav added', array('uid' => $uid, 'file' => $this->id));
                        $msg = 'ok';
                    } elseif ($query->count()) {
                        $msg = 'exist';
                    } else {
                        $msg = 'error';
                    }
        } else {
            $msg = 'login';
        }

        $count = $query = AdvMongo::getConn()->$type
            ->find(array('_id' => new \MongoId($this->id)))->count();

        $this->result = array("result" => $msg, "count" => $count);
    }

    public function thumbs()
    {
        if ($this->action == 'delete') {
            $this->thumbsDelete();
        } else {
            $this->thumbsGenerate();
        }
    }

    public function thumbsDelete()
    {
        hasToBeAdmin();

        Gallery::deleteAllThumbnails($this->id);
        $this->result = array('result' => 'deleted');
        if ($this->result) {
            getLogger()->addInfo('Deleted thumbnails', array(
                'filename' => $this->id,
                'size' => 'all',
                'uid' => UAM::getInstance()->getCurUserId()
            ));
        }
    }

    public function thumbsGenerate()
    {
        hasToBeAdmin();
        forward404Unless(Request::exists('type'));

        useHelper('Image');

        $size = Request::get('type');
        if (is_numeric($size)) {
            $new_size = $size;
        } else {
            switch ($Request->Type) {
                case 'tiny':
                case 'small':
                    $new_size = TN_SMALL_WIDTH;
                    break;
                case 'medium':
                case 'default':
                default:
                    $new_size = TN_DEFAULT_WIDTH;
                    break;
                case 'big':
                case 'large':
                    $new_size = TN_BIG_WIDTH;
                    break;
            }
        }

        // Fetch options
        $opts = array();
        $opts['sharpen'] = $new_size != TN_BIG_WIDTH;

        // We assume he's talking about the gallery

        // First delete the old thumbnail
        $deleted = Gallery::deleteThumbnail($this->id, $new_size);

        // Generate the new thumbnail
        $obj = Gallery::getImage($this->id);
        $src_img = imagecreatefromstring($obj->getBytes());
        if (!$src_img) {
            throw new \Yamw\Lib\Exceptions\HttpErrorException('Image not found!', 404);
        }

        $old_x = imageSX($src_img);
        $old_y = imageSY($src_img);

        $img = gen($src_img, $old_x, $old_y, $new_size, (int)($new_size / 1.618));
        imagedestroy($src_img);
        // Resize END

        // Now apply any additonal stuff
        if ($opts['sharpen']) {
            $img = ImageProcesser::UnsharpMask($img);
        }

        // Save to DB
        $this->result = array();

        ob_start();
        imagejpeg($img, null, 100);
        if (Gallery::storeThumbnail($this->id, $new_size, ob_get_clean(), 'jpg')) {
            $this->result['result'] = 'ok';
            $this->result['filename'] = $obj->file['filename'];
            $this->result['new_size'] = $new_size;
        } else {
            $this->result['result'] = 'error';
            $this->result['filename'] = $obj->file['filename'];
            $this->result['new_size'] = $new_size;
        }

        if ($deleted) {
            getLogger()->addNotice('Deleted thumbnail', array(
                'filename' => $this->id,
                'size' => $new_size,
                'uid' => UAM::getInstance()->getCurUserId()
            ));
            $this->result['deleted'] = 'ok';
        } else {
            $this->result['deleted'] = 'no';
        }

        getLogger()->addNotice('Generated thumbnail', array(
            'filename' => $this->id,
            'size' => $new_size,
            'old_size' => $old_x,
            'time' => getTime(),
            'type' => 'jpg',
            'sharpen' => $opts['sharpen'],
            'uid' => UAM::getInstance()->getCurUserId()
        ));
    }
}
