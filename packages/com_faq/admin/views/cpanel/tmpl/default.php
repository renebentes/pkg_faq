<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

// Get document
$doc = JFactory::getDocument();
$doc->setTitle(JText::_('COM_FAQ_CPANEL_TITLE'));
$doc->addStyleSheet(JURI::root() . 'media/com_faq/css/backend.css');

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

?>
<div class="adminform">
	<div class="cpanel-left">
		<div id="cpanel">
			<?php echo FaqHelper::button('cpanel', 'cpanel.png', JText::_('COM_FAQ_QUICKICON_CPANEL')); ?>
			<?php echo FaqHelper::button('faqs', 'faq.png', JText::_('COM_FAQ_QUICKICON_FAQS')); ?>
			<?php echo FaqHelper::button('categories', 'category.png', JText::_('COM_FAQ_QUICKICON_CATEGORIES')); ?>
		</div>
	</div>
	<div class="cpanel-right">
		<?php
		echo JHtml::_('sliders.start', 'panel-sliders', array('useCookie'=>'1'));

		foreach ($this->modules as $module)
		{
			$output = JModuleHelper::renderModule($module);
			$params = new JRegistry;
			$params->loadString($module->params);
			if ($params->get('automatic_title', '0') == '0')
			{
				echo JHtml::_('sliders.panel', $module->title, 'faq-cpanel-panel-' . $module->name);
			}
			elseif (method_exists('mod' . $module->name . 'Helper', 'getTitle'))
			{
				echo JHtml::_('sliders.panel', call_user_func_array(array('mod' . $module->name . 'Helper', 'getTitle'), array($params)), 'cpanel-panel-'. $module->name);
			}
			else
			{
				echo JHtml::_('sliders.panel', JText::_('MOD_' . $module->name .'_TITLE'), 'faq-cpanel-panel-' . $module->name);
			}
			echo $output;
		}

		echo JHtml::_('sliders.end');
		?>
	</div>
</div>