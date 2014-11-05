<?php

class Location extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'locations';
  
  public static $unguarded = true; // this should be removed when finished with mass assignment or it should be edited to allow certain fields

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}
  
  
  public function translation()
  {
    return $this->hasMany('Translation', 'master_id', 'master_id');
  }
      
  
	public function mergeCategories($orig,$updated)
	{
    if(count($updated) > 0){
		foreach($updated as $item){
      if (!in_array($item, $orig)) {
          $orig[] = $item;
      }
		}
  }
    echo "<pre style=font-weight:bold;>"; print_r($orig); echo "</pre>";
    return $orig;
	}
  
	public function createCategory($collections)
	{ 

        $matchArray  = array(
                        array('Armani Collezioni' => 
                              array('Armani Collezioni')),
                        array('Emporio Armani' => 
                              array('Emporio Armani')),
                        array('EA7' => 
                              array('EA7','EA 7')),
                        array('Armani Jeans' => 
                              array('Armani Jeans')),
                        array('Armani Junior' => 
                              array('Armani Junior')),
                        array('Armani Casa' => 
                              array('Armani Casa')),
                        array('Giorgio Armani' => 
                              array('Giorgio Armani')),
                        array('Armani Fiori' => 
                              array('Armani Fiori')),
                        array('Armani Dolci' => 
                              array('Armani Dolci')),
                        array('Armani Eyewear' => 
                              array('Armani Eyewear')),
                        array('Armani orologi_gioielli' => 
                              array('Armani orologi_gioielli')),
                        array('Armani cosmetics' => 
                              array('Armani Cosmetics')),
                        array('Armani libri' => 
                              array('Armani Libri'))
                        );
        

        $matchesArray = $collections;

        foreach ($matchArray as $match) {
            foreach ($match as $key => $ind) {
              foreach ($ind as $el) {
              if (preg_match('/'.$el.'/i', $this->name)) {
                //echo "<pre>"; echo "true"; echo "</pre>";
                              if (!in_array($el, $matchesArray)) {
                                  $matchesArray[] = $el;
                              }
                          }
            }  
          }
        }
        
        // get original 
        
        //$merged = array_merge($original,$matchesArray);
        //echo "<pre> already saved"; print_r(unserialize($this->brands_serialized)); echo "</pre>";
        echo "<pre>"; print_r($matchesArray); echo "</pre>";
    
		return $matchesArray;
	}
  
}
