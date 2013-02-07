<?php
namespace Modules\User;

use \Yamw\Modules\RootController;
use \Yamw\Lib\Request;
use \Yamw\Lib\UAM\UAM;

class UserController extends RootController
{
    public function indexAction()
    {
        switch ($this->module) {
            case 'login':
                $this->loginAction();
                break;
            case 'logout':
                $this->logoutAction();
                break;
            default:
                forward404();
                break;
        }
    }

    public function loginAction()
    {
        Request::populateFromPost(array('name', 'pw', 'ajax'));

        if (Request::exists('post-name')) {
            UAM::getInstance()->logIn(
                Request::get('post-name'),
                Request::get('post-pw')
            );
            if (Request::get('post-ajax')) {
                UserBuilder::renderLoginForm(true);
            }
        } else {
            UserBuilder::renderLoginForm(true);
        }
        UserBuilder::build();
    }

    public function logoutAction()
    {
        UAM::getInstance()->logout();
        UserBuilder::logoutMessage();
    }
}
