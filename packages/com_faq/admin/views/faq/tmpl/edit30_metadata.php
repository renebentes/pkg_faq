<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

$fieldSets = $this->form->getFieldsets();
echo JHtml::_('bootstrap.addTab', 'myTab', 'metadata-options', JText::_($fieldSets['metadata']->label, true)); ?>
	<div class="tab-pane" id="metadata-options">
	<?php if (isset($fieldSet->description) && trim($fieldSet->description)):
		echo '<p class="alert alert-info">'.$this->escape(JText::_($fieldSet->description)).'</p>';
	endif;
	foreach ($this->form->getFieldset('metadata') as $field) : ?>
		<div class="control-group">
		<?php if (!$field->hidden) : ?>
			<div class="control-label"><?php echo $field->label; ?></div>
		<?php endif; ?>
			<div class="controls"><?php echo $field->input; ?></div>
		</div>
	<?php endforeach;
	foreach($this->form->getGroup('metadata') as $field): ?>
		<div class="control-group">
		<?php if (!$field->hidden) : ?>
			<div class="control-label"><?php echo $field->label; ?></div>
		<?php endif; ?>
			<div class="controls"><?php echo $field->input; ?></div>
		</div>
	<?php endforeach; ?>
	</div>
<?php echo JHtml::_('bootstrap.endTab');