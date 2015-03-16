<?php
/**
 *	GeoDistanceBehavior
 *	Finds the closets rows of a given point defined by latitude and longitude
 *	in a rounded area
 */
class GeoDistanceBehavior extends ModelBehavior {

/**
 * Settings to configure the behavior
 *
 * @var array
 */
    public $settings = array();

	
/**
 * Initiate behaviour
 *
 * @param object $Model
 * @param array $settings
 */
    public function setup(Model $Model, $settings = array()) {
        if(!array_key_exists("lat_field",$settings) || !array_key_exists("lon_field",$settings)) {
			die("Latitude and longitude fields are not defined");
		}
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = $settings;
		}
		
    }
    
/**
 *	Find the closests rows around an area 
 *
 *	@param object $Model
 *	@param array $options
 */	
	public function findClosest(Model $Model, $options) {
		if(!$options['latitude'] || !$options['longitude']) {
			return null;
		} 
		$settings = array_merge($this->settings[$Model->alias], $options);
		
		if($settings['unit'] == 'miles') {
			$earth = 3959;
		}else {
			$earth = 6371;
		}
		
		if(!isset($settings['conditions'])){
			$settings['conditions'] = null;
		}
		

		$data = $Model->find('all', [
			'group' => ["distance HAVING distance < $settings[radius]"],
			'fields' => [
				'*',
				"($earth * acos(
					cos( radians($settings[latitude]) )
					* cos( radians( $settings[lat_field] ) )
					* cos( radians( $settings[lon_field] )
					- radians($settings[longitude]) )
					+ sin( radians($settings[latitude]) )
					* sin( radians( $settings[lat_field] ) )
				) ) AS distance",
				
			],
			'conditions' => $settings['conditions'],
			'order' => ['distance ASC'],
			'limit' => 10
		]);

		if(!empty($data)) {
			foreach($data as $index => $row) {
				$row['Sitio']['distance'] = $row[0]['distance'];
				unset($row[0]);
				$data[$index] = $row;
			}
		}
		return $data;
	}
}