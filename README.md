### Installing using Docker

> You need to have [docker](http://www.docker.com) (1.17.0+) and
> [docker-compose](https://docs.docker.com/compose/install/) (1.14.0+) installed.

## You can install the application using the following commands:

### Firstly you need to clone the project and do some basic setup of the .env file:

```sh
git clone *repository name*
cd *folder*
cp .env.example .env
npm install
npm run build or npm run dev
```

### You need to configure the env and change the variables

```sh
APP_SERVICE=backend
```

### Second you need install Sail using the Composer package manager and after configuring a shell alias using the following command:

_If your existing local development environment allows you to install Composer dependencies_

```sh
composer require laravel/sail --dev
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

*Note pls do it in Ubuntu WSL distro*

_Alias sail only available in the terminal where you entered them_
<br>
_Once the shell alias has been configured, you may execute Sail commands._
<br>
_You can add alias global to phpstorm terminal or other terminal_

### To start all of the Docker containers in the background, you may start Sail in "detached" mode:

```sh
sail up -d
```

_It may take some time to download the required images._

_To stop all of the containers run:_ `sail stop`


### When done, you need to execute the following commands:

```sh
sail composer install && sail artisan key:generate && sail artisan migrate && sail artisan storage:link && sail artisan db:seed
```

