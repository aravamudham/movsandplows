<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
// Config for payment via Stripe
defined('SECRET_KEY') or define('SECRET_KEY', 'sk_test_w8jox949HgMUQfUSuk8HOqn1');
defined('PUBLISHABLE_KEY') or define('PUBLISHABLE_KEY', 'pk_test_PHpVnGDg1yo77vX8orbT4arV');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
