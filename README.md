
## Instructions for starting API on local env

Clone project 
```
git clone https://github.com/nikolajaneski/sodo.git projectName
```

CD into your project

```
cd projectName
```

Install Composer dependencies

```
compser update
```

Create copy of .env file and add DB information for connection
```
cp config/env.example.php config/env.php
```

```
// Database
    $settings['db']['database'] = 'grabitest';
    $settings['db']['username'] = 'root';
    $settings['db']['password'] = '';

    $settings['api_auth'] = [
        'users' => [
            'api-admin' => 'secret',
            'api-user' => 'secret',
        ],
    ];

    // This is my personal Mailtrap acc used for testing , you can update with your credentials to test confirmation email
    $settings['smtp'] = [
        // use 'null' for the null adapter
        'type' => 'smtp',
        'host' => 'smtp.mailtrap.io',
        'port' => '2525',
        'username' => '355ad993fd21e6',
        'password' => '7c4fdfd3832502',
    ];

```

Create database 
```
mysql -e 'CREATE DATABASE IF NOT EXISTS grabitest CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;'
```

Run migrations 
```
composer phoenix migrate
```

Run php server
```
php -S localhost:8080 -t public
``` 


Examples of testing this API using Postman

![image](https://user-images.githubusercontent.com/26470567/145808610-23e6f635-a273-42da-b8b8-d883bfe17b2a.png)

![image](https://user-images.githubusercontent.com/26470567/145808835-a7a57393-559b-4526-abff-463e497afd7e.png)

![image](https://user-images.githubusercontent.com/26470567/145808782-30456adb-d377-4d71-a8df-64873dc6cd38.png)

![image](https://user-images.githubusercontent.com/26470567/145808892-1a6f8603-5c73-49b2-bf8f-f6bf1f56b897.png)

