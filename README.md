# Laravel Project

This is a web application built with Laravel for the backend and Vue.js for the frontend.

## Requirements

- PHP >= 7.3
- Composer
- Node.js and npm

## Installation

1. Clone the repository:
    ```sh
    git clone https://github.com/KristapsSteins/Hacker-News-Scraper.git
    ```
2. Navigate to the project directory:
    ```sh
    cd yourrepository
    ```
3. Install PHP dependencies:
    ```sh
    composer update
    ```
4. Install JavaScript dependencies:
    ```sh
    npm install
    ```
5. Copy the example environment file and modify according to your environment:
    ```sh
    cp .env.example .env
    ```
6. Generate a new application key:
    ```sh
    php artisan key:generate
    ```
7. Run the database migrations (**Set the database connection in .env before migrating**):
    ```sh
    php artisan migrate
    ```

## Usage

1. Start the local development server:
    ```sh
    php artisan serve
    ```

You can now access the server at http://localhost:8000

2. To scrape news from the Hacker News API, run:
    ```sh
    php artisan scrape:hackernews 100
    ```

This will scrape the top 100 news items from the Hacker News API.

## Testing

Run the tests with:

```sh
vendor/bin/phpunit
```
