# zf2-osd-entity

This module provides sane default functions for Zend Framework 2 entities. There is a single class included in this module:

  - OsdEntity/BasicEntity

### Version

0.1.1

### Installation and Setup

This package can be installed using composer. Add the following to your composer.json file:

```json
{
    "require": {
        ...,
        "damacisaac/zf2-osd-entity": "0.1.1"
    }
}
```

Run composer:

```sh
php composer.phar update
```

Extend BasicEntity with a class:

```php
<?php

use OsdEntity\BasicEntity;

class MyClass extends BasicEntity { ... }
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


