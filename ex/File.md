# Available Methods
```php
/**
 * get instance
 */
 $oo = new File;
```

```php
/**
 * create file method
 *
 * @param $file string file name
 * @param $mode string file create mode
 */
 $oo->create(string $file, string $mode = 'w+') : bool
```

```php
/**
 * write to file method
 *
 * @param $file string file name
 * @param $content string content
 */
 $oo->write(string $file, string $content) : bool
```

```php
/**
 * get file size method
 *
 * @param $file string file name
 */
 $oo->size(string $file) : int
```

```php
/**
 * rename file method
 *
 * @param $old string old file name
 * @param $new string new file name
 */
 $oo->rename(string $old, string $new) : bool
```

```php
/**
 * copy file method
 *
 * @param $old string file name
 * @param $new string new file
 */
 $oo->copy(string $old, string $new) : bool
```

```php
/**
 * move file method
 *
 * @param $file string file name
 * @param $destination string
 */
 $oo->move(string $file, string $destination) : bool
```

```php
/**
 * move file method
 *
 * @param $file string file name
 */
 $oo->delete(string $file) : bool
```

```php
/**
 * move file method
 *
 * @param $file string file name
 */
 $oo->exists(string $filename) : bool
```