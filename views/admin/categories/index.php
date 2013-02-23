<section class="title">
	<h4><?php echo lang('categories:list_title') ?></h4>
</section>

<section class="item">

	<div class="content">

		{{ streams:cycle stream="observer_categories" limit="5" paginate="yes" pag_segment="1" limit="10" }}
		<p style="text-align: right; padding-right: 5px">találatok: {{ total }} sor</p>
		<table border="0" class="table-list" cellspacing="0">
			<thead>
			<tr>
				<th><?php echo lang('merchants:category_label') ?></th>
				<th width="200" style="text-align: center"></th>
			</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="3">
						{{ pagination }}
					</td>
				</tr>
			</tfoot>
			<tbody>
				 {{ entries }}
				<tr>
					<td>{{ title }}</td>
					<td class="align-center buttons buttons-small" style="text-align: center">
						<a href="{{ url:site }}admin/observer/categories/edit/{{ id }}" class="button edit"> 
							<?= lang('global:edit'); ?>
						</a>
					</td>
				</tr>
				 {{ /entries }}
			</tbody>
		</table>
		{{ /streams:cycle }}

	</div>
</section>