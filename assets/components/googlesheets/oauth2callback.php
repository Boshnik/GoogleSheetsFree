<?php
/**
 * GoogleSheets connector
 *
 * @var modX $modx
 */

require_once dirname(__FILE__, 4) . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

/** @var GoogleSheets $gs */
if ($modx->services instanceof MODX\Revolution\Services\Container) {
    $googlesheets = $modx->services->get('googlesheets');
} else {
    $googlesheets = $modx->getService('googlesheets', 'GoogleSheets', MODX_CORE_PATH . 'components/googlesheets/model/');
}

if (isset($_GET['error'])) {
    return $_GET['error'];
}

$googlesheets->setAuth($_GET['code']);