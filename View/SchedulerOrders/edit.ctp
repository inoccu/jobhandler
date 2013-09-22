<div class="schedulerOrders form">
<?php echo $this->Form->create('SchedulerOrder'); ?>
	<fieldset>
		<legend><?php echo __('Edit Scheduler Order'); ?></legend>
	<?php
		echo $this->Form->input('spooler_id');
		echo $this->Form->input('job_chain');
		echo $this->Form->input('id');
		echo $this->Form->input('priority');
		echo $this->Form->input('state');
		echo $this->Form->input('state_text');
		echo $this->Form->input('title');
		echo $this->Form->input('created_time');
		echo $this->Form->input('mod_time');
		echo $this->Form->input('ordering');
		echo $this->Form->input('payload');
		echo $this->Form->input('run_time');
		echo $this->Form->input('initial_state');
		echo $this->Form->input('order_xml');
		echo $this->Form->input('distributed_next_time');
		echo $this->Form->input('occupying_cluster_member_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('SchedulerOrder.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('SchedulerOrder.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Scheduler Orders'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Job Chain Orders'), array('controller' => 'job_chain_orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Job Chain Order'), array('controller' => 'job_chain_orders', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Scheduler Order Histories'), array('controller' => 'scheduler_order_histories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Scheduler Order Histories'), array('controller' => 'scheduler_order_histories', 'action' => 'add')); ?> </li>
	</ul>
</div>
