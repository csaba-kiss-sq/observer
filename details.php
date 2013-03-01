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
			'menu' => 'content',

			'sections' => array(
				'observer' => array(
					'name' => 'observer:title',
					'uri' => 'admin/observer',
				),
				'streams' => array(
					'name' => 'Adatfolyam',
					'uri'  => 'admin/streams',
				),

			/*	'charts' => array(
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
				), */
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

		$this->load->driver('Streams');
		
		/* start: Kategóriák */
		$this->streams->streams->add_stream('Kategóriák', 'observer_categories', 'streams');
		$this->streams->fields->add_field(array(
	        'name'          => 'Megnevezés',
	        'slug'          => 'title',
	        'namespace'     => 'streams',
	        'type'          => 'text',
	        'assign'        => 'observer_categories',
	        'title_column'  => true,
	        'required'      => true,
	        'unique'        => true
	    ));
	    $stream_observer_categories = $this->streams->streams->get_stream('observer_categories', 'streams');
	    /* end: Kategóriák */

	    /* start: Kereskedők */
	    $this->streams->streams->add_stream('Kereskedők', 'observer_merchants', 'streams');
		$this->streams->fields->add_fields(array(
	        array(
				'name'          => 'Weboldal címe',
		        'slug'          => 'website',
		        'namespace'     => 'streams',
		        'type'          => 'text',
		        'assign'        => 'observer_merchants',
        	),
        	array(
				'name'          => 'Leírás',
		        'slug'          => 'description',
		        'namespace'     => 'streams',
		        'type'          => 'text',
		        'assign'        => 'observer_merchants',
        	),
        	array(
				'name'          => 'Google maps',
		        'slug'          => 'map',
		        'namespace'     => 'streams',
		        'type'          => 'text',
		        'assign'        => 'observer_merchants',
        	)
	    ));
		$this->streams->fields->assign_field('streams', 'observer_merchants', 'title', array('required' => true));		
		$stream_observer_merchants = $this->streams->streams->get_stream('observer_merchants', 'streams');
		/* end: Kereskedők */

		/* start: Termékek */
		$this->streams->streams->add_stream('Termékek', 'observer_products', 'streams');
		$this->streams->fields->add_fields(array(
	        array(
		        'name'          => 'Kereskedők',
		        'slug'          => 'observer_categories_id',
		        'namespace'     => 'streams',
		        'type'          => 'relationship',
		        'assign'        => 'observer_products',
		        'extra'         => array('choose_stream' => $stream_observer_categories->id),
		        'required'      => true
	        ),
	    ));
		$this->streams->fields->assign_field('streams', 'observer_products', 'title', array('required' => true));		
	    $stream_observer_products = $this->streams->streams->get_stream('observer_products', 'streams');
	    /* end: Termékek */

	    /* start: Selectotok */
	    $this->streams->streams->add_stream('Selectors', 'observer_selectors', 'streams');
		$this->streams->fields->add_fields(array(
			array(
		        'name'          => 'Termék',
		        'slug'          => 'observer_products_id',
		        'namespace'     => 'streams',
		        'type'          => 'relationship',
		        'assign'        => 'observer_selectors',
		        'extra'         => array('choose_stream' => $stream_observer_products->id),
		        'required'      => true
	        ),
	        array(
		        'name'          => 'Kereskedők',
		        'slug'          => 'observer_merchants_id',
		        'namespace'     => 'streams',
		        'type'          => 'relationship',
		        'assign'        => 'observer_selectors',
		        'extra'         => array('choose_stream' => $stream_observer_merchants->id),
		        'required'      => true
	        ),
	        array(
		        'name'          => 'Url',
		        'slug'          => 'url',
		        'namespace'     => 'streams',
		        'type'          => 'text',
		        'assign'        => 'observer_selectors',
		    ),   
		    array(
		        'name'          => 'Selector',
		        'slug'          => 'selector',
		        'namespace'     => 'streams',
		        'type'          => 'textarea',
		        'assign'        => 'observer_selectors',
		    )   
	    ));
		/* end: Selectotok */

		/* start: Adatok */
	    $this->streams->streams->add_stream('Adatok', 'observer_data', 'streams');
	    $stream_observer_data = $this->streams->streams->get_stream('observer_data', 'streams');
		$this->streams->fields->add_fields(array(
		    array(
		        'name'          => 'Ár',
		        'slug'          => 'price',
		        'namespace'     => 'streams',
		        'type'          => 'integer',
		        'assign'        => 'observer_data',
		    )   
	    ));
	    $this->streams->fields->assign_field('streams', 'observer_data', 'observer_products_id', array('required' => true));
	    $this->streams->fields->assign_field('streams', 'observer_data', 'observer_merchants_id', array('required' => true));
	    /* end: Adatok */

		$entry_data =array('id' => 1, 'title' => 'törtarany');
		$this->streams->entries->insert_entry($entry_data, 'observer_categories', 'streams');
		$entry_data =array('id' => 2, 'title' => 'befektetési aranytömb');
		$this->streams->entries->insert_entry($entry_data, 'observer_categories', 'streams');

		$this->insert_entry('observer_merchants', array('id' => 1, 'title' => 'Aranypont (saját)', 'website' => 'localhost0', 'description' => 'Saját árak és termékek', 'map' => '' ));
		$this->insert_entry('observer_merchants', array('id' => 2, 'title' => 'Aranyatveszek', 'website' => 'http://www.aranyatveszek.hu/', 'description' => '', 'map' => '' ));
		$this->insert_entry('observer_merchants', array('id' => 3, 'title' => 'Golderado', 'website' => 'http://golderado.hu/', 'description' => '', 'map' => '' ));
		$this->insert_entry('observer_merchants', array('id' => 4, 'title' => 'Törtarany-ezüst', 'website' => 'http://www.tortarany-ezust.hu/fooldal', 'description' => '', 'map' => '' ));
		$this->insert_entry('observer_merchants', array('id' => 5, 'title' => 'Tortaranyat.hu', 'website' => 'http://www.tortaranyat.hu/', 'description' => '', 'map' => '' ));
		$this->insert_entry('observer_merchants', array('id' => 6, 'title' => 'Aranypiac', 'website' => 'http://www.aranypiac.hu/', 'description' => '', 'map' => '' ));
		$this->insert_entry('observer_merchants', array('id' => 7, 'title' => 'Conclude', 'website' => 'http://www.conclude.hu', 'description' => '', 'map' => '' ));

		$this->insert_entry('observer_products', array('id' => 1, 'title' => '8kt', 'observer_categories_id' => 1));
		$this->insert_entry('observer_products', array('id' => 2, 'title' => '9kt', 'observer_categories_id' => 1));
		$this->insert_entry('observer_products', array('id' => 3, 'title' => '14kt', 'observer_categories_id' => 1));
		$this->insert_entry('observer_products', array('id' => 4, 'title' => '18kt', 'observer_categories_id' => 1));
		$this->insert_entry('observer_products', array('id' => 5, 'title' => '24kt', 'observer_categories_id' => 1));
		$this->insert_entry('observer_products', array('id' => 6, 'title' => '5g', 'observer_categories_id' => 2));
		$this->insert_entry('observer_products', array('id' => 7, 'title' => '10g', 'observer_categories_id' => 2));
		$this->insert_entry('observer_products', array('id' => 8, 'title' => '20g', 'observer_categories_id' => 2));
		$this->insert_entry('observer_products', array('id' => 9, 'title' => '50g', 'observer_categories_id' => 2));
		$this->insert_entry('observer_products', array('id' => 10, 'title' => '100g', 'observer_categories_id' => 2));
		$this->insert_entry('observer_products', array('id' => 11, 'title' => '250g', 'observer_categories_id' => 2));
		$this->insert_entry('observer_products', array('id' => 12, 'title' => '500g', 'observer_categories_id' => 2));
		$this->insert_entry('observer_products', array('id' => 13, 'title' => '1000g', 'observer_categories_id' => 2));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://mak.magyararanykereskedo.hu/artable.php?friss=yes&dev=HUF&for=F&typ=TORTARANY14K', 
			'selector' =>'table.beffhufTable td.beffhufadat', 
			'observer_products_id' => 3, 
			'observer_merchants_id' => 2 
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://mak.magyararanykereskedo.hu/artable.php?friss=yes&dev=HUF&for=F&typ=TORTARANY18K', 
			'selector' =>'table.beffhufTable td.beffhufadat', 
			'observer_products_id' => 4, 
			'observer_merchants_id' => 2 
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://mak.magyararanykereskedo.hu/artable.php?friss=yes&dev=HUF&for=F&typ=TORTARANY24K', 
			'selector' =>'table.beffhufTable td.beffhufadat', 
			'observer_products_id' => 5, 
			'observer_merchants_id' => 2
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://www.tortarany-ezust.hu/fooldal', 
			'selector' => '.arany_tabla tr:eq(6) td:eq(1)', 
			'observer_products_id' => 1, 
			'observer_merchants_id' => 4
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://www.tortarany-ezust.hu/fooldal', 
			'selector' => '.arany_tabla tr:eq(5) td:eq(1)', 
			'observer_products_id' => 2, 
			'observer_merchants_id' => 4
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://www.tortarany-ezust.hu/fooldal', 
			'selector' => '.arany_tabla tr:eq(4) td:eq(1)', 
			'observer_products_id' => 3, 
			'observer_merchants_id' => 4
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://www.tortarany-ezust.hu/fooldal', 
			'selector' => '.arany_tabla tr:eq(3) td:eq(1)', 
			'observer_products_id' => 4, 
			'observer_merchants_id' => 4
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://www.tortarany-ezust.hu/fooldal', 
			'selector' => '.arany_tabla tr:eq(1) td:eq(1)', 
			'observer_products_id' => 5, 
			'observer_merchants_id' => 4
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://golderado.hu/', 
			'selector' => '.price_table:eq(1) tr:eq(2) td:eq(1)', 
			'observer_products_id' => 1, 
			'observer_merchants_id' => 3
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://golderado.hu/', 
			'selector' => '.price_table:eq(1) tr:eq(2) td:eq(2)', 
			'observer_products_id' => 3, 
			'observer_merchants_id' => 3
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://www.tortaranyat.hu/', 
			'selector' => 'table.arany.arany2 #k14cont', 
			'observer_products_id' => 3, 
			'observer_merchants_id' => 5
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://www.tortaranyat.hu/', 
			'selector' => 'table.arany.arany2 #k18cont', 
			'observer_products_id' => 4, 
			'observer_merchants_id' => 5
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://golderado.hu/befektetesi-arany', 
			'selector' => '#mainContent .product:eq(0) .investment_price:eq(0) > strong', 
			'observer_products_id' => 13, 
			'observer_merchants_id' => 3
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://golderado.hu/befektetesi-arany', 
			'selector' => '#mainContent .product:eq(1) .investment_price:eq(0) > strong', 
			'observer_products_id' => 12, 
			'observer_merchants_id' => 3
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://golderado.hu/befektetesi-arany', 
			'selector' => '#mainContent .product:eq(2) .investment_price:eq(0) > strong', 
			'observer_products_id' => 11, 
			'observer_merchants_id' => 3
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://golderado.hu/befektetesi-arany', 
			'selector' => '#mainContent .product:eq(3) .investment_price:eq(0) > strong', 
			'observer_products_id' => 10, 
			'observer_merchants_id' => 3
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://golderado.hu/befektetesi-arany', 
			'selector' => '#mainContent .product:eq(4) .investment_price:eq(0) > strong', 
			'observer_products_id' => 9, 
			'observer_merchants_id' => 3
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://golderado.hu/befektetesi-arany', 
			'selector' => '#mainContent .product:eq(5) .investment_price:eq(0) > strong', 
			'observer_products_id' => 8, 
			'observer_merchants_id' => 3
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://golderado.hu/befektetesi-arany', 
			'selector' => '#mainContent .product:eq(6) .investment_price:eq(0) > strong', 
			'observer_products_id' => 7, 
			'observer_merchants_id' => 3
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://mak.magyararanykereskedo.hu/artable.php?friss=yes&dev=HUF&for=F&typ=BEF', 
			'selector' =>'tr.beffhufsor td.beffhufadat:eq(0)', 
			'observer_products_id' => 13, 
			'observer_merchants_id' => 2
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://mak.magyararanykereskedo.hu/artable.php?friss=yes&dev=HUF&for=F&typ=BEF', 
			'selector' =>'tr.beffhufsor td.beffhufadat:eq(1)', 
			'observer_products_id' => 12, 
			'observer_merchants_id' => 2
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://mak.magyararanykereskedo.hu/artable.php?friss=yes&dev=HUF&for=F&typ=BEF', 
			'selector' =>'tr.beffhufsor td.beffhufadat:eq(2)', 
			'observer_products_id' => 11, 
			'observer_merchants_id' => 2
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://mak.magyararanykereskedo.hu/artable.php?friss=yes&dev=HUF&for=F&typ=BEF', 
			'selector' =>'tr.beffhufsor td.beffhufadat:eq(3)', 
			'observer_products_id' => 10, 
			'observer_merchants_id' => 2
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://mak.magyararanykereskedo.hu/artable.php?friss=yes&dev=HUF&for=F&typ=BEF', 
			'selector' =>'tr.beffhufsor td.beffhufadat:eq(4)', 
			'observer_products_id' => 9, 
			'observer_merchants_id' => 2
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://mak.magyararanykereskedo.hu/artable.php?friss=yes&dev=HUF&for=F&typ=BEF', 
			'selector' =>'tr.beffhufsor td.beffhufadat:eq(7)', 
			'observer_products_id' => 8, 
			'observer_merchants_id' => 2
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://mak.magyararanykereskedo.hu/artable.php?friss=yes&dev=HUF&for=F&typ=BEF', 
			'selector' =>'tr.beffhufsor td.beffhufadat:eq(8)', 
			'observer_products_id' => 7, 
			'observer_merchants_id' => 2
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://mak.magyararanykereskedo.hu/artable.php?friss=yes&dev=HUF&for=F&typ=BEF', 
			'selector' =>'tr.beffhufsor td.beffhufadat:eq(9)', 
			'observer_products_id' => 6, 
			'observer_merchants_id' => 2
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://mak.magyararanykereskedo.hu/artable.php?friss=yes&dev=HUF&for=F&typ=BEF', 
			'selector' =>'tr.beffhufsor td.beffhufadat:eq(10)', 
			'observer_products_id' => 5, 
			'observer_merchants_id' => 2
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://www.conclude.hu/aranyrendeles?gclid=COyyqaqX6rQCFURY3godwT4A1g', 
			'selector' =>'.productrow_5 .price_sell_HUF_0', 
			'observer_products_id'  => 6, 
			'observer_merchants_id' => 7
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://www.conclude.hu/aranyrendeles?gclid=COyyqaqX6rQCFURY3godwT4A1g', 
			'selector' =>'.productrow_6 .price_sell_HUF_0', 
			'observer_products_id'  => 7, 
			'observer_merchants_id' => 7
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://www.conclude.hu/aranyrendeles?gclid=COyyqaqX6rQCFURY3godwT4A1g', 
			'selector' =>'.productrow_7 .price_sell_HUF_0', 
			'observer_products_id'  => 8, 
			'observer_merchants_id' => 7
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://www.conclude.hu/aranyrendeles?gclid=COyyqaqX6rQCFURY3godwT4A1g', 
			'selector' =>'.productrow_8 .price_sell_HUF_0', 
			'observer_products_id'  => 9, 
			'observer_merchants_id' => 7
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://www.conclude.hu/aranyrendeles?gclid=COyyqaqX6rQCFURY3godwT4A1g', 
			'selector' =>'.productrow_9 .price_sell_HUF_0', 
			'observer_products_id'  => 10, 
			'observer_merchants_id' => 7
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://www.conclude.hu/aranyrendeles?gclid=COyyqaqX6rQCFURY3godwT4A1g', 
			'selector' =>'.productrow_10 .price_sell_HUF_0', 
			'observer_products_id'  => 11, 
			'observer_merchants_id' => 7
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://www.conclude.hu/aranyrendeles?gclid=COyyqaqX6rQCFURY3godwT4A1g', 
			'selector' =>'.productrow_11 .price_sell_HUF_0', 
			'observer_products_id'  => 12, 
			'observer_merchants_id' => 7
		));
		$this->insert_entry('observer_selectors', array(
			'url' => 'http://www.conclude.hu/aranyrendeles?gclid=COyyqaqX6rQCFURY3godwT4A1g', 
			'selector' =>'.productrow_12 .price_sell_HUF_0', 
			'observer_products_id'  => 13, 
			'observer_merchants_id' => 7
		));
		$this->insert_entry('observer_merchants', array('id' => 8, 'title' => 'Stargold', 'website' => 'http://www.solargold.hu', 'description' => '', 'map' => '' ));
		$this->insert_entry('observer_selectors', array( // 5g
		'url' => 'http://solargold.hu/tombarany-adas-vetel.html', 
			'selector' =>'.goldtable tr:eq(2) td:eq(4) span:eq(0)', 
			'observer_products_id'  => 6, 
			'observer_merchants_id' => 8
		));
		$this->insert_entry('observer_selectors', array( // 10g
			'url' => 'http://solargold.hu/tombarany-adas-vetel.html', 
			'selector' =>'.goldtable tr:eq(3) td:eq(4) span:eq(0)', 
			'observer_products_id'  => 7, 
			'observer_merchants_id' => 8
		));
		$this->insert_entry('observer_selectors', array( // 20g
			'url' => 'http://solargold.hu/tombarany-adas-vetel.html', 
			'selector' =>'.goldtable tr:eq(4) td:eq(4) span:eq(0)', 
			'observer_products_id'  => 8, 
			'observer_merchants_id' => 8
		));
		$this->insert_entry('observer_selectors', array( // 50g
			'url' => 'http://solargold.hu/tombarany-adas-vetel.html', 
			'selector' =>'.goldtable tr:eq(6) td:eq(4) span:eq(0)', 
			'observer_products_id'  => 9, 
			'observer_merchants_id' => 8
		));
		$this->insert_entry('observer_selectors', array( // 100g
			'url' => 'http://solargold.hu/tombarany-adas-vetel.html', 
			'selector' =>'.goldtable tr:eq(8) td:eq(4) span:eq(0)', 
			'observer_products_id'  => 10, 
			'observer_merchants_id' => 8
		));	
		$this->insert_entry('observer_selectors', array( // 250g
			'url' => 'http://solargold.hu/tombarany-adas-vetel.html', 
			'selector' =>'.goldtable tr:eq(9) td:eq(4) span:eq(0)', 
			'observer_products_id'  => 11, 
			'observer_merchants_id' => 8
		));	
		$this->insert_entry('observer_selectors', array( // 500g
			'url' => 'http://solargold.hu/tombarany-adas-vetel.html', 
			'selector' =>'.goldtable tr:eq(10) td:eq(4) span:eq(0)', 
			'observer_products_id'  => 12, 
			'observer_merchants_id' => 8
		));		
		$this->insert_entry('observer_selectors', array( // 1000g
			'url' => 'http://solargold.hu/tombarany-adas-vetel.html', 
			'selector' =>'.goldtable tr:eq(11) td:eq(4) span:eq(0)', 
			'observer_products_id'  => 13, 
			'observer_merchants_id' => 8
		));		
		$this->insert_entry('observer_data', array('id' => 1, 'observer_products_id' => 1, 'observer_merchants_id' => 1, 'price' => 3600, 'created' => 0)); 
		$this->insert_entry('observer_data', array('id' => 2, 'observer_products_id' => 2, 'observer_merchants_id' => 1, 'price' => 4000, 'created' => 0)); 
		$this->insert_entry('observer_data', array('id' => 3, 'observer_products_id' => 3, 'observer_merchants_id' => 1, 'price' => 6250, 'created' => 0)); 
		$this->insert_entry('observer_data', array('id' => 4, 'observer_products_id' => 4, 'observer_merchants_id' => 1, 'price' => 8100, 'created' => 0)); 
		$this->insert_entry('observer_data', array('id' => 5, 'observer_products_id' => 5, 'observer_merchants_id' => 1, 'price' => 10675, 'created' => 0)); 
		$this->insert_entry('observer_data', array('id' => 6, 'observer_products_id' => 6, 'observer_merchants_id' => 1, 'price' => 1000, 'created' => 0)); 
		$this->insert_entry('observer_data', array('id' => 7, 'observer_products_id' => 7, 'observer_merchants_id' => 1, 'price' => 1000, 'created' => 0)); 
		$this->insert_entry('observer_data', array('id' => 8, 'observer_products_id' => 8, 'observer_merchants_id' => 1, 'price' => 1000, 'created' => 0)); 
		$this->insert_entry('observer_data', array('id' => 9, 'observer_products_id' => 9, 'observer_merchants_id' => 1, 'price' => 1000, 'created' => 0)); 
		$this->insert_entry('observer_data', array('id' => 10, 'observer_products_id' => 10, 'observer_merchants_id' => 1, 'price' => 1000, 'created' => 0)); 
		$this->insert_entry('observer_data', array('id' => 11, 'observer_products_id' => 11, 'observer_merchants_id' => 1, 'price' => 1000, 'created' => 0)); 
		$this->insert_entry('observer_data', array('id' => 12, 'observer_products_id' => 12, 'observer_merchants_id' => 1, 'price' => 1000, 'created' => 0)); 
		$this->insert_entry('observer_data', array('id' => 13, 'observer_products_id' => 13, 'observer_merchants_id' => 1, 'price' => 1000, 'created' => 0)); 

		return true;
	}

	public function uninstall()
	{
		$this->streams->streams->delete_stream( 'observer_categories', 'streams');
		$this->dbforge->drop_table('observer_categories');
		if ($this->db->table_exists('data_streams'))
		{
			$this->db->where('stream_namespace', 'observer_categories')->delete('data_streams');
		}

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

	protected function insert_entry( $stream, $entry_data  )
	{
		$this->streams->entries->insert_entry($entry_data, $stream, 'streams');
	}
}
