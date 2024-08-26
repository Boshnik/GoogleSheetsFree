<?php
/** @var MODX\Revolution\modX $modx */

require_once MODX_CORE_PATH . 'components/googlesheets/vendor/autoload.php';

$modx->services['googlesheets'] = $modx->services->factory(function($c) use ($modx) {
    return new Boshnik\GoogleSheets\GoogleSheets($modx);
});