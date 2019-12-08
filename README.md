# behat-installer

**This package install BeHat to your projet (or a new project) configured to work with Selenium directly after installing this package.**

Adding additionnal contexts &amp; functionnalities to Behat
- Advanced demo with Selenium navigation
- Demo with Internet Explorer as Profile
- Demo with Environement option
- Lot of new contexts
- HTML report using emuse/behat-html-formatter
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


## Todo list
- [ ] Translation
- [ ] Replace :value to RegExp
- [ ] Do more advanced scenario
- [ ] Add demo with BeHat TAG sample
