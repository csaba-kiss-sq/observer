<?php defined('BASEPATH') or exit('No direct script access allowed');

class Observer_merchants_m extends MY_Model
{
	public function insert($input = array(), $skip_validation = false)
	{
		parent::insert(array(
			'title' => $input['title'],
			'website' => $input['website'],
			'description' => $input['description'],
			'map' => $input['map'],
		));

		return $input['title'];
	}

	public function update($id, $input, $skip_validation = false)
	{
		return parent::update($id, array(
			'title' => $input['title'],
		));
	}

	public function get_dropdown()
	{
		$rows = $this->db->select()->get('observer_merchants')->result_array();
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
			->from('observer_merchants')
			->count_all_results();
	}

	public function check_slug($slug = '', $id = 0)
	{
		return (bool)$this->db->where('slug', $slug)
			->where('id != ', $id)
			->from('observer_merchants')
			->count_all_results();
	}
}