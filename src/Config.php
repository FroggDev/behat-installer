<?php
namespace froggdev\BehatContexts;

class Config
{

    /**
     * Screenshot internal config
     */

    /** @var string default report main path */
    const REPORT_DIR = 'public/output/behat';

    /** @var string default screenshot ok path in screenshot main path */
    const SCREENSHOT_DIR_OK = 'screenshotsOk';

    /** @var string default screenshot ko path in screenshot main path */
    const SCREENSHOT_DIR_KO = 'screenshots';

    /** @var string default screenshot custom path in screenshot main path */
    const SCREENSHOT_DIR_CUSTOM = 'screenshotsCustom';


}