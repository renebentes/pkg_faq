<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Faq Controller
 *
 * @package     Faq
 * @subpackage  com_faq
 * @since       2.5
 */
class FaqControllerFaq extends JControllerForm
{
	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 */
	protected $view_item = 'form';

	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 */
	protected $view_list = 'categories';

	/**
	 * Variable declaration for compatibility with future versions
	 *
	 * @var JInput
	 */
	protected $input;

	/**
 	 * Constructor
 	 *
 	 * @param   array  $config  An optional associative array of configuration settings.
 	 *
 	 * @see     JControllerLegacy
 	 * @since   2.5
 	*/
	public function __construct($config = array())
	{
		$this->input = JFactory::getApplication()->input;

		parent::__construct($config);
	}

	/**
	 * Method to add a new record.
	 *
	 * @return  boolean  True if the article can be added, false if not.
	 *
	 * @since   2.5
	 */
	public function add()
	{
		if (!parent::add())
		{
			// Redirect to the return page.
			$this->setRedirect($this->getReturnPage());
		}
	}

	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   2.5
	 */
	protected function allowAdd($data = array())
	{
		// Initialise variables.
		$user       = JFactory::getUser();
		$categoryId = JArrayHelper::getValue($data, 'catid', $this->input->getInt('catid'), 'int');
		$allow      = null;

		if ($categoryId)
		{
			// If the category has been passed in the URL check it.
			$allow = $user->authorise('core.create', 'com_faq.category.' . $categoryId);
		}

		if ($allow === null)
		{
			// In the absense of better information, revert to the component permissions.
			return parent::allowAdd($data);
		}
		else
		{
			return $allow;
		}
	}

	/**
	 * Method to check if you can add a new record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   2.5
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// Initialise variables.
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		$user     = JFactory::getUser();
		$userId   = $user->get('id');
		$asset    = 'com_faq.faq.' . $recordId;

		// Check general edit permission first.
		if ($user->authorise('core.edit', $asset)) {
			return true;
		}

		// Fallback on edit.own.
		// First test if the permission is available.
		if ($user->authorise('core.edit.own', $asset)) {
			// Now test the owner is the user.
			$ownerId = (int) isset($data['created_by']) ? $data['created_by'] : 0;
			if (empty($ownerId) && $recordId) {
				// Need to do a lookup from the model.
				$record = $this->getModel()->getItem($recordId);

				if (empty($record)) {
					return false;
				}

				$ownerId = $record->created_by;
			}

			// If the owner matches 'me' then do the test.
			if ($ownerId == $userId) {
				return true;
			}
		}

		// Since there is no asset tracking, revert to the component permissions.
		return parent::allowEdit($data, $key);
	}

	/**
	 * Method to cancel an edit.
	 *
	 * @param   string  $key  The name of the primary key of the URL variable.
	 *
	 * @return  Boolean  True if access level checks pass, false otherwise.
	 *
	 * @since   2.5
	 */
	public function cancel($key = 'f_id')
	{
		parent::cancel($key);

		// Redirect to the return page.
		$this->setRedirect($this->getReturnPage());
	}

	/**
	 * Method to edit an existing record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  Boolean	True if access level check and checkout passes, false otherwise.
	 *
	 * @since   2.5
	 */
	public function edit($key = null, $urlVar = 'f_id')
	{
		$result = parent::edit($key, $urlVar);

		return $result;
	}

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   2.5
	 */
	public function getModel($name = 'Form', $prefix = 'FaqModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * Gets the URL arguments to append to an item redirect.
	 *
	 * @param   int     $recordId  The primary key id for the item.
	 * @param   string  $urlVar    The name of the URL variable for the id.
	 *
	 * @return  string  The arguments to append to the redirect URL.
	 *
	 * @since   2.5
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'f_id')
	{
		$append = parent::getRedirectToItemAppend($recordId, $urlVar);
		$itemId = $this->input->getInt('Itemid');
		$return = $this->getReturnPage();

		if ($itemId)
		{
			$append .= '&Itemid=' . $itemId;
		}

		if ($return)
		{
			$append .= '&return=' . base64_encode($return);
		}

		return $append;
	}

