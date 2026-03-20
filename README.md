# Notebook PHP

Basic web application written in PHP (no framework used) to keep a notebook of daily notes. When I first created this project around 2018, my goal was to learn web development and PHP.

## Key Features

* User login.
* Store user notes by date.
* Store the user notes encrypted in the database.
* Navigate per month.

## Prerequisites

You can run the app with docker.
* Docker Engine 18.06 or later
* Docker Compose 1.25 or later

## Installation

To install and run the Notebook PHP application:

1. Clone the repository: `git clone git@github.com:jlpalaciosb/notebook-php.git`
2. Navigate to the project directory: `cd notebook-php`
3. Start the application using Docker Compose: `docker-compose up`
4. Open your web browser and go to [http://localhost:8080](http://localhost:8080)

## Database Persistence

The application uses a Docker volume (`notebook-postgres-data`) to store your data persistently, so your notes remain safe even after restarting the containers.

## Environment Variables (Optional)

Customize ports and database password by creating a `.env` file or setting variables before running `docker-compose up`:

* `APACHE_PORT` - Web server port (default: `8080`)
* `POSTGRES_PORT` - Database port (default: `5454`)
* `POSTGRES_PASSWORD` - Database password (default: `postgres`)

# Tech Stack

* [PHP 7](https://hub.docker.com/_/php)
* [PostgreSQL 9](https://hub.docker.com/_/postgres)
* [Apache 2.4](https://hub.docker.com/_/php)
