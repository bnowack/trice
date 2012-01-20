<?php

namespace trice\helpers;

use \trice\Trice as Trice;

/**
 * PopOver Helper.
 * 
 * @package Trice
 * @author Benjamin Nowack <mail@bnowack.de> 
 */
class PopOverHelper {
	
	/**
	 * Returns the relative (default) or absolute path to the favicon image.
	 * * @see \trice\Helper::run()
	 */
	public function run($title, $content) {
		$resp = Trice::getResponse();
		$resp->addStylesheet('popover.css');
		return '
			<div class="popover">
				<h3 class="title">' . $title . '</h3>
				<div class="content">' . $content . '</div>
			</div>
		';
	}
	
}
