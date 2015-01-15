<?php

namespace Arachne\Bootstrap;

use Nette\Configurator as BaseConfigurator;

/**
 * @author Jáchym Toušek
 */ 
class Configurator extends BaseConfigurator
{

	/** @var string[] */
	public $defaultExtensions = [
		'extensions' => 'Nette\DI\Extensions\ExtensionsExtension',
	];

}
