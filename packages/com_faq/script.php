<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

// Include dependencies
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.installer.installer');

/**
 * Script file of Faq component
 */
class Com_FaqInstallerScript
{
    /**
     * Extension name
     *
     * @var string
     */
    private $_extension = 'com_faq';

    /**
     * Array of sub extensions package
     *
     * @var array
     */
    private $_subextensions = array(
        'modules' => array(
            'mod_faq_latest' => array(
                'faq-cpanel',
                1,
                3,
                '{"count":"5","ordering":"c_dsc","catid":"","user_id":"0","layout":"_:default","moduleclass_sfx":"","cache":"1","cache_time":"900","cachemode":"static"}',
                1
                ),
            'mod_faq_popular' => array(
                'faq-cpanel',
                1,
                3,
                '{"count":"5","ordering":"c_dsc","catid":"","user_id":"0","layout":"_:default","moduleclass_sfx":"","cache":"1","cache_time":"900","cachemode":"static"}',
                2
                )
            ),
        'plugins' => array(
            'plg_search_faq' => array(
                'search',
                1
                )
            )
        );

    /**
     * Array of obsoletes files and folders
     *
     * @var array
     */
    private $_obsoletes = array(
        'files' => array(
            'administrator/components/com_faq/models/fields/faq.php',
            'administrator/components/com_faq/views/cpanel/tmpl/default_stats.php',
            'components/com_faq/models/forms/faq.xml'
            ),
        'folders' => array(
            )
        );

    /**
     * Method to install the component
     *
     * @param JInstaller $parent
     */
    function install($parent)
    {

    }

    /**
     * Method to uninstall the component
     *
     * @param JInstaller $parent
     */
    function uninstall($parent)
    {

    }

    /**
     * Method to update the component
     *
     * @param JInstaller $parent
     */
    function update($parent)
    {

    }

    /**
     * Method to run before an install/update/uninstall method
     *
	 * @param string 		$type Installation type (install, update, discover_install)
	 * @param JInstaller 	$parent Parent object
     */
    function preflight($type, $parent)
    {
        $this->_checkCompatible();

        // Workaround for JInstaller bugs
        if(in_array($type, array('install','discover_install')))
        {
            $this->_bugfixDBFunctionReturnedNoError();
        }
        else
        {
            $this->_bugfixCantBuildAdminMenus();
        }

        return true;
    }

    /**
     * Method to run after an install/update/uninstall method
     *
     * @param string 		$type install, update or discover_update
	 * @param JInstaller 	$parent
     */
    function postflight($type, $parent)
    {
        $this->_activeSubextensions($this->_subextensions);
        $this->_removeObsoletes($this->_obsoletes);
    }

    /**
     * Method for checking compatibility installation environment
     *
     * @return bool True if the installation environment is compatible
     */
    private function _checkCompatible()
    {
        // Only allow to install on Joomla! 2.5.0 or later with PHP 5.3.0 or later
        if(defined('PHP_VERSION'))
        {
            $version = PHP_VERSION;
        }
        elseif(function_exists('phpversion'))
        {
            $version = phpversion();
        }
        else
        {
            $version = '5.0.0'; // all bets are off!
        }

        if(!version_compare(JVERSION, '2.5.6', 'ge'))
        {
            $msg = '<p>' . JText::_('NO_SUPPORTED_JOOMLA') . '</p>';
            JError::raiseWarning(100, $msg);
            return false;
        }

        if(!version_compare($version, '5.3.1', 'ge'))
        {
            $msg = '<p>' . JText::_('NO_SUPPORTED_PHP') . '</p>';
            if(version_compare(JVERSION, '3.0', 'gt'))
            {
                JLog::add($msg, JLog::WARNING, 'jerror');
            }
            else
            {
                JError::raiseWarning(100, $msg);
            }
            return false;
        }

        return true;
    }

    /**
     * Method to activate subextensions in the administrative area
     */

