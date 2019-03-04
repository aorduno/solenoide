# test-trapp

# About the project...
* `api` contains all the server code. It was built in Laravel 5 and runs under http://localhost:6969.
* `client` contains all the client code. It was built in EmberJS and runs under http://localhost:4200.
* `processors` contains the code which process transactionUploads concurrently. It was built using phpthreads extension and it runs in the background using `supervisor` service.
* there's an instance of mysql server running at localhost:3306.

# Notes before running
1) Make sure you have docker installed.
2) Make sure ports 3306, 4200 and 6969 are available.

# Installation
In repo's root, open up .env file and update the ENV variables to repo's location, there's an example provided.
## NOTE: PLEASE DOWNLOAD THE PROJECT AND UNZIP IT, APPARENTLY CLONING IS WIP.

# Running
Make sure you're in repo's root and just do `docker-compose up`.
After that you should be able to load the client in: http://localhost:4200. Client communicates with API at http://localhost:6969.

Thanks!
