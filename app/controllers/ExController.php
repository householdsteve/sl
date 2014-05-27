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
    Excel::load('public/file.xls', function($reader) {

        // // Getting all results
//         $results = $reader->get();
// 
//         $reader->dump();
        

        $reader->each(function($row) {
          echo "<pre>";
          print_r($row->all());
          echo "</pre>";
          
          $usernew = new User;
          $usernew->addNewFromExcel($row->all());

            
        // Loop through all rows
          $row->each(function($cell) {
            
          });

        });
    });
	}

}
