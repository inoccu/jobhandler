<div class="schedulerOrderHistories form">
<?php echo $this->Form->create('SchedulerOrderHistory'); ?>
	<fieldset>
		<legend><?php echo __('Add Scheduler Order History'); ?></legend>
	<?php
		echo $this->Form->input('job_chain');
		echo $this->Form->input('order_id');
		echo $this->Form->input('spooler_id');
		echo $this->Form->input('title');
		echo $this->Form->input('state');
		echo $this->Form->input('state_text');
		echo $this->Form->input('start_time');
		echo $this->Form->input('end_time');
		echo $this->Form->input('log');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Scheduler Order Histories'), array('action' => 'index')); ?></li>
	</ul>
</div>