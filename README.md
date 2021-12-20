# Upload Image Symfony

## Devel Quick Setup
### Prerequisites

- Docker
- Docker Compose

### Steps
- `clone repo`
- `cd repo`
- `docker/setup.sh` this will take long time
- Open the browser and check it's working! [Serving in localhost](http://localhost)

### To use on development

- to stop environment use `docker/stop.sh`
- to start environment use `docker/start.sh`

Once started you can develop normally. For internal commands and console wise use `devel/console`

## Tests
To run the tests execute the following command `php ./vendor/bin/phpunit` from the console.

## Security configuration
The users are listed in the file `config\services.yalml` in the path `security:providers:users_in_memory:memory:users`

API Authentication is authenticated through the header `X-AUTH-TOKEN: some_user`

Existing user: 345689245425098243509