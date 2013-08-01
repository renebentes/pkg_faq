<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

$doc = JFactory::getDocument();
$doc->addScript(JURI::root() . 'media/com_faq/js/faq.js');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>
<?php if (empty($this->items) && $this->params->get('show_no_results', 1)) : ?>
<p><?php echo JText::_('COM_FAQ_NO_RESULTS'); ?></p>
<?php else : ?>
<div id="faq-items" class="accordion">
	<?php
	$x = 1;
	foreach ($this->items as $item) : ?>
	<div class="accordion-group">
		<div class="accordion-heading">
			<a href="#collapse<?php echo $item->id; ?>" class="accordion-toggle" data-toggle="collapse" data-parent="#faq-items" onclick="hit(<?php echo $item->id; ?>);">
				<i class="icon-question-sign"></i>
				<?php echo $this->escape($item->title); ?>
			</a>
		</div>
		<div id="collapse<?php echo $item->id; ?>" class="accordion-body collapse<?php echo $x == 1 ? ' in' : ''; ?>">
			<div class="accordion-inner">
				<?php echo JHtml::_('content.prepare', $item->description); ?>
				<dl class="pull-right">
					<dd>
						<i class="icon-eye-open"></i>
						<span id="hits<?php echo $item->id; ?>">
							<?php echo JText::sprintf('COM_FAQ_HITS', $item->hits); ?>
						</span>
					</dd>
					<dd>
						<i class="icon-thumbs-up"></i>
						<span id="up<?php echo $item->id; ?>"></span>
						<i class="icon-thumbs-down"></i>
						<span id="down<?php echo $item->id; ?>"></span>
				</dl>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	<?php
	$x++;
	endforeach; ?>
</div>
<?php if ($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2 && $this->pagination->get('pages.total') > 1)) : ?>
<nav class="pagination pagination-centered">
	<?php echo $this->pagination->getPagesLinks(); ?>
	<?php if ($this->params->def('show_pagination_results', 1)) : ?>
	<p class="counter muted"><?php echo $this->pagination->getPagesCounter(); ?></p>
	<?php endif; ?>
</nav>
<?php endif;?>
<?php endif;?>