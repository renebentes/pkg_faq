<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Supports an HTML select list of faqs
 *
 * @package     Faq
 * @subpackage  com_faq
 * @since       2.5
 */
class JFormFieldOrdering extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	2.5
	 */
	protected $type = 'Ordering';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 *
	 * @since	2.5
	 */
	protected function getInput()
	{
		// Initialize variables.
		$html = array();
		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		// Get some field values from the form.
		$faqId  = (int) $this->form->getValue('id');
		$categoryId = (int) $this->form->getValue('catid');

		// Build the query for the ordering list.
		$query = 'SELECT ordering AS value, title AS text'
			. ' FROM #__faq'
			. ' WHERE catid = '
			. (int) $categoryId
			. ' ORDER BY ordering';

		// Create a read-only list (no title) with a hidden input to store the value.
		if ((string) $this->element['readonly'] == 'true')
		{
			$html[] = JHtml::_('list.ordering', '', $query, trim($attr), $this->value, $faqId ? 0 : 1);
			$html[] = '<input type="hidden" name="' . $this->title . '" value="' . $this->value . '"/>';
		}
		// Create a regular list.
		else
		{
			$html[] = JHtml::_('list.ordering', $this->title, $query, trim($attr), $this->value, $faqId ? 0 : 1);
		}

		return implode($html);
	}
}