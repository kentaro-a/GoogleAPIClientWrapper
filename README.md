# GoogleAPIClientWrapper
Wrapper of GoogleAPIClient

## Requires
- Env-requirements:
   - php70 or later
   - composer
       ex) $ curl -sS https://getcomposer.org/installer | php
           $ mv composer.phar /usr/local/bin/composer
   - apiclient
       ex) $ composer require google/apiclient:^2.0

## Sample
- index.php: Access point of user.
- oauth2callback.php: Callback from Google Authentication page.
