<div class="jobChainOrders view">
<h2><?php echo __('Job Chain Order'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($jobChainOrder['JobChainOrder']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Host'); ?></dt>
		<dd>
			<?php echo $this->Html->link($jobChainOrder['Host']['name'], array('controller' => 'hosts', 'action' => 'view', $jobChainOrder['Host']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Job Chain'); ?></dt>
		<dd>
			<?php echo $this->Html->link($jobChainOrder['JobChain']['name'], array('controller' => 'job_chains', 'action' => 'view', $jobChainOrder['JobChain']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Scheduler Order'); ?></dt>
		<dd>
			<?php echo $this->Html->link($jobChainOrder['SchedulerOrder']['id'], array('controller' => 'scheduler_orders', 'action' => 'view', $jobChainOrder['SchedulerOrder']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nagios Service Description'); ?></dt>
		<dd>
			<?php echo h($jobChainOrder['JobChainOrder']['nagios_service_description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Param 1'); ?></dt>
		<dd>
			<?php echo h($jobChainOrder['JobChainOrder']['param_1']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Param 2'); ?></dt>
		<dd>
			<?php echo h($jobChainOrder['JobChainOrder']['param_2']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Param 3'); ?></dt>
		<dd>
			<?php echo h($jobChainOrder['JobChainOrder']['param_3']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Param 4'); ?></dt>
		<dd>
			<?php echo h($jobChainOrder['JobChainOrder']['param_4']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Param 5'); ?></dt>
		<dd>
			<?php echo h($jobChainOrder['JobChainOrder']['param_5']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($jobChainOrder['JobChainOrder']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($jobChainOrder['JobChainOrder']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Job Chain Order'), array('action' => 'edit', $jobChainOrder['JobChainOrder']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Job Chain Order'), array('action' => 'delete', $jobChainOrder['JobChainOrder']['id']), null, __('Are you sure you want to delete # %s?', $jobChainOrder['JobChainOrder']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Job Chain Orders'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Job Chain Order'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Hosts'), array('controller' => 'hosts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Host'), array('controller' => 'hosts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Job Chains'), array('controller' => 'job_chains', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Job Chain'), array('controller' => 'job_chains', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Scheduler Orders'), array('controller' => 'scheduler_orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Scheduler Order'), array('controller' => 'scheduler_orders', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Scheduler Order Histories'), array('controller' => 'scheduler_order_histories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Scheduler Order Histories'), array('controller' => 'scheduler_order_histories', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Scheduler Order Histories'); ?></h3>
	<?php if (!empty($jobChainOrder['SchedulerOrderHistories'])): ?>
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
	<?php foreach ($jobChainOrder['SchedulerOrderHistories'] as $schedulerOrderHistories): ?>
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
