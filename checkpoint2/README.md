# Potato ORM
Build a simple ORM that can perform the basic CRUD database operations.

## How to use it
Download and set up vagrant and Homestead in your machine

Create a database in the MySQL Monitor - accessed via vagrant

Check if the database has been created by running `show databases;`

Open a new terminal window

Clone the repository : `git clone <Repository Link>`

Go to back to the MySQL Monitor and create the actual table by running
  `source full/path/to/car-sql.sql`

To check if the table created is correct, run `DESC table_name;`

Open a new terminal window and change directory to that with the `/Homestead` folder

Run `vagrant ssh` and change directory to `/../checkpoint2`

Run the tests on the `/tests` folder by running `phpunit` in vagrant
