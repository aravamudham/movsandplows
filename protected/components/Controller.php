<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';
    public $mainMenu = array();
    public $isHomePage = false;
    public $uploadFolder;
    public $smallDescription;
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();
    public $toolBarActions = array();

    public function init()
    {
        parent::init();

        $url = Yii::app()->request->url;
        $uriString = substr($url, strpos($url, $this->route));
        $suffixPos = strrpos($uriString, '.html');
        if ($suffixPos > 0) {
            $uriString = substr($uriString, 0, $suffixPos);
        }

        $segments = explode('/', $uriString);
        $first_segment = isset($segments[0]) ? $segments[0] : FALSE;
        $second_segment = isset($segments[1]) ? $segments[1] : FALSE;
        $third_segment = isset($segments[2]) ? $segments[2] : FALSE;
        if ($first_segment) {
                if ($first_segment == 'site') {
                    if ($second_segment) {
                        if ($second_segment == 'index') {
                            $this->isHomePage = true;
                        }
                    } else {
                        $this->isHomePage = false;
                    }
                }
        } else {
            $this->isHomePage = true;
        }
        $this->_registerClientScript();
//        $this->user = Yii::app()->user->currentUser;
//        if (empty($this->user)) {
//            Yii::app()->user->logout(FALSE);
//            return;
//        }


        $this->mainMenu = array(
            array(
                'active' => $first_segment == 'user',
                'name' => Yii::t('common', 'menu.user'),
                'icon' => 'glyphicon glyphicon-user',
                'url' => Yii::app()->createUrl('/user/index'),
            ),
            array(
                'active' => $first_segment == 'state',
                'name' => Yii::t('common', 'menu.state'),
                'icon' => 'fa fa-location-arrow',
                'url' => Yii::app()->createUrl('/state/index'),
            ),
            // array(
            //     'active' => $first_segment == 'city',
            //     'name' => Yii::t('common', 'menu.city'),
            //     'icon' => 'fa fa-map-marker',
            //     'url' => Yii::app()->createUrl('/city/index'),
            // ),
            array(
                'active' => $first_segment == 'trackOnlineDrivers',
                'name' => 'Track online drivers ',
                'icon' => 'fa fa-car',
                'url' => Yii::app()->createUrl('/trackOnlineDrivers/index'),
            ),
            array(
                'active' => $first_segment == 'trip',
                'name' => Yii::t('common', 'menu.trip'),
                'icon' => 'fa fa-suitcase',
                'url' => Yii::app()->createUrl('/trip/index'),
            ),
            array(
                'active' => $first_segment == 'vehicle',
                'name' => Yii::t('common', 'menu.vehicle'),
                'icon' => 'fa fa-car',
                'url' => Yii::app()->createUrl('/vehicle/index'),
            ),
            array(
                'active' => $first_segment == 'updatePending',
                'name' => Yii::t('common', 'menu.updatePending'),
                'icon' => 'fa fa-pencil',
                'url' => Yii::app()->createUrl('/updatePending/index'),
            ),
            array(
                'active' => $first_segment == 'transaction',
                'name' => Yii::t('common', 'menu.transaction'),
                'icon' => 'fa fa-usd',
                'children' => array(
                    array('label' => Yii::t('common', 'menu.transaction'), 'url' => array('/transaction/index'),  'active' => $first_segment == 'transaction' AND $second_segment == 'index','icon' => '',),
                    array('label' => Yii::t('common', 'menu.adjust.balance'), 'url' => array('/transaction/adjustBalance'), 'active' => $first_segment == 'transaction' AND $second_segment == 'adjustBalance','icon' => '',),
                ),
            ),
            array(
                'active' => $first_segment == 'pointExchange' || $first_segment == 'pointRedeem' || $first_segment == 'pointTransfer',
                'name' => Yii::t('common', 'menu.point.management'),
                'icon' => 'fa fa-money',
                'children' => array(
                    array('label' => Yii::t('common', 'menu.point.exchange'), 'url' => array('/pointExchange/index'),  'active' => $first_segment == 'pointExchange' AND $second_segment == 'index','icon' => '',),
                    array('label' => Yii::t('common', 'menu.point.redeem'), 'url' => array('/pointRedeem/index'), 'active' => $first_segment == 'pointRedeem' AND $second_segment == 'admin','icon' => '',),
                    array('label' => Yii::t('common', 'menu.point.transfer'), 'url' => array('/pointTransfer/index'), 'active' => $first_segment == 'pointTransfer' AND $second_segment == 'create','icon' => '',),

                ),
            ),
           
            array(
                'active' => $first_segment == 'settings',
                'name' => Yii::t('common', 'menu.settings'),
                'icon' => 'glyphicon glyphicon-cog',
                'children' => array(
                    array('label' => Yii::t('common', 'menu.settings'), 'url' => array('/settings/index'),  'active' => $first_segment == 'settings' AND $second_segment == 'index','icon' => '',),
                    array('label' => Yii::t('common', 'menu.push.notification'), 'url' => array('/settings/pushNotification'), 'active' => $first_segment == 'settings' AND $second_segment == 'pushNotification','icon' => '',),
                ),
            ),

            //example multi level
//            array(
//                'active' => $first_segment == 'settings',
//                'name' => Yii::t('common', 'menu.settings'),
//                'icon' => 'icon-settings',
//                'children' => array(
//                    array('label' => Yii::t('common', 'menu.settingAAAA'), 'url' => array('/settings/index'),  'active' => $first_segment == 'settings' AND $second_segment == 'index','icon' => '',),
//                    array('label' => Yii::t('common', 'menu.settingBBBB'), 'url' => array('/settings/admin'), 'active' => $first_segment == 'settings' AND $second_segment == 'admin','icon' => '',),
//                    array('label' => Yii::t('common', 'menu.settingCCCC'), 'url' => array('/settings/create'), 'active' => $first_segment == 'settings' AND $second_segment == 'create','icon' => '',),
//                ),
//            ),
            //example last
//            array(
//                'active' => $first_segment == 'site' AND $second_segment == 'page',
//                'name' => Yii::t('common', 'menu.about'),
//                'icon' => 'icon-paper-plane',
//                'url' => Yii::app()->createUrl('/site/page',array('view'=>'about')),
//            ),
        );

    }

    private function _registerClientScript()
    {
        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerCoreScript('jquery.ui');
        Yii::app()->clientScript->registerLinkTag('shortcut icon', null, Yii::app()->request->baseUrl . '/themes/metronic/assets/favicon.png');
        Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . '/themes/metronic/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css');
        Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/themes/metronic/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js');
        Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . '/themes/metronic/assets/global/plugins/fancybox/source/jquery.fancybox.css');
        Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/themes/metronic/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js', CClientScript::POS_END);

    }


    protected function beforeAction($action){
        $this->uploadFolder = Yii::getPathOfAlias(UPLOAD_DIR);
        return parent::beforeAction($action);
    }

}