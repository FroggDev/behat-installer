<?php
namespace froggdev\BehatContexts\Context;

use froggdev\BehatContexts\Tool\HZip;
use froggdev\BehatContexts\Util\FileTrait;

Trait ReportContext
{


    /**
     * @Given J'efface les anciens rapports behat
     */
    public function iCleanOldRepport(): void
    {
        $this->delTree( $this->reportPath );
    }

    /**
     * @Given Je sauvegarde le rapport des tests dans ":chemin_du_rapport"
     *
     * @throws \Exception
     */
    public function ISaveTheRepport(string $path): void
    {
        // replace uservar in param
        $path = $this->replaceUserVar($path);

        // create main folder
        $currentDate = new \DateTime();
        $currentRepport = $currentDate->format('Y-m-d');
        // TODO : GET THE BROWSER FROM BEHAT CONF
        $fullPath = $path.'/'.$currentRepport. '/' . $currentDate->format('His') . $this->userVars['browser'];

        // Create folder if not exist
        mkdir($fullPath,0777 , true);

        // Copy folders
        $this->copyr( $this->reportPath ,$fullPath );

        // Get main script path
        $mainPath=str_replace('/features/bootstrap/Context/', "",__DIR__);
        //read the entire string
        $str=file_get_contents($fullPath.'/index.html');
        //remove the file path
        $str=str_replace('file://' . $mainPath . $this->reportPath, "",$str);
        //write the entire string
        file_put_contents($fullPath.'/index.html', $str);

        // Zip + Delete file if no errors
        if( !file_exists($fullPath.'/assets/screenshots/') ) {
            // Zip the files
            HZip::zipDir($fullPath,  $fullPath.'.zip');
            // Clean folder
            $this->delTree($fullPath );
        }
    }
}
