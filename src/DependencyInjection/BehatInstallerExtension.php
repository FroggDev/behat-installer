<?php
namespace froggdev\BehatInstaller\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class BehatInstallerExtension extends Extension
{
	public function load(array $configs, ContainerBuilder $container)
	{
		echo "\nAdding Behat-installer services...\n";
		
		$loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
		$loader->load('services.xml');		
	}
}