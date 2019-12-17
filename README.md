[![Latest Stable Version](https://poser.pugx.org/froggdev/behat-installer/v/stable.svg)](https://packagist.org/packages/froggdev/behat-installer)
[![Latest Unstable Version](https://poser.pugx.org/froggdev/behat-installer/v/unstable.svg)](https://packagist.org/packages/froggdev/behat-installer)
[![Total Downloads](https://poser.pugx.org/froggdev/behat-installer/downloads.svg)](https://packagist.org/packages/froggdev/behat-installer)
[![License](https://poser.pugx.org/froggdev/behat-installer/license.svg)](https://packagist.org/packages/froggdev/behat-installer)

# behat-installer

**This package install BeHat to your symfony projet (or a new symfony project) configured to work with Selenium directly after installing this package.**

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
Symfony new ./myProject --version=4.4
```
Or with composer create-project command
```
composer create-project symfony/skeleton ./myProject 4.4.99
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
vendor\bin\behat
```

## Todo list

- [x] Install without stability dev required
- [x] Remove package remove bootstrap.php ( was in symfony extension recipies)
- [ ] symfony 5.0 

```
            behat/behat/Testwork/src/ServiceContainer/Configuration/ConfigurationTree.php 
            // Symfony <= 4.4
            $tree = new TreeBuilder();
            $root = $tree->root('testwork');
            // Symfony >= 5.0
            $tree = new TreeBuilder('testwork');
            $root = $tree->getRootNode();
```
```
            // Symfony <= 4.4
            Symfony\Component\EventDispatcher\Event 
            // Symfony >= 5.0            
            Symfony\Contracts\EventDispatcher\Event
```

```
           // Symfony >= 5.0  
           mink-drivers/src/BrowserKitDriver.php
           public function __construct($client
           
           behat/Behat/src/Output/Node/Printer/CounterPrinter.php
           public function __construct(
                   ResultToStringConverter $resultConverter,
                   ExceptionPresenter $exceptionPresenter,
                   $translator,
                   $basePath
                   
           behat/Behat/src/Output/Node/Printer/ListPrinter.php
           public function __construct(ResultToStringConverter $resultConverter, $translator)
           
           behat/Behat/src/Gherkin/Cli/SyntaxController.php               
           public function __construct(KeywordsDumper $dumper,  $translator)
```
- [ ] Work with IE
- [ ] Remove ralouphie package
- [ ] Script behat:config
- [ ] add uservar to user feature
- [ ] Bug result ok is based only on 1st scenario
- [ ] Replace :value to RegExp
- [ ] Do more advanced scenario
- [ ] Add demo with BeHat TAG sample
- [ ] Linux version
- [ ] Translation



## Credits

**Maintainers**

[Frogg FroggDev](https://github.com/FroggDev)

**Specials thanks**

[Mouncef ZAGHRAT](https://github.com/Mouncef)

**Copyright**

Copyright (c) 2019 Frogg admin@frogg.fr.
