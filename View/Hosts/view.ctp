<div class="hosts view">
<h2><?php echo __('Host'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($host['Host']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($host['Host']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nagios Host Name'); ?></dt>
		<dd>
			<?php echo h($host['Host']['nagios_host_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($host['Host']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($host['Host']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Host'), array('action' => 'edit', $host['Host']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Host'), array('action' => 'delete', $host['Host']['id']), null, __('Are you sure you want to delete # %s?', $host['Host']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Hosts'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Host'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Job Chain Orders'), array('controller' => 'job_chain_orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Job Chain Order'), array('controller' => 'job_chain_orders', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Job Chain Orders'); ?></h3>
	<?php if (!empty($host['JobChainOrder'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Host Id'); ?></th>
		<th><?php echo __('Job Chain Id'); ?></th>
		<th><?php echo __('Order Id'); ?></th>
		<th><?php echo __('Param 1'); ?></th>
		<th><?php echo __('Param 2'); ?></th>
		<th><?php echo __('Param 3'); ?></th>
		<th><?php echo __('Param 4'); ?></th>
		<th><?php echo __('Param 5'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($host['JobChainOrder'] as $jobChainOrder): ?>
		<tr>
			<td><?php echo $jobChainOrder['id']; ?></td>
			<td><?php echo $jobChainOrder['host_id']; ?></td>
			<td><?php echo $jobChainOrder['job_chain_id']; ?></td>
			<td><?php echo $jobChainOrder['order_id']; ?></td>
			<td><?php echo $jobChainOrder['param_1']; ?></td>
			<td><?php echo $jobChainOrder['param_2']; ?></td>
			<td><?php echo $jobChainOrder['param_3']; ?></td>
			<td><?php echo $jobChainOrder['param_4']; ?></td>
			<td><?php echo $jobChainOrder['param_5']; ?></td>
			<td><?php echo $jobChainOrder['created']; ?></td>
			<td><?php echo $jobChainOrder['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'job_chain_orders', 'action' => 'view', $jobChainOrder['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'job_chain_orders', 'action' => 'edit', $jobChainOrder['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'job_chain_orders', 'action' => 'delete', $jobChainOrder['id']), null, __('Are you sure you want to delete # %s?', $jobChainOrder['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Job Chain Order'), array('controller' => 'job_chain_orders', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
