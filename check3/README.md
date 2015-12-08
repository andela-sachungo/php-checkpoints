# Naija Emoji
A simple RESTful API built using Slim Framework for an Emoji Service

##Initial installation
* Clone the repository `git clone <Repository Link>`
* Create a `.env` file and add the following details
  * DATABASE_NAME
  * DATABASE_USERNAME
  * DATABASE_PASSWORD
  * DATABASE_HOST
  * DATABASE_SERVER (This is the base uri of your api e.g *example.com*)
  * SECRET_KEY (This is the key used for encoding and decoding JSON Web Token)
* Change the timezone in `config/database.php` to yours
* Run `composer install` to install dependencies

##Server Information
You can use **Vagrant** virtual machine or set up a server using **Apache 2**

####Using Vagrant
Implement the following after you have installed and set up vagrant:
  * Add the application on `Homestead.yaml` and `/etc/hosts` files. E.g.
  In `Homestead.yaml` as:
  ```
  - map: emoji.app
    to: /home/homestead/php-checkpoints/check3/public
  ```
  In `/etc/hosts` as:
  ```192.168.10.10 emoji.app```
  * Run `vagrant provision`
  * To access the database, run `vagrant ssh` and log into `mysql monitor`
  * The base uri or DATABASE_SERVER for your application is `emoji.app`
  * To run `phpunit`, `vagrant ssh` and `cd /home/homestead/php-checkpoints/check3`

####Setting up a server
To use the in-built php server, follow this steps:
  * Run ` php -S <addr>:<port> [-t docroot]` e.g. `php -S localhost:3000 -t path/to/public directory`
  * Open another terminal tab and navigate to `check3`
  * Run `phpunit`
**NOTE:** Ensure that you create the database according to the details in `.env` before you run `phpunit`. `.htaccess` has already been created for you.

##Testing the API
You can use command-line curl or postman to test the routes.
For instance, using command-line curl:

**Register a user**
`curl -i -X POST -H 'Content-Type: application/json' -d '{"name": "your name", "username": "unique username", "password": "your password"}' http://emoji.app/register `

**Login a user**
`curl -i -X POST -H 'Content-Type: application/json' -d '{"username": "unique username", "password": "your password"}' http://emoji.app/auth/login `

**Logout a user**
`curl -i -X POST -H 'Authorization: Bearer <token_string>'  http://your_base_uri/auth/logout `
The `token_string` is generated and sent to the client on login.

**Get all emojis**
`curl -i -X GET http://your_base_uri/emojis `

To see more example of using curl, [visit this page](http://coenraets.org/blog/2011/12/restful-services-with-jquery-php-and-the-slim-framework/).

**NOTE:** In addition to the `Content-Type` header, add the `Authorization` header to the requests that *creates*, *updates* and *deletes* data.
