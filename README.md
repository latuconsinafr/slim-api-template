# slim-api-template

This is a repository template based on Slim 4 Framework.


## Features

Several features are already supported inside this repository template such as:
- Validation using [cakephp/validation](https://github.com/cakephp/validation) combined with [selective/validation](https://github.com/selective-php/validation) to construct a nice exception middleware, thanks a lot to [Daniel Opitz a.k.a odan](https://github.com/odan).
- ORM using [cycle/orm](https://cycle-orm.dev/) with a simple database seeder provided. To make use the repository just simply get repository based on the entity for example `$orm->getRepository(UserEntity::class)`.
- Logger using [monolog/monolog](https://github.com/Seldaek/monolog) with supports for file and console logger.
- Environment variables using [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv). The current settings support an overridden environment, simply make another env file for example `.env.staging` and add your staging environment to the default `.env`. The settings under the same environment key would be automatically replaced.
- Unit Test using [phpunit/phpunit](https://github.com/sebastianbergmann/phpunit) with mocked database. If the tests getting bigger and more complex and furthermore it runs slower than before, preferably shifting to the mocked repositores and transactions approach. The service `UserService` is already testable, you can run `composer test` to see all the test case scenarios and all the output.
- Beautiful swagger docs loaded with [slim/php-view](https://github.com/slimphp/PHP-View).

## Screenshoot
![screencapture-localhost-8000-docs-v1-2022-01-17-18_48_37](https://user-images.githubusercontent.com/23124690/149764628-2d5df8d8-b912-4abe-9c44-1312d40214f0.png)

## Usage

Basically, you want to set up your environment variables inside the `.env` file and then simply run a php command: 
```
php -S localhost:<your_lovely_port_here> -t public/
```

If you want to run the database seeder, make sure that you've set up the initial data inside the `resources\setup` folder with the `initialdata.json` name to your appropriate database structur (according to your table(s) and column(s) name) and then run the following command:
```
php src/Supports/Seeder.php
```
If the seeding process is done, the success message would appear in your whatever runs the command in.

## License

The slim-api-template is open-sourced template repository licensed based on Slim 4 Framework, under the [MIT license](https://opensource.org/licenses/MIT).
