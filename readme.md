# Appprova

That repository  contains a API
## Requirements

 - Docker
 - Docker Compose

## Installation
Clone repository
```sh
$ git clone git@github.com:andregaldino/appprova.git
$ cd appprova
```

Now we need to create our containers witch has API

That command create two containers, one with nginx and php running on localhost:8080 to server our application and other with our database mysql running on localhost:33067 
```sh
$ docker-compose up -d
```

Copy file `.env.example` to `.env`


Install dependencies of lumen framework and create database

```
$ docker exec appprova-app composer install
$ docker exec appprova-app php artisan migrate
```

that's it, our Application is running on localhost:8080


## Usage 
 To run tests we use PHPUnit 
 
 ```docker exec appprova-app vendor/bin/phpunit```
 

 to use API it's required insert data in database
 
 ### Using API  
 - Create institutions
 - Create courses
 - Create students
 - Sync students to course 

 
 ## API endpoints
 
 ## Heroku 
 
 Endpoint to heroku (https://andre-appprova.herokuapp.com)
 
 ### Institutions
ENDPOINT /api/v1/institutions
- [GET /](./doc/institutions.md#get)
- [POST /](./doc/institutions.md#post)
- [GET /orderedGrade](./doc/institutions.md#get-orderedGrade)
- [PATCH /{id}/update](./doc/institutions.md#patch-idupdate)
- [GET /searchByName/{name}](./doc/institutions.md#get-searchByNamename)
- [GET /findByGrade/{grade}](./doc/institutions.md#get-findGradegrade)
 
 ### Courses
 ENDPOINT /api/v1/courses
 - [GET /](./doc/courses.md#get)
 - [POST /](./doc/courses#post)
 - [GET /searchByName/{name}](./doc/courses.md#get-searchByNamename)
 - [GET /averageGrade](./doc/courses.md#get-aerageGrade)
 - [PATCH /{id}/update](./doc/courses.md#patch-idupdate)
 - [POST /{id}/subscribeStudents](./doc/courses.md#post-idsubscribeStudents)
  
  
  ### Students
  ENDPOINT /api/v1/students
  - [GET /](./doc/students.md#get)
  - [POST /](./doc/students.md#post)
  - [POST /{id}/subscribeCourse](./doc/students.md#post-idsubscribeCourse)
   
 