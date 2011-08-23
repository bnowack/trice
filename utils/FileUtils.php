<?php

/**
 * Trice file system utilities
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */

namespace trice\utils;

class FileUtils {

  /**
   * Generates missing parent directories for a given filePath.
   * 
   * @param bool success
   */
  static public function createFileDirectories($filePath, $mode = 0777) {
    $dirPath = preg_replace('/\/[^\/]*$/', '', $filePath);
    if (!is_dir($dirPath)) {
      mkdir($dirPath, $mode, true);
    }
    return is_dir($dirPath);
  }
  
}

