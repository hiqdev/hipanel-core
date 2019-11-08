<?php

use hipanel\widgets\AdBanner;
use hipanel\widgets\SidebarAdBanner;

$this->beginBlock('ad-banner.dashboard');
echo AdBanner::widget();
$this->endBlock();

$this->beginBlock('ad-banner.sidebar');
echo SidebarAdBanner::widget();
$this->endBlock();
?>

