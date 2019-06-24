<?php
/**
 * Author: Radek Zíka
 * Email: radek.zika@dipcom.cz
 */
namespace Bajzany\Paginator\Listeners;

use Bajzany\Paginator\Statements;
use Kdyby\Events\Subscriber;
use Nette\Application\Application;
use Nette\Application\IRouter;
use Nette\Application\Request;
use Nette\Application\Routers\RouteList;

class Router implements Subscriber
{

	/**
	 * @var Statements
	 */
	private $statements;

	/**
	 * @var \Nette\Http\Request
	 */
	private $request;

	/**
	 * @var bool
	 */
	private $appRequestCreated = FALSE;

	/**
	 * @param Statements $statements
	 * @param \Nette\Http\Request $request
	 */
	public function __construct(Statements $statements, \Nette\Http\Request $request)
	{
		$this->statements = $statements;
		$this->request = $request;
	}

	/**
	 * @return array
	 */
	public function getSubscribedEvents()
	{
		return [
			Application::class . "::onRequest" => "onRequest",
			RouteList::class . "::onConstructUrl" => "onConstructUrl",
		];
	}

	/**
	 * @param Application $application
	 * @param Request $request
	 */
	public function onRequest(Application $application, Request $request)
	{
		$this->statements->setApplicationRequest($request);
		$this->appRequestCreated = TRUE;
	}

	/**
	 * @param IRouter $routerList
	 * @param Request $request
	 */
	public function onConstructUrl($routerList, Request $request)
	{
		if (!$this->appRequestCreated) {
			return;
		}

		if ($paginator = $this->statements->getPaginatorByRequest($request)) {
			if (!$request->getParameter($paginator["parameter"])) {
				$parameters = $request->getParameters();
				$parameters[$paginator["parameter"]] = $paginator["page"];
				$request->setParameters($parameters);
			}
		}
	}

}
