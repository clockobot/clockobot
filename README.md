<p align="center"><a href="https://clockobot.com" target="_blank"><img src="https://dandvoracek.ch/clockobot-github-banner.jpg" alt="Clockobot banner"></a></p>

![Static Badge](https://img.shields.io/badge/coverage%20-%20100%25%20-%20%2384cc16)
![Static Badge](https://img.shields.io/badge/license%20-%20MIT%20-%20%2384cc16)


## About Clockobot

[Clockobot](https://clockobot.com) allows freelancers to track time against their estimates, create billable and non-billable time entries, and generate customized reports.

- Get valuable insights
- Generate tailored reports
- Multiple languages (de, en, es, fr)
- Team spirit: add as many users you want
- API ready (WIP)
- Darkmode available

More information about this project here [www.clockobot.com](https://clockobot.com).

## Installation

Clockobot is built on top of Laravel, using the TALL stack. In order to install it, make sure you follow the following steps. If you struggle, make sure you refer to the extensive [Laravel documentation](https://laravel.com/docs) before submitting a new issue.  

### Project requirements
- PHP 8.2.0 Required: latest Laravel requires PHP 8.2.0 or greater.
- curl 7.34.0: Laravel's HTTP client now requires curl 7.34.0 or greater.

### Noticeable composer dependencies
- [filament/notifications](https://filamentphp.com/docs/3.x/notifications/installation)
- [laravel/sanctum](https://laravel.com/docs/11.x/sanctum)
- [livewire/livewire](https://laravel-livewire.com/)
- [maatwebsite/excel](https://laravel-excel.com/)
- [wire-elements/modal](https://github.com/wire-elements/modal)

### Project setup

1. Clone the project: `git clone https://github.com/clockobot/clockobot.git`
2. Install composer dependencies: `composer install`
3. Install npm dependencies: `composer install`
4. Copy .env.example to .env and amend its values accordingly.
5. Run `php artisan migrate` or if you want some dummy data run `php artisan migrate --seed`
6. Run `php artisan storage:link`
7. Run the app using `php artisan serve` or any other tool (we use [Herd](https://herd.laravel.com/))

### Mail setup

Before adding any user, you need to make sure you have amended these variables in your .env file.

```
MAIL_MAILER=
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="${APP_NAME}"
```

Feel free to use the setup you like when using Laravel apps: https://laravel.com/docs/11.x/mail

### First connexion

Open the site and register a new user. You will directly be redirected to the dashboard.

__When using dummy data:__

If you seeded dummy data during installation, you can login using the following credentials:

User: `clockobot@clockobot.com` | Password: `password`

Once you're connected to the admin, create a new user using your email and **make sure to assign admin rights to it** . It will send you a link to reset your password. Once you've done so, login by using your newly created user. Feel free to then delete the default user created during installation. Note that deleting a user deletes all its data as well, so all the dummy data will be gone.

### Disable registration
User registration hasn't been removed by default but as the app allows you to add users, you might want to disable registration depending on how you want it to be setup.
In order to do so, please follow these steps:

Comment/delete these lines in `routes/auth.php`:

```
Route::get('register', [RegisteredUserController::class, 'create'])
    ->name('register');

Route::post('register', [RegisteredUserController::class, 'store']);
```

### Api Ready (WIP)

While it isn't documented yet, Clockobot comes bundled with some endpoints that allow you to interact with its data from an external tool. As we have projects going this way for Clockobot, this part might evolve along the way. Meanwhile, you can check the `app/Http/Controllers/Api` directory and the `routes/api.php` file to see what endpoints are made available to you. 

## Patreon

If you are interested in becoming a sponsor, please visit the Clockobot [Patreon page](https://patreon.com/Clockobot).

## Contributing

To ensure a smooth and efficient contribution process, please follow the guidelines below.

Bug Fixes
- Branch: All bug fixes should be submitted to the latest version of the develop branch.
- Do not submit to main: Please do not submit bug fixes directly to the main or any tagged branches. This ensures that all changes undergo proper testing and review before being merged into the main codebase.

New Features
- Branch: If you are adding a new feature, please base your work on the develop branch.
- Discussion: Consider opening an issue to discuss the feature before starting to work on it. This helps align with the project’s goals and avoids duplicated efforts.

Pull Requests
- Creating a pull request: Once your changes are ready, open a pull request (PR) targeting the develop branch. Please provide a clear and descriptive title, and include any relevant issue numbers in the PR description.
- Code review: Your pull request will be reviewed by one or more maintainers. Please be responsive to feedback and make any requested changes.

Coding Standards
- Testing: All new features and bug fixes should include appropriate tests. Ensure that all tests pass before submitting your pull request.

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, the Clockobot code of conduct is derived from the [Ruby code of conduct](https://www.ruby-lang.org/en/conduct/).

## Security Vulnerabilities

If you discover a security vulnerability within Clockobot, please send an e-mail to Dan Dvoracek via [hello@clockobot.com](mailto:hello@clockobot.com). Ultimately, as Clockobot is built using Laravel and the TALL stack, make sure to check their documentation / Github accounts too.

## License

The Clockobot web app is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT). Please see [License File](LICENSE.md) for more information.
