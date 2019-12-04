<?php
namespace froggdev\BehatContexts\Util;

trait FileTrait
{

    /**
     * @Then J'efface les anciens téléchargements
     */
    public function iDeleteOldDownloads(): void
    {
        $this->delTree($this->userVars['downloadPath'],false);
    }

    /**
     * @param string $dir
     * @return bool
     */
    public function delTree(string $dir,bool $delMainDir=true): bool
    {
        $files = @array_diff(@scandir($dir), array('.', '..'));
        if($files){
            foreach ($files as $file) {
                (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : @unlink("$dir/$file");
            }
        }
        if( false===$delMainDir ) return true;

        return @rmdir($dir);
    }

    /**
     * @param $filePath
     * @param $content
     */
    public function writeTofile( $filePath , $content ): void
    {
        $dir = @dirname($filePath);

        //create directory if it doesn't exist
        if (!@file_exists($dir)) {
            @mkdir($dir, 0777, true);
        }

        // send data to the file
        @file_put_contents(
            $filePath,
            $content
        );
    }


    /**
     * @param string $file
     * @param string $content
     */
    public function saveFileAsUTF8(string $file, string $content)
    {
        // Create the dir if not exist
        $dir = dirname($file);
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        // save as UTF 8 format
        $f = fopen($file, "w");
        # Now UTF-8 - Add byte order mark
        fwrite($f, pack("CCC", 0xef, 0xbb, 0xbf));
        fwrite($f, $content);
        fclose($f);
    }

    /**
     * @param string $url
     * @return bool
     */
    public function fileUrlExist(string $url): bool
    {
        $file_headers = @get_headers($url);

        return (!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found');

        /* CURL VERSION :
         * if (!$fp = curl_init($url)) return false;
         * return true;
         */

    }

    /**
     * Copy a file, or recursively copy a folder and its contents
     *
     * @author      Aidan Lister <aidan@php.net>
     * @version     1.0.1
     * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
     * @param       string $source Source path
     * @param       string $dest Destination path
     * @param       bool $silent
     * @return      bool     Returns TRUE on success, FALSE on failure
     */
    public static function copyr(string $source,string $dest , bool $silent = true)
    {
        // Check if source exist
        if (!file_exists($source) && $silent) {
            return false;
        }

        // Check for symlinks
        if (is_link($source)) {
            return symlink(readlink($source), $dest);
        }

        // Simple copy for a file
        if (is_file($source)) {
            return copy($source, $dest);
        }

        // Make destination directory
        if (!is_dir($dest)) {
            mkdir($dest,0777 ,true);
        }

        // Loop through the folder
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            // Deep copy directories
            self::copyr("$source/$entry", "$dest/$entry");
        }

        // Clean up
        $dir->close();
        return true;
    }

}
