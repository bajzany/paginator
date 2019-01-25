<?php
/**
 * Author: Radek Zíka
 * Email: radek.zika@dipcom.cz
 * Created: 22.12.2018
 */

namespace Bajzany\Paginator;

use Bajzany\Paginator\Exceptions\PaginatorException;
use Nette\Application\UI\Control;

class PaginationControl extends Control
{

	/**
	 * @var int @persistent
	 */
	public $currentPage = 1;

	/**
	 * @var IPaginator
	 */
	public $paginator;

	/**
	 * @var bool
	 */
	private $rendered = FALSE;

	public function __construct(IPaginator $paginator, $name = NULL)
	{
		parent::__construct($name);
		$this->paginator = $paginator;
	}

	public function attached($presenter)
	{
		parent::attached($presenter);
		$this->paginator->setCurrentPage($this->currentPage);
	}

	/**
	 * @return string|void
	 * @throws PaginatorException
	 * @throws \Nette\Application\UI\InvalidLinkException
	 */
	public function render()
	{
		$paginator = $this->getPaginator();
		if (empty($paginator)) {
			throw PaginatorException::paginatorIsNotSet();
		}

		if ($this->isRendered()) {
			$paginator->getPaginatorWrapped()->render();
			return;
		}

		if ($paginator->getPages() <= 1) {
			return;
		}

		$paginatorWrapped = $paginator->getPaginatorWrapped();

		$firstItem = $paginatorWrapped->createItem();
		$firstItem->getContent()->addHtml($paginator->getFirstPageSymbol());
		$firstItem->getContent()->setAttribute('href', $this->link('this', ['currentPage' => 1]));

		$previous = $paginatorWrapped->createItem();
		$previous->getContent()->addHtml($paginator->getPreviousPageSymbol());
		$previous->getContent()->setAttribute(
			'href',
			$this->link(
				'this',
				[
					'currentPage' => $paginator->getCurrentPage() - 1 < 1 ? 1 : $paginator->getCurrentPage() - 1,
				]
			)
		);

		$beforeOverRange = FALSE;
		$afterOverRange = FALSE;

		for ($page = 0; $page < $paginator->getPages(); $page++) {

			if ($paginator->getCurrentPage() + $paginator->getRange() <= ($page + 1)) {
				if (!$afterOverRange) {

					$item = $paginatorWrapped->createItem();
					$item->getContent()->setText('...');
					$item->getContent()->setAttribute(
						'href',
						'#'
					);

					$afterOverRange = TRUE;
				}
				continue;
			}

			if ($paginator->getCurrentPage() - $paginator->getRange() >= ($page + 1)) {
				if (!$beforeOverRange) {
					$item = $paginatorWrapped->createItem();
					$item->getContent()->setText('...');
					$item->getContent()->setAttribute(
						'href',
						'#'
					);

					$beforeOverRange = TRUE;
				}
				continue;
			}

			$item = $paginatorWrapped->createItem();
			if ($paginator->getCurrentPage() == $page + 1) {
				$item->setWrappedClass('active');
			}
			$item->getContent()->setText($page + 1);
			$item->getContent()->setAttribute(
				'href',
				$this->link('this', ['currentPage' => $page + 1])
			);
		}

		$next = $paginatorWrapped->createItem();
		$next->getContent()->addHtml($paginator->getNextPageSymbol());
		$next->getContent()->setAttribute(
			'href',
			$this->link(
				'this',
				[
				'currentPage' => $paginator->getCurrentPage() + 1 > $paginator->getPages() ? $paginator->getPages() : $paginator->getCurrentPage() + 1,
				]
			)
		);

		$lastItem = $paginatorWrapped->createItem();
		$lastItem->getContent()->addHtml($paginator->getLastPageSymbol());
		$lastItem->getContent()->setAttribute(
			'href',
			$this->link('this', ['currentPage' => $paginator->getPages()])
		);

		$paginator->getPaginatorWrapped()->render();

		$this->rendered = TRUE;
	}

	/**
	 * @return IPaginator
	 */
	public function getPaginator(): ?IPaginator
	{
		return $this->paginator;
	}

	/**
	 * @param IPaginator $paginator
	 * @return $this
	 */
	public function setPaginator(IPaginator $paginator)
	{
		$this->paginator = $paginator;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getCurrentPage(): int
	{
		return $this->currentPage;
	}

	/**
	 * @return bool
	 */
	public function isRendered(): bool
	{
		return $this->rendered;
	}

}
