# Potato ORM
A simple agnostic ORM that can perform the basic crud database operations.
To use the ORM, extend the Model Class and *ensure you have MySQL installed*.

## How to use it
* Create a database in MySQL before using the Potato ORM
* Clone the repository: `git clone <Repository Link>`
* Change the database connection parameters in `tests/` accordingly
* Run `composer install` to install phpunit and autoload the files
* Create a class which extends the Model class and set a table name using the setter.
  * If you do not assign a table name the name of the class in lowercase will be used
* Run the tests on the `tests/` folder by running `phpunit` 
* You can change the testing table fields in the `tests/DbSettingTest.php` file as well as
the other tests to your liking.



