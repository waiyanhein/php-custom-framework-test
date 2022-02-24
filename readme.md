### Spinning up the project

The project is using Docker and I was using Mac OS while I was working the project. So please run the docker and run the following command inside the project root folder to spin up the project.

- `docker-compose up -d`

After you run the command, you can access project in the browser on `localhost` on port 80.

### Running the unit tests

To run all the unit test please run the following command.

- `docker-compose exec php-fpm php test.php`

To run a certain test method, please run the following command.

- `docker-compose exec php-fpm php test.php {test method name}`


### Report

Please open `report.md`.


Please let me know if you have any questions.

Best Regards,

Wai