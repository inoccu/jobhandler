<div class="jobChainOrders index">
	<h2><?php echo __('Job Chain Orders'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('host_id'); ?></th>
			<th><?php echo $this->Paginator->sort('job_chain_id'); ?></th>
			<th><?php echo $this->Paginator->sort('order_id'); ?></th>
			<th><?php echo $this->Paginator->sort('nagios_service_description'); ?></th>
			<th><?php echo $this->Paginator->sort('run_time'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($jobChainOrders as $jobChainOrder): ?>
	<tr>
		<td><?php echo h($jobChainOrder['JobChainOrder']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($jobChainOrder['Host']['name'], array('controller' => 'hosts', 'action' => 'view', $jobChainOrder['Host']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($jobChainOrder['JobChain']['name'], array('controller' => 'job_chains', 'action' => 'view', $jobChainOrder['JobChain']['id'])); ?>
		</td>
		<td>
			<?php echo $jobChainOrder['SchedulerOrder']['id']; ?>
		</td>
		<td><?php echo h($jobChainOrder['JobChainOrder']['nagios_service_description']); ?>&nbsp;</td>
		<td><?php echo h($jobChainOrder['JobChainOrder']['run_time']); ?></td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $jobChainOrder['JobChainOrder']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $jobChainOrder['JobChainOrder']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $jobChainOrder['JobChainOrder']['id']), null, __('Are you sure you want to delete # %s?', $jobChainOrder['JobChainOrder']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Job Chain Order'), array('action' => 'add')); ?></li>
	</ul>
</div>
