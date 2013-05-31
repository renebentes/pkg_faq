<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.html.pagination');

/**
 * Faq Pagination Class.  Provides a common interface for content pagination for the
 * Joomla! Platform.
 *
 * @package     Faq
 * @subpackage  com_faq
 * @since       2.5
 */
class FaqPagination extends JPagination
{
	/**
	 * Creates a dropdown box for selecting how many records to show per page.
	 *
	 * @return  string  The HTML for the limit # input box.
	 *
	 * @since   2.5
	 */
	public function getLimitBox()
	{
		$app = JFactory::getApplication();

		// Initialise variables.
		$limits = array();

		// Make the option list.
		for ($i = 5; $i <= 30; $i += 5)
		{
			$limits[] = JHtml::_('select.option', "$i");
		}
		$limits[] = JHtml::_('select.option', '50', JText::_('J50'));
		$limits[] = JHtml::_('select.option', '100', JText::_('J100'));
		$limits[] = JHtml::_('select.option', '0', JText::_('JALL'));

		$selected = $this->_viewall ? 0 : $this->limit;

		// Build the select list.
		if ($app->isAdmin())
		{
			$html = JHtml::_(
				'select.genericlist',
				$limits,
				$this->prefix . 'limit',
				'class="inputbox input-small" size="1" onchange="Joomla.submitform();"',
				'value',
				'text',
				$selected
			);
		}
		else
		{
			$html = JHtml::_(
				'select.genericlist',
				$limits,
				$this->prefix . 'limit',
				'class="inputbox input-small" size="1" onchange="this.form.submit()"',
				'value',
				'text',
				$selected
			);
		}
		return $html;
	}
}