<div class="jobChains view">
<h2><?php echo __('Job Chain'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($jobChain['JobChain']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($jobChain['JobChain']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Job Chain Path'); ?></dt>
		<dd>
			<?php echo h($jobChain['JobChain']['job_chain_path']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Param Name 1'); ?></dt>
		<dd>
			<?php echo h($jobChain['JobChain']['param_name_1']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Param Name 2'); ?></dt>
		<dd>
			<?php echo h($jobChain['JobChain']['param_name_2']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Param Name 3'); ?></dt>
		<dd>
			<?php echo h($jobChain['JobChain']['param_name_3']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Param Name 4'); ?></dt>
		<dd>
			<?php echo h($jobChain['JobChain']['param_name_4']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Param Name 5'); ?></dt>
		<dd>
			<?php echo h($jobChain['JobChain']['param_name_5']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($jobChain['JobChain']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($jobChain['JobChain']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Job Chain'), array('action' => 'edit', $jobChain['JobChain']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Job Chain'), array('action' => 'delete', $jobChain['JobChain']['id']), null, __('Are you sure you want to delete # %s?', $jobChain['JobChain']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Job Chains'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Job Chain'), array('action' => 'add')); ?> </li>
	</ul>
</div>
