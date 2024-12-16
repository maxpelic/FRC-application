# Employee API

## Data Storage

Each employee's record is stored in a text file named with the employee's ID. These files are stored in the ./data folder.

Each record has data stored on each line:
- Name
- Email
- Position

Storing data this way allows the system to retrieve individual records easily.

## Endpoints

### POST /employees

Add a new employee. POST data should be in JSON format and include the following fields:

    {
        "name": "John Doe",
        "email": "example@gmail.com",
        "position": "Developer"
    }

Returns the new employee's data:

    {
        "id": 000000,
        "name": "John Doe",
        "email": "example@gmail.com",
        "position": "Developer"
    }

Returns a 400 error if any required fields are missing or format is incorrect.

### GET /employees

Get a list of all employees. Returns an array of employee objects:

    [
        {
            "id": 000000,
            "name": "John Doe",
            "email": "example@gmail.com",
            "position": "Developer"
        },
        {
            "id": 000000,
            "name": "John Doe",
            "email": "example@gmail.com",
            "position": "Developer"
        },
        ...
    ]

### GET /employees/{id}

Get an employee by ID. Returns the employee's data if found:

    {
        "id": 000000,
        "name": "John Doe",
        "email": "example@gmail.com",
        "position": "Developer"
    }

Returns a 404 error if the employee is not found.

### PUT /employees/{id}

Update an employee by ID. PUT data should be in JSON format and can include any or all of the following fields - any omitted fields will remain the same:

    {
        "name": "Jane Doe",
        "email": "example@gmail.com",
        "position: "Developer"
    }

Returns the updated employee's data:

    {
        "id": 000000,
        "name": "Jane Doe",
        "email": "example@gmail.com",
        "position": "Developer"
    }

Returns a 404 error if the employee is not found.

Returns a 400 error if any fields are in the incorrect format.

### DELETE /employees/{id}

Deletes an existing employee by ID. Returns a 204 status code if successful.

Returns a 404 error if the employee is not found.