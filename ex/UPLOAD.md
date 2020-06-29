# Available Methods
```php
/**
 * $filename    : input file name
 * $destination : where to save file
 */
 $up = new Upload($filename, $destination) : void
```

```php
/**
 * $size : 1 = 1M, 2 = 2M
 * default allowed size is 8M
 */
 $up->setMaxSize($size) : self
```

```php
/**
 * $types : array|string  of types to be allowed
 * better use both extension and MIME TYPE : ['txt', 'text/plain']
 * default allowed files are images
 */
 $up->addAllowedTypes($types) : self
```

```php
/**
 * this method can be used to override default allowed types which is images only
 */
 $up->resetAllowedTypes($arrayOfTypes) : self
```

```php
/**
 * $strict :  check if file already uploaded
 * default   FALSE => checks only file name
 *           TRUE  => checks file name size and content with sha1_file and file_size
 */
$up->unique($strict = FALSE) : self
```

```php
/**
 * check if no errors and file can be uploaded
 */
 $up->isValid() : bool
```

```php
/**
 * upload file
 */
 $up->proceed() : array

```

```php
/**
 * errors array. can be used if validation fails
 */
 $up->errors() : array
```
