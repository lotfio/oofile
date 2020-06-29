# Available Methods
```php
/**
 * get instance
 */
 $up = new File
```

```php
/**
 * create file method
 *
 * @param $file string file name
 * @param $mode string file create mode
 */
 $up->create(string $file, string $mode = 'w+') : bool
```

```php
/**
 * write to file method
 *
 * @param $file string file name
 * @param $content string content
 */
 $up->write(string $file, string $content) : bool
```

```php
/**
 * get file size method
 *
 * @param $file string file name
 */
 $up->size(string $file) : int
```

```php
/**
 * rename file method
 *
 * @param $old string old file name
 * @param $new string new file name
 */
 $up->rename(string $old, string $new) : bool
```

```php
/**
 * copy file method
 *
 * @param $old string file name
 * @param $new string new file
 */
 $up->copy(string $old, string $new) : bool
```

```php
/**
 * move file method
 *
 * @param $file string file name
 * @param $destination string
 */
 $up->move(string $file, string $destination) : bool
```

```php
/**
 * move file method
 *
 * @param $file string file name
 */
 $up->delete(string $file) : bool
```

```php
/**
 * move file method
 *
 * @param $file string file name
 */
 $up->exists(string $filename) : bool
```