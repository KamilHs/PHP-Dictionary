# Dictionary PHP Implementation

## Description

* In this project user can add words and their translations which will be stored in database. 
* Fully CRUD implementation. 
* Project requires authentication and no one can see someone else's words. 
* Statistics are provided in profile page.
* User can search certain words by filters or by query.
* Quizzes functionality is provided with review.



## Key Moments

* It is possible to add several translations to a word simultaneously by seperating translations by comma(,)
* If there are more than certain number of words then load more button will appear


## Configuration

In ```config/db.php``` file database constants can be modified. 
```php
const HOST = "localhost";
const USER = "root";
const PASSWORD = "";
const DATABASE = "dictionary";
```

For default values you must create a database name ```dictionary``` on your local machine.

## Demo

http://kamilhs.alwaysdata.net/online_dictionary/