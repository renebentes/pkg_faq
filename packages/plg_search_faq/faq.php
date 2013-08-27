<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_SITE . '/components/com_faq/helpers/route.php';
require_once JPATH_ADMINISTRATOR . '/components/com_search/helpers/search.php';

/**
 * Faq Search plugin
 *
 * @package		Joomla.Plugin
 * @subpackage	Search.faq
 * @since		2.5
 */
class plgSearchFaq extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An array that holds the plugin configuration
	 *
	 * @access  protected
	 * @since   2.5
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * FAQ Search Areas method.
	 *
	 * @return  array An array of search areas
	 *
	 * @since   2.5
	 */
	function onContentSearchAreas()
	{
		static $areas = array(
			'faq' => 'PLG_SEARCH_FAQ_FAQ'
			);
			return $areas;
	}

	/**
	 * Faq Search method
	 *
	 * The sql must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav.
	 *
	 * @param   string  $text      Target search string.
	 * @param   string  $phrase    mathcing option, exact|any|all.
	 * @param   string  $ordering  ordering option, newest|oldest|popular|alpha.
	 * @param   mixed   $areas     An array if the search it to be restricted to areas, null if search all.
	 *
	 * @return  string
	 *
	 * @since   2.5
	 */
	function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		$db     = JFactory::getDbo();
		$app    = JFactory::getApplication();
		$user   = JFactory::getUser();
		$groups = implode(',', $user->getAuthorisedViewLevels());

		$searchText = $text;

		if (is_array($areas))
		{
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas())))
			{
				return array();
			}
		}

		$sContent  = $this->params->get('search_content', 1);
		$sArchived = $this->params->get('search_archived', 1);
		$limit     = $this->params->def('search_limit', 50);

		$nullDate = $db->getNullDate();
		$date     = JFactory::getDate();
		$now      = $date->toSql();

		$state = array();

		if ($sContent)
		{
			$state[] = 1;
		}
		if ($sArchived)
		{
			$state[] = 2;
		}

		$text = trim($text);

		if ($text == '')
		{
			return array();
		}

		$section = JText::_('PLG_SEARCH_FAQ');

		$wheres  = array();

		switch ($phrase)
		{
			case 'exact':
				$text		= $db->Quote('%' . $db->escape($text, true) . '%', false);
				$wheres2	= array();
				$wheres2[]	= 'a.title LIKE ' . $text;
				$wheres2[]	= 'a.description LIKE ' . $text;
				$wheres2[]	= 'a.metakey LIKE ' . $text;
				$wheres2[]	= 'a.metadesc LIKE ' . $text;
				$where		= '(' . implode(') OR (', $wheres2) . ')';
				break;

			case 'all':
			case 'any':
			default:
				$words  = explode(' ', $text);
				$wheres = array();
				foreach ($words as $word) {
					$word		= $db->Quote('%' . $db->escape($word, true) . '%', false);
					$wheres2	= array();
					$wheres2[]	= 'a.title LIKE ' . $word;
					$wheres2[]	= 'a.description LIKE ' . $word;
					$wheres2[]	= 'a.metakey LIKE ' . $word;
					$wheres2[]	= 'a.metadesc LIKE ' . $word;
					$wheres[]	= implode(' OR ', $wheres2);
				}
				$where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
				break;
		}

		switch ($ordering) {
			case 'oldest':
				$order = 'a.created ASC';
				break;

			case 'popular':
				$order = 'a.hits DESC';
				break;

			case 'alpha':
				$order = 'a.title ASC';
				break;

			case 'category':
				$order = 'c.title ASC, a.title ASC';
				break;

			case 'newest':
			default:
				$order = 'a.created DESC';
				break;
		}

		$return = array();

		if (!empty($state))
		{
			$query	= $db->getQuery(true);

			// Sqlsrv changes
			$case_when = ' CASE WHEN ';
			$case_when .= $query->charLength('a.alias');
			$case_when .= ' THEN ';
			$a_id      = $query->castAsChar('a.id');
			$case_when .= $query->concatenate(array($a_id, 'a.alias'), ':');
			$case_when .= ' ELSE ';
			$case_when .= $a_id . ' END as slug';

			$case_when1 = ' CASE WHEN ';
			$case_when1 .= $query->charLength('c.alias');
			$case_when1 .= ' THEN ';
			$c_id       = $query->castAsChar('c.id');
			$case_when1 .= $query->concatenate(array($c_id, 'c.alias'), ':');
			$case_when1 .= ' ELSE ';
			$case_when1 .= $c_id . ' END as catslug';

			$query->select(
				'a.title AS title, a.description AS text, a.metadesc, a.metakey, ' .
				'a.created AS created, ' . $case_when . ', ' . $case_when1 . ', ' .
				$query->concatenate(array($db->Quote($section), "c.title"), " / ") . ' AS section, \'2\' AS browsernav'
			);

			$query->from('#__faq AS a');
			$query->innerJoin('#__categories AS c ON c.id = a.catid');
			$query->where(
				'(' . $where . ') ' . 'AND a.published IN (' . implode(', ', $state) . ') ' .
				'AND c.published = 1 AND a.access IN (' . $groups .') AND c.access IN (' . $groups . ') ' .
				'AND (a.publish_up = ' . $db->Quote($nullDate) . ' OR a.publish_up <= ' . $db->Quote($now) . ') ' .
				'AND (a.publish_down = ' .$db->Quote($nullDate) . ' OR a.publish_down >= ' . $db->Quote($now) . ')'
			);
			$query->group('a.id, a.title, a.metadesc, a.metakey, a.created, a.description, c.title, a.alias, c.alias, c.id');
			$query->order($order);

			// Filter by language
			if ($app->isSite() && $app->getLanguageFilter())
			{
				$tag = JFactory::getLanguage()->getTag();
				$query->where('a.language IN (' . $db->Quote($tag) . ', ' . $db->Quote('*') . ')');
				$query->where('c.language IN (' . $db->Quote($tag) . ', ' . $db->Quote('*') . ')');
			}

			// Set the query and load the result.
			$db->setQuery($query, 0, $limit);
			$list  = $db->loadObjectList();
			$limit -= count($list);

			if (isset($list))
			{
				foreach($list as $key => $item)
				{
					$list[$key]->href = FaqHelperRoute::getFaqRoute($item->slug, $item->catslug);

					if (searchHelper::checkNoHTML($item, $searchText, array('text', 'title', 'metadesk', 'metakey')))
					{
						$return[] = $item;
					}
				}
			}
		}

		return $return;
	}
}