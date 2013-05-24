<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Makesoft, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

foreach ($this->form->getFieldsets('metadata') as $name => $fieldSet):
	$element = $this->form->getFieldset($name);
	if ($name == 'jmetadata' && !empty($element)) :
		echo JHtml::_('sliders.panel', JText::_($fieldSet->label), $name . '-options');
		if (isset($fieldSet->description) && trim($fieldSet->description)):
			echo '<p class="tip">' . $this->escape(JText::_($fieldSet->description)) . '</p>';
		endif;
		?>
		<fieldset class="panelform">
			<ul class="adminformlist">
			<?php foreach ($element as $field): ?>
				<li><?php echo $field->label; ?>
				<?php echo $field->input; ?></li>
			<?php endforeach; ?>
			</ul>
		</fieldset>
	<?php endif;
endforeach;