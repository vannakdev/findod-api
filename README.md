Find OD API
====================

Ocean property is the whole of the Cambodia real estate market in the palm of your hand. Whether you are a house hunter, real estate enthusiast or looking for somewhere to buy or rent there is now a simple, way to find out what is on the market – right here – right now!
You can search for a home for sale or apartment for rent by location, feature, size, price, keyword, and much, much more. Save and share your favourite homes, or contact the listing agent directly. Plus get instant alerts when new homes hit the market that match your preferences. 

Whether you are looking to buy or rent or you just like to keep on top of what is new, interesting, and inspiring in the world of real estate, Ocean property is the fastest, smartest, simplest way to get the real estate info you want.
This is a Laravel package for translatable models. Its goal is to remove the complexity in retrieving and storing multilingual model instances. With this package you write less code, as the translations are being fetched/saved when you fetch/save your instance.


# Getting started

## Installation

Clone the repository (for testing Environment Please select the **Testing** Branch)

```
git clone -b Testing https://gitlab.com/ousophea/lumenapi.git [your-directory-name]
```

Switch to the **[your-directory-name]**

```
cd [your-directory-name]
```

Install all the dependencies using composer (Please make your that composer is installed in your machine)

```
composer install
```

Copy the .env.example and save the new file as .env .

```
cp .env.example .env
```

Provide your application's configuration (Application, Database...) by editing the .env file:

```
APP_URL=http:https://your-domain-name.com
APP_KEY=
APP_X_APP_KEY=

DB_HOST=localhost
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

- APP_KEY : (SHA1 Hash string) : is the private key for Lumen to encrypte the data.
- APP_X_APP_KEY : (SHA1 Hash string) is the key for other front-end need to provide in the request header when they want to interact with the API.

In Live Production APP_ENV=production, APP_DEBUG=false 


Run the database migrations (**Set the database connection in .env before migrating**)

```
php artisan migrate
```

## Database seeding

If it is the first time you install the API, You must run the database seeding process in order to allow the API create the default Data for your Application. 

to run the database seeding, please type the command below, for more information regarding Database Seeder please visit : [Laravel Database Seeder](https://laravel.com/docs/5.5/seeding)

```
php artisan db:seed
```

***Note*** : Seeding your database incorrectly could result in the severe Functionality Error! please do this step carefully.

## Directory Permission

All Directories in the application should set the permission **0755**, All Files should set the permission to **0644**.

There are some exceptions to the directories specify below:

- public/uploads : this directory and child directory must have Read/Write permission to allow file upload functionality work.
- storage : this directory and child directory must have Read/Write permission
- bootstrap/cache : this directory and child directory must have Read/Write permission

## Running the Application

### Local Enviornment

Open your Command Propmt(Window), Terminal(Linux, MAC OSX).
Navigate to your project directory, and execute the command below.

```
php -S [your-ip-address]:[your-port-number] -t public
```

### Virtual Host Enviornment
<Directory "D:/Dev/Laravel/admin-panel/public">

#### Apache
- **DocumentRoot** should point to **[applicaiton-directory]/public**
- **Directory** should point to **[applicaiton-directory]/public**

#### Nginx
Please refer to official Laravel Deployment Website : [Nginx](https://laravel.com/docs/5.5/deployment#server-configuration)

## Test
Open your Command Propmt(Window), Terminal(Linux, MAC OSX). 

```
curl -X GET \
  [APP_URL]/api/ \
  -H 'Cache-Control: no-cache' \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -H 'x-application-key: [APP_X_APP_KEY]'
```

- change the [APP_URL] with your .env -> APP_URL value;
- change the [APP_X_APP_KEY] with your .env -> APP_X_APP_KEY value;

## Integration 

For front-end integration, we will provide the document soon