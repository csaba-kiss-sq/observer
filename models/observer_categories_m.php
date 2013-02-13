<?php defined('BASEPATH') or exit('No direct script access allowed');

class Observer_categories_m extends MY_Model
{
	public function insert($input = array(), $skip_validation = false)
	{
		parent::insert(array(
			'title' => $input['title']
		));

		return $input['title'];
	}

	public function update($id, $input, $skip_validation = false)
	{
		$result = parent::update($id, array(
			'title' => $input['title'],
		));

		return $result;
	}

	public function get_dropdown()
	{
		$rows = $this->db->select()->get('observer_categories')->result_array();
		$result = array();
		foreach ($rows as $row) {
			$result[$row['id']] = $row['title'];
		}	
		return $result;
	}

	public function check_title($title = '', $id = 0)
	{
		$result = (bool)$this->db->where('title', $title)
			->where('id != ', $id)
			->from('observer_categories')
			->count_all_results();

		return $result;
	}
}