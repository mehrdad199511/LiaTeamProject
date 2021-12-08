# LiaTeam
## About
Lia company employment project  
It is a project in which the possibility of registering, login and editing users and controlling the access level of users has been implemented.
In this project, by passport scope, three types of roles are envisaged(superAdmin,admin,basic), and each of which has a specific level of access.
Therefore, each token that is created has a specific access level.

## How can I run it?
### #1 Execute composer install command.
```bash
composer install
```

### #2 Create a database in your machin and set configs in .env file and run below commands .
```bash
php artisan key:generate
php artisan config:cache
```

### #3 Run migrate command for create the tables.
```bash
php artisan migrate
```

### #4 You should execute the passport:install Artisan command. This command will create the encryption keys needed to generate secure access tokens .
```bash
composer require laravel/passport
php artisan passport:install
```

### #5 Run below command for create sample user.
```bash
php artisan db:seed
```

### #6 Run below command for start the app.
```bash
php artisan serve
```

### #7 Make sure everything is OK! :wink:


## How can I dive into?

### #1 Authentication section
#### Do Register
```bash
curl -X POST "http://localhost/api/v1/auth/register" -H "Content-Type: application/json" -H "Accept: application/json" -d '{"name": "your name", "email": "your email", "password": "your password", "password_confirmation": "your password"}'
```
##### Result sample
```json
{
  "message": "User creation is successful!",
  "user": {
   "id": ?,
    "name": "Your name",
    "email": "Your email",
    "created_at": "create datetime",
    "updated_at": "update dateTime"
  },
  "token": "eyJ0eXAiO.........."
}
```

#### Do login
```bash
curl -X POST "http://localhost/api/v1/auth/login" -H "Content-Type: application/json" -H "Accept: application/json" -d '{"email": "your email", "password": "your password"}'
```
##### Result sample
```json
{
  "message": "Successful login!",
  "user": {
    "name": "your name",
    "email": "your email"
  },
  "token": "eyJ0eXAiO.........."
}
```

### #2 user section

#### get all users list (Note: Any type of user has access to this data.)
```bash
curl -X GET "http://localhost:8000/api/v1/user/usersList" -H "Accept: application/json" -H "Authorization: Bearer token"
```
##### Result sample
```json
{
    "message": "Successful get user info",
    "data": [
        {
            "id": ?,
            "name": "User name",
            "email": "User email address",
            "created_at": "create datetime",
            "updated_at": "update dateTime"
        },
        .
        .
        .
    ]
}
```

#### Get a user by id (Note: Any type of user has access to this data.)
```bash
curl -X GET "http://localhost:8000/api/v1/user/getUser/?" -H "Content-Type: application/json" -H "Accept: application/json" -H "Authorization: Bearer token"
```
##### Result sample
```json
{
  "message": "Successful get user info",
    "data": {
        "id": ?,
        "name": "User name",
        "email": "User email address",
        "created_at": "create datetime",
        "updated_at": "update dateTime"
    }
  ]
}
```

#### update user name and password (Note: superAdmin and admin has access to this action.)
```bash
curl -X GET "localhost:8000/api/v1/user/update/?" -H "Content-Type: application/json" -H "Accept: application/json" -H "Authorization: Bearer token" -d '{"name": "Your name", "password": "your password"}'
```
##### Result sample
```json
{
  {
     "message": "Successfull update info."
  }
}
```

#### Delete user (Note: only superAdmin has access to this action.)
```bash
curl -X DELETE "http://localhost/api/v1/user/delete/?" -H "Content-Type: application/json" -H "Accept: application/json" -H "Authorization: Bearer token"
```
##### Result sample
```json
{
  {
    "message": "Successfull delete user"
  }
}
```


### #3 Message section

#### change user role (Note: only superAdmin has access to this action.)
```bash
curl -X GET "http://localhost:8000/api/v1/role/change/3" -H "Content-Type: application/json" -H "Accept: application/json" -H "Authorization: Bearer token" -d '{"role": "admin"}'
```
##### Result sample
```json
{
  "message": "Successfull update role."
}
```
