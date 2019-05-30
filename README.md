# nomoss-dev-homework
Symfony app for movies

When you have pulled this repo you will need to do some steps.

- 1 - The database is not set up so you will want to run a doctrine migration command which runs SQLite queries (I used SQLite because I didn't know how to spin up a MySQL database and I was racing time). Migration files can be found at 
`\src\Migrations`
You can edit them there as much as you like... do not edit and try to run migrations after you alread have run the command\*.
It stores the version number into the database and knows that you've run this migration file.
\*command to run migration is
```php bin/console doctrine:migrations:migrate```
and should be run from the root directory of the project.

- 2 - run 
```php bin/console server:start```
to start up the server. This usually puts uses the localhost address and port 8000.
Accessed via http://127.0.0.1:8000.

- 3 - You have an empty database at this point. You'll want to fill it up with accessing this endpoint http://127.0.0.1:8000/import-movies-once. Follow the instructions there to load data/consume the api from https://www.eventcinemas.com.au/Movies/GetNowShowing into your local database.

- 4 - Access this endpoint http://127.0.0.1:8000/movie and you're all good for the demo.

