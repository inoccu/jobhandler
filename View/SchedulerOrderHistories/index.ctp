<div class="schedulerOrderHistories index">
	<h2><?php echo __('Scheduler Order Histories'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('history_id'); ?></th>
			<th><?php echo $this->Paginator->sort('job_chain'); ?></th>
			<th><?php echo $this->Paginator->sort('order_id'); ?></th>
			<th><?php echo $this->Paginator->sort('spooler_id'); ?></th>
			<th><?php echo $this->Paginator->sort('title'); ?></th>
			<th><?php echo $this->Paginator->sort('state'); ?></th>
			<th><?php echo $this->Paginator->sort('state_text'); ?></th>
			<th><?php echo $this->Paginator->sort('start_time'); ?></th>
			<th><?php echo $this->Paginator->sort('end_time'); ?></th>
			<th><?php echo $this->Paginator->sort('log'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($schedulerOrderHistories as $schedulerOrderHistory): ?>
	<tr>
		<td><?php echo h($schedulerOrderHistory['SchedulerOrderHistory']['history_id']); ?>&nbsp;</td>
		<td><?php echo h($schedulerOrderHistory['SchedulerOrderHistory']['job_chain']); ?>&nbsp;</td>
		<td><?php echo h($schedulerOrderHistory['SchedulerOrderHistory']['order_id']); ?>&nbsp;</td>
		<td><?php echo h($schedulerOrderHistory['SchedulerOrderHistory']['spooler_id']); ?>&nbsp;</td>
		<td><?php echo h($schedulerOrderHistory['SchedulerOrderHistory']['title']); ?>&nbsp;</td>
		<td><?php echo h($schedulerOrderHistory['SchedulerOrderHistory']['state']); ?>&nbsp;</td>
		<td><?php echo h($schedulerOrderHistory['SchedulerOrderHistory']['state_text']); ?>&nbsp;</td>
		<td><?php echo h($schedulerOrderHistory['SchedulerOrderHistory']['start_time']); ?>&nbsp;</td>
		<td><?php echo h($schedulerOrderHistory['SchedulerOrderHistory']['end_time']); ?>&nbsp;</td>
		<td><?php echo h($schedulerOrderHistory['SchedulerOrderHistory']['log']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $schedulerOrderHistory['SchedulerOrderHistory']['history_id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $schedulerOrderHistory['SchedulerOrderHistory']['history_id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $schedulerOrderHistory['SchedulerOrderHistory']['history_id']), null, __('Are you sure you want to delete # %s?', $schedulerOrderHistory['SchedulerOrderHistory']['history_id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Scheduler Order History'), array('action' => 'add')); ?></li>
	</ul>
</div>
