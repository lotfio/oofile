# Available Methods
```php
/**
 * $filename    : input file name
 * 
 * this method loads a single file to config array
 */
 Conf::load(string $filename)
```

```php
/**
 * $path    : directory path
 * 
 * this method loads all your php config files to config array
 */
Conf::loadDir(string $path)
```

```php
/**
 * $key    : config key
 * $conf   : array of config values
 * 
 * this method adds a config key with an array of values to config array
 */
Conf::add(string $key, array $conf)
```

```php
/**
 * $config    : group key (file name | config name)
 * $key       : single config key
 * $value     : default value if not exists 
 * 
 * get a key from config array or set (by default NULL)
 */
Conf::get(string $config, string $key, string $value = null)
```

```php
/**
 * this method return config array
 */
Conf::all()
```