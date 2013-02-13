<section class="title">
	<h4><?php echo lang('observer:dashboard') ?></h4>
</section>

<section class="item">
	<div class="content">

		<?php echo form_input('created_on', $date, 'maxlength="10" id="datepicker" class="text width-20 inputGridDate"') ?>
		<br />

		<?php foreach ($grid as $table): ?>

		<h4><?php echo $table["title"] ?></h4>

		<table border="0" class="table-list" cellspacing="0">

			<thead>
			<tr>
				<th width="200"></th>
				<?php foreach ($table['products'] as $product): ?>
					<th style="text-align: center">
						<?php echo $product['title']; ?>
					</th>
				<?php endforeach; ?>
			</tr>
			</thead>

			<?php if(isset($table['data'])): ?>
			<tbody>
				<?php foreach ($table['data'] as $index => $row): ?>
				<tr>
					<td>
						<?php echo $merchants[$index] ?>
					</td>
					<?php foreach ($table['products'] as $product): ?>
						<?php if(isset($row[$product['id']])): ?>
						<td style="text-align: center">
							<?php echo $row[$product['id']]; ?>
						</td>
						<?php else: ?>
						<td style="text-align: center">
							-
						</td>
						<?php endif; ?>
					<?php endforeach; ?>
				</tr>
				<?php endforeach; ?>
			</tbody>
			<?php endif; ?>

		</table>

		<?php endforeach; ?>

	</div>
</section>

<script type="text/javascript">
$(document).ready(function() {
	$('.inputGridDate').on("change", function(e) {
		e.preventDefault();
		window.location = "http://localhost/aranypont/index.php/admin/observer/grid/" + $(".inputGridDate").val();
	});
})
</script>