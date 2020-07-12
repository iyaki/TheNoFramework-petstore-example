# TheNoFramework-petstore-example
Example implementation of a simplified petstore rest api with [TheNoFramework](https://github.com/iyaki/TheNoFramework).

## Deploy
```shell
git clone git@github.com:iyaki/TheNoFramework-petstore-example.git && cd TheNoFramework-petstore-example
composer install
docker-compose up -d
```
TheNoFramework doesn't implements a router system so all the routing of this app is done by nginx.

## Available endpoints
**Note**: By default the app listens the port 8080.
All the endpoints implements a Bearer token authentication with the token: `iamatoken`

### GET /pet/<id>
example curl:
```shell
curl --request GET \
  --url http://localhost:8080/pet/1590270079 \
  --header 'authorization: Bearer iamatoken'
```

### POST /pet/
example curl:
```shell
curl --request POST \
  --url http://localhost:8080/pet \
  --header 'authorization: Bearer iamatoken' \
  --header 'content-type: application/json' \
  --data '{
	"name": "Firulais"
}'
```

### PUT /pet/
example curl:
```shell
curl --request PUT \
  --url http://localhost:8080/pet \
  --header 'authorization: Bearer iamatoken' \
  --header 'content-type: application/json' \
  --data '{
	"id": 1590270079,
	"name": "Mr Whiskers"
}'
```

### DELETE /pet/<id>
example curl:
```shell
curl --request DELETE \
  --url http://localhost:8080/pet/1590251389 \
  --header 'authorization: Bearer iamatoken'
```

### GET /pet/findByStatus
example curl:
```shell
curl --request GET \
  --url 'http://localhost:8080/pet/findByStatus?status=sold' \
  --header 'authorization: Bearer iamatoken'
```

### GET /store/order/<id>
example curl:
```shell
curl --request GET \
  --url http://localhost:8080/store/order/1592275553 \
  --header 'authorization: Bearer iamatoken'
```

### POST /store/order
example curl:
```shell
curl --request POST \
  --url http://localhost:8080/store/order \
  --header 'authorization: Bearer iamatoken' \
  --header 'content-type: application/json' \
  --data '{
	"petId": 1590270079,
	"shipDate": "2020-06-20T10:00:00"
}'
```

### DELETE /store/order/<id>
example curl:
```shell
curl --request DELETE \
  --url http://localhost:8080/store/order/1590270111 \
  --header 'authorization: Bearer iamatoken' \
  --header 'content-type: application/json'
```
