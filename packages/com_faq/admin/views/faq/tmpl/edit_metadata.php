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

echo JHtml::_('sliders.panel', JText::_($fieldSets['metadata']->label), 'metadata-options');
if (isset($fieldSets['metadata']->description) && trim($fieldSets['metadata']->description)):
	echo '<p class="tip">' . $this->escape(JText::_($fieldSets['metadata']->description)) . '</p>';
endif; ?>
<fieldset class="panelform">
	<ul class="adminformlist">
		<?php foreach ($this->form->getFieldset('metadata') as $field) : ?>
			<li>
				<?php if (!$field->hidden) :
					echo $field->label;
				endif;
				echo $field->input; ?>
			</li>
		<?php endforeach;
		foreach($this->form->getGroup('metadata') as $field): ?>
			<li>
				<?php if (!$field->hidden) :
					echo $field->label;
				endif;
				echo $field->input; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</fieldset>