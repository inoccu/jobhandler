<div class="jobChains form">
<?php echo $this->Form->create('JobChain'); ?>
	<fieldset>
		<legend><?php echo __('Add Job Chain'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('job_chain_path');
		echo $this->Form->input('param_name_1');
		echo $this->Form->input('param_name_2');
		echo $this->Form->input('param_name_3');
		echo $this->Form->input('param_name_4');
		echo $this->Form->input('param_name_5');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Job Chains'), array('action' => 'index')); ?></li>
	</ul>
</div>
