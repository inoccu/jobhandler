<div class="schedulerOrders index">
	<h2><?php echo __('Scheduler Orders'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('spooler_id'); ?></th>
			<th><?php echo $this->Paginator->sort('job_chain'); ?></th>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('priority'); ?></th>
			<th><?php echo $this->Paginator->sort('state'); ?></th>
			<th><?php echo $this->Paginator->sort('state_text'); ?></th>
			<th><?php echo $this->Paginator->sort('title'); ?></th>
			<th><?php echo $this->Paginator->sort('created_time'); ?></th>
			<th><?php echo $this->Paginator->sort('mod_time'); ?></th>
			<th><?php echo $this->Paginator->sort('ordering'); ?></th>
			<th><?php echo $this->Paginator->sort('payload'); ?></th>
			<th><?php echo $this->Paginator->sort('run_time'); ?></th>
			<th><?php echo $this->Paginator->sort('initial_state'); ?></th>
			<th><?php echo $this->Paginator->sort('order_xml'); ?></th>
			<th><?php echo $this->Paginator->sort('distributed_next_time'); ?></th>
			<th><?php echo $this->Paginator->sort('occupying_cluster_member_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($schedulerOrders as $schedulerOrder): ?>
	<tr>
		<td><?php echo h($schedulerOrder['SchedulerOrder']['spooler_id']); ?>&nbsp;</td>
		<td><?php echo h($schedulerOrder['SchedulerOrder']['job_chain']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($schedulerOrder['JobChainOrder']['nagios_service_description'], array('controller' => 'job_chain_orders', 'action' => 'view', $schedulerOrder['JobChainOrder']['id'])); ?>
		</td>
		<td><?php echo h($schedulerOrder['SchedulerOrder']['priority']); ?>&nbsp;</td>
		<td><?php echo h($schedulerOrder['SchedulerOrder']['state']); ?>&nbsp;</td>
		<td><?php echo h($schedulerOrder['SchedulerOrder']['state_text']); ?>&nbsp;</td>
		<td><?php echo h($schedulerOrder['SchedulerOrder']['title']); ?>&nbsp;</td>
		<td><?php echo h($schedulerOrder['SchedulerOrder']['created_time']); ?>&nbsp;</td>
		<td><?php echo h($schedulerOrder['SchedulerOrder']['mod_time']); ?>&nbsp;</td>
		<td><?php echo h($schedulerOrder['SchedulerOrder']['ordering']); ?>&nbsp;</td>
		<td><?php echo h($schedulerOrder['SchedulerOrder']['payload']); ?>&nbsp;</td>
		<td><?php echo h($schedulerOrder['SchedulerOrder']['run_time']); ?>&nbsp;</td>
		<td><?php echo h($schedulerOrder['SchedulerOrder']['initial_state']); ?>&nbsp;</td>
		<td><?php echo h($schedulerOrder['SchedulerOrder']['order_xml']); ?>&nbsp;</td>
		<td><?php echo h($schedulerOrder['SchedulerOrder']['distributed_next_time']); ?>&nbsp;</td>
		<td><?php echo h($schedulerOrder['SchedulerOrder']['occupying_cluster_member_id']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $schedulerOrder['SchedulerOrder']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $schedulerOrder['SchedulerOrder']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $schedulerOrder['SchedulerOrder']['id']), null, __('Are you sure you want to delete # %s?', $schedulerOrder['SchedulerOrder']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Scheduler Order'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Job Chain Orders'), array('controller' => 'job_chain_orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Job Chain Order'), array('controller' => 'job_chain_orders', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Scheduler Order Histories'), array('controller' => 'scheduler_order_histories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Scheduler Order Histories'), array('controller' => 'scheduler_order_histories', 'action' => 'add')); ?> </li>
	</ul>
</div>
