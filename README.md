# Bhavin Shah - Magento 2 - Product Price Calculator
Magento 2 module for product price calculation by height and width entered by customer

## How to install & upgrade Bhavin_Calculator
### 1. Install via composer (recommend)
We recommend you to install Bhavin_Calculator module via composer. It is easy to install, update and maintaince.

Run the following command in Magento 2 root folder.

#### 1.1 Install

```
composer require bhavin/module-calculator
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```
#### 1.2 Upgrade

```
composer update bhavin/module-calculator
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```

Run compile if your store in Product mode:

```
php bin/magento setup:di:compile
```

### 2. Copy and paste

If you don't want to install via composer, you can use this way. 

- Download [the latest version here](https://github.com/bhavin786/module-calculator/archive/refs/heads/main.zip) 
- Extract `master.zip` file to `app/code/Bhavin/Calculator` ; You should create a folder path `app/code/Bhavin/Calculator` if not exist.
- Go to Magento 2 root folder and run upgrade command in command line to install `Bhavin_Calculator`:

```
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```
#### Warning :
- This package is not installable via Composer 1.x
