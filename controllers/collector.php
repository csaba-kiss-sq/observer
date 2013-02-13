<?php

class Collector extends Public_Controller
{
	private $config = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model('observer_data_m');
	}

	public function index()
	{
		$this->load->helper('observer/phpQuery');
		$selectors = $this->db->select()->get('observer_selectors')->result_array();
		$date = date("Y-m-d H:i:s");
		$dateHourly = date("Y-m-d H:i:s", time()-3600);

		foreach ($selectors as $selector) {
		
			$dataRow = $this->db->select()
				->where('observer_data.observer_products_id =', $selector['observer_products_id'])
				->where('observer_data.observer_merchants_id =', $selector['observer_merchants_id'])
			    ->where( 'observer_data.created > ', $dateHourly ) 
				->get('observer_data')->row();

			$content = file_get_contents($selector['url']);

			if (strpos($content, '</html>')) {
				$html = $content;
			} else {
				$html = '<html><head></head><body>'.$content.'</body></html>';
			}

			phpQuery::newDocumentHTML($html, $charset = 'utf-8'); 
			$text = pq($selector['selector'])->text();
			$price = preg_replace('[\D]', '', $text);

			if (is_null($dataRow) || $dataRow->price != $price) {
	 			$this->db->insert('observer_data', array(
	 				'observer_products_id' => $selector['observer_products_id'], 
	 				'observer_merchants_id' => $selector['observer_merchants_id'], 
	 				'price' => $price, 
	 				'created' => $date
 				)); 
			} 
		}
	}
}