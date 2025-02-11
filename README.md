## Table of Contents
1. [Requirements](#requirements)
2. [Setup](#setup)
3. [Usage](#usage)

## Requirements
- [PHP ^8.2](https://www.php.net/releases/8.2/en.php)

## Setup
1. Clone or download repository

2. CD into project folder

3. Install Laravel dependency
```sh
composer install
```

4. Create copy of ```.env```
```sh
cp .env.example .env
```

5. Generate laravel key
```sh
php artisan key:generate
```

6. Set database environment in ```.env```
```sh
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

7. Run Laravel migrate and seeder
```sh
php artisan migrate --seed
``` 

8. Start development server
```sh
php artisan serve
```

## Usage
Account
- Email: test@example.com
- Password: password

Endpoints
- Auth
	- `POST` `/api/auth/login`
	- `POST` `/api/auth/register`
	- `POST` `/api/auth/logout`
	- `GET` `/api/auth/me`

- Post
	- `GET` `/api/posts`
	- `POST` `/api/posts`
	- `GET` `/api/posts/{id}`
	- `PUT|PATCH` `/api/posts{id}`
	- `DELETE` `/api/posts{id}`

- User
	- `GET` `/api/users`
	- `POST` `/api/users`
	- `GET` `/api/users/{id}`
	- `PUT|PATCH` `/api/users{id}`
	- `DELETE` `/api/users{id}`