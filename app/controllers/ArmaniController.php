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
  
	public function imports()
	{
    echo "man";
    
    $obj = Array();
    $obj['name'] = "coolersss";
    $obj['sign'] = "maners";
    
    DB::collection('locations')->where('name', 'dudes')
                           ->update($obj, array('upsert' => true));
    
    
    
    
    echo "<pre>"; print_r($obj); echo "</pre>";
    
  }
  
	public function phone()
	{
    
    $phoneUtils = \libphonenumber\PhoneNumberUtil::getInstance();
        
       
        
    //echo phone_format("248 982 4247", "US");
    
    //$locs = Location::whereNotNull('phone')->get();
    $locs = Location::all();
    echo $locs->count();
    foreach($locs as $key => $v):

      if(isset($v->country_iso_verified) && $v->phone_verified == NULL):
        
        try {
            $numberProto = $phoneUtils->parse($v->phone, $v->country_iso_verified);

            $updated_phone = $phoneUtils->format($numberProto, \libphonenumber\PhoneNumberFormat::INTERNATIONAL);
            echo $updated_phone."<br>";
            $updatedloc = Location::find($v->id);
            $updatedloc->phone_verified = $updated_phone;
            $updatedloc->save();
            
        } catch (\libphonenumber\NumberParseException $e) {
            //echo "<pre>"; print_r($e); echo "</pre> --------------------------------------------------------------------------------------------";
        }

      endif;  
    endforeach;
    
    
  }
  
	public function genderizer()
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
    
    $locs = Location::all();
    echo $locs->count();
    
    
    
    foreach($locs as $key => $v):
      $lvar = null;
      
      // used this code to set database tables
      foreach($gender_keys["it"] as $k => $val):
        if(str_contains($v->sign,$val)) $lvar = $k;
        echo $v->id." ".$lvar."<br>";
      endforeach;
      $updatedloc = Location::find($v->id);
      $updatedloc->sign_translation_key = $lvar;
      $updatedloc->save();
            

    endforeach;
    
    
  }
  
  
  
  public function geocode()
  {
    $locs = Location::whereNull('lat')->get();
    foreach($locs as $key => $v):
       
      $locationaddress = $v->address." ".$v->city.", ".$v->nation_verified;
      $this->curl = New Curl;
      $locationobject = $this->curl->simple_get('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($locationaddress).'&sensor=false&key=AIzaSyAtiEkSR9a6K2Ih-avv8Meu_N8SpEgOK9g');
      $nn = json_decode($locationobject);
      
      if(count($nn->results) > 0){
        $updatedloc = Location::find($v->id);
        $updatedloc->lat = $nn->results[0]->geometry->location->lat;
        $updatedloc->long = $nn->results[0]->geometry->location->lng;
        $updatedloc->save();
        
        echo $v->address." ".$v->city.", ".$v->nation_verified."<br>";
        echo $nn->results[0]->geometry->location->lat."<br>";
        echo $nn->results[0]->geometry->location->lng;
        echo "<pre>"; print_r($nn);echo "</pre> --------------------------------------------------------------------------------------------";
      }
      
    endforeach;
   
    
    
  }
  
  public function fix_country()
  {
    $locs = Location::whereNull('country_iso_verified')->get();
    foreach($locs as $key => $v):
       
      $locationaddress = $v->address." ".$v->city.", ".$v->nation_iso3166;
      echo "<pre>"; print_r($locationaddress);echo "</pre> --------------------------------------------------------------------------------------------";
      $this->curl = New Curl;
      $locationobject = $this->curl->simple_get('http://api.geonames.org/searchJSON?q='.urlencode($v->nation_iso3166).'&maxRows=1&username=steve');
      $nn = json_decode($locationobject);
      echo "<pre>"; print_r($nn);echo "</pre> --------------------------------------------------------------------------------------------";
      if(count($nn->geonames) > 0){

        $updatedloc = Location::find($v->id);
        //$updatedloc->country_iso = $nn->geonames[0]->countryCode;
        $updatedloc->country_iso_verified = $nn->geonames[0]->countryCode;
        $updatedloc->nation_verified = $nn->geonames[0]->countryName;

        $updatedloc->save();

        echo "cool";
        //echo "<pre>"; print_r($nn);echo "</pre> --------------------------------------------------------------------------------------------";

      }
      
    endforeach;
   
    
    
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
          
         echo "<pre>"; print_r($r);echo "</pre> --------------------------------------------------------------------------------------------";
         
          $t = Location::firstOrNew(array('master_id' => $r['aam_code']));
          //$t = Location::where('master_id', '=', $r['master'])->first();


          $t->master_id = $r['aam_code'];
          $t->name = $r['sales_point_name'];
          $t->sign = $r['sign'];
          $t->address = $r['address'];
          $t->city = $r['city'];
          $t->nation_iso3166 = $r['nation'];
          $t->phone = $r['thelephone_n0'];
          $t->type_macro = $r['tipo'];
          $t->brand_type = $r['type_of_store'];
          $t->relationship = $r['relationship_kind'];


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

          $date = trim($r['opening_date']);
          $year  = substr($date,0,4);  # extract 4 char starting at position 0.
          $month = substr($date,4,2);  # extract 2 char starting at position 4.
          $day   = substr($date,6);

          if(isset($r['opening_date'])) $t->date_opened = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, $day, $year));
          
          $datec = trim($r['closing_date']);
          $yearc  = substr($datec,0,4);  # extract 4 char starting at position 0.
          $monthc = substr($datec,4,2);  # extract 2 char starting at position 4.
          $dayc   = substr($datec,6);

          if(isset($r['closing_date']) && $r['closing_date'] != '47121231') $t->date_closed = date('Y-m-d H:i:s', mktime(0, 0, 0, $monthc, $dayc, $yearc));

          $t->save();
            
        // Loop through all rows
          $row->each(function($cell) {
            
          });

        });
        
    });
	}
  
	public function import_signs()
	{
    Excel::load('public/stores_armani_signs.xls', function($reader) {

        // // Getting all results
//         $results = $reader->get();
// 
//         $reader->dump();
        
        
        $reader->each(function($row) {
          

          
          $r = $row->all();
          
          if(isset($r['id_entity'])):
          //$t = Location::firstOrNew(array('master_id' => $r['id_entity']));
          $t = Location::where('master_id', '=', $r['id_entity'])->first();
          
            if($t):
              echo "<pre>"; print_r($r); echo "</pre>";
          
              $t->co_sign = $r['accompanying_name'];
              $t->address = $r['address'];
              $t->save();
            endif;
          endif;

        });
        
    });
	}

}
