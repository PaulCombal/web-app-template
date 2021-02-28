Boilerplate backend
===

Supports google auth, has some premade logic for entities like addresses
and logging login attempts.
Make sure to check your .env files first.

Added command `bin/console app:make-admin <userid>` to add ROLE_ADMIN to a user

For GET requests, there is built in pagination with PaginationService. Use GET 's' for start, and 'n' for number of results

There already is a few fixtures for users.
