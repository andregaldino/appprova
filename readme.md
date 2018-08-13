# Appprova

That repository  contains a API
## Requirements

 - Docker
 - Docker Compose

## Instalation
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
 
 ##Heroku 
 
 Endpoint to heroku (https://andre-appprova.herokuapp.com)
 
 ### Institutions
ENDPOINT /api/v1/institutions
- [GET /](./doc/institutions#get)
- [POST /](./doc/institutions#post)
- [GET /orderedGrade](./doc/institutions#get-orderedGrade)
- [PATCH /{id}/update](./doc/institutions#patch-idupdate)
- [GET /searchByName/{name}](./doc/institutions#get-searchByNamename)
- [GET /findByGrade/{grade}](./doc/institutions#get-findGradegrade)
 
 ### Courses
 ENDPOINT /api/v1/courses
 - [GET /](./doc/courses#get)
 - [POST /](./doc/courses#post)
 - [GET /searchByName/{name}](./doc/courses#get-searchByNamename)
 - [GET /averageGrade](./doc/courses#get-aerageGrade)
 - [PATCH /{id}/update](./doc/courses#patch-idupdate)
 - [POST /{id}/subscribeStudents](./doc/courses#post-idsubscribeStudents)
  
  
  ### Students
  ENDPOINT /api/v1/students
  - [GET /](./doc/students#get)
  - [POST /](./doc/students#post)
  - [POST /{id}/subscribeCourse](./doc/students#post-idsubscribeCourse)
   
 