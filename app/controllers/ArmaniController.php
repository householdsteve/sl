<?php
//use Excel;

class ArmaniController extends BaseController {

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
  
	public function importi()
	{
    echo "man";
  }
	public function import()
	{
    Excel::load('public/stores_armani.xls', function($reader) {

        // // Getting all results
//         $results = $reader->get();
// 
//         $reader->dump();
        
        
        $reader->each(function($row) {
          
          $collection_array = [];
          
          $r = $row->all();
          
          
          $t = Location::firstOrNew(array('master_id' => $r['master']));
          //$t = Location::where('master_id', '=', $r['master'])->first();
          
          //echo "<pre>"; print_r($t->createCategory()); echo "</pre>";
          
          $t->master_id = $r['master'];
          $t->name = $r['name'];
          $t->sign = $r['sign'];
          $t->address = $r['indirizzo'];
          $t->city = $r['localita'];
          $t->nation_iso3166 = $r['nazione_iso_3166'];
          $t->phone = $r['tel_punto_vendita'];
          $t->type_macro = $r['tipo_store_macro'];
          $t->brand_type = $r['tipo_store'];
          $t->relationship = $r['tipo_rapporto'];
          
          
          if(trim($r['ac'])!='') $collection_array[] = 'Armani Collezioni';
          if(trim($r['ea'])!='') $collection_array[] = 'Emporio Armani';
          if(trim($r['ea7'])!='') $collection_array[] = 'EA7';
          if(trim($r['aj'])!='') $collection_array[] = 'Armani Jeans';
          if(trim($r['aju'])!='') $collection_array[] = 'Armani Junior';
          if(trim($r['casa'])!='') $collection_array[] = 'Armani Casa';
          if(trim($r['ga'])!='') $collection_array[] = 'Giorgio Armani';
          if(trim($r['fiori'])!='') $collection_array[] = 'Armani Fiori';
          if(trim($r['dolci'])!='') $collection_array[] = 'Armani Dolci';
          // if(trim($r['occhiali'])!='') $collection_array[] = 'Armani Eyewear'; // doesnt exist with yoox
          // if(trim($r['orologi_gioielli'])!='') $collection_array[] = 'Armani orologi_gioielli'; // doesnt exist with yoox
          // if(trim($r['cosmetics'])!='') $collection_array[] = 'Armani cosmetics'; // doesnt exist with yoox
          // if(trim($r['libri'])!='') $collection_array[] = 'Armani libri'; // doesnt exist with yoox
          
          $createFromName = $t->createCategory($collection_array);
          //$mergedArray = $t->mergeCategories($collection_array,$createFromName);
          
          $t->brands_serialized = serialize($createFromName);
          $t->last_import_data = serialize($r);
          
          $date = trim($r['data_apertura']);
          $year  = substr($date,0,4);  # extract 4 char starting at position 0.
          $month = substr($date,4,2);  # extract 2 char starting at position 4.
          $day   = substr($date,6);
          
          if(!isset($r['data_apertura']) || trim($r['data_apertura'])==='') $t->date_opened = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, $day, $year));

          $t->save();
            
        // Loop through all rows
          $row->each(function($cell) {
            
          });

        });
        
    });
	}

}
