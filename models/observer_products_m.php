<?php defined('BASEPATH') or exit('No direct script access allowed');

class Observer_products_m extends MY_Model
{
	public function insert($input = array(), $skip_validation = false)
	{
		parent::insert(array(
			'title' => $input['title'],
		));

		return $input['title'];
	}

	public function update($id, $input, $skip_validation = false)
	{
		return parent::update($id, array(
			'title' => $input['title'],
		));
	}

	public function get_dropdown($categories_id = null)
	{

		if( $categories_id ) {
			$rows = $this->db->select()->where('observer_categories_id =', $categories_id)->get('observer_products')->result_array();
		} else {
			$rows = $this->db->select()->get('observer_products')->result_array();
		}
		
		$result = array();
		foreach ($rows as $row) {
			$result[$row['id']] = $row['title'];
		}	

		return $result;
	}
	
	public function check_title($title = '', $id = 0)
	{
		return (bool)$this->db->where('title', $title)
			->where('id != ', $id)
			->from('observer_products')
			->count_all_results();
	}
}