<?php
if (defined('AD_BOOTSTRAP')) {
    return;
}

session_start();

const AD_BOOTSTRAP = true;
const APP_ROOT = __DIR__;
const APP_PUBLIC = __DIR__ . '/public';
const APP_IMAGES = __DIR__ . '/images';
const APP_JS = __DIR__ . '/js';
/**
 * @var int
 */
const ARES_VERSION = '3.0';
/**
 * @var string
 */
const config('app.versionName') = 'Ares Digital 3.0';


/**
 * Kick things off
 */
require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Helpers/database.php';

!empty($confirmLoggedIn) && confirmLoggedIn();
!empty($adminOnly) && confirmAdmin();
