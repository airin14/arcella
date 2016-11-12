# TokenValidator
The TokenValidator is a service that allows you to add an extra level of confirmation to certain actions. For example you could use it to validate that a user actually has control of the email address he/ she provided within the registration process.

## Configuration
```yaml
arcella_utilities:
    token:
        lifetime: 1800000
        length:   10
        keyspace: "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
```

Inside the main config you can define the following parameters for your Tokens:
* the lifetime (in seconds) 
* the length (in characters)
* and the keyspace that will be used during the creation.

## Usage
You should always use the TokenValidator as a service via the DI-container which can be realized by calling `@arcella_utility_tokenvalidator`.

### Creating a new Token
To create a new Token entity you just need to call the `generateToken()` function.

```php
$token = $this->tokenValidator->generateToken();
```

The returned `$token` variable is the key by which the Token entity could be identified.

Optional you could also provide additional parameters or a life time in seconds to be added to the Token entity like this

```php
// Parameters need to be stored inside an associative array.
$params = array(
    'foo' => $bar,
);
 
// The lifespan is set in seconds as integer.
$lifespan = 60;
 
$token = $this->tokenValidator->generateToken($params, $lifespan);
```

### Validating a Token
If you`d like to validate a Token you just need to call the `validateToken()` function.

```php
$this->tokenValidator->validateToken($key);
```

This function will either return `true` if the provided key represents a valid Token or `false`if this is not the case. If there is no Token entity matching the key this function will throw a `EntityNotFound` Exception.

If the validation was successful and there are any additional parameters set they can be accessed via the TokenValidator.
```php
$params = $this->tokenValidator->getTokenParams();
```

#### Removing a Token
After a successful validation you should remove the Token entity from the TokenRepository.

```php
$this->tokenValidator->removeToken($key);
```
