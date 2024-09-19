<p align="center">
  <a href="https://enterprise-classifieds.com/"><img src="https://raw.githubusercontent.com/MercanoGlobal/Osclass-Enterprise/main/oc-includes/images/osclass-logo.png" alt="Enterprise-Classifieds.com"></a>
</p>

# Osclass Enterprise
Osclass Enterprise is the continuation of the Osclass v3.8.0 project and contains hundreds of fixes and improvements (check the CHANGELOG).<br>
:arrow_right: Create your own online business with the best open-source classifieds software. :arrow_left:

> [Plugins & Themes Market](https://enterprise-classifieds.com/)

## Project Info
- [Official Website](https://enterprise-classifieds.com/)
- [Code Repository](https://github.com/MercanoGlobal/Osclass-Enterprise)
- [Documentation](https://docs.enterprise-classifieds.com/)

## Like what we do?
Support our open-source project and help us keep creating amazing software for you!<br>
:point_down: Please consider gifting us a coffee :point_down: <br>
<a href="https://www.buymeacoffee.com/Osclass"><img src="https://i.ibb.co/TP3qYLG/donate-button.png" alt="Support Us!"></a>

## Hosting Requirements and Settings
- Apache 2.4.17+ / LiteSpeed 5.4+
- PHP 7.1 - 7.4 (Experimental 8+)
- MySQL 5.7+ / MariaDB 10.2+
- MySQLi module for PHP
- GD module for PHP
- ImageMagick module for PHP (optional)

*PHP - php.ini*

```
max_execution_time = 600
allow_url_fopen = On
```

*MySQL - my.ini*

```
[mysqld]
innodb_ft_min_token_size=2
ft_min_word_len=2
```

*Permalinks on NGINX - Virtual Host Config*

```
location / {
    try_files $uri $uri/ /index.php?$args;
}

## If you encounter errors using the above, try replacing '$args' with '$query_string'.
```

## Documented Core Changes

*We now have some options that allow us to enhance the core functionality of Osclass, as it follows:*
- In `oc-includes/osclass/UserActions.php - Line 281` and `oc-includes/osclass/controller/login.php - Line 228` we have the option to limit multiple password requests within a predefined time frame (default is 1 request per hour). You can change this value to anything you like.
- In `oc-includes/osclass/classes/datatables/UsersDataTable.php - Lines 96 & 166` we have the option to show the user avatar in oc-admin, if an avatar plugin is used. It's set by default to the *Madhouse Avatar Plugin*, but can be changed just by modifying the function name.
- In `oc-content/themes/bender/item-sidebar.php - Line 76-86` we've added JS bot protection to the e-mail field. This code can be used by any theme.
- In `hUsers.php` we've added a new function `osc_user_is_online` that returns true if the user has been online in the last 5 minutes, and false otherwise.

> [Installation Guide](https://www.youtube.com/watch?v=bOr7U81Y-IM)

> Many thanks to all contributors at [MindStellar](https://github.com/mindstellar/Osclass) and [OsclassPoint](https://forums.osclasspoint.com/)
