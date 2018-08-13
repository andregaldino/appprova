## Request & Response Examples

### GET /
Example: http://localhost:8080/api/students

Return all students

Response body:

    {
        "data": [
            {
                "id": 1,
                "name": "Galdino"
            },
            {
                "id": 2,
                "name": "Andre"
            }
        ]
    }


### POST /

Example: http://localhost:8080/api/students

Create new student

Request body:

    [
        {
            "name":"Andre",
        }
    ]
            

Response body:

    {
        "data": {
            "id": 2,
            "name": "Andre"
        }
    }

### POST /{id}/subscribeCourse

Example: http://localhost:8080/api/students/2/subscribeCourse
    
Subscribe a student to courses with grade(optional) 

Request body:

    [
        "courses" : [
            "2":{
                "grade":2
            },
            "3":{
                "grade":4
            },
        ]
    ]


    Or without grade


    [
        "courses" : [
            2,3
        ]
    ]

Other example:

```
courses[2][grade] = 2
courses[3][grade] = 4
```          
Response body:


    {
        "data": {
            "id": 2,
            "name": "Andre"
        }
    }