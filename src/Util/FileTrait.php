<?php
namespace froggdev\BehatContexts\Util;

/**
 * Trait FileTrait
 * @package froggdev\BehatContexts\Util
 */
trait FileTrait
{

    /**
     * @param string $folder
     * @return string
     */
    private function setTrailingSlash(string $folder) : string
    {
        return rtrim($folder , '/') . '/';
    }

    /**
     * @param string $dir
     * @param bool $delMainDir
     * @return bool
     */
    private function delTree(string $dir,bool $delMainDir=true): bool
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
    private function writeTofile( $filePath , $content ): void
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
    private static function copyr(string $source,string $dest , bool $silent = true)
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
