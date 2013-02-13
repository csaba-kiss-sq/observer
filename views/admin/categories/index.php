<section class="title">
	<h4><?php echo lang('categories:list_title') ?></h4>
</section>

<section class="item">
	<div class="content">
	
	<?php if ($merchants): ?>

		<?php echo form_open('admin/observer_merchants/delete') ?>

		<table border="0" class="table-list" cellspacing="0">
			<thead>
			<tr>
				<th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')) ?></th>
				<th><?php echo lang('merchants:category_label') ?></th>
				<th width="200" style="text-align: center"></th>
			</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="4">
						<div class="inner"><?php $this->load->view('admin/partials/pagination') ?></div>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach ($merchants as $merchant): ?>
				<tr>
					<td><?php echo form_checkbox('action_to[]', $merchant->id) ?></td>
					<td><?php echo $merchant->title ?></td>
					<td class="align-center buttons buttons-small" style="text-align: center">
						<?php echo anchor('admin/observer/merchants/edit/'.$merchant->id, lang('global:edit'), 'class="button edit"') ?>
					</td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>

		<?php echo form_close() ?>

	<?php else: ?>
		<div class="no_data"><?php echo lang('merchants:no_categories') ?></div>
	<?php endif ?>
	</div>
</section>