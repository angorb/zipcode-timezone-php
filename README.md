# zipcode-timezone-php
 A utility class to convert US ZIP codes to 'tz' timezone strings.

### Example:
```php
// import the Timezone class
require_once "/path/to/Timezone.php";

// specify whether or not to use the compressed zipcode data file
// default: false
$timezone = new \Angorb\ZipCodeTimezone\Timezone(true);

echo $timezone->getForZipCode(90120); // prints 'America/Los_Angeles'

echo $timezone->getForZipCode(00000); // invalid ZIP code - prints (bool) 'false' 

// if you're not super concerned with optimization because you have few lookups
// to do, a static method exists
echo \Angorb\ZipCodeTimezone\TimezoneForZip::get(10001); // prints 'America/New_York'
```
### Compressed File Security:
**MD5:** *c4fc51c364cf39c509eba37df01aeb0d*
To verify the gz compressed database matches the plaintext database in the distribution: ``$ php -f tests/compressed_datafile_integrity_check.php``
**Passed:**
```
Uncompressed Checksum:  c4fc51c364cf39c509eba37df01aeb0d    Path: /Users/angorb/Sites/zipcode-timezone-php/data/ziptzdb.json
Compressed Checksum:    c4fc51c364cf39c509eba37df01aeb0d    Path: /Users/angorb/Sites/zipcode-timezone-php/data/ziptzdb.json.gz
OK: Compressed and uncompressed files match.
```
**Failed:**
```
Uncompressed Checksum:  a8f3d499c499ea925a5ca835c436eb1f    Path: /Users/angorb/Sites/zipcode-timezone-php/data/ziptzdb.json
Compressed Checksum:    c4fc51c364cf39c509eba37df01aeb0d    Path: /Users/angorb/Sites/zipcode-timezone-php/data/ziptzdb.json.gz
WARNING: Compressed and uncompressed files DO NOT match!
```