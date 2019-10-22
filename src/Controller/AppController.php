<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Controller\Component\AuthComponent;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Http\Exception\HttpException;
use Cake\Http\Response;
use Exception;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     * @throws Exception
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');

        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');

        $this->loadComponent('Auth', [
            'authenticate'   => [
                'Form' => [
                    'fields' => ['username' => 'username', 'password' => 'password'],
                    'finder' => 'auth'
                ]
            ],
            'loginRedirect'  => [
                'controller' => 'Dashboard',
                'action'     => 'index'
            ],
            'logoutRedirect' => [
                'controller' => 'Users',
                'action'     => 'login'
            ],
            'authError'      => __('You are not authorized to access that location without being logged in.')
        ]);
//        $this->loadComponent('Csrf');
    }

    public function beforeFilter(Event $event)
    {
        return parent::beforeFilter($event);
    }

    public function beforeRender(Event $event)
    {
        $this->viewBuilder()->setTheme('AdminLTE');
        $this->viewBuilder()->setClassName('AdminLTE.AdminLTE');

        $this->set('appUser', $this->getUser());
    }

    /**
     * Returns a json Response with tha data and meta data passed in
     *
     * @param  mixed  $data  Main data payload
     * @param  mixed|null  $meta  Additional data to include in payload
     * @param  int  $statusCode  Https response code to respond with
     *
     * @return Response
     */
    protected function jsonSuccessResponse($data, $meta = null, $statusCode = 200)
    {
        $this->viewBuilder()->setClassName('Json');

        return $this->response
            ->withStatus($statusCode)
            ->withType('application/json')
            ->withStringBody(json_encode(compact('data', 'meta')));
    }

    /**
     * @param  HttpException  $exception
     * @param  null  $meta
     *
     * @return Response
     */
    protected function jsonErrorResponse(HttpException $exception, $meta = null)
    {
        return $this->response
            ->withType('application/json')
            ->withStatus($exception->getCode())
            ->withStringBody(json_encode([
                'message' => $exception->getMessage(),
                'code'    => $exception->getCode(),
                'meta'    => $meta
            ]));
    }

    /**
     * Used to set the page title and head. Sub title is also set with this function
     * @param string $title
     * @param string|null  $subTitle
     */
    public function setTitle($title, $subTitle = null)
    {
        $this->set(compact('title', 'subTitle'));
    }

    /**
     * Return the currently logged in user
     *
     * @param  string|null  $field  If set, only the specific field will be returned
     *
     * @return mixed|null
     */
    protected function getUser($field = null)
    {
        if (isset($_SESSION) && isset($_SESSION['Auth']) && count($_SESSION['Auth'])) {
            if ($field) {
                return isset($_SESSION['Auth']['User'][$field]) ? $_SESSION['Auth']['User'][$field] : '';
            }

            return $_SESSION['Auth']['User'];
        } else {
            return null;
        }
    }
}
