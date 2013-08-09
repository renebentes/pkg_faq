<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Faq Component HTML Helper
 *
 * @static
 * @package     Faq
 * @subpackage  com_faq
 * @since  		3.0
 */

abstract class FaqToolBarHelper extends JToolBarHelper
{
	/**
	 * Writes a batch button for a given option (opens a modal window).
	 *
	 * @param  string  $alt     Button text
	 *
	 * @since  3.0
	 */
	public static function batch($alt = 'JTOOLBAR_BATCH')
	{
		require_once (JPATH_COMPONENT_ADMINISTRATOR . '/helpers/html/toolbar/button/batch.php');
		$bar = JToolbar::getInstance('toolbar');
		$bar->appendButton('Batch', 'batch', $alt);
	}
}