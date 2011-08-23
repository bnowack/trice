<?php

namespace trice\log;

use \trice\Configuration as Configuration;
use \trice\Exception as Exception;
use \trice\utils\DateTimeUtils as DateTimeUtils;
use \trice\utils\FileUtils as FileUtils;

/**
 * Trice FileLog.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class FileLog implements Log {
  
  protected $logName;
  
  public function __construct($logName) {
    $this->logName = $logName;
  }
  
  /**
   * @see \trice\Log::add()
   */
  public function add($message) {
    /* Get log path via $logName. */
    $path = Configuration::get("log/{$this->logName}_path");
    /* replace large files */
    $maxSize = Configuration::get("log/{$this->logName}_max_size", 10000);
    if (file_exists($path) && (filesize($path) > $maxSize)) {
      $mode = 'wb';
    }
    /* append to small-enough files */
    else {
      $mode = 'ab';
    }
    /* tweak the message */
    $prefix = '[' . DateTimeUtils::getUtcXsd(null, true) . ']';
    /* make sure the log directory exists */
    if (!FileUtils::createFileDirectories($path)) {
      throw new Exception("Could not create directories for ${path}.");
    }
    /* write to the log file */
    $fp = @fopen($path, $mode);
    if (!$fp) {
      throw new Exception("Error opening file '{$path}'[{$mode}]");
    }
    fwrite($fp, "{$prefix} {$message}\n");
    fclose($fp);
    chmod($path, 0777);
  }
    
  
}

