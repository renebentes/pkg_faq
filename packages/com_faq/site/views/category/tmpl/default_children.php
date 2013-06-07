<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

?>
<?php if (count($this->children[$this->category->id]) > 0) : ?>
	<ul class="nav nav-list">
		<?php foreach($this->children[$this->category->id] as $id => $child) :
			if ($this->params->get('show_empty_categories') || $child->getNumItems(true) || count($child->getChildren())) : ?>
				<li>
					<a href="<?php echo JRoute::_(FaqHelperRoute::getCategoryRoute($child->id));?>"><?php echo $this->escape($child->title); ?></a>
					<?php if ($this->params->get('show_cat_num_items', 1) == 1) : ?>
						<span class="label label-info"><?php echo JText::_('COM_FAQ_NUM_ITEMS') . ' ' . $child->getNumItems(true); ?></span>
					<?php endif; ?>
					<?php if (count($child->getChildren()) > 0 ) :
						$this->children[$child->id] = $child->getChildren();
						$this->category = $child;
						$this->maxLevel--;
						if ($this->maxLevel != 0) :
							echo $this->loadTemplate('children');
						endif;
						$this->category = $child->getParent();
						$this->maxLevel++;
					endif; ?>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>