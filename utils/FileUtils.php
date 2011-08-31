<?php

namespace trice\utils;

/**
 * Trice file system utilities class
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class FileUtils {

  /**
   * Generates missing parent directories for a given $filePath.
   * 
   * @param string $filePath
   * @param int $mode
   * @return bool success
   */
  static public function createFileDirectories($filePath, $mode = 0777) {
    $dirPath = preg_replace('/\/[^\/]*$/', '', $filePath);
    if (!is_dir($dirPath)) {
      mkdir($dirPath, $mode, true);
    }
    return is_dir($dirPath);
  }
  
}

