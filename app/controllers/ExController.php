<?php
//use Excel;

class ExController extends BaseController {

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

	public function showWelcome()
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
          
          $t = new Location;
          $r = $row->all();
          
          $t->master_id = $r['yoox_store_source_id'];
          $t->name = $r['post_title'];
          $t->sign = $r['wpcf_yoox_store_sign'];
          $t->save();
          
          $usernew = new User;
     //     $usernew->addNewFromExcel($row->all());

            
        // Loop through all rows
          $row->each(function($cell) {
            
          });

        });
    });
	}

}
