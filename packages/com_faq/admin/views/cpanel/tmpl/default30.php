<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

// Get the current user object.
$user = JFactory::getUser();
?>
<div class="row-fluid">
	<div class="span2">
		<div class="sidebar-nav">
			<ul class="nav nav-list">
				<li class="nav-header"><?php echo JText::_('COM_FAQ_HEADER_SUBMENU'); ?></li>
				<li class="active"><a href="<?php echo $this->baseurl; ?>/index.php?option=com_faq"><?php echo JText::_('COM_FAQ_LINK_DASHBOARD'); ?></a></li>
				<li><a href="<?php echo $this->baseurl; ?>/index.php?option=com_faq&amp;view=faqs"><?php echo JText::_('COM_FAQ_LINK_FAQS'); ?></a></li>
				<li><a href="<?php echo $this->baseurl; ?>/index.php?option=com_categories&amp;extension=com_faq"><?php echo JText::_('COM_FAQ_LINK_CATEGORIES'); ?></a></li>
			</ul>
		</div>
	</div>
	<div class="span6">
		<?php
		foreach ($this->modules as $module)
		{
			$output = JModuleHelper::renderModule($module, array('style' => 'well'));
			$params = new JRegistry;
			$params->loadString($module->params);
			echo $output;
		}
		?>
	</div>
	<div class="span4">
		<?php
		foreach ($this->iconmodules as $iconmodule)
		{
			$output = JModuleHelper::renderModule($iconmodule, array('style' => 'well'));
			$params = new JRegistry;
			$params->loadString($iconmodule->params);
			echo $output;
		}
		?>
	</div>
</div>