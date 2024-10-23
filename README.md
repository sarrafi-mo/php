
# Simple PHP API

A lightweight PHP API for handling CRUD (Create, Read, Update, Delete) operations for user management. This project demonstrates how to build a basic RESTful API using PHP and MySQL.

## Features
- **GET**: Fetch all users from the database.
- **POST**: Add a new user with a name and email.
- **PUT**: Update the details of an existing user.
- **DELETE**: Remove a user from the database.

## Technologies Used
- PHP
- MySQL
- JavaScript (with Axios)
- HTML & Bootstrap (for client-side testing)

## Setup Instructions
1. Clone the repository:
    ```
    git clone https://github.com/sarrafi-mo/simple-api-php.git
    ```
2. Navigate to the project directory:
    ```
    cd simple-api-php
    ```
3. Import the `users.sql` file into your MySQL database to create the `users` table.
4. Update the database connection settings in `db.php` if necessary.
5. Run the PHP server:
    ```
    php -S localhost:8000
    ```
6. Access the API via `http://localhost:8000/api.php`.

## Endpoints
- **GET /api.php**: Retrieve all users.
- **POST /api.php**: Add a new user. Requires JSON body with `name` and `email`.
- **PUT /api.php**: Update an existing user. Requires JSON body with `id`, `name`, and `email`.
- **DELETE /api.php**: Delete a user. Requires JSON body with `id`.

### Example JSON Payloads
- **POST**:
    ```json
    {
        "name": "John Doe",
        "email": "john@example.com"
    }
    ```
- **PUT**:
    ```json
    {
        "id": 1,
        "name": "John Smith",
        "email": "john.smith@example.com"
    }
    ```
- **DELETE**:
    ```json
    {
        "id": 1
    }
    ```

## Testing the API
The `postman.html` file in the project can be used for basic testing of the API. It provides a simple interface built with Bootstrap and Axios.

1. Open `postman.html` in your browser.
2. Use the forms to interact with the API.

## License
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
