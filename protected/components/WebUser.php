<?php
class WebUser extends CWebUser {
    private $_model;

    public function init(){
        parent::init();
    }

    // Load user model.
    protected function loadUser($id = null)
    {
        if ($this->_model === null) {
            if ($id !== null)
                $this->_model = Account::model()->findByPk($id);
        }
        return $this->_model;
    }

    // Load user model.
    public function refreshUser($id = null)
    {
        if ($id !== null)
            $this->_model = Account::model()->findByPk($id);
    }



    // This is a function that checks the field 'role'
    // in the User model to be equal to 1, that means it's admin
    // access it by Yii::app()->user->isAdmin()

    function isAdmin()
    {
        $user = $this->getCurrentUser();
        if ($user != null) {
            return intval($user->role) == Globals::ROLE_ADMIN;
        } else {
            return false;
        }
    }

    // access it by Yii::app()->user->isClientAdmin()
    function isModerator()
    {
        $user = $this->getCurrentUser();
        if ($user != null) {
            return intval($user->role) == Globals::ROLE_MODERATOR;
        } else {
            return false;
        }
    }
    // access it by Yii::app()->user->currentUser
    public function getCurrentUser()
    {
        $user = $this->loadUser(Yii::app()->user->id);
        return $user;
    }
}