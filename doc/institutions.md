## Request & Response Examples

### GET /
Example: http://localhost:8080/api/institutions

Return all institutions ordered by id

Response body:

    {
        "data":[
            {
                "id":1,
                "name":"instituto Federal",
                "grade":0,
                "courses":1
            }
        ]
    }


### POST /

Example: http://localhost:8080/api/institutions

Create new institution

Request body:

    [
        {
            "name":"instituto Federal",
            "grade":4,
        }
    ]
            

Response body:

    {
        "data": {
            "id": 1,
            "name": "instituto Federal",
            "grade": 4,
            "courses": 0
        }
    }



### GET /orderedGrade

Example: http://localhost:8080/api/institutions/orderedGrade

Return all institutions ordered by grade

Response body:

    {
        "data":[
            {
                "id":2,
                "name":"instituto Federal",
                "grade":4,
                "courses":1
            },
            {
                "id":1,
                "name":"Universidade Federal",
                "grade":3,
                "courses":2
            }
        ]
    }
    
### PATCH /{id}/update

Example: http://localhost:8080/api/institutions/1/update

Update a institution

Request body:

    [
        {
            "name":"Instituto Federal",
            "grade":5,
        }
    ]
            

Response body:

    {
        "data": {
            "id": 1,
            "name": "Instituto Federal",
            "grade": 5,
            "courses": 0
        }
    }


### GET /searchByName/{name}

Search institutions by part of name

Example: http://localhost:8080/api/institutions/searchByName/instituto

    {
        "data":[
            {
                "id":21,
                "name":"Instituto Tecnologia de Aeronautica",
                "grade":5,
                "courses":3
            },
            {
                "id":2,
                "name":"instituto Federal",
                "grade":4,
                "courses":1
            },
        ]
    }

### GET /findByGrade/{grade}

Return institutions by grade

Example: http://localhost:8080/api/institutions/findByGrade/5
    
    
    {
        "data":[
            {
                "id":21,
                "name":"Instituto Tecnologia de Aeronautica",
                "grade":5,
                "courses":3
            },
            {
                "id":10,
                "name":"Impacta",
                "grade":5,
                "courses":3
            },
        ]
    }
