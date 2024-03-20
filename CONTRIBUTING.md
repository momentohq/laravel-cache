<img src="https://docs.momentohq.com/img/logo.svg" alt="logo" width="400"/>

# Welcome to laravel-cache contributing guide :wave:

Thank you for taking your time to contribute to our Momento Laravel cache driver!
<br/>
This guide will provide you information to start your own development and testing.
<br/>
Happy coding :dancer:
<br/>

## Requirements

Check out our SDK [requirements](https://github.com/momentohq/laravel-cache#requirements)!

## Run Integration Test

```bash
export MOMENTO_API_KEY=<YOUR_AUTH_TOKEN>
export MOMENTO_CACHE_NAME=<YOUR_CACHE_NAME>
php vendor/phpunit/phpunit/phpunit --configuration phpunit.xml
```