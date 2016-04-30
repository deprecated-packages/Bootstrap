<?php

namespace Arachne\Bootstrap;

use Nette\Configurator as BaseConfigurator;
use Nette\DI\Container;
use Nette\Utils\Strings;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class Configurator extends BaseConfigurator
{

	/** @var string[] */
	public $defaultExtensions = [
		'extensions' => 'Nette\DI\Extensions\ExtensionsExtension',
	];

	/** @var string[] */
	private $ignorePaths;

	public function __construct(array $ignorePaths = [])
	{
		$this->ignorePaths = $ignorePaths;
		$this->parameters = $this->getDefaultParameters();
	}

	/**
	 * Returns system DI container.
	 * @return Container
	 */
	public function createContainer()
	{
		$loader = new ContainerLoader(
			function ($files) {
				return array_filter($files, function ($file) {
					foreach ($this->ignorePaths as $path) {
						if (Strings::startsWith($file, $path)) {
							return false;
						}
					}
					return true;
				});
			},
			$this->getCacheDirectory() . '/Arachne.Configurator',
			$this->parameters['debugMode']
		);

		$class = $loader->load(
			array($this->parameters, $this->files),
			array($this, 'generateContainer')
		);

		$container = new $class;
		$container->initialize();

		return $container;
	}

}
