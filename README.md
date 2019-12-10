[![Latest Stable Version](https://poser.pugx.org/froggdev/behat-installer/v/stable.svg)](https://packagist.org/packages/froggdev/behat-installer)
[![Latest Unstable Version](https://poser.pugx.org/froggdev/behat-installer/v/unstable.svg)](https://packagist.org/packages/froggdev/behat-installer)
[![Total Downloads](https://poser.pugx.org/froggdev/behat-installer/downloads.svg)](https://packagist.org/packages/froggdev/behat-installer)
[![License](https://poser.pugx.org/froggdev/behat-installer/license.svg)](https://packagist.org/packages/froggdev/behat-installer)

# behat-installer

**This package install BeHat to your projet (or a new project) configured to work with Selenium directly after installing this package.**

Adding additionnal contexts &amp; functionnalities to Behat
- Advanced demo with Selenium navigation
- Demo with Internet Explorer using BeHat Profile configuration
- Demo with Environment options
- Lot of new contexts
- possibility to define and use variables in scenarios
- HTML report using [emuse/behat-html-formatter](https://packagist.org/packages/emuse/behat-html-formatter)
- (optional) copy the html report and zip it if no error occured
- (optional) sending mail once test are done

## Installation

**Symfony**

Using symfony.exe
```
Symfony new ./ --version=4.4
```
Or with composer create-project command
```
composer create-project symfony/skeleton ./ 4.4.99
```
**Stability**

ATM require minimum-stability dev
```
composer config minimum-stability dev	
```
**Package**
```
composer require --dev froggdev/behat-installer
```
**Installation & configuration**
```
php bin\console behat:install
```
**Run BeHat**

command to run BeHat advanced demo with Selenium
```
vendor\bin\selenium
vendor\bin\behat
```

**Stability (optional)**

If you don't require minimum-stability dev, it is better to restore default value to prevent composer update looking for dev packages.
```
composer config minimum-stability stable
```


## Todo list
- [ ] Install without stability dev required
- [ ] Remove package remove bootstrap.php
- [ ] Bug result ok is based only on 1st scenario
- [ ] Translation
- [ ] Replace :value to RegExp
- [ ] Do more advanced scenario
- [ ] Add demo with BeHat TAG sample
