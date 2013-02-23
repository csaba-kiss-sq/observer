<?php defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends Admin_Controller
{
	protected $section = 'observer';

	public function __construct()
	{
		parent::__construct();

		$this->load->model(array('observer_data_m'));
		$this->load->model(array('observer_categories_m'));
		$this->load->model(array('observer_merchants_m'));
		$this->load->model(array('observer_products_m'));
		$this->lang->load(array('observer','merchants'));
	}

	public function grid($date) 
	{
		$grid = $this->get_data_grid($date);
		$this->template
			->title($this->module_details['name'])
			->build('admin/index', $grid);
	}

	public function index()
	{
		$grid = $this->get_data_grid(date("Y-m-d"));
		$this->template
			->enable_parser(true)
			->title($this->module_details['name'])
			->build('admin/index', $grid);
	}

	private function get_data_grid($date)
	{
		$categories = $this->db->select()->order_by('id', 'ASC')->get('observer_categories')->result_array();

		foreach ($categories as $category) {
			$result[$category['id']] = array(
				'title' => $category['title']
			);
		}

		$products = $this->db->select()->order_by('id', 'ASC')->get('observer_products')->result_array();

		foreach ($products as $product) {
			$result[$product['observer_categories_id']]['products'][$product['id']] = $product;
			$data = $this->db->select()
				->where('observer_data.created < ', $date." 23:59:59" )
				->where('observer_data.observer_products_id =', $product['id'])
				->group_by(array('observer_merchants_id','observer_products_id'))
				->order_by('created', 'DESC')
				->get('observer_data')->result_array();
			foreach ($data as $key => $value) {
				$result[$product['observer_categories_id']]['data'][$value['observer_merchants_id']][$value['observer_products_id']]  = $value['price']; 
			}	
		}

		foreach ($categories as $category) {
			@ksort($result[$category['id']]['data']);
		}

		$merchants = array();
		$tmp = $this->db->select()->order_by('id', 'ASC')->get('observer_merchants')->result_array();
		foreach ($tmp as $key => $value) {
			$merchants[$value['id']] = $value['title'];
		}
		unset($tmp);

		return array(
			'date'      => $date,
			'grid'      => $result,
			'merchants' => $merchants
		);
	}

	public function product()
	{
		$this->template
			->title($this->module_details['name']);
	
		$this->template->build('admin/product');	
	}
}