© 2022-2023 Kamil Kitka

#### CookSpot website
This project is a basic version of a website focused on cooking. 
It enables users to create their own recipes and share them with friends.

#### Technologies
Project is built with docker containers and uses:
* PHP 8.1
* NGINX 1.23
* HTML 
* CSS
* JavaScript 
* MySQL
* Composer

#### How to run the website locally
Requirements:
* [docker](https://docs.docker.com/engine/install/) installed on your system

 Navigate to ```CookSpot/docker``` folder and run ```docker compose up``` to start docker containers (in development mode). 
When startup is finished you will have 3 containers running: 
* cookspot-app - running application 
* cookspot-nginx - running server
* cookspot-db - running database

You can access the site at http://localhost:8000

#### Additional Info
By default email verifications are disabled so mailing service provider is not required to run the application. To enable email verifications modify ```CookSpot/docker/.env``` file:
-  fill missing mailing provider information
- set ```ENABLE_EMAIL_VERIFICATION=TRUE``` 