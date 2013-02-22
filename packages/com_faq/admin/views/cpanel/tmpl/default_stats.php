<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Makesoft, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

?>

<?php echo JHtml::_('sliders.start', 'stat-pane'); ?>
<?php echo JHtml::_('sliders.panel', JText::_('COM_FAQ_FIELDSET_FAQS'), 'faqs-panel'); ?>
<pre>
	<?php var_dump($this); ?>
</pre>
<table class="adminlist">
	<thead>
		<tr>
			<th width="1%" class="nowrap">
				<?php echo JText::_('JGRID_HEADING_ID'); ?>
			</th>
			<th class="left">
				<?php echo JText::_('COM_FAQ_HEADING_TITLE'); ?>
			</th>
			<th width="10%">
				<?php echo JText::_('JDATE'); ?>
			</th>
			<th width="5%">
				<?php echo JText::_('JGLOBAL_HITS'); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	if ($this->items):
		foreach ($this->items as $item):

		// Define variables
		$link = JRoute::_('index.php?option=com_faq&task=faq.edit&id=' . $item->id);
		$component = JRequest::getCmd('option');
		?>
		<tr class="row<?php echo $k; ?>">
			<td class="center">
				<?php echo $this->escape($item->id); ?>
			</td>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $this->escape($item->name); ?></a>
			</td>
			<td class="center">
				<?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?>
			</td>
			<td class="center nowrap">
				<?php echo $this->escape($item->hits); ?>
			</td>
		</tr>
		<?php $k = 1 - $k; ?>
		<?php endforeach; ?>
	<?php else: ?>
		<?php echo '<tr class="row' . $k . '"><td colspan="3" align="center">' . JText::_('COM_FAQ_NO_RESULTS') . '</td></tr>'; ?>
	<?php endif; ?>
	</tbody>
</table>
<?php echo JHtml::_('sliders.end'); ?>