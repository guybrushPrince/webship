<?php

use webship\menus\ControlPanelHtmlView;

include_once __DIR__ . '/../../../vendor/autoload.php';
include_once __DIR__ . '/../../../cliff/php/loader.php';

$controlPanel = ControlPanelHtmlView::create(3, 2)->fix(); // fix() locates the view in the top right corner.
$elementsArea = $controlPanel->area(2, 1);

echo $controlPanel->generate() . PHP_EOL;
echo $controlPanel->generate(true);
?>