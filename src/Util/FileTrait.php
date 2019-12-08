<?php
namespace froggdev\BehatInstaller\Util;

trait FileTrait
{

  /**
   * @Then J'efface les anciens téléchargements
   */
  public function iDeleteOldDownloads(): void
  {
      $this->delTree($this->userVars['downloadPath'],false);
  }
 
}
   