<?php
/**
 * @package     Faq
 * @subpackage  com_faq
 * @copyright   Copyright (C) 2013 Rene Bentes Pinto. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 */

// No direct access.
defined('_JEXEC') or die;

if (count($this->items[$this->parent->id]) > 0 && $this->maxLevel != 0) : ?>
<ul class="nav nav-tabs">
<?php foreach($this->items[$this->parent->id] as $id => $item) : ?>
	<?php if ($this->params->get('show_empty_categories') || $item->numitems || count($item->getChildren())) : ?>
	<li>
		<?php if (count($item->getChildren()) > 0) :
			$this->items[$item->id] = $item->getChildren();
			$this->parent = $item;
			$this->maxLevel--;
			echo $this->loadTemplate('items');
			$this->parent = $item->getParent();
			$this->maxLevel++;
		endif; ?>
	</li>
	<?php endif; ?>
<?php endforeach; ?>
</ul>
<?php endif; ?>