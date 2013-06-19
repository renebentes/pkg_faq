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

// Get document
$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::root() . 'media/com_faq/css/frontend.css');

// Create shortcuts to some parameters.
$params  = $this->item->params;
$canEdit = $params->get('access-edit');
$user    = JFactory::getUser();

// Load the tooltip behavior.
JHtml::_('behavior.caption');
?>
<section class="faq-item<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')): ?>
	<div class="page-header">
		<h1>
			<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>
	</div>
	<?php endif; ?>

	<article>
		<header>
			<h2><?php echo $this->escape($this->item->title); ?></h2>
		</header>

		<aside class="aside-tools clearfix">
			<?php if (($params->get('show_author')) or ($params->get('show_category')) or ($params->get('show_create_date')) or ($params->get('show_modify_date')) or ($params->get('show_publish_date')) or ($params->get('show_parent_category')) or ($params->get('show_hits'))) : ?>
				<dl class="item-info pull-left">
	 				<dt class="item-info-term"><?php  echo JText::_('COM_FAQ_INFO'); ?></dt>
			<?php endif; ?>

			<?php if (($params->get('show_author') && !empty($this->item->author)) || ($params->get('show_parent_category') && $this->item->parent_slug != '1:root') || $params->get('show_category')) : ?>
				<dd>
					<?php if ($params->get('show_author') && !empty($this->item->author)) : ?>
						<i class="icon-user"></i>
						<?php $author =  $this->item->author; ?>
						<?php $author = ($this->item->created_by_alias ? $this->item->created_by_alias : $author);?>

						<?php if (!empty($this->item->contactid ) &&  $params->get('link_author') == true):?>
							<?php echo JText::sprintf('COM_FAQ_WRITTEN_BY' ,
						 	'<span>' . JHtml::_('link', JRoute::_('index.php?option=com_contact&view=contact&id='.$this->item->contactid), $author) . '</span>'); ?>
						<?php else :?>
							<?php echo JText::sprintf('COM_FAQ_WRITTEN_BY', '<span>' . $author . '</span>'); ?>
						<?php endif; ?>

						<?php if ($params->get('show_parent_category') && $this->item->parent_slug != '1:root') : ?>
							<i class="icon-tags"></i>
							<?php $title = $this->escape($this->item->parent_title);
							$url = '<a href="' . JRoute::_(FaqHelperRoute::getCategoryRoute($this->item->parent_slug)) . '">' . $title . '</a>'; ?>
							<?php if ($params->get('link_parent_category') and $this->item->parent_slug) : ?>
								<?php echo JText::sprintf('COM_FAQ_PARENT', $url); ?>
							<?php else : ?>
								<?php echo JText::sprintf('COM_FAQ_PARENT', $title); ?>
							<?php endif; ?>
						<?php endif; ?>
					<?php endif; ?>

					<?php if ($params->get('show_category')) : ?>
						<i class="icon-tag"></i>
						<?php $title = $this->escape($this->item->category_title);
						$url = '<a href="' . JRoute::_(FaqHelperRoute::getCategoryRoute($this->item->catslug)) . '">' . $title . '</a>';?>
						<?php if ($params->get('link_category') and $this->item->catslug) : ?>
							<?php echo JText::sprintf('COM_FAQ_CATEGORY', $url); ?>
						<?php else : ?>
							<?php echo JText::sprintf('COM_FAQ_CATEGORY', $title); ?>
						<?php endif; ?>
					<?php endif; ?>
			<?php endif; ?>

			<?php if ($params->get('show_publish_date') || $params->get('show_created_date') || $params->get('show_modified_date')) : ?>
				<dd>
					<?php if ($params->get('show_create_date')) : ?>
						<i class="icon-time"></i>
						<?php echo JText::sprintf('COM_FAQ_CREATED_DATE_ON', JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3'))); ?>
					<?php endif; ?>

					<?php if ($params->get('show_modify_date')) : ?>
						<i class="icon-time"></i>
						<?php echo JText::sprintf('COM_FAQ_LAST_UPDATED', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3'))); ?>
					<?php endif; ?>

					<?php if ($params->get('show_publish_date')) : ?>
						<i class="icon-time"></i>
						<?php echo JText::sprintf('COM_FAQ_PUBLISHED_DATE_ON', JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC3'))); ?>
					<?php endif; ?>
				</dd>
			<?php endif; ?>

			<?php if ($params->get('show_hits')) : ?>
      			<dd>
      				<i class="icon-signal"></i>
      				<?php echo JText::sprintf('COM_FAQ_HITS', '<span>'.$this->item->hits.'</span>'); ?>
      			</dd>
      		<?php endif; ?>

			<?php if (($params->get('show_author')) or ($params->get('show_category')) or ($params->get('show_publish_date')) or ($params->get('show_parent_category')) or ($params->get('show_hits'))) : ?>
 				</dl>
			<?php endif; ?>

			<?php if (!$this->print) : ?>
				<?php if ($canEdit || $params->get('show_print_icon', 1) || $params->get('show_email_icon', 1)): ?>
					<div class="btn-group pull-right">
						<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
							<i class="icon-cog"></i>
							<span class="caret"></span>
						</a>
						<?php // Note the actions class is deprecated. Use dropdown-menu instead. ?>
						<ul class="dropdown-menu actions">
							<?php if ($params->get('show_print_icon', 1)): ?>
								<li class="print-icon"><?php echo JHtml::_('icon.print_popup', $this->item, $params); ?></li>
							<?php endif; ?>
							<?php if ($params->get('show_email_icon', 1)): ?>
								<li class="email-icon"><?php echo JHtml::_('icon.email', $this->item, $params); ?></li>
							<?php endif; ?>
							<?php if ($canEdit): ?>
								<li class="edit-icon"><?php echo JHtml::_('icon.edit', $this->item, $params); ?></li>
							<?php endif; ?>
						</ul>
					</div>
				<?php endif; ?>
			<?php else : ?>
				<div class="pull-right">
					<?php echo JHtml::_('icon.print_screen', $this->item, $params); ?>
				</div>
			<?php endif; ?>
		</aside>
		<section class="item-content clearfix">
			<?php echo $this->item->description; ?>
		</section>
	</article>
</section>

<?php var_dump($this->params); ?>