<section class="title">
	<?php if ($this->controller == 'admin_categories' && $this->method === 'edit'): ?>
	<h4><?php echo sprintf(lang('merchants:edit_title'), $merchant->title);?></h4>
	<?php else: ?>
	<h4><?php echo lang('merchants:create_title');?></h4>
	<?php endif ?>
</section>

<section class="item">
<div class="content">
<?php echo form_open($this->uri->uri_string(), 'class="crud'.((isset($mode)) ? ' '.$mode : '').'" id="categories"') ?>

<div class="form_inputs">

	<ul>
		<li class="even">
			<div class="input"><?php echo  form_hidden('id', $merchant->id) ?></div>
			<label for="title"><?php echo lang('global:title');?> <span>*</span></label>
			<div class="input"><?php echo  form_input('title', $merchant->title) ?></div>
			<label for="website"><?php echo lang('merchants:website');?> <span>*</span></label>
			<div class="input"><?php echo  form_input('website', $merchant->website) ?></div>
			<label for="title"><?php echo lang('merchants:description');?> <span>*</span></label>
			<div class="textarea"><?php echo  form_textarea('description', $merchant->description) ?></div>
			<label for="title"><?php echo lang('merchants:map');?> <span>*</span></label>
			<div class="input"><?php echo  form_input('map', $merchant->map) ?></div>
		</li>
	</ul>

</div>

	<div><?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )) ?></div>

<?php echo form_close() ?>
</div>
</section>