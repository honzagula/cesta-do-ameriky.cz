# Cesta do Ameriky

This is source code of blog [cesta-do-ameriky.cz](https://cesta-do-ameriky.cz) based on Nette Framework.

## How to deploy:

1. install dependencies by executing `composer install` from project root
2. Ensure that the database schema exists and is empty. Optionally, you may create one extra database schema for tests.
3. Ensure that the www server has write access to these folders:
    - temp
    - log
    - www/css
    - www/js
4. setup local environment using `bin/deployment/deploy-project.php`
5. create administrator by running `php www/index.php app:create-admin <username> <email> <password>`

# Google analytics

To get GA working, you need to add your GA IDs to local.neon config file. For example:
```
parameters:
    social:
        googleAnalyticsId: UA-12345678-1
        gaViewId: 11111111
```

For GA charts in Admin section you need to create GA service key with correspondent GA rights and put it to `/app/config/ga-key.json`.

# Facebook comments

To get Facebook comments working, you need to override `social.facebookAppId` parameter.