    private function _activeSubextensions($subextensions = array())
    {
        // Initialize variables
        $db = JFactory::getDbo();

        // Checking for modules
        if (count($subextensions['modules']))
        {
            foreach ($subextensions['modules'] as $module => $preferences)
            {
                if (count($module))
                {
                    $query = $db->getQuery(true);
                    $query->select('COUNT(*)');
                    $query->from($db->quoteName('#__modules'));
                    $query->where($db->quoteName('module') . ' = ' . $db->quote($module));
                    $db->setQuery($query);
                    $count = $db->loadResult();

                    if($count > 0)
                    {
                        list($position, $published, $access, $params, $ordering) = $preferences;

                        // Activate modules

                        $query = $db->getQuery(true);
                        $query->update($db->quoteName('#__modules'));
                        $query->set(
                            array(
                                $db->quoteName('position') . ' = ' . $db->quote($position),
                                $db->quoteName('published') . ' = ' . $published,
                                $db->quoteName('access') . ' = ' . $access,
                                $db->quoteName('params') . ' = \'' . $params . '\'',
                                $db->quoteName('ordering') . ' = ' . $ordering
                                )
                            );
                        $query->where($db->quoteName('module') . ' = ' . $db->quote($module));
                        $db->setQuery($query);
                        $db->execute();

                        // Link to all pages
                        $query = $db->getQuery(true);
                        $query->select('id');
                        $query->from($db->quoteName('#__modules'));
                        $query->where($db->quoteName('module') . ' = ' . $db->quote($module));
                        $db->setQuery($query);
                        $moduleId = $db->loadResult();

                        $query = $db->getQuery(true);
                        $query->select('*');
                        $query->from($db->quoteName('#__modules_menu'));
                        $query->where($db->quoteName('moduleid') . ' = ' . $moduleId);
                        $db->setQuery($query);
                        $result = $db->loadColumn();
                        if(empty($result))
                        {
                            $query = $db->getQuery(true);
                            $query->insert($db->quoteName('#__modules_menu'));
                            $query->columns($db->quoteName(array('moduleid', 'menuid')));
                            $query->values(implode(',', array($moduleId, 0)));

                            $db->setQuery($query);
                            $db->execute();
                        }
                    }
                }
            }
        }

        // Checking for plugins
        if (count($subextensions['plugins']))
        {
            foreach ($subextensions['plugins'] as $plugin => $preferences)
            {
                list($group, $published) = $preferences;
                $query = $db->getQuery(true);
                $query->select('COUNT(*)');
                $query->from($db->quoteName('#__extensions'));
                $query->where($db->quoteName('element') . ' = ' . $db->quote($plugin));
                $query->where($db->quoteName('folder') . ' = ' . $db->quote($group));
                $db->setQuery($query);
                $count = $db->loadResult();

                if ($count > 0)
                {
                    $query = $db->getQuery(true);
                    $query->update($db->quoteName('#__extensions'));
                    $query->set($db->quoteName('enabled') . ' = ' . $db->quote($published));
                    $query->where($db->quoteName('element') . ' = ' . $db->quote($plugin));
                    $query->where($db->quoteName('folder') . ' = ' . $db->quote($group));
                    $db->setQuery($query);
                    $db->execute();
                }
            }
        }
    }

    /**
     * Method for bugfix for "DB function returned no error"
     */
    private function _bugfixDBFunctionReturnedNoError()
    {
        $db = JFactory::getDbo();

        // Fix broken #__assets records
        $query = $db->getQuery(true);
        $query->select('id')
            ->from('#__assets')
            ->where($db->qn('name') . ' = ' . $db->q($this->_extension));
        $db->setQuery($query);
        $ids = $db->loadColumn();

        if(!empty($ids))
        {
            foreach($ids as $id)
            {
                $query = $db->getQuery(true);
                $query->delete('#__assets')
                    ->where($db->qn('id') . ' = ' . $db->q($id));
                $db->setQuery($query);
                $db->execute();
            }
        }

        // Fix broken #__extensions records
        $query = $db->getQuery(true);
        $query->select('extension_id')
            ->from('#__extensions')
            ->where($db->qn('element') . ' = ' . $db->q($this->_extension));
        $db->setQuery($query);
        $ids = $db->loadColumn();

        if(!empty($ids))
        {
            foreach($ids as $id)
            {
                $query = $db->getQuery(true);
                $query->delete('#__extensions')
                    ->where($db->qn('extension_id') . ' = ' . $db->q($id));
                $db->setQuery($query);
                $db->execute();
            }
        }

        // Fix broken #__menu records
        $query = $db->getQuery(true);
        $query->select('id')
            ->from('#__menu')
            ->where($db->qn('type') . ' = ' . $db->q('component'))
            ->where($db->qn('menutype') . ' = ' . $db->q('main'))
            ->where($db->qn('link') . ' LIKE ' . $db->q('index.php?option=' . $this->_extension));
        $db->setQuery($query);
        $ids = $db->loadColumn();

        if(!empty($ids))
        {
            foreach($ids as $id)
            {
                $query = $db->getQuery(true);
                $query->delete('#__menu')
                    ->where($db->qn('id') . ' = ' . $db->q($id));
                $db->setQuery($query);
                $db->execute();
            }
        }
    }

