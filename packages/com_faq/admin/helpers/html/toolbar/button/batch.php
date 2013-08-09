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
 * Renders a batch modal button
 *
 * @package     Faq
 * @subpackage  com_faq
 * @since       3.0
 */

class JToolbarButtonBatch extends JToolbarButton
{
	/**
	 * Button type
	 *
	 * @var    string
	 */
	protected $_name = 'Batch';

	/**
	 * Fetch the HTML for the button
	 *
	 * @param   string  $type     Unused string.
	 * @param   string  $name     Name to be used as apart of the id
	 * @param   string  $text     Button text
	 *
	 * @return  string  HTML string for the button
	 *
	 * @since   3.0
	 */
	public function fetchButton($type = 'Batch', $name = 'batch', $text = '')
	{
		JHtml::_('bootstrap.modal', 'collapseModal');

		$text   = JText::_($text);

		$html = "<button data-toggle=\"modal\" data-target=\"#collapseModal\" class=\"btn btn-small\">\n";
		$html .= "<i class=\"icon-checkbox-partial\" title=\"$text\"></i>\n";
		$html .= "$text</button>\n";

		return $html;
	}

	/**
	 * Get the button CSS Id
	 *
	 * @param   string  $type  The button type.
	 * @param   string  $name  The name of the button.
	 *
	 * @return  string  Button CSS Id
	 *
	 * @since   3.0
	 */
	public function fetchId($type = 'Batch', $name = 'batch')
	{
		return $this->_parent->getName() . '-' . $name;
	}
}