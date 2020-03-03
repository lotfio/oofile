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
$env->initialize(void) : bool
```

```php
/**
 * parse and load env file to env array
 * 
 */
$env->load(void) : array
```

```php
/**
 * change env key value
 * 
 */
$env->changeValue(string $key, string $value) : array
```

```php
/**
 * update env file
 * 
 */
$env->update() : int
```

```php
/**
 * read env file key
 * 
 */
$env->read(string $key, string $default = NULL) : string
```

```php
/**
 * delete env file key
 * 
 */
$env->delete() : bool
```