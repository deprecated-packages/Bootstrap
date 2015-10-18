<?php

namespace Arachne\Bootstrap;

use Nette\DI\Compiler;
use Nette\DI\ContainerLoader as BaseContainerLoader;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class ContainerLoader extends BaseContainerLoader
{

	/** @var callable */
	private $filter;

	public function __construct(callable $filter, $tempDirectory, $autoRebuild = false)
	{
		parent::__construct($tempDirectory, $autoRebuild);
		$this->filter = $filter;
	}

	/**
	 * @return array of (code, file[])
	 */
	protected function generate($class, $generator)
	{
		$compiler = new Compiler;
		$compiler->getContainerBuilder()->setClassName($class);
		$code = call_user_func_array($generator, [ & $compiler ]);
		$code = $code ?: implode("\n\n\n", $compiler->compile());
		$files = $compiler->getDependencies();
		$files = call_user_func($this->filter, $files);
		$files = array_combine($files, $files);

		return array(
			"<?php\n$code",
			serialize(@array_map('filemtime', $files)), // @ - file may not exist
		);
	}

}
