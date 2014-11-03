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
  protected $localangvar;
  
	public function merge($lang)
	{
    $this->localangvar = $lang;
    Excel::load('public/available-languages/stores_'.$lang.'.xls', function($reader) {
        
        $reader->each(function($row) {
        
          
          $r = $row->all();
          
          $t = Location::where('master_id', '=', $r['yoox_store_source_id'])->first();
          //$t = Location::firstOrNew(array('master_id' => $r['yoox_store_source_id']));
          
          $copied_fields = array(
              "sign" => "post_title",
              "wpcf_yoox_store_geolocation_address" => "wpcf_yoox_store_geolocation_address",
          );
          
          foreach($copied_fields as $key => $line){
            $trans = new Translation;
            $trans->master_id = $r['yoox_store_source_id'];
            $trans->key_name_reference = $key;
            $trans->language = $this->localangvar;
            $trans->value = $r[$line];
            echo $trans->master_id." ok! <br>";
            $trans->save();
          }
       

        });
    });
  }

	public function import()
	{
    Excel::load('public/stores_it.xls', function($reader) {

        // // Getting all results
//         $results = $reader->get();
// 
//         $reader->dump();

        $reader->each(function($row) {
          

          
          
          $r = $row->all();
          
          $t = Location::where('master_id', '=', $r['yoox_store_source_id'])->first();
          //$t = Location::firstOrNew(array('master_id' => $r['yoox_store_source_id']));
          //$t = new Location;
          
          if($t){ // check to make sure location still exists
           // this is to merge existing brand categories
          $existing_items = unserialize($t->brands_serialized);
          $yb = explode(",",$r['store_brands']);
  
          if(is_array($existing_items)){
            array_merge($existing_items, $yb);
            $t->brands_serialized = serialize($existing_items);
          } 
          
          
          //$t->master_id = $r['yoox_store_source_id'];
          $t->name = $r['post_title'];
          $t->sign = $r['wpcf_yoox_store_sign'];
          $t->lat = $r['yoox_store_lat'];
          $t->long = $r['yoox_store_lng'];
          //$t->address = $r['wpcf_yoox_store_geolocation_address'];
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
          $t->type_macro = $r['store_types_macro'];
          $t->brand_type = $r['store_types_general'];
//           $t->relationship = "";

          $t->save();
            
        }
        // Loop through all rows
          $row->each(function($cell) {
            
          });

        });
    });
	}

}
