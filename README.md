# Behat-Contexts

**This package add BeHat to your projet (or a new project) configured to work with Selenium directly after installing this package.**

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
**Stability**

ATM require minimum-stability dev
```
composer config minimum-stability dev	
```
**Recipies**

Allow comtributors recipies for the package
```
composer config extra.symfony.allow-contrib true
```
**Package**
```
composer require --dev froggdev/behat-contexts
```

## Troubleshooting

You may experiment trouble with vendor\symfony\dependency-injection\ContainerBuilder.php
actually i didn't found how to solve it in the clean way (this error does not come from my package but dependencies)
so the work arround is to edit the error line and add a test like:
```
if ( null !== $definition->getFile() && ''!==$definition->getFile()&& __DIR__.'/'!==$definition->getFile() ) {
```

## Todo list
- [ ] Translation
- [ ] Replace :value to RegExp
- [ ] Do more advanced scenario
- [ ] Add demo with BeHat TAG sample
