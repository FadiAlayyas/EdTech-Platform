# Laravel EdTech Platform

This project is a RESTful API for an EdTech platform built using Laravel. It is designed to handle users (students and teachers), courses, assignments, and submissions, while ensuring security, concurrency, and performance optimization.

## How It Works

The project is configured using Docker, with all services (web server, database, and Elasticsearch) running in separate containers. This setup simulates a realistic environment, allowing smooth development without needing to install all prerequisites locally.

(Http::pool) : For the purpose of submitting the data to the external jsonplaceholder API, Laravel's Http Pool feature is used. This ability allows multiple requests to be sent at the same time. When a student submits several tasks at once, to a limit of 5 tasks, would cause up to 5 submissions to be processed in parallel .

(Jobs) : The submission logging process is handled in the background using Laravel's job system. When a student submits an assignment, the logging requests to JSONPlaceholder are dispatched to a job queue .

(Elasticsearch) : is used for fast and efficient course search. When a new course is created, it is automatically indexed in Elasticsearch. This enables quicker and more accurate searches across the courses, improving user experience, especially when the course list grows large.

## Table of Contents

- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Running the Application](#running-the-application)
- [Usage](#usage)
- [Test](#phpUnit-testing)

## Prerequisites

Before you begin, ensure you have met the following requirements:

- **PHP 8.1 or higher**
- **Composer**
- **Docker** and **Docker Compose**
- **Laravel Sail** (for development environment)
- **Elasticsearch** (for courses search)
  
## Installation

Follow these steps to install the application:

1. **Clone the repository**:
    ```bash
    git clone <repository-url>
    cd <project-directory>
    ```

2. **Install the dependencies**:
    ```bash
    ./vendor/bin/sail composer install Or composer install
    ```

3. **Copy the environment file**:
    ```bash
    cp .env.example .env
    ```

4. **Generate the application key**:
    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

## Rebuilding Sail Images

Sometimes you may want to completely rebuild your Sail images to ensure all of the image's packages and software are up to date. You may accomplish this using the following commands:

1. **Stop and remove all containers and volumes**:
    ```bash
    docker compose down -v
    # or
    ./vendor/bin/sail down
    ```

2. **Rebuild the Sail images**:
    ```bash
    docker compose build --no-cache
    # or
    ./vendor/bin/sail build --no-cache
    ```

3. **Start the Sail environment**:
    ```bash
    docker compose up -d
    # or
    ./vendor/bin/sail up -d
    ```

## Running the Application

After setting up the Sail environment, proceed with the following steps:

5. **Run the migrations**:
    ```bash
    ./vendor/bin/sail artisan migrate
    ```

6. **Seed the database**:
    ```bash
    ./vendor/bin/sail artisan db:seed --class=DatabaseSeeder
    ```

7. **If needed, refresh the database** (drop all tables, run migrations, and seed again):
    ```bash
    ./vendor/bin/sail artisan migrate:refresh --seed
    ```

8. **Reindex Elasticsearch**:
    ```bash
    ./vendor/bin/sail artisan search:reindex
    ```

9. **Optimize the application**:
    ```bash
    ./vendor/bin/sail artisan optimize
    ```
10. **Work Queue**:
    ```bash
    ./vendor/bin/sail artisan queue:work
    ```
## PHPUnit Testing

Run PHPUnit tests: You can use PHPUnit to test CRUD operations, authentication, and the integration with the mock JSONPlaceholder service.

1. **To run the tests**:
  ```bash
    ./vendor/bin/sail artisan test
  ```

## Usage

Once the application is running, you can access it at `http://localhost`.