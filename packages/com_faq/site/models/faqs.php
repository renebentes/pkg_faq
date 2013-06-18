<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Faq Component Faqs Model
 *
 * @package     Faq
 * @subpackage  com_faq
 * @since       2.5
 */
class FaqModelFaqs extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   2.5
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'alias', 'a.alias',
				'catid', 'a.catid', 'category_title',
				'published', 'a.published',
				'access', 'a.access', 'access_level',
				'created', 'a.created',
				'created_by', 'a.created_by',
				'modified', 'a.modified',
				'ordering', 'a.ordering',
				'language', 'a.language',
				'hits', 'a.hits',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'publish_up', 'a.publish_up',
				'publish_down', 'a.publish_down'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
	protected function populateState($ordering = 'ordering', $direction = 'ASC')
	{
		// Initialise variables.
		$app    = JFactory::getApplication();

		// List state information
		$value = JRequest::getUInt('limit', $app->getCfg('list_limit', 0));
		$this->setState('list.limit', $value);

		$value = JRequest::getUInt('limitstart', 0);
		$this->setState('list.start', $value);

		$orderCol = JRequest::getCmd('filter_order', 'a.ordering');
		if (!in_array($orderCol, $this->filter_fields))
		{
			$orderCol = 'a.ordering';
		}
		$this->setState('list.ordering', $orderCol);

		$listOrder = JRequest::getCmd('filter_order_Dir', 'ASC');
		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', '')))
		{
			$listOrder = 'ASC';
		}
		$this->setState('list.direction', $listOrder);

		$params = $app->getParams();
		$this->setState('params', $params);
		$user = JFactory::getUser();

		if ((!$user->authorise('core.edit.state', 'com_faq')) && (!$user->authorise('core.edit', 'com_faq')))
		{
			// Limit to published for people who can't edit or edit.state.
			$this->setState('filter.published', 1);
		}

		$this->setState('filter.language', $app->getLanguageFilter());

		$this->setState('filter.access', true);

		$this->setState('layout', JRequest::getCmd('layout'));
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 *
	 * @return	string		A store id.
	 * @since	2.5
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . serialize($this->getState('filter.published'));
		$id .= ':' . $this->getState('filter.access');
		$id .= ':' . $this->getState('filter.faq_id');
		$id .= ':' . $this->getState('filter.faq_id.include');
		$id	.= ':' . serialize($this->getState('filter.category_id'));
		$id .= ':' . $this->getState('filter.category_id.include');
		$id .= ':' . $this->getState('filter.language');

		return parent::getStoreId($id);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  string  An SQL query
	 *
	 * @since   2.5
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.title, a.alias, a.description, a.checked_out, a.checked_out_time, ' .
				'a.catid, a.created, a.created_by, a.created_by_alias, ' .
				// use created if modified is 0
				'CASE WHEN a.modified = 0 THEN a.created ELSE a.modified END AS modified, ' .
				'a.modified_by, uam.name AS modified_by_name,' .
				// use created if publish_up is 0
				'CASE WHEN a.publish_up = 0 THEN a.created ELSE a.publish_up END AS publish_up,' .
				'a.publish_down, a.images, a.params, a.metadata, a.metakey, a.metadesc, a.access, ' .
				'a.hits'
			)
		);

		// Process Archived FAQs
		if($this->getState('filter.published') == 2)
		{
			// If badcats is not null, this means that the FAQ is inside an archived category
			// In this case, the state is set to 2 to indicate Archived (even if the FAQ state is Published)
			$query->select($this->getState('list.select', 'CASE WHEN badcats.id IS NULL THEN a.published ELSE 2 END AS published'));
		}
		else
		{
			// Process non-archived layout
			// If badcats is not null, this means that the FAQ is inside an unpublished category
			// In this case, the state is set to 0 to indicate Unpublished (even if the FAQ state is Published)
			$query->select($this->getState('list.select', 'CASE WHEN badcats.id IS NOT NULL THEN 0 ELSE a.published END AS published'));
		}

		$query->from('#__faq AS a');

		// Join over the categories
		$query->select('c.title AS category_title, c.path AS category_route, c.access AS category_access, c.alias AS category_alias');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

		// Join over the users for the author and modified_by names.
		$query->select("CASE WHEN a.created_by_alias > ' ' THEN a.created_by_alias ELSE ua.name END AS author");
		$query->select("ua.email AS author_email");

		$query->join('LEFT', '#__users AS ua ON ua.id = a.created_by');
		$query->join('LEFT', '#__users AS uam ON uam.id = a.modified_by');

		// Join on contact table
		$subQuery = $db->getQuery(true);
		$subQuery->select('contact.user_id, MAX(contact.id) AS id, contact.language');
		$subQuery->from('#__contact_details AS contact');
		$subQuery->where('contact.published = 1');
		$subQuery->group('contact.user_id, contact.language');
		$query->select('contact.id AS contactid' );
		$query->join('LEFT', '(' . $subQuery . ') AS contact ON contact.user_id = a.created_by');

		// Join over the categories to get parent category titles
		$query->select('parent.title AS parent_title, parent.id AS parent_id, parent.path AS parent_route, parent.alias AS parent_alias');
		$query->join('LEFT', '#__categories as parent ON parent.id = c.parent_id');

		// Sqlsrv change... aliased c.published to cat_published
		// Join to check for category published state in parent categories up the tree
		$query->select('c.published AS cat_published, CASE WHEN badcats.id IS NULL THEN c.published ELSE 0 END AS parents_published');
		$subquery = 'SELECT cat.id AS id FROM #__categories AS cat JOIN #__categories AS parent ';
		$subquery .= 'ON cat.lft BETWEEN parent.lft AND parent.rgt ';
		$subquery .= 'WHERE parent.extension = ' . $db->quote('com_faq');

		if ($this->getState('filter.published') == 2)
		{
			// Find any up-path categories that are archived
			// If any up-path categories are archived, include all children in archived layout
			$subquery .= ' AND parent.published = 2 GROUP BY cat.id ';

			// Set effective state to archived if up-path category is archived
			$publishedWhere = 'CASE WHEN badcats.id IS NULL THEN a.published ELSE 2 END';
		}
		else
		{
			// Find any up-path categories that are not published
			// If all categories are published, badcats.id will be null, and we just use the Faq state
			$subquery .= ' AND parent.published != 1 GROUP BY cat.id ';
			// Select state to unpublished if up-path category is unpublished
			$publishedWhere = 'CASE WHEN badcats.id IS NULL THEN a.published ELSE 0 END';
		}
		$query->join('LEFT OUTER', '(' . $subquery . ') AS badcats ON badcats.id = c.id');

		// Filter by access level.
		if ($access = $this->getState('filter.access')) {
			$user	= JFactory::getUser();
			$groups	= implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
			$query->where('c.access IN (' . $groups . ')');
		}

		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published))
		{
			// Use Faq state if badcats.id is null, otherwise, force 0 for unpublished
			$query->where($publishedWhere . ' = ' . (int) $published);
		}
		elseif (is_array($published))
		{
			JArrayHelper::toInteger($published);
			$published = implode(',', $published);
			// Use faq state if badcats.id is null, otherwise, force 0 for unpublished
			$query->where($publishedWhere . ' IN (' . $published . ')');
		}

		// Filter by a single or group of faqs.
		$faqId = $this->getState('filter.faq_id');
		if (is_numeric($faqId))
		{
			$type = $this->getState('filter.faq_id.include', true) ? '= ' : '<> ';
			$query->where('a.id ' . $type . (int) $faqId);
		}
		elseif (is_array($faqId))
		{
			JArrayHelper::toInteger($faqId);
			$faqId = implode(',', $faqId);
			$type = $this->getState('filter.faq_id.include', true) ? 'IN' : 'NOT IN';
			$query->where('a.id ' . $type .' (' . $faqId .')');
		}

		// Filter by a single or group of categories
		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId))
		{
			$type = $this->getState('filter.category_id.include', true) ? '= ' : '<> ';

			// Add subcategory check
			$includeSubcategories = $this->getState('filter.subcategories', false);
			$categoryEquals = 'a.catid ' . $type . (int) $categoryId;

			if ($includeSubcategories)
			{
				$levels = (int) $this->getState('filter.max_category_levels', '1');

				// Create a subquery for the subcategory list
				$subQuery = $db->getQuery(true);
				$subQuery->select('sub.id');
				$subQuery->from('#__categories AS sub');
				$subQuery->join('INNER', '#__categories AS this ON sub.lft > this.lft AND sub.rgt < this.rgt');
				$subQuery->where('this.id = ' . (int) $categoryId);
				if ($levels >= 0) {
					$subQuery->where('sub.level <= this.level + ' . $levels);
				}
				// Add the subquery to the main query
				$query->where('(' . $categoryEquals . ' OR a.catid IN (' . $subQuery->__toString() . '))');
			}
			else
			{
				$query->where($categoryEquals);
			}
		}
		elseif (is_array($categoryId) && (count($categoryId) > 0))
		{
			JArrayHelper::toInteger($categoryId);
			$categoryId = implode(',', $categoryId);
			if (!empty($categoryId))
			{
				$type = $this->getState('filter.category_id.include', true) ? 'IN' : 'NOT IN';
				$query->where('a.catid '. $type . ' (' . $categoryId . ')');
			}
		}

		// Filter by start and end dates.
		$nullDate = $db->Quote($db->getNullDate());
		$nowDate = $db->Quote(JFactory::getDate()->toSql());

		$query->where('(a.publish_up = ' . $nullDate . ' OR a.publish_up <= ' . $nowDate . ')');
		$query->where('(a.publish_down = ' . $nullDate . ' OR a.publish_down >= ' . $nowDate . ')');

		// Filter by language
		if ($this->getState('filter.language'))
		{
			$query->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
			$query->where('(contact.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ') OR contact.language IS NULL)');
		}

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'a.ordering')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));

		$query->group(
			'a.id, a.title, a.alias, a.description, a.checked_out, a.checked_out_time, a.catid, ' .
			'a.created_by, a.created_by_alias, a.created, a.modified, a.modified_by, uam.name, ' .
			'a.publish_up, a.params, a.images, a.metakey, a.metadesc, a.metadata, a.access, a.hits, ' .
			'a.published, a.publish_down, badcats.id, c.title, c.path, c.access, c.alias, uam.id, '.
			'ua.name, ua.email, contact.id, parent.title, parent.id, parent.path, parent.alias, ' .
			'c.published, c.lft, a.ordering, parent.lft, c.id'
		);

		return $query;
	}

	/**
	 * Method to get a list of items.
	 *
	 * @return  mixed  An array of objects on success, false on failure.
	 *
	 * @since   2.5
	 */
	public function getItems()
	{
		// Invoke the parent getItems method to get the main list
		$items  = parent::getItems();
		$user   = JFactory::getUser();
		$userId = $user->get('id');
		$guest  = $user->get('guest');
		$groups = $user->getAuthorisedViewLevels();

		// Get the global params
		$globalParams = JComponentHelper::getParams('com_faq', true);

		// Convert the params field into an object, saving original in _params
		foreach ($items as &$item)
		{
			$itemParams = new JRegistry;
			$itemParams->loadString($item->params);

			$item->layout = $itemParams->get('layout');
			$item->params = clone $this->getState('params');
			$item->params->merge($itemParams);

			// Compute the asset access permissions.
			// Technically guest could edit an faq, but lets not check that to improve performance a little.
			if (!$guest) {
				$asset	= 'com_faq.faq.' . $item->id;

				// Check general edit permission first.
				if ($user->authorise('core.edit', $asset))
				{
					$item->params->set('access-edit', true);
				}
				// Now check if edit.own is available.
				elseif (!empty($userId) && $user->authorise('core.edit.own', $asset))
				{
					// Check for a valid user and that they are the owner.
					if ($userId == $item->created_by)
					{
						$item->params->set('access-edit', true);
					}
				}
			}

			$access = $this->getState('filter.access');

			if ($access)
			{
				// If the access filter has been set, we already have only the faqs this user can view.
				$item->params->set('access-view', true);
			}
			else
			{
				// If no access filter is set, the layout takes some responsibility for display of limited information.
				if ($item->catid == 0 || $item->category_access === null)
				{
					$item->params->set('access-view', in_array($item->access, $groups));
				}
				else
				{
					$item->params->set('access-view', in_array($item->access, $groups) && in_array($item->category_access, $groups));
				}
			}
		}

		return $items;
	}
}