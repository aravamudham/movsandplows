<?php
class PageToolbar extends CWidget
{
    public $toolBarActions = array();

    public function run()
    {
        if (count($this->toolBarActions)==0)
            return;
        $this->render('pageToolbar',
            array('toolBarActions' => $this->toolBarActions)
        );
    }
}

?>