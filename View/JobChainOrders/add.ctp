<div class="jobChainOrders form">
<?php echo $this->Form->create('JobChainOrder'); ?>
	<fieldset>
		<legend><?php echo __('Add Job Chain Order'); ?></legend>
	<?php
		echo $this->Form->input('host_id');
		echo $this->Form->input('job_chain_id');
		echo $this->Form->input('order_id', array('type' => 'text', 'readonly' => 'readonly'));
		echo $this->Form->input('nagios_service_description');
		echo $this->Form->input('run_time');
		echo $this->Form->input('param_1');
		echo $this->Form->input('param_2');
		echo $this->Form->input('param_3');
		echo $this->Form->input('param_4');
		echo $this->Form->input('param_5');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Job Chain Orders'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Hosts'), array('controller' => 'hosts', 'action' => 'index')); ?> </li>
	</ul>
</div>
