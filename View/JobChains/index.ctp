<div class="jobChains index">
	<h2><?php echo __('Job Chains'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('job_chain_path'); ?></th>
			<th><?php echo $this->Paginator->sort('param_name_1'); ?></th>
			<th><?php echo $this->Paginator->sort('param_name_2'); ?></th>
			<th><?php echo $this->Paginator->sort('param_name_3'); ?></th>
			<th><?php echo $this->Paginator->sort('param_name_4'); ?></th>
			<th><?php echo $this->Paginator->sort('param_name_5'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($jobChains as $jobChain): ?>
	<tr>
		<td><?php echo h($jobChain['JobChain']['id']); ?>&nbsp;</td>
		<td><?php echo h($jobChain['JobChain']['name']); ?>&nbsp;</td>
		<td><?php echo h($jobChain['JobChain']['job_chain_path']); ?>&nbsp;</td>
		<td><?php echo h($jobChain['JobChain']['param_name_1']); ?>&nbsp;</td>
		<td><?php echo h($jobChain['JobChain']['param_name_2']); ?>&nbsp;</td>
		<td><?php echo h($jobChain['JobChain']['param_name_3']); ?>&nbsp;</td>
		<td><?php echo h($jobChain['JobChain']['param_name_4']); ?>&nbsp;</td>
		<td><?php echo h($jobChain['JobChain']['param_name_5']); ?>&nbsp;</td>
		<td><?php echo h($jobChain['JobChain']['created']); ?>&nbsp;</td>
		<td><?php echo h($jobChain['JobChain']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $jobChain['JobChain']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $jobChain['JobChain']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $jobChain['JobChain']['id']), null, __('Are you sure you want to delete # %s?', $jobChain['JobChain']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Job Chain'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Job Chain Orders'), array('controller' => 'job_chain_orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Job Chain Order'), array('controller' => 'job_chain_orders', 'action' => 'add')); ?> </li>
	</ul>
</div>
