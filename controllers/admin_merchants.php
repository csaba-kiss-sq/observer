<?php defined('BASEPATH') or exit('No direct script access allowed');

class Admin_Merchants extends Admin_Controller
{
	protected $section = 'merchants';

	protected $validation_rules = array(
		array(
			'field' => 'title',
			'label' => 'Megnevezés',
			'rules' => 'trim|required|max_length[255]|callback__check_title'
		),
		array(
			'field' => 'id',
			'rules' => 'trim|numeric'
		),
		array(
			'field' => 'website',
			'rules' => 'trim|string'
		),
		array(
			'field' => 'description',
			'rules' => 'trim|string'
		),
		array(
			'field' => 'map',
			'rules' => 'trim|string'
		)
	);

	public function __construct()
	{
		parent::__construct();

		$this->load->model('observer_merchants_m');
		$this->lang->load( 'merchants');
		$this->lang->load( 'observer');

		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->validation_rules);
	}

	public function index()
	{
		$this->pyrocache->delete_all('module_m');

		$total_rows = $this->observer_merchants_m->count_all();
		$pagination = create_pagination('admin/observer/merchants/index', $total_rows, null, 5);

		$merchants = $this->observer_merchants_m->order_by('title')->limit($pagination['limit'])->get_all();

		$this->template
			->title($this->module_details['name'])
			->set('merchants', $merchants)
			->set('pagination', $pagination)
			->build('admin/merchants/index');
	}

	public function create()
	{
		$merchant = new stdClass;

		if ($this->form_validation->run())
		{
			if ($id = $this->observer_merchants_m->insert($this->input->post()))
			{
				// Fire an event. A new blog category has been created.
				// Events::trigger('observer_merchant_created', $id);

				$this->session->set_flashdata('success', sprintf(lang('merchants:add_success'), $this->input->post('title')));
			}
			else
			{
				$this->session->set_flashdata('error', lang('merchants:add_error'));
			}

			redirect('admin/observer/merchants');
		}

		$merchant = new stdClass();

		foreach ($this->validation_rules as $rule)
		{
			$merchant->{$rule['field']} = set_value($rule['field']);
		}

		$this->template
			->title($this->module_details['name'], lang('merchants:create_title'))
			->set('merchant', $merchant)
			->set('mode', 'create')
			->build('admin/merchants/form');
	}

	public function edit($id = 0)
	{
		// Get the category
		$merchant = $this->observer_merchants_m->get($id);

		// ID specified?
		$merchant or redirect('admin/blog/categories/index');

		$this->form_validation->set_rules('id', 'ID', 'trim|required|numeric');

		// Validate the results
		if ($this->form_validation->run())
		{
			$this->observer_merchants_m->update($id, $this->input->post())
				? $this->session->set_flashdata('success', sprintf(lang('merchants:edit_success'), $this->input->post('title')))
				: $this->session->set_flashdata('error', lang('merchants:edit_error'));

			// Fire an event. A blog category is being updated.
			// Events::trigger('blog_category_updated', $id);

			redirect('admin/observer/merchants/index');
		}

		// Loop through each rule
		foreach ($this->validation_rules as $rule)
		{
			if ($this->input->post($rule['field']) !== null)
			{
				$merchant->{$rule['field']} = $this->input->post($rule['field']);
			}
		}

		$this->template
			->title($this->module_details['name'], sprintf(lang('merchants:edit_title'), $merchant->title))
			->set('merchant', $merchant)
			->set('mode', 'edit')
			->build('admin/merchants/form');
	}

	/**
	 * Delete method, deletes an existing category (obvious isn't it?)
	 *
	 * @param int $id The ID of the category to edit
	 */
	public function delete($id = 0)
	{
		$id_array = (!empty($id)) ? array($id) : $this->input->post('action_to');

		// Delete multiple
		if (!empty($id_array))
		{
			$deleted = 0;
			$to_delete = 0;
			$deleted_ids = array();
			foreach ($id_array as $id)
			{
				if ($this->observer_merchants_m->delete($id))
				{
					$deleted++;
					$deleted_ids[] = $id;
				}
				else
				{
					$this->session->set_flashdata('error', sprintf(lang('merchants:mass_delete_error'), $id));
				}
				$to_delete++;
			}

			if ($deleted > 0)
			{
				$this->session->set_flashdata('success', sprintf(lang('merchants:mass_delete_success'), $deleted, $to_delete));
			}

			// Fire an event. One or more categories have been deleted.
			// Events::trigger('blog_category_deleted', $deleted_ids);
		}
		else
		{
			$this->session->set_flashdata('error', lang('merchants:no_select_error'));
		}

		redirect('admin/observer/merchants/index');
	}

	public function _check_title($title = '')
	{
		if ($this->observer_merchants_m->check_title($title, $this->input->post('id')))
		{
			$this->form_validation->set_message('_check_title', sprintf(lang('merchants:already_exist_error'), $title));

			return false;
		}

		return true;
	}
}