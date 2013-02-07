<?php
namespace Modules\Gallery;

use Yamw\Modules\RootController;
use Yamw\Lib\Exceptions\HttpErrorException;
use Yamw\Lib\Mongo\Gallery;
use Yamw\Lib\Request;

class GalleryController extends RootController
{
    public function indexAction()
    {
        try {
            $images = Gallery::getSection($this->section."|all");
            if ($images === null || !$images->count()) {
                throw new HttpErrorException('We\'re sorry, but no images had been found to display', 404);
            }
        } catch(\Exception $e) {
            throw $e;
        }

        set_slot('title', 'The '.ucwords($this->section).' Image Gallery');
        set_slot('menu', $this->section);

        $view = new \Custom\Views\Gallery\GalleryView;
        foreach ($images as $image) {
            $view->addImage($image);
        }
        echo $view;
    }
}
