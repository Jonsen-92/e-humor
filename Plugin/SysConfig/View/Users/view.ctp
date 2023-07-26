<div class="users view">
	<h2><?php echo __('User');?></h2>
	<dl>
		<?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $val['UserAccount']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $val['UserAccount']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Username'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $val['UserAccount']['username']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Email'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $val['UserAccount']['email']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Is Actived'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $val['UserAccount']['is_actived']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Is Login'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $val['UserAccount']['is_login']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Last Login'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $val['UserAccount']['last_login']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Last Activity'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $val['UserAccount']['last_activity']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $val['UserAccount']['created']; ?>
			&nbsp;
		</dd>
	</dl-->
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit User', true), array('action' => 'edit', $val['UserAccount']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete User', true), array('action' => 'delete', $val['UserAccount']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $val['UserAccount']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Users', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Groups', true), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group', true), array('controller' => 'groups', 'action' => 'add')); ?> </li>
	</ul>
</div>

<?php if (!empty($val['GroupAccount'])) : ?>
<div class="related">
	<h3><?php echo __('Related Groups');?></h3>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($val['GroupAccount'] as $group):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $group['id'];?></td>
			<td><?php echo $group['title'];?></td>
			<td><?php echo $group['description'];?></td>
			<td><?php echo $group['created'];?></td>
			<td><?php echo $group['modified'];?></td>
			
		</tr>
	<?php endforeach; ?>
	</table>
</div>
<?php endif; ?>

<!--
<?php if (!empty($val['Branch'])) : ?>
<div class="related">
	<h3><?php echo __('Related Branches');?></h3>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Address'); ?></th>
		<th><?php echo __('Phone Num'); ?></th>
		<th><?php echo __('Fax Num'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($val['Branch'] as $branch):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $branch['id'];?></td>
			<td><?php echo $branch['name'];?></td>
			<td><?php echo $branch['address'];?></td>
			<td><?php echo $branch['phone_num'];?></td>
			<td><?php echo $branch['fax_num'];?></td>
			
		</tr>
	<?php endforeach; ?>
	</table>
</div>
<?php endif; ?>

<?php if (!empty($val['Region'])) : ?>
<div class="related">
	<h3><?php echo __('Related Regions');?></h3>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($val['Region'] as $region):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $region['id'];?></td>
			<td><?php echo $region['name'];?></td>
			<td><?php echo $region['created'];?></td>
			<td><?php echo $region['modified'];?></td>
			
		</tr>
	<?php endforeach; ?>
	</table>
</div>
<?php endif; ?>
-->
