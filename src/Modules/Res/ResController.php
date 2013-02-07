<?php
namespace Modules\Res;

use Yamw\Lib\ResourceManagement\ResMgr;
use Yamw\Modules\RootController;

/**
 * Description of newPHPClass
 *
 * @author AnhNhan
 */
class ResController extends RootController
{
    public function indexAction()
    {
        // End the current output buffer and start a gzip one if supported
        /*ob_end_clean();
        if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
            ob_start("ob_gzhandler");
        } else {
            ob_start();
        }*/

        noTemplate();
        dontSaveStats();

        if ($this->module == 'js') {
            $contenttype = 'application/x-javascript';
        } elseif ($this->module == 'css') {
            $contenttype = 'text/css';
        }

        contenttype($contenttype);
        $content = "";

        if ($this->action) {
            $content = ResMgr::requestResource($this->action);
        }

        if (!$content) {
            $resource_list = array();
            switch ($this->module) {
                case 'css':
                    $resource_list = $this->getCssResources();
                    break;
                case 'js':
                    $resource_list = $this->getJsResources();
                    break;
                default:
                    throw new \RuntimeException("Nuthing special to find here!");
            }

            $compiler = ResMgr::compileAndSave($this->module);
            foreach ($resource_list as $value) {
                $compiler->pushResource($value);
            }
            $content = ResMgr::requestResource($compiler->compile());
        }
        // println($content['_id']);
        lastModified($content['timestamp']->sec, $content['_id']);
        if ($this->action) {
            cache(720);
        } else {
            cache(2);
        }
        echo $content['content'];
    }

    public function __call($name, $params)
    {
        $this->indexAction();
    }

    public function getCssResources()
    {
        return array(
            'css/less/main.less',
        );
    }

    public function getJsResources()
    {
        return array(
            'js/jquery-1.8.3.min.js',
            'js/jquery.effects.core.min.js',
            'js/jquery.effects.blind.min.js',
            'js/jquery.effects.bounce.min.js',
            'js/jquery.effects.drop.min.js',
            'js/jquery.effects.fade.min.js',
            'js/jquery.effects.highlight.min.js',
            'js/jquery.effects.pulsate.min.js',
            'js/jquery.extras.min.js',
            'js/mbExtruder.min.js',
            'js/jquery.easing.1.3.js',
            'js/jquery.blockUI.js',
            'js/jquery.notice.js',
        );
    }
}
