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
 * Faq component helper.
 *
 * @package     Faq
 * @subpackage  com_faq
 * @since       2.5
 */
class FaqHelper
{
	public static $extension = 'com_faq';

	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
     *
	 * @since   2.5
	 */
	public static function addSubmenu($vName)
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_FAQ_SUBMENU_CPANEL'),
			'index.php?option=com_faq&view=cpanel',
			$vName == 'cpanel'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_FAQ_SUBMENU_FAQS'),
			'index.php?option=com_faq&view=faqs',
			$vName == 'faqs'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_FAQ_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&extension=com_faq',
			$vName == 'categories'
		);
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param   int  $categoryId  The category ID.
	 *
	 * @return  JObject
	 *
	 * @since   2.5
	 */
	public static function getActions($categoryId = 0)
	{
		$user   = JFactory::getUser();
		$result = new JObject;

		if (empty($categoryId))
		{
			$assetName = 'com_faq';
			$level = 'component';
		}
		else
		{
			$assetName = 'com_faq.category.' . (int) $categoryId;
			$level = 'category';
		}

		$actions = JAccess::getActions('com_faq', $level);

		foreach ($actions as $action)
		{
			$result->set($action->name, $user->authorise($action->name, $assetName));
		}

		return $result;
	}

	/**
	 * Method to generate html code for a list of buttons
	 *
	 * @param   string  $controller  The name of controller.
	 * @param   string  $image       The name of image.
	 * @param   string  $text        The title of buttom.
	 *
	 * @return  string
	 *
	 * @since   2.5
	 */
	public static function button($controller, $image, $text)
	{
		// Define variables
		$option = JRequest::getCmd('option');
		if ($controller == 'categories')
		{
			$link   = JRoute::_('index.php?option=com_categories&extension=' . $option, false);
		}
		else
		{
			$link   = JRoute::_('index.php?option=' . $option . '&view=' . $controller, false);
		}

		$html[] = '<div class="icon-wrapper">';
		$html[] = '<div class="icon">';
		$html[] = '<a href=' . $link . '>';
		$html[] = JHTML::_('image', JURI::root() . 'media/' . $option . '/images/icon/' . $image, $text, null, $text);
		$html[] = '<span>' . $text . '</span>';
		$html[] = '</a>';
		$html[] = '</div>';
		$html[] = '</div>';

		return implode($html);
	}
}