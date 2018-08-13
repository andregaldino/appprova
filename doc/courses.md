##Request & Response Examples

###GET /
Example: http://localhost:8080/api/courses

Return all courses ordered by id

Response body:

    {
        "data": [
            {
                "id": 1,
                "name": "Analise e Desenvolvimento de Sistemas",
                "grade": 3,
                "institution": {
                    "data": {
                        "id": 1,
                        "name": "instituto Federal",
                        "grade": 0,
                        "courses": 1
                    }
                }
            }
        ]
    }


###POST /

Example: http://localhost:8080/api/courses

Create new course

Request body:

    [
        {
            "name":"Engenharia da Computacao",
            "institution_id":1,
            "grade" : 3
        }
    ]
            

Response body:

    {
        "data": {
            "id": 1,
            "name":"Engenharia da Computacao",
            "grade": 3,
            "institution": {
                "data": {
                    "id": 1,
                    "name": "instituto Federal",
                    "grade": 0,
                    "courses": 1
                }
            }
        }
    }


###GET /averageGrade

Example: http://localhost:8080/api/courses/1/averageGrade

Return average grade by course

Response body:

    {
        "course": "1",
        "averageGrade": 0
    }
    
###PATCH /{id}/update

Example: http://localhost:8080/api/courses/1/update

Update a course

Request body:

    [
        {
            "name":"Ciencias da Computacao",
            "grade":2,
            "institution_id":1 
        }
    ]
            

Response body:

    {
        "data": {
            "id": 1,
            "name":"Ciencias da Computacao",
            "grade": 2,
            "institution": {
                "data": {
                    "id": 1,
                    "name": "instituto Federal",
                    "grade": 0,
                    "courses": 1
                }
            }
        }
    }


###GET /searchByName/{name}

Search all courses by part of nme

Example: http://localhost:8080/api/courses/searchByName/sis

     {
        "data": [
            {
                "id": 1,
                "name": "Analise e Desenvolvimento de Sistemas",
                "grade": 3,
                "institution": {
                    "data": {
                        "id": 1,
                        "name": "instituto Federal",
                        "grade": 0,
                        "courses": 2
                    }
                }
            },
            {
                "id": 1,
                "name": "Sistemas da Informacao",
                "grade": 2,
                "institution": {
                    "data": {
                        "id": 1,
                        "name": "instituto Federal",
                        "grade": 0,
                        "courses": 2
                    }
                }
            }
        ]
    }

###POST /{id}/subscribeStudents

Subscribe students to course with grade(optional)

Example: http://localhost:8080/api/courses/5/subscribeStudents
    
    

Request body:

    [
        "students" : [
            "1":{
                "grade":2
            },
            "5":{
                "grade":4
            },
            "6":{
                "grade":5
            }
        ]
    ]


    Or without grade


    [
        "students" : [
            1,5,6
        ]
    ]

Other example:

```
students[1][grade] = 2
students[5][grade] = 4
students[6][grade] = 5
```          
Response body:

    {
        "data": {
            "id": 5,
            "name": "Analise",
            "grade": 0,
            "institution": {
                "data": {
                    "id": 1,
                    "name": "App prova",
                    "grade": 5,
                    "courses": 3
                }
            }
        }
    }
