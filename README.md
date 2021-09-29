# Challenge

This applicacion can list, create, update and delete profiles, it also can calculate the avg age of the profiles. 

## Requirements

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)
-  Make 

## Initialize Project
To initialize proyect run ```make initialize``` in order to build the docker, run composer etc. Then run ```make migrate-fresh``` to migrate tables.

Apache will be running in __port 85__ and Mysql in __port 3308__ to avoid conflicts with other projects. 

##  Commands
 
```make initialize``` 
Initializes the project, runs docker compose build, runs composer install, and give permissions to */storage* directory

```migrate-fresh```
Runs database migrations

```make up ```
run docker compose and start docker 

```make down```
run docker compose and stop docker 

```make run-tests```
run phpunit tests inside docker

## Endpoints

### List Profiles
```GET http://localhost:85/profiles```
### List One Profile
```GET http://localhost:85/profiles/{id}```
### Create a Profile
```
POST http://localhost:85/profiles 

Content-Type:application/json
Accept:application/json

{
	"name":  "<string>",
	"age":  <integer>,
	"bio":  "<string>",
	"image_url":  "<string>"
}


```

*Properties __name__ and __age__ are mandatories*


### Update a Profile

```
PUT http://localhost:85/profiles/{id} 

Content-Type:application/json
Accept:application/json

{
	"name":  "<string>",
	"age":  <integer>,
	"bio":  "<string>",
	"image_url":  "<string>"
}
```



### Delete a Profile

``` DELETE http://localhost:85/profiles/{id}```


### Calculate average age of profiles

``` GET http://localhost:85/profiles/average/age```