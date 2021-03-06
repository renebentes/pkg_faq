<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');

/**
 * Faq Component Faq Model
 *
 * @package     faq
 * @subpackage  com_faq
 * @since       2.5
 */
class FaqModelFaq extends JModelItem
{
	/**
	 * Model context string.
	 *
	 * @var     string
	 * @since   2.5
	 */
	protected $_context = 'com_faq.faq';

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('site');

		// Load state from the request.
		$pk = JRequest::getInt('id');
		$this->setState('faq.id', $pk);

		$offset = JRequest::getUInt('limitstart', 0);
		$this->setState('list.offset', $offset);

		// Load the parameters. Merge Global and Menu Item params into new object
		$params = $app->getParams();
		$menuParams = new JRegistry;

		if ($menu = $app->getMenu()->getActive()) {
			$menuParams->loadString($menu->params);
		}

		$mergedParams = clone $menuParams;
		$mergedParams->merge($params);

		$this->setState('params', $mergedParams);

		// Get the user object.
		$user = JFactory::getUser();

		if ((!$user->authorise('core.edit.state', 'com_faq')) &&  (!$user->authorise('core.edit', 'com_faq')))
		{
			$this->setState('filter.published', 1);
			$this->setState('filter.archived', 2);
		}

		$this->setState('filter.language', JLanguageMultilang::isEnabled());
	}

	/**
	 * Method to get faq data.
	 *
	 * @param   integer  $pk  The id of the faq.
	 *
	 * @return  mixed  Menu item data object on success, false on failure.
	 *
	 * @since   2.5
	 */
	public function &getItem($pk = null)
	{
		// Initialise variables.
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('faq.id');

		if ($this->_item === null)
		{
			$this->_item = array();
		}

		if (!isset($this->_item[$pk]))
		{
			try
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true);

				$query->select($this->getState(
					'item.select',
					'a.id, a.title, a.alias, a.description, a.catid, ' .
					'a.created, a.created_by, a.created_by_alias, ' .
					// If badcats is not null, this means that the faq is inside an unpublished category
					// In this case, the state is set to 0 to indicate Unpublished (even if the faq state is Published)
					'CASE WHEN badcats.id IS NULL THEN a.published ELSE 0 END AS published, ' .
					// use created if modified is 0
					'CASE WHEN a.modified = 0 THEN a.created ELSE a.modified END AS modified, ' .
					'a.modified_by, a.checked_out, a.checked_out_time, uam.name AS modified_by_name, ' .
					// use created if publish_up is 0
					'CASE WHEN a.publish_up = 0 THEN a.created ELSE a.publish_up END AS publish_up, ' .
					'a.publish_down, a.images, a.params, a.version, a.metadata, a.metakey, a.metadesc, ' .
					'a.access, a.hits, a.language'
					)
				);
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
				$query->join('LEFT', '#__categories AS parent ON parent.id = c.parent_id');

				// Join to check for category published state in parent categories up the tree
				// If all categories are published, badcats.id will be null, and we just use the faq state
				// Sqlsrv change... aliased c.published to cat_published
				$query->select('c.published AS cat_published, CASE WHEN badcats.id IS NULL THEN c.published ELSE 0 END AS parents_published');
				$subquery = ' (SELECT cat.id AS id FROM #__categories AS cat JOIN #__categories AS parent ';
				$subquery .= 'ON cat.lft BETWEEN parent.lft AND parent.rgt ';
				$subquery .= 'WHERE parent.extension = ' . $db->quote('com_faq');
				$subquery .= ' AND parent.published <= 0 GROUP BY cat.id)';
				$query->join('LEFT OUTER', $subquery . ' AS badcats ON badcats.id = c.id');

				// Filter by access level.
				if ($access = $this->getState('filter.access')) {
					$user	= JFactory::getUser();
					$groups	= implode(',', $user->getAuthorisedViewLevels());
					$query->where('a.access IN (' . $groups . ')');
					$query->where('c.access IN (' . $groups . ')');
				}

				// Filter by language
				if ($this->getState('filter.language'))
				{
					$query->where('a.language IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') .')');
					$query->where('(contact.language IN ('.$db->quote(JFactory::getLanguage()->getTag()).','.$db->quote('*').') OR contact.language IS NULL)');
				}

				$query->where('a.id = ' . (int) $pk);

				// Filter by start and end dates.
				$nullDate = $db->Quote($db->getNullDate());
				$nowDate = $db->Quote(JFactory::getDate()->toSql());

				$query->where('(a.publish_up = ' . $nullDate . ' OR a.publish_up <= ' . $nowDate . ')');
				$query->where('(a.publish_down = ' . $nullDate . ' OR a.publish_down >= ' . $nowDate . ')');

				// Filter by published state.
				$published = $this->getState('filter.published');
				$archived = $this->getState('filter.archived');

				if (is_numeric($published))
				{
					$query->where('(a.published = ' . (int) $published . ' OR a.published =' . (int) $archived . ')');
				}

				$db->setQuery($query);

				$data = $db->loadObject();

				if ($error = $db->getErrorMsg())
				{
					throw new Exception($error);
				}

				if (empty($data))
				{
					return JError::raiseError(404, JText::_('COM_FAQ_ERROR_FAQ_NOT_FOUND'));
				}

				// Check for published state if filter set.
				if (((is_numeric($published)) || (is_numeric($archived))) && (($data->published != $published) && ($data->published != $archived)))
				{
					return JError::raiseError(404, JText::_('COM_FAQ_ERROR_FAQ_NOT_FOUND'));
				}

				// Convert parameter fields to objects.
				$registry = new JRegistry;
				$registry->loadString($data->params);

				$data->params = clone $this->getState('params');
				$data->params->merge($registry);

				$registry = new JRegistry;
				$registry->loadString($data->metadata);
				$data->metadata = $registry;

				// Compute selected asset permissions.
				$user = JFactory::getUser();

				// Technically guest could edit an faq, but lets not check that to improve performance a little.
				if (!$user->get('guest'))
				{
					$userId	= $user->get('id');
					$asset	= 'com_faq.faq.' . $data->id;

					// Check general edit permission first.
					if ($user->authorise('core.edit', $asset))
					{
						$data->params->set('access-edit', true);
					}
					// Now check if edit.own is available.
					elseif (!empty($userId) && $user->authorise('core.edit.own', $asset))
					{
						// Check for a valid user and that they are the owner.
						if ($userId == $data->created_by)
						{
							$data->params->set('access-edit', true);
						}
					}
				}

				// Compute access permissions.
				if ($access = $this->getState('filter.access'))
				{
					// If the access filter has been set, we already know this user can view.
					$data->params->set('access-view', true);
				}
				else
				{
					// If no access filter is set, the layout takes some responsibility for display of limited information.
					$user = JFactory::getUser();
					$groups = $user->getAuthorisedViewLevels();

					if ($data->catid == 0 || $data->category_access === null)
					{
						$data->params->set('access-view', in_array($data->access, $groups));
					}
					else
					{
						$data->params->set('access-view', in_array($data->access, $groups) && in_array($data->category_access, $groups));
					}
				}

				$this->_item[$pk] = $data;
			}
			catch (JException $e)
			{
				if ($e->getCode() == 404)
				{
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError(404, $e->getMessage());
				}
				else
				{
					$this->setError($e);
					$this->_item[$pk] = false;
				}
			}
		}

		return $this->_item[$pk];
	}
}