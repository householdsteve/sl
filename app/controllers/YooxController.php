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
  protected $export_array = array();
  
	public function export($lang)
	{
    $this->localangvar = $lang; // the current languge
    
    // these are the keys we should export in excel
    $export_keys = array(
      "master_id" => array(
        "column_name" => "_yoox-store-source-id",
        "process_method" => NULL
      ),
      "sign_pre_translation" => array(
        "column_name" => "post_title",
        "process_method" => "add_translated_store_name"
      ),
      "url_slug" => array(
        "column_name" => "post_name",
        "process_method" => "create_post_slug"
      ),
      "address" => array(
        "column_name" => "wpcf-yoox-store-geolocation-address",
        "process_method" => NULL
      ),
      "lat" => array(
        "column_name" => "_yoox-store-lat",
        "process_method" => NULL
      ),
      "long" => array(
        "column_name" => "_yoox-store-lng",
        "process_method" => NULL
      ),
      "phone_verified" => array(
        "column_name" => "wpcf-yoox-store-phone",
        "process_method" => NULL
      ),
      "co_sign" => array(
        "column_name" => "wpcf-yoox-store-sign",
        "process_method" => NULL
      ),
      "email" => array(
        "column_name" => "wpcf-yoox-store-email",
        "process_method" => NULL
      ),
      "hours" => array(
        "column_name" => "wpcf-yoox-store-hours",
        "process_method" => NULL
      ),
      "email_mtm_store_manager" => array(
        "column_name" => "wpcf-yoox-store-mtm-store-manager-email",
        "process_method" => NULL
      ),
      "email_mtm_area_manager" => array(
        "column_name" => "wpcf-yoox-store-mtm-area-manager-email",
        "process_method" => NULL
      ),
      "mtm_store" => array(
        "column_name" => "wpcf-yoox-store-mtm",
        "process_method" => NULL
      ),
      "accepts_gift_card" => array(
        "column_name" => "wpcf-yoox-store-gift-card",
        "process_method" => NULL
      ),
      "country_iso_verified" => array(
        "column_name" => "wpcf-yoox-store-country-iso",
        "process_method" => NULL
      ),
      "type_macro" => array(
        "column_name" => "store-types-macro",
        "process_method" => NULL
      ),
      "type" => array(
        "column_name" => "store-types",
        "process_method" => NULL
      ),
      "brand_type" => array(
        "column_name" => "store-types-general",
        "process_method" => NULL
      ),
      "nation_iso3166" => array(
        "column_name" => "location_1",
        "process_method" => NULL
      ),
      "city" => array(
        "column_name" => "location_2",
        "process_method" => NULL
      ),
      "brands_serialized" => array(
        "column_name" => "store-brands",
        "process_method" => "prepare_store_brands"
      )                                                
    );
    //$export_array = array();
    
    //$translated = Location::whereNull('date_closed')->take(10)->get();
    $translated = Location::whereNull('date_closed')->get();
    
    foreach($translated as $key => $v):
        $location_cached = $v->toArray();
        $locales = $v->translation()->where('language', '=', $this->localangvar)->get()->toArray();
        // main loop does all locations and builds new export array.
        
        foreach($locales as $k => $val):
          // second loops overwrites translated variables
          if (array_key_exists($val['key_name_reference'], $location_cached)) {
              $location_cached[$val['key_name_reference']] = $val['value'];
          }
        endforeach;
        
        $localarray = array();

          
        foreach($export_keys as $lkey => $name):
             $func = $name['process_method'];
             $local_val = (array_key_exists($lkey, $location_cached)) ? $location_cached[$lkey] : $lkey;
             
             if(isset($func) && method_exists($this,$func)): 
                 $nv = $this->$func($local_val,$location_cached); // pass in the object too to get additional characteristics from location  
                 $localarray[$name['column_name']] = $nv;
             else:
                 $localarray[$name['column_name']] = $local_val;
             endif;
             
        endforeach;
        
        $this->export_array[] = $localarray;
        
    endforeach;
    
        //echo "<pre>"; print_r($export_array);echo "</pre> --------------------------------------------------------------------------------------------";
    // outside of this loop create the sheet with the new array we've just created
    
    Excel::create('export_'.$this->localangvar, function($excel) {

        $excel->sheet('Sheetname', function($sheet) {

            
            $sheet->fromArray($this->export_array);

        });

    })->download('xlsx');
     echo "exported file";
    
  }
  
  
  public function prepare_store_brands($f,$o)
  {
        $val = unserialize($f);
        return implode(",",$val);
  }
  
	public function create_post_slug($f,$o)
	{
      $title = $o["sign_pre_translation"]." ".((isset($o["sign_translation_key"])) ? $o["sign_translation_key"].' ' : '').$o["address"]." ".$o["city"];
      $slug = Str::slug($title);
      return $slug;
  }
  
  
	public function add_translated_store_name($f,$o)
	{
    $gender_keys = array(
      "en" => array(
        "man" => "MAN",
        "woman" => "WOMAN",
        "accessories" => "ACCESSORIES",
        "prepend" => false
      ),
      "cn" => array(
        "man" => "男士",
        "woman" => "女士",
        "accessories" => "配饰",
        "prepend" => false
      ),
      "jp" => array(
        "man" => "MAN",
        "woman" => "WOMAN",
        "accessories" => "ACCESSORIES",
        "prepend" => false
      ),
      "ru" => array(
        "man" => "МУЖСКОЙ БУТИК",
        "woman" => "ЖЕНСКИЙ БУТИК",
        "accessories" => "АКСЕССУАРЫ",
        "prepend" => true
      ),
      "fr" => array(
        "man" => "HOMME",
        "woman" => "FEMME",
        "accessories" => "ACCESSOIRES",
        "prepend" => false
      ),
      "it" => array(
        "man" => "UOMO",
        "woman" => "DONNA",
        "accessories" => "ACCESSORI",
        "prepend" => false
      ),
      "de" => array(
        "man" => "HERREN",
        "woman" => "DAMEN",
        "accessories" => "ACCESSORIES",
        "prepend" => false
      )
    );
    

    if($gender_keys[$this->localangvar]["prepend"]){
      $val = (isset($gender_keys[$this->localangvar][$o["sign_translation_key"]])) ? $gender_keys[$this->localangvar][$o["sign_translation_key"]]." ".$f : $f;
    }else{
      $val = (isset($gender_keys[$this->localangvar][$o["sign_translation_key"]])) ? $f.$gender_keys[$this->localangvar][$o["sign_translation_key"]] : $f;
    }
      $val = ucwords(strtolower($val));

    return $val;
  }
  
  
  
  public function merge($lang="it")
	{
    $this->localangvar = $lang;
    Excel::load('public/available-languages/stores_'.$lang.'.xls', function($reader) {
        
        $reader->each(function($row) {
        
          
          $r = $row->all();
          
          $t = Location::where('master_id', '=', $r['yoox_store_source_id'])->first();
          //$t = Location::firstOrNew(array('master_id' => $r['yoox_store_source_id']));
          
          $copied_fields = array(
              //"sign" => "post_title",
              "address" => "wpcf_yoox_store_geolocation_address"
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
          echo "things are cool";
            
        }
        // Loop through all rows
          // $row->each(function($cell) {
//
//           });

        });
    });
	}

}
