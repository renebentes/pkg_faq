<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>
<?php if (empty($this->items) && $this->params->get('show_no_results', 1)) : ?>
<p><?php echo JText::_('COM_FAQ_NO_RESULTS'); ?></p>
<?php else : ?>
<div class="accordion" id="accordion2">
	<?php
	$x = 1;
	foreach ($this->items as $item) : ?>
	<div class="accordion-group">
		<div class="accordion-heading">
			<a href="#" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" data-target="#collapse<?php echo $item->id; ?>" >
				<i class="icon-question-sign"></i>
				<?php echo $this->escape($item->title); ?>
			</a>
		</div>
		<div id="collapse<?php echo $item->id; ?>" class="accordion-body collapse<?php echo $x == 1 ? ' in' : ''; ?>">
			<div class="accordion-inner">
				<?php echo JHtml::_('content.prepare', $item->description); ?>
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