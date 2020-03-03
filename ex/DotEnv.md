# Available Methods
```php
/**
 * $projectDir string root project directory
 * 
 */
$env = new DotEnv(string $projectDir)
```

```php
/**
 * 
 * initialize .env file from example env 
 * example env by default is set to .env.example (it can be changed from conf::env('filename'))
 * 
 */
initialize(void) : bool
```

```php
/**
 * parse and load env file to env array
 * 
 */
load(void) : array
```

```php
/**
 * change env key value
 * 
 */
changeValue(string $key, string $value) : array
```

```php
/**
 * update env file
 * 
 */
update() : int
```

```php
/**
 * read env file key
 * 
 */
read(string $key, string $default = NULL) : string
```

```php
/**
 * delete env file key
 * 
 */
delete() : bool
```