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
		$this->lang->load(array('observer', 'merchants'));
	}

	function teszt(){
		echo intval('2 354 Ft');
	}

	public function grid($date) 
	{
		$grid = $this->get_data_grid($date);
		$this->template
			->title($this->module_details['name'])
			->enable_parser(true)
			->append_js('module::jquery.dataTables.min.js')
			->build('admin/index', $grid);
	}

	public function index()
	{
		$grid = $this->get_data_grid(date("Y-m-d"));
		$this->template
			->enable_parser(true)
			->title($this->module_details['name'])
			->append_js('module::jquery.dataTables.min.js')
			->build('admin/index', $grid);
	}

	private function get_data_grid($date)
	{
		$cache_key = md5("grid".$date);

		$memcache = memcache_connect("localhost", 11211);

		$categories = $this->db->select()->order_by('id', 'ASC')->get('observer_categories')->result_array();

		foreach ($categories as $category) {
			$result[$category['id']] = array(
				'title' => $category['title']
			);
		}

		$products  = $this->db->select()->order_by('id', 'ASC')->get('observer_products')->result_array();
		$merchants = $this->db->select()->order_by('ordering_count', 'ASC')->get('observer_merchants')->result_array();

		$cached = $memcache->get($cache_key);
		if( empty($cached) ) {

			foreach ($products as $product) {

				foreach ($merchants as $merchant) {

					$result[$product['observer_categories_id']]['products'][$product['id']] = $product;

		 			$sql = "SELECT d.*, s.id AS sid
						FROM default_observer_data d
						LEFT JOIN 
							default_observer_selectors s
						ON
							d.observer_merchants_id = s.observer_merchants_id
						AND
							d.observer_products_id = s.observer_products_id
						WHERE d.observer_products_id = ".$product['id']."
						AND d.observer_merchants_id = ".$merchant['id']."
						AND d.created >= '".$date." 00:00:00'
			 			AND d.created < '".$date." 23:59:59'
			 			ORDER BY created DESC";
					$data = $this->db->query($sql)->row();

					if(!empty($data)) {
						$result[$product['observer_categories_id']]['data'][$merchant['id']][$product['id']]['price']  = $data->price; 
						$result[$product['observer_categories_id']]['data'][$merchant['id']][$product['id']]['sid']  = $data->sid; 

					}
				}
			}

			$memcache->add($cache_key, $result, false, 120);
		} else {
			$result = $cached;
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