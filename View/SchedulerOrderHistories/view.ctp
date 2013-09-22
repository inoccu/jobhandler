<div class="schedulerOrderHistories view">
<h2><?php echo __('Scheduler Order History'); ?></h2>
	<dl>
		<dt><?php echo __('History Id'); ?></dt>
		<dd>
			<?php echo h($schedulerOrderHistory['SchedulerOrderHistory']['history_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Job Chain'); ?></dt>
		<dd>
			<?php echo h($schedulerOrderHistory['SchedulerOrderHistory']['job_chain']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Order Id'); ?></dt>
		<dd>
			<?php echo h($schedulerOrderHistory['SchedulerOrderHistory']['order_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Spooler Id'); ?></dt>
		<dd>
			<?php echo h($schedulerOrderHistory['SchedulerOrderHistory']['spooler_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($schedulerOrderHistory['SchedulerOrderHistory']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State'); ?></dt>
		<dd>
			<?php echo h($schedulerOrderHistory['SchedulerOrderHistory']['state']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State Text'); ?></dt>
		<dd>
			<?php echo h($schedulerOrderHistory['SchedulerOrderHistory']['state_text']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Start Time'); ?></dt>
		<dd>
			<?php echo h($schedulerOrderHistory['SchedulerOrderHistory']['start_time']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('End Time'); ?></dt>
		<dd>
			<?php echo h($schedulerOrderHistory['SchedulerOrderHistory']['end_time']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Log'); ?></dt>
		<dd>
			<?php echo h($schedulerOrderHistory['SchedulerOrderHistory']['log']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Scheduler Order History'), array('action' => 'edit', $schedulerOrderHistory['SchedulerOrderHistory']['history_id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Scheduler Order History'), array('action' => 'delete', $schedulerOrderHistory['SchedulerOrderHistory']['history_id']), null, __('Are you sure you want to delete # %s?', $schedulerOrderHistory['SchedulerOrderHistory']['history_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Scheduler Order Histories'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Scheduler Order History'), array('action' => 'add')); ?> </li>
	</ul>
</div>
