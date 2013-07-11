<?php
/**
 * @package     Faq
 * @subpackage  mod_faq_popular
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_faq/models', 'FaqModel');

jimport('joomla.application.categories');

/**
 * @package		Faq
 * @subpackage	mod_faq_popular
 * @since		2.5
 */
abstract class modFaqPopularHelper
{
	/**
	 * Get a list of the most popular faqs
	 *
	 * @param	JObject		The module parameters.
	 *
	 * @return	array
	 */
	public static function getList($params)
	{
		// Initialise variables
		$user = JFactory::getuser();

		// Get an instance of the generic faqs model
		$model = JModelLegacy::getInstance('Faqs', 'FaqModel', array('ignore_request' => true));

		// Set List SELECT
		$model->setState(
			'list.select',
			'a.id, a.title, a.checked_out, a.checked_out_time, ' .
			'a.created, a.hits'
		);

		// Set Ordering filter
		$model->setState('list.ordering', 'a.hits');
		$model->setState('list.direction', 'DESC');

		// Set Category Filter
		$categoryId = $params->get('catid');
		if (is_numeric($categoryId)){
			$model->setState('filter.category_id', $categoryId);
		}

		// Set User Filter.
		$userId = $user->get('id');
		switch ($params->get('user_id')) {
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

		if ($error = $model->getError()) {
			JError::raiseError(500, $error);
			return false;
		}

		// Set the links
		foreach ($items as &$item) {
			if ($user->authorise('core.edit', 'com_faq.faq.' . $item->id)){
				$item->link = JRoute::_('index.php?option=com_faq&task=faq.edit&id=' . $item->id);
			} else {
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
		if ($catid)
		{
			$category = JCategories::getInstance('Faq')->get($catid);
			if ($category) {
				$title = $category->title;
			}
			else {
				$title = JText::_('MOD_FAQ_POPULAR_UNEXISTING');
			}
		}
		else
		{
			$title = '';
		}
		return JText::plural('MOD_FAQ_POPULAR_TITLE' . ($catid ? "_CATEGORY" : '') . ($who != '0' ? "_$who" : ''), (int)$params->get('count'), $title);
	}
}