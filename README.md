# zf2-osd-entity

This module provides some default functions for Zend Framework 2 entities. There is a single class included in this module:

  - OsdEntity/BasicEntity

### Version

0.1.1

### Installation and Setup

This package can be installed using composer. Add the following to your composer.json file:

```js
{
    "require": {
        "damacisaac/zf2-osd-entity": "0.1.1"
    }
}
```

Run composer:

```sh
php composer.phar update
```

Extend BasicEntity with your entity:

```php

use OsdEntity\BasicEntity;

class MyEntity extends BasicEntity { ... }
```

### Usage

##### Fill

An easy way to assign values to your entity. Accepts either an object or an array of key values and an optional list of attributes to exclude:

```php
$myEntity->fill(
    array(
        'firstName' => 'Angus',
        'lastName' => 'MacIsaac',
        'excludedAttributeOne' => 'wontBeSet',
    ),
    array(
        'excludedAttributeOne',
    ));
```

##### Create
Creates an instance of the entity. Accepts either an object or an array of key values and an optional list of attributes to exclude. Uses `fill` internally:

```php
MyEntity::create(
    array(
        'firstName' => 'Angus',
        'lastName' => 'MacIsaac',
        'excludedAttributeOne' => 'wontBeSet',
    ),
    array(
        'excludedAttributeOne',
    ));
```

##### Update
Updates the entity. Accepts either an object or an array of key values and an optional list of attributes to exclude. Uses `fill` internally:

```php
$myEntity->update(
    array(
        'firstName' => 'Angus',
        'lastName' => 'MacIsaac',
        'excludedAttributeOne' => 'wontBeSet',
    ),
    array(
        'excludedAttributeOne',
    ));
```

##### toArray
Converts an entity to an array of key/value pairs. Also accepts an optional list of relations that should be converted to an array and added to the result. Relations can be nested.

To know which attributes to use when converting to an array, we must define them on our model:

```php
protected $this->attributes = array(
    'firstName',
    'lastName'
);
```

In order to convert relationships to an array, we must also define them on the model, specifying the type of relation:

```php
protected $this->relations = array(
    'friends' => self::RELATION_MANY,
    'profile' => self::RELATION_ONE
);
```

Consider a user entity that has many friends, and each friend has a job and a car. We could get a full nested array with the following:

```php
$myUser->toArray(array('friends.job', 'friends.car'))
```

If we only wanted to join friends, we could use:

```php
$myUser->toArray(array('friends'));
```

##### Customizing toArray

To override the return values of the toArray function, we can define a getter on the model. Consider a model that has date attribute that we would like to return as a formatted date string. We would add the following to our entity:

```php
public function getDate()
{
    return $this->date()->format(\DateTime::W3C);
}
```

Of course, if we wanted a greater degree of customization, we can always override the `toArray()` method:

```php
public function toArray(array $with = array())
{
    $result = parent::toArray($with);

    /* Add custom attributes */

    return $result;
}
```





### License

The MIT License (MIT)

Copyright (c) 2015 damacisaac

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.


