<?php
namespace froggdev\BehatInstaller;

Abstract class Config
{
  /**
   * Default values
   */
  const DO_SCREENSHOT = true;
  const DO_EXPORT = true;
  const EXPORT_PATH = 'C:\behat-demo';
  const DO_MAIL = true;
  const MAIL_FROM = 'behat@test.fr';
  const MAIL_TO = 'behat-test@yopmail.com';
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

  /**
   * Package informations (for the installer)
   */
  const PACKAGE_NAME = 'froggdev/behat-installer';
   
  const PACKAGE_PATH = 'vendor/froggdev/behat-installer';

  const PACKAGE_FILES = 'manifest.json';

  const PACKAGE_MESSAGE = 'post-install.txt';
  
  const PACKAGE_INTRO = 'post-install-intro.txt';

}