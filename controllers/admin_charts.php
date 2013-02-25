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
		$this->lang->load(array('observer', 'merchants'));
	}

	public function get_charts_data($products_id, $merchants_id, $categories_id)
	{
		$json = array(
			'data'    => array(),
			'success' => true
		);

		$data_array = $this->db->order_by('created_i', 'ASC')
		    ->select('MAX(price) as price, DATE_FORMAT( created,"%Y-%m-%d %H:00:00") as created_i', FALSE)
		    ->group_by('created_i')
		    ->get_where('default_observer_data', array(
		    	'observer_merchants_id' => $products_id, 
		    	'observer_products_id' => $merchants_id, 
		    	'created >=' => date('Y-m-d', strtotime("-2 week")) 
		    	) 
		    )->result();

	        foreach ($data_array as $row) {

            $json['data'][] = array(strtotime($row->created_i) * 1000, (float) $row->price );
        }

        if(empty($json['data']))
        {
	       	$json['success'] = false;
		} 
		echo json_encode($json);
	}

	public function index($way, $constant_id, $categories_id)
	{
		$view['params'] = array(
			'constant_id'   => $constant_id,
			'categories_id' => $categories_id
		);

		switch ($way) {
			case 'by_products':
				$view['products'] = $this->observer_products_m->get_dropdown();
				break;
			
			case 'by_merchants':
				$view['merchants'] =$this->observer_merchants_m->get_dropdown();
				break;
		}

		$this->template
			->enable_parser(true)
			->title($this->module_details['name'])
			->append_js('module::highcharts.js')
			->append_js('module::highcharts-more.js')
			->append_js('module::highstock.js')
			->build('admin/charts', $view);
	}
}