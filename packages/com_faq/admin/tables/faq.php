<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Makesoft, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.database.table');

/**
 * Faq table class.
 *
 * @package     Faq
 * @subpackage  com_faq
 * @since       2.5
 */
class FaqTableFaq extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   object  &$db  JDatabase connector object.
	 *
	 * @since   2.5
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__faq', 'id', $db);
		$this->created = JFactory::getDate()->toSql();
	}

	/**
	 * Method to bind an associative array or object to the JTable instance.  This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   array  $array   Named array
	 * @param   mixed  $ignore  An optional array or space separated list of properties
	 *                          to ignore while binding. [optional]
	 *
	 * @return  mixed  Null if operation was satisfactory, otherwise returns an error string
	 *
	 * @since   2.5
	 */
	public function bind($array, $ignore = '')
	{
		if (isset($array['params']) && is_array($array['params']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		if (isset($array['metadata']) && is_array($array['metadata']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * Method to perform sanity checks on the JTable instance properties to ensure
	 * they are safe to store in the database.  Child classes should override this
	 * method to make sure the data they are storing in the database is safe and
	 * as expected before storage.
	 *
	 * @return  boolean  True if the instance is sane and able to be stored in the database.
	 *
	 * @since   2.5
	 */
	public function check()
	{
		// Check for valid title.
		if (trim($this->title) == '')
		{
			$this->setError(JText::_('COM_FAQ_WARNING_PROVIDE_VALID_NAME'));

			return false;
		}

		if (trim($this->alias) == '')
		{
			$this->alias = $this->title;
		}

		$this->alias = JApplication::stringURLSafe($this->alias);

		if (trim(str_replace('-', '', $this->alias)) == '')
		{
			$this->alias = JFactory::getDate()->format('Y-m-d-H-i-s');
		}

		// Set ordering
		if ($this->published < 0) {
			// Set ordering to 0 if state is archived or trashed
			$this->ordering = 0;
		} elseif (empty($this->ordering)) {
			// Set ordering to last if ordering was 0
			$this->ordering = self::getNextOrder($this->_db->quoteName('catid'). ' = ' . $this->_db->Quote($this->catid). ' AND published >= 0');
		}

		// Check the publish down date is not earlier than publish up.
		if (intval($this->publish_down) > 0 && $this->publish_down < $this->publish_up)
		{
			$this->setError(JText::_('JGLOBAL_START_PUBLISH_AFTER_FINISH'));

			return false;
		}

		return true;
	}

	/**
	 * Method to store a row in the database from the JTable instance properties.
	 * If a primary key value is set the row with that primary key value will be
	 * updated with the instance property values.  If no primary key value is set
	 * a new row will be inserted into the database with the properties from the
	 * JTable instance.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null. [optional]
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   2.5
	 */
	public function store($updateNulls = false)
	{
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		if ($this->id)
		{
			// Existing item
			$this->modified = $date->toSql();
			$this->modified_by = $user->get('id');
			$this->version++;
		}
		else
		{
			// New item. A filter's created field can be set by the user,
			// so we don't touch it if it is set.
			if (!intval($this->created))
			{
				$this->created = $date->toSql();
			}
			if (empty($this->created_by))
			{
				$this->created_by = $user->get('id');
			}
			if (empty($this->created_by_alias))
			{
				$this->created_by_alias = $user->get('alias');
			}
		}

		// Verify that the alias is unique
		$table = JTable::getInstance('Faq', 'FaqTable');
		if ($table->load(array('alias' => $this->alias)) && ($table->id != $this->id || $this->id == 0))
		{
			$this->setError(JText::_('COM_Faq_DATABASE_ERROR_UNIQUE_ALIAS'));

			return false;
		}

		return parent::store($updateNulls);
	}
}