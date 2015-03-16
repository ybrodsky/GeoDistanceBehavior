# GeoDistanceBehavior
A GeoDistance behavior for CakePHP

## What does it do?
It finds records based on proximity by latitude and longitude. Perfect for those "find businesses near your location" kind of things.

## Usage
In your model:
```php
  public $actsAs = [
      'GeoDistance' => [
          'lat_field' => 'latitude',  //The name of the field where you store latitude
          'lon_field' => 'longitude', //The name of the field where you store longitude
          'unit' => 'miles'           //The unit you use for distances (miles | kilometres)
      ]
  ];
```

To find data:
```php
  $options = [
      'latitude' => -31.5021452,  //The reference latitude
      'longitude' => -60.541249,  //The reference longitude
      'radius' => 2,              //How far around the latitude and longitude where you want to search
      'unit' => 'kilometres',     //Optional, override model configuration
      'conditions' => [           //Optional, pass extra conditions to filter the results
          'active' => 1,
      ]
  ];
  $fetchedData = $this->YourModel->findClosest($options);
```

And that's the way the cookie crumbles. Would like to add some Tests, but I don't have much time. Feel free to make improvements.
