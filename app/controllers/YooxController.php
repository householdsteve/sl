<?php
//use Excel;

class YooxController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function import()
	{
    Excel::load('public/stores_en.xls', function($reader) {

        // // Getting all results
//         $results = $reader->get();
// 
//         $reader->dump();

        $reader->each(function($row) {
          
          echo "<pre>";
          print_r($row->all());
          echo "</pre>";
          
          
          $r = $row->all();
          $t = Location::firstOrNew(array('master_id' => $r['yoox_store_source_id']));
          
          $t->master_id = $r['yoox_store_source_id'];
          $t->name = $r['post_title'];
          $t->sign = $r['wpcf_yoox_store_sign'];
          $t->address = $r['wpcf_yoox_store_geolocation_address'];
          $t->city = $r['location_2'];
          $t->country_iso = $r['wpcf_yoox_store_country_iso'];
          $t->nation_iso3166 = $r['location_1'];
          $t->phone = $r['wpcf_yoox_store_phone'];
          $t->email = $r['wpcf_yoox_store_email'];
          $t->email_mtm_area_manager = $r['wpcf_yoox_store_mtm_area_manager_email'];
          $t->email_mtm_store_manager = $r['wpcf_yoox_store_mtm_store_manager_email'];          
          $t->mtm_store = (!isset($r['wpcf_yoox_store_mtm']) || trim($r['wpcf_yoox_store_mtm'])==='') ? 0 : 1;
          $t->accepts_gift_card = (!isset($r['wpcf_yoox_store_gift_card']) || trim($r['wpcf_yoox_store_gift_card'])==='') ? 0 : 1;
          $t->type = $r['store_types'];
          $t->brand_type = $r['store_types_general'];
          $t->relationship = $r['store_types_macro'];

          $t->save();
            
        // Loop through all rows
          $row->each(function($cell) {
            
          });

        });
    });
	}

}
