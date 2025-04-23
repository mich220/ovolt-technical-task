# 🚀 Ovolt recruitment task

Simple Order create, read, update functionality

---

## 📦 Requirements

- Docker + Docker Compose
- make (installed by default on most linux dists)

---

## Setup

### 1. Configure `.env`

Copy `.env.dist` to `.env` and adjust the values:

```bash
cp .env.dist .env
```

Suggested content:

```dotenv
###> symfony/framework-bundle ###
APP_ENV=dev
APP_TIMEZONE=GMT+2
APP_SECRET=randomsecret
APP_URL=localhost
###< symfony/framework-bundle ###

###> doctrine/doctrine-bundle ###
DATABASE_URL="mysql://root:root@ovolt-mysql:3306/ovolt_db?serverVersion=13&charset=utf8"
###< doctrine/doctrine-bundle ###
```

## Run containers

You can check the MAKEFILE or just run:

```bash
make build
```

---

## Run composer install

Run makefile command:

```bash
make install
```

---

## Create the test + dev database

Create and migrate the `dev` database:

```bash
make console
bin/console doctrine:database:create
bin/console doctrine:migrations:migrate
```

Create and migrate the `test` database:

```bash
make console
bin/console doctrine:database:create --env=test
bin/console doctrine:migrations:migrate --env=test
```

---

## Tests

```bash
make test
```

---

## PHP CS Fixer

```bash
make cs-fix
```
## Author notes

### Project Architecture

This project is built on **Symfony's default architectural approach**, following conventional application structure using Controllers, Services, DTOs, and Entities. 

I intentionally **did not adopt Domain-Driven Design (DDD)** for this project, as the business logic is relatively simple and does not require additional abstraction layers or complex modeling. Sticking with Symfony's built-in structure keeps the codebase clean, understandable, and easier to onboard new developers.

### Why PHP-CS-Fixer?

We use **PHP-CS-Fixer** to enforce consistent coding standards across the project. It helps:
- Maintain readable and clean code,
- Automate formatting during development,
- Prevent unnecessary code-style changes in pull requests.

The fixer is configured with rules compatible with Symfony and PSR standards, allowing us to stay aligned with the ecosystem's best practices.

### Symfony Components Used

#### `symfony/maker-bundle`

This project uses **Symfony Maker Bundle** to make development proces faster, I used it for:
- Entities,
- Controllers,
- Form Types (dtos),
- Subscribers / event handlers.

#### `symfony/validator`

Used to validate incoming data in a declarative and reusable way. Validation constraints are applied in:
- **DTOs** for request data validation (especially important for JSON APIs),

This improves reliability, testability, and clearly communicates requirements.

#### `symfony/serializer`

The serializer enables extra features for DTO denormalizing.

#### `doctrine/orm`

Doctrine is the default ORM for Symfony and it provides easy interface to interact with database.

In addition I've added lazy virtual property for Order.total field. It could be calculated during Order creation, but having current requirements I've decided to use this approach, as it's easier to maintain.
