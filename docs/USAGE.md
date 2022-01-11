# Basic usage

```php
// Create main object
$storage = new KV\Storage();

// Set or update value for the key
$storage->set('key-name', 'value');

// Check if key exist
$storage->isExist('key-name');

// Delete key
$storage->delete('key');

// Get key value
try {
    $storage->get('key-name');
} catch (KV\NotExistKeyException $e) {
    // ...
}

```
