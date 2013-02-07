<?php
namespace Modules\Files;

use \Yamw\Modules\RootController;
use \Yamw\Lib\Request;
use \Yamw\Lib\Mongo\Gallery;
use \Yamw\Lib\Mongo\AdvMongo;

class FilesController extends RootController
{
    private $file;
    private $file_ext;
    private $id;
    private $buffersize = 32768;

    public function indexAction()
    {
        statGroup('media-'.$this->module);
        noTemplate();

        forward404Unless(Request::exists('id'));
        $this->id = Request::get('id');

        switch ($this->module) {
            case 'gallery':
                $this->galleryAction();
                break;
            case 'thumbs':
                $this->thumbsAction();
                break;
            case 'media':
                $this->mediaAction();
                break;
            default:
                forward404();
                break;
        }

        if (!$this->file) {
            throw new \Yamw\Lib\Exceptions\HttpErrorException('No image had been found', 404);
        }

        $this->file_ext = $this->file->file['metadata']['type'];
        $this->sendMime();
        cache(720);
        lastModified($this->file->file['uploadDate']->sec, $this->file->file['md5']);
        $this->sendContent();
        $this->updateCounter();

        statGroup('media-'.$this->section);
    }

    public function galleryAction()
    {
        // Retrieve file
        $this->file = Gallery::getImage($this->id);
    }

    public function thumbsAction()
    {
        $this->size = Request::exists('type') ?
            Request::get('type') : TN_BIG_WIDTH;

        // Retrieve file
        $this->file = Gallery::getThumbnail($this->id, $this->size);
    }

    public function mediaAction()
    {
        // Retrieve file
        $this->file = AdvMongo::gridFs($this->module)
            ->findOne(array('filename' => $this->id));
    }

    protected function sendContent()
    {
        // We have to disable outbput buffering here
        // else we can run out of memory easily
        ob_end_flush();
        $stream = $this->file->getResource();
        while (!feof($stream)) {
            echo fread($stream, $this->buffersize);
        }
        ob_start();
    }

    public function sendMime()
    {
        contenttype(resolveContentType($this->file_ext), '');
    }

    public function updateCounter()
    {
        if (!($this->module == 'gallery' || $this->module == 'media')) {
            return;
        }

        // Update the Download counter
        AdvMongo::gridFs($this->module)->update(
            array('_id' => $this->id),
            array('$inc' => array('metadata.downloads' => 1)),
            array('w' => 0)
        );
    }
}
