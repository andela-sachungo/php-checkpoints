# Open Source Evangelist
Create a class that uses the github api to get the number of repositories
a user has.

Analyze the resulting JSON string and assign various status to each user according
the number of repositories they have. The status description are:
  * Junior Evangelist
  * Associate Evangelist
  * Most Senior Evangelist

## How to use it
Clone the repository : `git clone <Repository Link>`

Change the directory to `/1B`

Add phpunit package to the `composer.json` file and run `composer install`

Run the tests on the tests folder by running `phpunit`

## More on PHPUnit
Compose a test suite in the root folder using xml configuration as explained in [PHPUnit website](https://phpunit.de/manual/current/en/organizing-tests.html#organizing-tests.xml-configuration)
