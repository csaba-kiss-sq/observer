<?php

class Collector extends Public_Controller
{
	private $config = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->driver('Streams');
		$this->load->model('observer_data_m');
	}

	protected function insert_entry( $stream, $entry_data  )
	{
		$this->streams->entries->insert_entry($entry_data, $stream, 'streams');
	}

	public function index($install = false)
	{
		$grid_cache = array();

		$this->benchmark->mark('start');
		$this->load->helper('observer/phpQuery');
		$selectors = $this->db->select()->get('observer_selectors')->result_array();
		$date = date("Y-m-d H:i:s");
		$dateHourly = date("Y-m-d H:i:s", time() - 3600);

		$content = file_get_contents("http://tortarany.com/json?token=o1W6O6F6312V78U");
		$data = json_decode($content);
		// saját
		foreach( $data as $key => $item ) {

			switch( $item->karat ) {
				case '8':
				$price = (int)$item->huf_raw;
				$merchants_id = 1;
				$products_id  = 1;
				break;
				case '9':
				$price = (int)$item->huf_raw;
				$merchants_id = 1;
				$products_id  = 2;
				break;
				case '14':
				$price = (int)$item->huf_raw;
				$merchants_id = 1;
				$products_id  = 3;
				break;
				case '18':
				$price = (int)$item->huf_raw;
				$merchants_id = 1;
				$products_id  = 4;
				break;
				case '24':
				$price = (int)$item->huf_raw;
				$merchants_id = 1;
				$products_id  = 5;
				break;
			}

			$dataRow = $this->db->select()
			->where('observer_data.observer_products_id =', $products_id)
			->where('observer_data.observer_merchants_id =', $merchants_id)
			->where( 'observer_data.created > ', $dateHourly ) 
			->get('observer_data')->row();

			if (is_null($dataRow) || $dataRow->price != $price) {
				$this->db->insert('observer_data', array(
					'observer_products_id' => $products_id, 
					'observer_merchants_id' => $merchants_id, 
					'price' => $price, 
					'created' => $date
					)); 
			} 

			$grid_cache[1]['data'][$merchants_id][$products_id]['price'] = $price;
			$grid_cache[1]['data'][$merchants_id][$products_id]['sid']   = null;
		} 

		$content = file_get_contents("http://aranytomb.me/json?token=o1W6O6F6312V78U");
		$data = json_decode($content);
		
		foreach( $data as $key => $item ) {

			switch( $item->suly ) {
				case '5':
				$price = (int)$item->huf_raw;
				$merchants_id = 1;
				$products_id  = 6;
				break;
				case '10':
				$price = (int)$item->huf_raw;
				$merchants_id = 1;
				$products_id  = 7;
				break;
				case '20':
				$price = (int)$item->huf_raw;
				$merchants_id = 1;
				$products_id  = 8;
				break;
				case '50':
				$price = (int)$item->huf_raw;
				$merchants_id = 1;
				$products_id  = 9;
				break;
				case '100':
				$price = (int)$item->huf_raw;
				$merchants_id = 1;
				$products_id  = 10;
				break;
				case '250':
				$price = (int)$item->huf_raw;
				$merchants_id = 1;
				$products_id  = 11;
				break;
				case '500':
				$price = (int)$item->huf_raw;
				$merchants_id = 1;
				$products_id  = 12;
				break;
				case '1000':
				$price = (int)$item->huf_raw;
				$merchants_id = 1;
				$products_id  = 13;
				break;
			}

			$dataRow = $this->db->select()
			->where('observer_data.observer_products_id =', $products_id)
			->where('observer_data.observer_merchants_id =', $merchants_id)
			->where( 'observer_data.created > ', $dateHourly ) 
			->get('observer_data')->row();

			if (is_null($dataRow) || $dataRow->price != $price) {
				$this->db->insert('observer_data', array(
					'observer_products_id' => $products_id, 
					'observer_merchants_id' => $merchants_id, 
					'price' => $price, 
					'created' => $date
					)); 
			} 
			$grid_cache[2]['data'][$merchants_id][$products_id]['price'] = $price;
			$grid_cache[2]['data'][$merchants_id][$products_id]['sid']   = null;
		}

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

			// Aranytömbnél fordult elő
			$strpos = strpos($text, '(');
			if ($strpos) {
				$text = substr($text, $strpos);
			}

			$price = preg_replace('[\D]', '', $text);

			if (is_null($dataRow) || $dataRow->price != $price) {
				$this->db->insert('observer_data', array(
					'observer_products_id' => $selector['observer_products_id'], 
					'observer_merchants_id' => $selector['observer_merchants_id'], 
					'price' => $price, 
					'created' => $date
					)); 
			} 
			$grid_cache[3]['data'][$selector['observer_merchants_id']][$selector['observer_products_id']]['price'] = $price;
			$grid_cache[3]['data'][$selector['observer_merchants_id']][$selector['observer_products_id']]['sid']   = $selector['id'];
		}
		$this->benchmark->mark('end');
		echo $this->benchmark->elapsed_time('start', 'end');
		$this->db->update('futtatva',array('futtatva'=>date('Y-m-d H:i:s',now())),array('id'=>1));
	}
}