    /**
     * Method for bugfix for "Can not build admin menus"
     */
    private function _bugfixCantBuildAdminMenus()
    {
        $db = JFactory::getDbo();

        // If there are multiple #__extensions record, keep one of them
        $query = $db->getQuery(true);
        $query->select('extension_id')
            ->from('#__extensions')
            ->where($db->qn('element') . ' = ' . $db->q($this->_extension));
        $db->setQuery($query);
        $ids = $db->loadColumn();

        if(count($ids) > 1)
        {
            asort($ids);
            $extension_id = array_shift($ids); // Keep the oldest id

            foreach($ids as $id)
            {
                $query = $db->getQuery(true);
                $query->delete('#__extensions')
                    ->where($db->qn('extension_id') . ' = ' . $db->q($id));
                $db->setQuery($query);
                $db->execute();
            }
        }

        // If there are multiple assets records, delete all except the oldest one
        $query = $db->getQuery(true);
        $query->select('id')
            ->from('#__assets')
            ->where($db->qn('name') . ' = ' . $db->q($this->_extension));
        $db->setQuery($query);
        $ids = $db->loadObjectList();
        if(count($ids) > 1)
        {
            asort($ids);
            $asset_id = array_shift($ids); // Keep the oldest id

            foreach($ids as $id)
            {
                $query = $db->getQuery(true);
                $query->delete('#__assets')
                    ->where($db->qn('id') . ' = ' . $db->q($id));
                $db->setQuery($query);
                $db->execute();
            }
        }

        // Remove #__menu records for good measure!
        $query = $db->getQuery(true);
        $query->select('id')
            ->from('#__menu')
            ->where($db->qn('type') . ' = ' . $db->q('component'))
            ->where($db->qn('menutype') . ' = ' . $db->q('main'))
            ->where($db->qn('link') . ' LIKE ' . $db->q('index.php?option='.$this->_extension));
        $db->setQuery($query);
        $ids1 = $db->loadColumn();

        if(empty($ids1))
        {
            $ids1 = array();
        }

        $query = $db->getQuery(true);
        $query->select('id')
            ->from('#__menu')
            ->where($db->qn('type') . ' = ' . $db->q('component'))
            ->where($db->qn('menutype') . ' = ' . $db->q('main'))
            ->where($db->qn('link') . ' LIKE ' . $db->q('index.php?option='.$this->_extension . '&%'));
        $db->setQuery($query);
        $ids2 = $db->loadColumn();

        if(empty($ids2))
        {
            $ids2 = array();
        }
        $ids = array_merge($ids1, $ids2);

        if(!empty($ids))
        {
            foreach($ids as $id)
            {
                $query = $db->getQuery(true);
                $query->delete('#__menu')
                    ->where($db->qn('id') . ' = ' . $db->q($id));
                $db->setQuery($query);
                $db->execute();
            }
        }
    }

    /**
     * Removes obsolete files and folders
     *
     * @param array $obsoletes
     */
    private function _removeObsoletes($obsoletes = array())
    {
        // Remove files
         if(!empty($obsoletes['files']))
        {
            foreach($obsoletes['files'] as $file)
            {
                $f = JPATH_ROOT . '/' . $file;
                if(!JFile::exists($f))
                {
                    continue;
                }
                JFile::delete($f);
            }
        }

        // Remove folders
        if(!empty($obsoletes['folders']))
        {
            foreach($obsoletes['folders'] as $folder)
            {
                $f = JPATH_ROOT . '/' . $folder;
                if(!JFolder::exists($f))
                {
                    continue;
                }
                JFolder::delete($f);
            }
        }
    }
}