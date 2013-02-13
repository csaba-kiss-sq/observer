<?php defined('BASEPATH') or exit('No direct script access allowed');

class Admin_charts extends Admin_Controller
{
	protected $section = 'charts';

	public function __construct()
	{
		parent::__construct();

		$this->load->model(array('observer_data_m'));
		$this->load->model(array('observer_categories_m'));
		$this->load->model(array('observer_merchants_m'));
		$this->load->model(array('observer_products_m'));
		$this->lang->load(array('observer','merchants'));
	}

	public function index($type = 0, $category = 1)
	{
		if($type == 0) 
		{
			$products = $this->observer_products_m->get_dropdown();

			$lines = array();

			foreach($products as $product_id => $product_title) 
			{
				$pricesArray = array();

				$data = $this->db->select()
					->where('observer_data.observer_products_id = ', $product_id)
					->where('observer_data.observer_merchants_id = ', 4)
					->order_by('created', 'DESC')
					->get('observer_data')->result_array();

				$data = array_reverse($data);

				foreach ($data as $key => $value) 
				{
					$pricesArray[] = $value['price'];
				}
				
				$prices = implode(', ', $pricesArray);
				$lines[] = '{name: \''.$product_title.'\', data: ['.$prices.']}';
			}
		}
		else 
		{
			$merchants = $this->observer_merchants_m->get_dropdown();

			$lines = array();

			foreach($merchants as $merchant_id => $merchant_title) 
			{
				$pricesArray = array();

				$data = $this->db->select()
					->where('observer_data.observer_products_id = ', 4)
					->where('observer_data.observer_merchants_id = ', $merchant_id)
					->order_by('created', 'DESC')
					->get('observer_data')->result_array();

				$data = array_reverse($data);

				foreach ($data as $key => $value) 
				{
					$pricesArray[] = $value['price'];
				}
				
				$prices = implode(', ', $pricesArray);
				$lines[] = '{name: \''.$merchant_title.'\', data: ['.$prices.']}';
			}
		}

 
		$json = 'series = ['. implode(', ', $lines).'];';

		$series = array(
			'series' => json_encode(array(
				'name' => 'Termék 1',
				'data' => $pricesArray,
			)),
			'json' => $json,
			'categories' => $this->observer_categories_m->get_dropdown(),
			'merchants'  => $this->observer_merchants_m->get_dropdown(),
			'products'   => $this->observer_products_m->get_dropdown(),
		);

		$this->template
			->enable_parser(true)
			->title($this->module_details['name'])
			->append_js('module::highcharts.js')
			->build('admin/charts', $series);
	}
}