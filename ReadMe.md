© 2022-2023 Kamil Kitka

#### CookSpot website
This project is a basic version of a website focused on cooking. 
It enables users to create their own recipes and share them with friends.

#### Technologies
Application follows widely used MVC pattern.
Project is built on docker containers and uses:
* PHP 8.1
* NGINX 1.23
* HTML 
* CSS
* JavaScript 
* MySQL
* Composer

#### How to run the website locally
#Requirements:
* docker installed on your system
* mailing service for email confirmations (for example gmail account configured for application access)

1. Navigate to ```CookSpot/docker``` folder and run ```sudo docker-compose up -d``` to build docker containers. 
When it is finished you will have 3 containers running: 
* cookspot-app - running application 
* cookspot-nginx - running server
* cookspot-db - running database

2. Bash into cookspot-app container using ```sudo docker exec -it cookspot-app bash```.

3. In container run ```composer install```. It will download depedencies used by application and 
generate autoloading files for used classes. When it is finished you can exit the container by typing ```exit```.

4. Rename ```.env.example``` file located in ```CookSpot/src``` to ```.env```, open the file and fill 
undefined constants with your mailing service information.