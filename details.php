<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Observer extends Module
{
	public $version = '1.0.0a';

	public function info()
	{
		$info = array(
			'name' => array(
				'en' => 'Árfolyamok',
				'hu' => 'Árfolyamok',
			),
			'description' => array(
				'en' => 'Árofolyamok figyelése, beállítása.',
				'hu' => 'Árofolyamok figyelése, beállítása.',
			),
			'frontend' => false,
			'backend' => true,
			'menu' => 'obsever',

			'sections' => array(
				'observer' => array(
					'name' => 'observer:title',
					'uri' => 'admin/observer',
				),
				'charts' => array(
					'name' => 'observer:charts_title',
					'uri' => 'admin/observer/charts',
				),
				'merchants' => array(
					'name' => 'Kereskedők',
					'uri' => 'admin/observer/merchants',
					'shortcuts' => array(
						array(
							'name' => 'observer:create_title',
							'uri' => 'admin/observer/merchants/create',
							'class' => 'add',
						),
					),
				),
				'product' => array(
					'name' => 'Termékek',
					'uri' => 'admin/observer/product/',
					'shortcuts' => array(
						array(
							'name' => 'Létrehozás',
							'uri' => 'admin/observer/product/create'
						)
					)
				),
				'categories' => array(
					'name' => 'Kategóriák',
					'uri' => 'admin/observer/categories/',
					'shortcuts' => array(
						array(
							'name' => 'Létrehozás',
							'uri' => 'admin/observer/categories/create'
						)
					)
				),
				'selectros' => array(
					'name' => 'Selectros (devel)',
					'uri' => 'admin/observer/selectros/',
					'shortcuts' => array(
						array(
							'name' => 'Létrehozás',
							'uri' => 'admin/observer/selectros/create'
						)
					)
				),
				'collector' => array(
					'name' => 'Collector (devel)',
					'uri'  => 'observer/collector' 
				),
			),
		);

		return $info;
	}

	public function install()
	{
		$this->dbforge->drop_table('observer_categories');
		$this->dbforge->drop_table('observer_data');
		$this->dbforge->drop_table('observer_merchants');
		$this->dbforge->drop_table('observer_products');
		$this->dbforge->drop_table('observer_selectors');

		$this->install_tables(array(
			'observer_categories' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true), 
				'title' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => false, 'unique' => true),
			),
			'observer_data' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'observer_products_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false, 'key' => true),
				'observer_merchants_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false, 'key' => true),
				'price' => array('type' => 'INT', 'constraint' => 11, 'null' => false, 'key' => true),
				'created' => array('type' => 'TIMESTAMP', 'null' => false)
			),
			'observer_merchants' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'title' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => false, 'unique' => true),
				'website' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => false),
				'description' => array('type' => 'TEXT', 'null' => true),
				'map' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => false),
			),
			'observer_products' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'title' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => false, 'unique' => true),
				'observer_categories_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false, 'key' => true),
			),
			'observer_selectors' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'url' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => false),
				'selector' => array('type' => 'TEXT', 'null' => true),
				'observer_products_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false, 'key' => true),
				'observer_merchants_id' => array('type' => 'INT', 'constraint' => 11, 'null' => false, 'key' => true),
			),
		));
		$this->db->insert('observer_categories', array('id' => 1, 'title' => 'törtarany'));
		$this->db->insert('observer_categories', array('id' => 2, 'title' => 'befektetési aranytömb'));

		$this->db->insert('observer_merchants', array('id' => 1, 'title' => 'Aranypont (saját)', 'website' => 'localhost0', 'description' => 'Saját árak és termékek', 'map' => '' ));
		$this->db->insert('observer_merchants', array('id' => 2, 'title' => 'Aranyatveszek', 'website' => 'http://www.aranyatveszek.hu/', 'description' => '', 'map' => '' ));
		$this->db->insert('observer_merchants', array('id' => 3, 'title' => 'Golderado', 'website' => 'http://golderado.hu/', 'description' => '', 'map' => '' ));
		$this->db->insert('observer_merchants', array('id' => 4, 'title' => 'Törtarany-ezüst', 'website' => 'http://www.tortarany-ezust.hu/fooldal', 'description' => '', 'map' => '' ));
		$this->db->insert('observer_merchants', array('id' => 5, 'title' => 'Tortaranyat.hu', 'website' => 'http://www.tortaranyat.hu/', 'description' => '', 'map' => '' ));
		$this->db->insert('observer_merchants', array('id' => 6, 'title' => 'Aranypiac', 'website' => 'http://www.aranypiac.hu/', 'description' => '', 'map' => '' ));
		$this->db->insert('observer_merchants', array('id' => 7, 'title' => 'Conclude', 'website' => 'http://www.conclude.hu', 'description' => '', 'map' => '' ));

		$this->db->insert('observer_products', array('id' => 1, 'title' => '8kt', 'observer_categories_id' => 1));
		$this->db->insert('observer_products', array('id' => 2, 'title' => '9kt', 'observer_categories_id' => 1));
		$this->db->insert('observer_products', array('id' => 3, 'title' => '14kt', 'observer_categories_id' => 1));
		$this->db->insert('observer_products', array('id' => 4, 'title' => '18kt', 'observer_categories_id' => 1));
		$this->db->insert('observer_products', array('id' => 5, 'title' => '24kt', 'observer_categories_id' => 1));
		$this->db->insert('observer_products', array('id' => 6, 'title' => '5g', 'observer_categories_id' => 2));
		$this->db->insert('observer_products', array('id' => 7, 'title' => '10g', 'observer_categories_id' => 2));
		$this->db->insert('observer_products', array('id' => 8, 'title' => '20g', 'observer_categories_id' => 2));
		$this->db->insert('observer_products', array('id' => 9, 'title' => '50g', 'observer_categories_id' => 2));
		$this->db->insert('observer_products', array('id' => 10, 'title' => '100g', 'observer_categories_id' => 2));
		$this->db->insert('observer_products', array('id' => 11, 'title' => '250g', 'observer_categories_id' => 2));
		$this->db->insert('observer_products', array('id' => 12, 'title' => '500g', 'observer_categories_id' => 2));
		$this->db->insert('observer_products', array('id' => 13, 'title' => '1000g', 'observer_categories_id' => 2));
		$this->db->insert('observer_selectors', array(
			'url' => 'http://mak.magyararanykereskedo.hu/artable.php?friss=yes&dev=HUF&for=F&typ=TORTARANY14K', 
			'selector' =>'table.beffhufTable td.beffhufadat', 
			'observer_products_id' => 3, 
			'observer_merchants_id' => 2 
		));
		$this->db->insert('observer_selectors', array(
			'url' => 'http://mak.magyararanykereskedo.hu/artable.php?friss=yes&dev=HUF&for=F&typ=TORTARANY18K', 
			'selector' =>'table.beffhufTable td.beffhufadat', 
			'observer_products_id' => 4, 
			'observer_merchants_id' => 2 
		));
		$this->db->insert('observer_selectors', array(
			'url' => 'http://mak.magyararanykereskedo.hu/artable.php?friss=yes&dev=HUF&for=F&typ=TORTARANY24K', 
			'selector' =>'table.beffhufTable td.beffhufadat', 
			'observer_products_id' => 5, 
			'observer_merchants_id' => 2
		));
		$this->db->insert('observer_selectors', array(
			'url' => 'http://www.tortarany-ezust.hu/fooldal', 
			'selector' => '.arany_tabla tr:eq(6) td:eq(1)', 
			'observer_products_id' => 1, 
			'observer_merchants_id' => 4
		));
		$this->db->insert('observer_selectors', array(
			'url' => 'http://www.tortarany-ezust.hu/fooldal', 
			'selector' => '.arany_tabla tr:eq(5) td:eq(1)', 
			'observer_products_id' => 2, 
			'observer_merchants_id' => 4
		));
		$this->db->insert('observer_selectors', array(
			'url' => 'http://www.tortarany-ezust.hu/fooldal', 
			'selector' => '.arany_tabla tr:eq(4) td:eq(1)', 
			'observer_products_id' => 3, 
			'observer_merchants_id' => 4
		));
		$this->db->insert('observer_selectors', array(
			'url' => 'http://www.tortarany-ezust.hu/fooldal', 
			'selector' => '.arany_tabla tr:eq(3) td:eq(1)', 
			'observer_products_id' => 4, 
			'observer_merchants_id' => 4
		));
		$this->db->insert('observer_selectors', array(
			'url' => 'http://www.tortarany-ezust.hu/fooldal', 
			'selector' => '.arany_tabla tr:eq(1) td:eq(1)', 
			'observer_products_id' => 5, 
			'observer_merchants_id' => 4
		));
		$this->db->insert('observer_selectors', array(
			'url' => 'http://golderado.hu/', 
			'selector' => '.price_table:eq(1) tr:eq(2) td:eq(1)', 
			'observer_products_id' => 1, 
			'observer_merchants_id' => 3
		));
		$this->db->insert('observer_selectors', array(
			'url' => 'http://golderado.hu/', 
			'selector' => '.price_table:eq(1) tr:eq(2) td:eq(2)', 
			'observer_products_id' => 3, 
			'observer_merchants_id' => 3
		));
		$this->db->insert('observer_selectors', array(
			'url' => 'http://www.tortaranyat.hu/', 
			'selector' => 'table.arany.arany2 #k14cont', 
			'observer_products_id' => 3, 
			'observer_merchants_id' => 5
		));
		$this->db->insert('observer_selectors', array(
			'url' => 'http://www.tortaranyat.hu/', 
			'selector' => 'table.arany.arany2 #k18cont', 
			'observer_products_id' => 4, 
			'observer_merchants_id' => 5
		));
		/*
		$this->db->insert('observer_selectors', array(
			'url' => 'http://www.aranypiac.hu/arfolyamok', 
			'selector' => '.arfolyamok tr:eq(3) td:eq(1) a', 
			'observer_products_id' => 6, 
			'observer_merchants_id' => 6
		));
		*/

		// todo
		$this->db->insert('observer_data', array('id' => 1, 'observer_products_id' => 1, 'observer_merchants_id' => 1, 'price' => 6000, 'created' => 0)); 
		$this->db->insert('observer_data', array('id' => 2, 'observer_products_id' => 2, 'observer_merchants_id' => 1, 'price' => 6500, 'created' => 0)); 
		$this->db->insert('observer_data', array('id' => 3, 'observer_products_id' => 3, 'observer_merchants_id' => 1, 'price' => 7200, 'created' => 0)); 
		$this->db->insert('observer_data', array('id' => 4, 'observer_products_id' => 4, 'observer_merchants_id' => 1, 'price' => 7500, 'created' => 0)); 
		$this->db->insert('observer_data', array('id' => 5, 'observer_products_id' => 5, 'observer_merchants_id' => 1, 'price' => 8000, 'created' => 0)); 

		return true;
	}

	public function uninstall()
	{
		$this->dbforge->drop_table('observer_categories');
		$this->dbforge->drop_table('observer_data');
		$this->dbforge->drop_table('observer_merchants');
		$this->dbforge->drop_table('observer_products');
		$this->dbforge->drop_table('observer_selectors');

		return true;
	}

	public function upgrade($old_version)
	{
		return true;
	}
}
