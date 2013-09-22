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
		<li><?php echo $this->Html->link(__('List Job Chain Orders'), array('controller' => 'job_chain_orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Job Chain Order'), array('controller' => 'job_chain_orders', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Job Chain Orders'); ?></h3>
	<?php if (!empty($jobChain['JobChainOrder'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Host Id'); ?></th>
		<th><?php echo __('Job Chain Id'); ?></th>
		<th><?php echo __('Order Id'); ?></th>
		<th><?php echo __('Nagios Service Description'); ?></th>
		<th><?php echo __('Param 1'); ?></th>
		<th><?php echo __('Param 2'); ?></th>
		<th><?php echo __('Param 3'); ?></th>
		<th><?php echo __('Param 4'); ?></th>
		<th><?php echo __('Param 5'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($jobChain['JobChainOrder'] as $jobChainOrder): ?>
		<tr>
			<td><?php echo $jobChainOrder['id']; ?></td>
			<td><?php echo $jobChainOrder['host_id']; ?></td>
			<td><?php echo $jobChainOrder['job_chain_id']; ?></td>
			<td><?php echo $jobChainOrder['order_id']; ?></td>
			<td><?php echo $jobChainOrder['nagios_service_description']; ?></td>
			<td><?php echo $jobChainOrder['param_1']; ?></td>
			<td><?php echo $jobChainOrder['param_2']; ?></td>
			<td><?php echo $jobChainOrder['param_3']; ?></td>
			<td><?php echo $jobChainOrder['param_4']; ?></td>
			<td><?php echo $jobChainOrder['param_5']; ?></td>
			<td><?php echo $jobChainOrder['created']; ?></td>
			<td><?php echo $jobChainOrder['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'job_chain_orders', 'action' => 'view', $jobChainOrder['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'job_chain_orders', 'action' => 'edit', $jobChainOrder['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'job_chain_orders', 'action' => 'delete', $jobChainOrder['id']), null, __('Are you sure you want to delete # %s?', $jobChainOrder['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Job Chain Order'), array('controller' => 'job_chain_orders', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
