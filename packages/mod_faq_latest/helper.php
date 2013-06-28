<?php
/**
 * @package     Faq
 * @subpackage  mod_faq_latest
 * @copyright   Copyright (C) 2012 Rene Bentes Pinto, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_faq/models', 'FaqModel');

/**
 * Faq module helper.
 *
 * @package     Faq
 * @subpackage  mod_faq_latest
 * @since       2.5
 */
abstract class modFaqLatestHelper
{
	/**
	 * Get a list of the faq items.
	 *
	 * @param   JRegistry  &$params  The module options.
	 *
	 * @return  array
	 *
	 * @since   2.5
	 */
	public static function getList(&$params)
	{
		$user = JFactory::getuser();

		// Get an instance of the generic articles model
		$model = JModelLegacy::getInstance('Faqs', 'FaqModel', array('ignore_request' => true));

		// Set List SELECT
		$model->setState(
			'list.select',
			'a.id, a.title, a.checked_out, a.checked_out_time, ' .
			'a.access, a.created, a.created_by, a.created_by_alias, a.published'
		);

		// Set Ordering filter
		switch ($params->get('ordering'))
		{
			case 'm_dsc':
				$model->setState('list.ordering', 'modified DESC, created');
				$model->setState('list.direction', 'DESC');
				break;

			case 'c_dsc':
			default:
				$model->setState('list.ordering', 'created');
				$model->setState('list.direction', 'DESC');
				break;
		}

		// Set Category Filter
		$categoryId = $params->get('catid');

		if (is_numeric($categoryId))
		{
			$model->setState('filter.category_id', $categoryId);
		}

		// Set User Filter.
		$userId = $user->get('id');

		switch ($params->get('user_id'))
		{
			case 'by_me':
				$model->setState('filter.author_id', $userId);
				break;

			case 'not_me':
				$model->setState('filter.author_id', $userId);
				$model->setState('filter.author_id.include', false);
				break;
		}

		// Set the Start and Limit
		$model->setState('list.start', 0);
		$model->setState('list.limit', $params->get('count', 5));

		$items = $model->getItems();

		if ($error = $model->getError())
		{
			JError::raiseError(500, $error);

			return false;
		}

		// Set the links
		foreach ($items as &$item)
		{
			if ($user->authorise('core.edit', 'com_faq.faq.' . $item->id))
			{
				$item->link = JRoute::_('index.php?option=com_faq&task=faq.edit&id=' . $item->id);
			}
			else
			{
				$item->link = '';
			}
		}

		return $items;
	}

	/**
	 * Get the alternate title for the module
	 *
	 * @param	JObject	The module parameters.
	 * @return	string	The alternate title for the module.
	 */
	public static function getTitle($params)
	{
		$who = $params->get('user_id');
		$catid = (int)$params->get('catid');
		$type = $params->get('ordering') == 'c_dsc' ? '_CREATED' : '_MODIFIED';
		if ($catid)
		{
			$category = JCategories::getInstance('Faq')->get($catid);
			if ($category)
			{
				$title = $category->title;
			}
			else
			{
				$title = JText::_('MOD_FAQ_LATEST_UNEXISTING');
			}
		}
		else
		{
			$title = '';
		}
		return JText::plural('MOD_FAQ_LATEST_TITLE' . $type . ($catid ? "_CATEGORY" : '') . ($who != '0' ? "_$who" : ''), (int)$params->get('count'), $title);
	}
}