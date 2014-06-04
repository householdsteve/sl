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
  
	public function cool()
	{
		echo "cool";
	}
  
}
