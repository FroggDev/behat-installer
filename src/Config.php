<?php
namespace froggdev\BehatContexts;

class Config
{
		/**
		 * Default values
		 */
		const	DO_SCREENSHOT = true;
		const	EXPORT_PATH = null;
		const	DO_MAIL = true;
		const	MAIL_FROM = null;
		const	MAIL_TO = null;
		const SMTP = 'smtp.free.fr';
		const SMTP_PORT = 25; 

    /**
     * Screenshot internal config
     */

    /** @var string default report main path */
    const REPORT_PATH = 'public/output/behat';

    /** @var string default screenshot ok path in screenshot main path */
    const SCREENSHOT_DIR_OK = 'screenshotsOk';

    /** @var string default screenshot ko path in screenshot main path */
    const SCREENSHOT_DIR_KO = 'screenshots';

    /** @var string default screenshot custom path in screenshot main path */
    const SCREENSHOT_DIR_CUSTOM = 'screenshotsCustom';



}