	/**
	 * Get the return URL.
	 *
	 * If a "return" variable has been passed in the request
	 *
	 * @return  string  The return URL.
	 *
	 * @since   2.5
	 */
	protected function getReturnPage()
	{
		$return = $this->input->get('return', null, 'base64');

		if (empty($return) || !JUri::isInternal(base64_decode($return)))
		{
			return JUri::base();
		}
		else
		{
			return base64_decode($return);
		}
	}

	/**
	 * Function that allows child controller access to model data after the data has been saved.
	 *
	 * @param   JModel  &$model     The data model object.
	 * @param   array   $validData  The validated data.
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{
		$task = $this->getTask();

		if ($task == 'save')
		{
			$this->setRedirect(JRoute::_('index.php?option=com_faq&view=category&id=' . $validData['catid'], false));
		}
	}

	/**
	 * Method to save a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  Boolean  True if successful, false otherwise.
	 *
	 * @since   2.5
	 */
	public function save($key = null, $urlVar = 'f_id')
	{
		$result = parent::save($key, $urlVar);

		// If ok, redirect to the return page.
		if ($result)
		{
			$this->setRedirect($this->getReturnPage());
		}

		return $result;
	}

	/**
	 * Method to save the submitted hit values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
	public function saveHitAjax()
	{
		// Get the input.
		$id = $this->input->getInt('Itemid');

		// Get the model.
		$model = $this->getModel('Faq', 'FaqModel');

		// Save the hit.
		$return = $model->hit($id);

		if ($return)
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select($db->quoteName('hits'));
			$query->from($db->quoteName('#__faq'));
			$query->where($db->quoteName('id') .' = ' . (int) $id);
			$db->setQuery($query);
			$hits = $db->loadResult();

			$response = array(
				'status' => '1',
				'message' => JText::sprintf('COM_FAQ_HITS', $hits)
			);
			echo json_encode($response);
		}

		JFactory::getApplication()->close();
	}

	public function saveRatingAjax()
	{
		// Get the input.
		$id = $this->input->getInt('Itemid');
		$like = $this->input->getInt('like');

		// Get the model.
		$model = $this->getModel('Faq', 'FaqModel');

		// Save the rating.
		$return = $model->rating($id);

		if ($return)
		{
			echo "1";
		}

		JFactory::getApplication()->close();
	}

	/**
	 * Method to submit a record for guests
	 *
	 * @return  Boolean  True if successful, false otherwise.
	 *
	 * @since 2.5
	 */
	public function submit()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app   = JFactory::getApplication();
		$lang  = JFactory::getLanguage();
		$data  = $this->input->post->get('jform', array(), 'array');
		$model = $this->getModel('Form', 'FaqModel');

		// Validate the posted data.
		$form = $model->getForm();
		if (!$form)
		{
			JError::raiseError(500, $model->getError());
			return false;
		}

		// Test whether the data is valid.
		$validData = $model->validate($form, $data);

		// Check for validation errors.
		if ($validData === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_faq.faq.data', $data);

			// Redirect back to the faq form.
			$this->setRedirect(
				JRoute::_(
					'index.php?option=com_faq&view=category', false
				)
			);

			return false;
		}

		// Attempt to save the data.
		if (!$model->save($validData))
		{
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

			// Save the data in the session.
			$app->setUserState('com_faq.faq.data', $data);

			// Redirect back to the faq form.
			$this->setRedirect(
				JRoute::_(
					'index.php?option=com_faq&view=category', false
				)
			);

			return false;
		}

		$this->setMessage(
			JText::_(
				$lang->hasKey($this->text_prefix . '_SUBMIT_SAVE_SUCCESS') ? $this->text_prefix : 'JLIB_APPLICATION' . '_SUBMIT_SAVE_SUCCESS'
			),
			'success'
		);

		$this->setRedirect(
				JRoute::_(
					'index.php?option=com_faq&view=category', false
				)
			);

		return true;
	}
}