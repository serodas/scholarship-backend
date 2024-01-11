# Scholarship Backend
This project is a backend that facilitates the application of the best high school graduates from educational institutions to a scholarship. The backend is based on Symfony 4 with PHP, following the MVC pattern and using a MySQL database. The backend allows to register, consult and evaluate the candidates, as well as generate reports and statistics. This readme describes the main functions of the backend and the requirements for its installation and use.

# Preview
![Preview](/scholarship-backend.gif)

# Stack of technologies
- Symfony 4.4.*
- PHP 7.4.*
- MySQL
- Docker
- NGINX


## Installation

1. Clone the repository: `git clone https://github.com/serodas/scholarship-backend.git`

2. Create the file `./.docker/.env.nginx.local` using `./.docker/.env.nginx` as a guide. The value of the variable `NGINX_BACKEND_DOMAIN` is the `server_name` used in NGINX.

3. Add to the file `C:\Windows\System32\drivers\etc\hosts` the line `127.0.0.1` and its corresponding value of the variable used in `NGINX_BACKEND_DOMAIN`.

4. Go to `./docker` and run `docker-compose up -d --build` to build the containers for the first time. From now on, you just need to run `docker-compose up -d`.

5. You should work inside the container `php`. This project is configured to work with [Remote Container](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers). So you can run the command `Reopen in container` after opening the project.

6. Inside the container `php`, run `composer install` to install the dependencies from the folder `/var/www/project`.

7. Create the file `.env.local` using `.env` as a guide.

8. Use the following value for the environment variable DATABASE_URL and work with the database of the container `mysql`:

```
DATABASE_URL="mysql://root:1234@mysql/bachiller?serverVersion=5.7&charset=utf8mb4"
```
You can change the username and password of the database in `././docker/.env`.".