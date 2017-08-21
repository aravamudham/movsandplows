<?php
/* @var $this SiteController */

$this->pageTitle = 'Dashboard';
$this->breadcrumbs = array(
    'Dashboard'
);
$this->smallDescription = 'reports & statistics';
?>
<div class="row">
    <div class="portlet light tasks-widget">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-share font-green-haze hide"></i>
                <span class="caption-subject font-green-haze bold uppercase">Todo</span>
                <span class="caption-helper">Your todo tasks...</span>
            </div>
        </div>
        <div class="portlet-body">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat blue-madison">
                    <div class="visual">
                        <i class="fa fa-comments"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <?php echo count($pendingUpdates) ?>
                        </div>
                        <div class="desc">
                            Pending Driver Updates
                        </div>
                    </div>
                    <a class="more" href="<?php echo Yii::app()->createUrl('updatePending/index'); ?>">
                        View more <i class="m-icon-swapright m-icon-white"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat red-intense">
                    <div class="visual">
                        <i class="fa fa-bar-chart-o"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <?php echo count($redeemRequests) ?>
                        </div>
                        <div class="desc">
                            Redeem Requests
                        </div>
                    </div>
                    <a class="more" href="<?php echo Yii::app()->createUrl('pointRedeem/index'); ?>">
                        View more <i class="m-icon-swapright m-icon-white"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat green-haze">
                    <div class="visual">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <?php echo count($transferRequests) ?>
                        </div>
                        <div class="desc">
                            Transfer Requests
                        </div>
                    </div>
                    <a class="more" href="<?php echo Yii::app()->createUrl('pointTransfer/index'); ?>">
                        View more <i class="m-icon-swapright m-icon-white"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat purple-plum">
                    <div class="visual">
                        <i class="fa fa-globe"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <?php echo count($newDriverRegister) ?>
                        </div>
                        <div class="desc">
                            New Driver Register
                        </div>
                    </div>
                    <a class="more" href="<?php echo Yii::app()->createUrl('user/index',array('User[driver_check]' => Globals::NEW_DRIVER_REGISTER_CUSTOM_INDEX)); ?>">
                        View more <i class="m-icon-swapright m-icon-white"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix">
</div>
<div class="row">
    <div class="portlet light tasks-widget">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-share font-green-haze hide"></i>
                <span class="caption-subject font-green-haze bold uppercase">Statistics</span>
                <span class="caption-helper">System statistics...</span>
            </div>
        </div>
        <div class="portlet-body">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat blue-madison">
                    <div class="visual">
                        <i class="fa fa-comments"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <?php echo count($driverOnline) ?>
                        </div>
                        <div class="desc">
                            Online Drivers
                        </div>
                    </div>
                    <a class="more" href="<?php echo Yii::app()->createUrl('user/driverOnline'); ?>">
                        View more <i class="m-icon-swapright m-icon-white"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat red-intense">
                    <div class="visual">
                        <i class="fa fa-bar-chart-o"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <?php echo count($tripInProgress) ?>
                        </div>
                        <div class="desc">
                            Trip In Progress
                        </div>
                    </div>
                    <a class="more"
                       href="<?php echo Yii::app()->createUrl('trip/index', array('status' => Globals::TRIP_STATUS_IN_PROGRESS)); ?>">
                        View more <i class="m-icon-swapright m-icon-white"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat green-haze">
                    <div class="visual">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <?php echo count($tripToday) ?>
                        </div>
                        <div class="desc">
                            Trips Today
                        </div>
                    </div>
                    <a class="more" href="<?php echo Yii::app()->createUrl('trip/index', array('today' => 'true')); ?>">
                        View more <i class="m-icon-swapright m-icon-white"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat purple-plum">
                    <div class="visual">
                        <i class="fa fa-globe"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <?php echo count($pointExchanges) ?>
                        </div>
                        <div class="desc">
                            Exchanges
                        </div>
                    </div>
                    <a class="more" href="<?php echo Yii::app()->createUrl('pointExchange/index'); ?>">
                        View more <i class="m-icon-swapright m-icon-white"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="clearfix">
</div>