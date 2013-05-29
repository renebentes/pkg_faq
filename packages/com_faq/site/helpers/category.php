<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

// Component Helper
jimport('joomla.application.component.helper');
jimport('joomla.application.categories');

/**
 * Faq Component Category Tree
 *
 * @static
 * @package     Faq
 * @subpackage  com_faq
 *
 * @since       2.5
 */
class FaqCategories extends JCategories
{
	/**
	 * Class constructor
	 *
	 * @param   array  $options  Array of options
	 *
	 * @since   2.5
	 */
	public function __construct($options = array())
	{
		$options['table'] = '#__faq';
		$options['extension'] = 'com_faq';
		$options['statefield'] = 'published';
		$options['countItems'] = 1;

		parent::__construct($options);
	}
}