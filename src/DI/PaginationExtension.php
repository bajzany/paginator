<?php
/**
 * Author: Radek Zíka
 * Email: radek.zika@dipcom.cz
 * Created: 13.12.2018
 */

namespace Bajzany\Paginator\DI;

use Bajzany\Paginator\IPaginationControl;
use Bajzany\Paginator\Listeners\Router;
use Bajzany\Paginator\Statements;
use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;

class PaginationExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('tableControl'))
			->setImplement(IPaginationControl::class);

		$builder->addDefinition($this->prefix('request'))
			->setFactory(Router::class)
			->addTag("kdyby.subscriber");

		$builder->addDefinition($this->prefix('statements'))
			->setFactory(Statements::class);
	}

	/**
	 * @param Configurator $configurator
	 */
	public static function register(Configurator $configurator)
	{
		$configurator->onCompile[] = function ($config, Compiler $compiler) {
			$compiler->addExtension('bajzanyPaginator', new PaginationExtension());
		};
	}

}
