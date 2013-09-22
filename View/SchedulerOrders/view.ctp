<div class="schedulerOrders view">
<h2><?php echo __('Scheduler Order'); ?></h2>
	<dl>
		<dt><?php echo __('Spooler Id'); ?></dt>
		<dd>
			<?php echo h($schedulerOrder['SchedulerOrder']['spooler_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Job Chain'); ?></dt>
		<dd>
			<?php echo h($schedulerOrder['SchedulerOrder']['job_chain']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Job Chain Order'); ?></dt>
		<dd>
			<?php echo $this->Html->link($schedulerOrder['JobChainOrder']['nagios_service_description'], array('controller' => 'job_chain_orders', 'action' => 'view', $schedulerOrder['JobChainOrder']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Priority'); ?></dt>
		<dd>
			<?php echo h($schedulerOrder['SchedulerOrder']['priority']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State'); ?></dt>
		<dd>
			<?php echo h($schedulerOrder['SchedulerOrder']['state']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State Text'); ?></dt>
		<dd>
			<?php echo h($schedulerOrder['SchedulerOrder']['state_text']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($schedulerOrder['SchedulerOrder']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created Time'); ?></dt>
		<dd>
			<?php echo h($schedulerOrder['SchedulerOrder']['created_time']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mod Time'); ?></dt>
		<dd>
			<?php echo h($schedulerOrder['SchedulerOrder']['mod_time']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Ordering'); ?></dt>
		<dd>
			<?php echo h($schedulerOrder['SchedulerOrder']['ordering']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Payload'); ?></dt>
		<dd>
			<?php echo h($schedulerOrder['SchedulerOrder']['payload']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Run Time'); ?></dt>
		<dd>
			<?php echo h($schedulerOrder['SchedulerOrder']['run_time']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Initial State'); ?></dt>
		<dd>
			<?php echo h($schedulerOrder['SchedulerOrder']['initial_state']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Order Xml'); ?></dt>
		<dd>
			<?php echo h($schedulerOrder['SchedulerOrder']['order_xml']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Distributed Next Time'); ?></dt>
		<dd>
			<?php echo h($schedulerOrder['SchedulerOrder']['distributed_next_time']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Occupying Cluster Member Id'); ?></dt>
		<dd>
			<?php echo h($schedulerOrder['SchedulerOrder']['occupying_cluster_member_id']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Scheduler Order'), array('action' => 'edit', $schedulerOrder['SchedulerOrder']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Scheduler Order'), array('action' => 'delete', $schedulerOrder['SchedulerOrder']['id']), null, __('Are you sure you want to delete # %s?', $schedulerOrder['SchedulerOrder']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Scheduler Orders'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Scheduler Order'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Job Chain Orders'), array('controller' => 'job_chain_orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Job Chain Order'), array('controller' => 'job_chain_orders', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Scheduler Order Histories'), array('controller' => 'scheduler_order_histories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Scheduler Order Histories'), array('controller' => 'scheduler_order_histories', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Scheduler Order Histories'); ?></h3>
	<?php if (!empty($schedulerOrder['SchedulerOrderHistories'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('History Id'); ?></th>
		<th><?php echo __('Job Chain'); ?></th>
		<th><?php echo __('Order Id'); ?></th>
		<th><?php echo __('Spooler Id'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('State'); ?></th>
		<th><?php echo __('State Text'); ?></th>
		<th><?php echo __('Start Time'); ?></th>
		<th><?php echo __('End Time'); ?></th>
		<th><?php echo __('Log'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($schedulerOrder['SchedulerOrderHistories'] as $schedulerOrderHistories): ?>
		<tr>
			<td><?php echo $schedulerOrderHistories['history_id']; ?></td>
			<td><?php echo $schedulerOrderHistories['job_chain']; ?></td>
			<td><?php echo $schedulerOrderHistories['order_id']; ?></td>
			<td><?php echo $schedulerOrderHistories['spooler_id']; ?></td>
			<td><?php echo $schedulerOrderHistories['title']; ?></td>
			<td><?php echo $schedulerOrderHistories['state']; ?></td>
			<td><?php echo $schedulerOrderHistories['state_text']; ?></td>
			<td><?php echo $schedulerOrderHistories['start_time']; ?></td>
			<td><?php echo $schedulerOrderHistories['end_time']; ?></td>
			<td><?php echo $schedulerOrderHistories['log']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'scheduler_order_histories', 'action' => 'view', $schedulerOrderHistories['history_id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'scheduler_order_histories', 'action' => 'edit', $schedulerOrderHistories['history_id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'scheduler_order_histories', 'action' => 'delete', $schedulerOrderHistories['history_id']), null, __('Are you sure you want to delete # %s?', $schedulerOrderHistories['history_id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Scheduler Order Histories'), array('controller' => 'scheduler_order_histories', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
