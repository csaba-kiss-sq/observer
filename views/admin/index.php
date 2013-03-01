<section class="title">
	<h4><?php echo lang('observer:dashboard') ?></h4>
</section>

<section class="item">
	<div class="content">

		<?php echo form_input('created_on', $date, 'maxlength="10" id="datepicker" class="text width-20 inputGridDate"') ?>
		<br />

		<?php foreach ($grid as $key => $table): ?>

		<h4><?php echo $table["title"] ?></h4>

		<table border="0" class="table-list" cellspacing="0">

			<thead>
			<tr>
				<th width="200"></th>
				<?php foreach ($table['products'] as $product): ?>
					<th style="text-align: center">
						<a href="{{ url:site }}admin/observer/charts/index/by_merchants/<?= $product['id']; ?>/<?=$key; ?>">
						<?php echo $product['title']; ?>
						</a>
					</th>
				<?php endforeach; ?>
			</tr>
			</thead>

			<?php if(isset($table['data'])): ?>
			<tbody>
				<?php foreach ($table['data'] as $index => $row): ?>
				<tr class="arany_<?=$key; ?>">
					<td>
						<a href="{{ url:site }}admin/observer/charts/index/by_products/<?= $index; ?>/<?=$key; ?>">
						<?php echo $merchants[$index] ?>
						</a>
					</td>
					<?php foreach ($table['products'] as $product): ?>
						<?php if(isset($row[$product['id']])): ?>
						<td style="text-align: center">
							<?php echo number_format($row[$product['id']], 0, ',', ' '); ?> Ft/g
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
		window.location = "{{ url:site }}admin/observer/grid/" + $(".inputGridDate").val();
	});

	for( i = 1 ; i < 6 ; i++ ) {
		var actualVal = 0;
		var maxVal = 0;
		var maxSel = null;

		$( ".arany_1" ).each( function() {
			actualVal = parseInt($(this).find( "td:eq(" + i + ")" ).text());

			if( actualVal > maxVal ) {
				maxVal = actualVal;
				maxSel = $(this).find( "td:eq(" + i + ")" );
			}

		});

		if ( maxVal > 0 ) {
			console.log(maxSel);
			maxSel.css( "font-weight", "700" );
			maxSel.css( "color", "#3311bb" );
		} 
	}

	for( i = 1 ; i < 9 ; i++ ) {
		var actualVal = 0;
		var maxVal = 0;
		var maxSel = null;

		$( ".arany_2" ).each( function() {
			actualVal = parseInt($(this).find( "td:eq(" + i + ")" ).text());

			if( actualVal > maxVal ) {
				maxVal = actualVal;
				maxSel = $(this).find( "td:eq(" + i + ")" );
			}

		});

		if ( maxVal > 0 ) {
			console.log(maxSel);
			maxSel.css( "font-weight", "700" );
			maxSel.css( "color", "#3311bb" );
		} 
	}
})
</script>