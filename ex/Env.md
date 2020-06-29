# Available Methods
```php
/**
 * $projectDir string root project directory
 *
 */
$env = new Env(string $projectDir)
```

```php
/**
 *
 * initialize .env file from example env
 * example env by default is set to .env.example (it can be changed from conf::env('filename'))
 *
 */
$env->init(void) : bool
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
$env->set(string $key, string $value) : array
```

```php
/**
 * read env file key
 *
 */
$env->get(string $key, string $default = NULL) : string
```

```php
/**
 * update env file
 *
 */
$env->update() : bool
```

```php
/**
 * delete env file key
 *
 */
$env->delete() : bool
```