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