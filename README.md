Yii2 SmsBat extension
=============
Yii2 sends SMS from smsbat.com via HTTP protocol

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist noetikosgroup/yii2-smsbat "*"
```

or add

```
"noetikosgroup/yii2-smsbat": "*"
```

to the require section of your `composer.json` file.

Subsequently, run

```
./yii migrate/up --migrationPath=@vendor/noetikosgroup/yii2-smsbat/migrations
```

## Basic setup

You should:
* registered account at https://smsbat.com/
* create login, password and alpha name for usage in SmsBat API

### Configuration

Add the following code in your config file ('components' section):

```php
<?php
...
    'components' => [
        'smsbat' => [
            'class' => 'noetikosgroup\smsbat\SmsBat',
            'login' => 'your_login',
            'password' => 'your_password',
            'from' => 'your_name',
        ],
        ...
    ],
...
```
If you want to save messages in the database - change config:
```php
<?php
...
    'components' => [
        'smsbat' => [
            'class' => 'noetikosgroup\smsbat\SmsBat',
            'login' => 'your_login',
            'password' => 'your_password',
            'from' => 'your_name',
            'saveToDb' => true,
        ],
        ...
    ],
...
```

Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
 <?php Yii::$app->smsbat->send('+380XXXXXXXXX', 'test'); ?>
 ```

## License

**yii2-smsbat** is released under the BSD 3-Clause License. See the bundled `LICENSE.md` for details.
