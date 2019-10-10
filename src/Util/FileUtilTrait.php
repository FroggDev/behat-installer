<?php
namespace froggdev\BehatContexts\Util;

/**
 * Trait FileUtilTrait
 * @package froggdev\BehatContexts\Util
 */
trait FileUtilTrait
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
}
