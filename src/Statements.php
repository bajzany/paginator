<?php
/**
 * Author: Mykola Chomenko
 * Email: mykola.chomenko@dipcom.cz
 */

namespace Bajzany\Paginator;

use Nette\Application\Request;
use Nette\Application\UI\Presenter;

class Statements
{

	/**
	 * @var array
	 */
	private $paginator = [];

	/**
	 * @var \Nette\Http\Request
	 */
	private $request;

	/**
	 * @param \Nette\Http\Request $request
	 */
	public function __construct(\Nette\Http\Request $request)
	{
		$this->request = $request;
		$url = $request->getUrl();
		foreach ($url->getQueryParameters() as $name => $value) {
			if (preg_match('/paginator\-currentPage$/', $name)) {
				$exp = explode("-", $name);
				array_pop($exp);
				$name = implode("-", $exp);
				$this->paginator[$name] = [
					"page" => $value,
					"name" => $name,
					"parameter" => $name . "-currentPage",
					"presenter" => NULL,
				];
			}
		}
	}

	/**
	 * @param Request $request
	 */
	public function setApplicationRequest(Request $request)
	{
		$presenter = $request->getPresenterName() . ":" . $request->getParameter("action");
		foreach ($this->paginator as $name => $paginator) {
			if (!$paginator["presenter"]) {
				$paginator["presenter"] = $presenter;
				$this->paginator[$name] = $paginator;
			}
		}
	}

	/**
	 * @param PaginationControl $paginator
	 * @param mixed $page
	 */
	public function usePaginator(PaginationControl $paginator, $page)
	{
		$name = $this->getFullName($paginator);
		$presenter = $paginator->getPresenter()->getName() . ":" . $paginator->getPresenter()->getAction();
		$this->paginator[$name] = [
			"page" => $page,
			"name" => $name,
			"parameter" => $name . "-currentPage",
			"presenter" => $presenter,
		];
	}

	/**
	 * @param PaginationControl $paginator
	 * @return string
	 */
	private function getFullName(PaginationControl $paginator)
	{
		$control = $paginator;
		$name = [];
		while (!$control instanceof Presenter) {
			$name[] = $control->getName();
			$control = $control->getParent();
		}
		$name = array_reverse($name);
		return implode("-", $name);
	}

	/**
	 * @param Request $request
	 * @return array|null
	 */
	public function getPaginatorByRequest(Request $request): ?array
	{
		$presenter = $request->getPresenterName() . ":" . $request->getParameter("action");
		foreach ($this->paginator as $paginator) {
			if ($paginator["presenter"] === $presenter) {
				return $paginator;
			}
		}
		return NULL;
	}

}
