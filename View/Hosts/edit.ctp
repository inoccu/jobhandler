<div class="hosts form">
<?php echo $this->Form->create('Host'); ?>
	<fieldset>
		<legend><?php echo __('Edit Host'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('nagios_host_name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Host.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Host.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Hosts'), array('action' => 'index')); ?></li>
	</ul>
</div>
