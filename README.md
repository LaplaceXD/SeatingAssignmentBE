# Seating Assignment Backend

![Last Update](https://img.shields.io/github/last-commit/LaplaceXD/SeatingAssignmentBE?color=blue&label=Last%20Update)
![Activity](https://img.shields.io/badge/Activity-Completed-blue)

SeatingAssignmentBE is the backend service for the issue tracking and logging system made for the seats assigned in a laboratory. The primary stack used for this system is [React](https://beta.reactjs.org/) for the front-end, [Laravel](https://laravel.com/) for the back-end, and [MySQL](https://www.mysql.com/) for the database.

## Functionalities

-   Track, validated, and manage logged issues for seats in a laboratory.
-   View issues by status, seats, and laboratories.
-   Track progress on validated issues.
-   Paper trail system for issue changes.
-   Role-based authorization on different endpoints.
-   Authentication using [Sanctum](https://laravel.com/docs/10.x/sanctum).

## Requirements

To be able to setup this project, you would need to install the following beforehand.

-   [Xampp](https://www.apachefriends.org/) for MySQL database, don't forget to add `php.exe` to your environment variables.
-   [Composer](https://getcomposer.org/) for managing PHP dependency and running Laravel.
-   (Optional) [Sqlite](https://www.sqlite.org/download.html) and [Sqlite Browser](https://sqlitebrowser.org/) as an alternative for MySQL database, as this is a lightweight database. Sqlite Browser allows you to view the database over a GUI.

## Setup

1. Make sure you have the requirements installed, and don't forget to add `php` to your environment variables.
2. Clone this project to your local machine.
3. Open a terminal inside the cloned directory.
4. Run `composer install` to install the composer dependencies.
5. Run `cp .env.example .env` to create the environment variables for the project.
6. Run `php artisan key:generate` to generate a unique application key.
7. Setup database.
    - **For MySQL:** Open Xampp, and run Apache and MySQL services.
    - **For Sqlite:** Run `touch database/database.sqlite` to create database file.
8. Run `php artisan migrate` to automatically populate database.
9. If prompted if you want to create a database, type Y and press enter.
10. Run `php artisan db:seed` to populate the database with mock data.
11. Run `php artisan serve` to run the backend service.
12. To be able to use the endpoints on this backend service, use the `Postman Collections` that is referenced in the resources.
13. You can login on authenticated endpoints with the following accounts:

```md
# Student

Email: student@example.com
Password: password

# Professor

Email: professor@example.com
Password: password

# Technician

Email: technician@example.com
Password: password
```

## Contributing

Unfortunately, we are not accepting pull requests, since this is a one-time project. However, feel free to
fork this project, and improve on it!

## Resources

-   [FrontEnd](https://github.com/JulianErnest/CIS2201-Class-Project-FE) - Private Front-end
-   [Postman Collection](https://www.postman.com/pan-team-42069/workspace/seatingassignment)
-   [Database ERD](https://drive.google.com/file/d/15KpwS_wjltypArkUHD224PWOEZg9Lcjc/view?usp=sharing)

## License

[MIT](https://github.com/LaplaceXD/SeatingAssignmentBE/blob/master/LICENSE)
