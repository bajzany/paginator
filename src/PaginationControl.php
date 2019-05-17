<?php
/**
 * Author: Radek Zíka
 * Email: radek.zika@dipcom.cz
 * Created: 22.12.2018
 */

namespace Bajzany\Paginator;

use Bajzany\Paginator\Events\Listener;
use Bajzany\Paginator\Exceptions\PaginatorException;
use Nette\Application\UI\Control;

class PaginationControl extends Control
{

	const ON_LINK_CREATE = 'ON_LINK_CREATE';

	/**
	 * @var int @persistent
	 */
	public $currentPage;

	/**
	 * @var IPaginator
	 */
	public $paginator;

	/**
	 * @var bool
	 */
	private $rendered = FALSE;

	/** @var Listener  */
	private $listener;

	public function __construct(IPaginator $paginator, $name = NULL)
	{
		parent::__construct($name);
		$this->paginator = $paginator;
		$this->listener = new Listener();
	}

	public function attached($presenter)
	{
		parent::attached($presenter);
		$currentPage = $this->currentPage && $this->currentPage <= $this->paginator->getPages() ? $this->currentPage : 1;

		$this->paginator->setCurrentPage($currentPage);
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

		$currentPage = 1;

		$this->listener->emit(self::ON_LINK_CREATE, $this, $currentPage, $link);

		$firstItem = $paginatorWrapped->createItem();

		$firstItem->getContent()
			->addHtml($paginator->getFirstPageSymbol())
			->setAttribute('href', $this->getLink(1));

		$previous = $paginatorWrapped->createItem();
		$previous->getContent()
			->addHtml($paginator->getPreviousPageSymbol())
			->setAttribute(
				'href',
				$this->getLink($paginator->getCurrentPage() - 1 < 1 ? 1 : $paginator->getCurrentPage() - 1)
			);

		$beforeOverRange = FALSE;
		$afterOverRange = FALSE;

		for ($page = 0; $page < $paginator->getPages(); $page++) {
			if ($paginator->getCurrentPage() + $paginator->getRange() <= ($page + 1)) {
				if (!$afterOverRange) {

					$item = $paginatorWrapped->createItem();
					$item->getContent()
						->setText('...')
						->setAttribute('href', '#')
						->setAttribute('class', '');

					$afterOverRange = TRUE;
				}
				continue;
			}

			if ($paginator->getCurrentPage() - $paginator->getRange() >= ($page + 1)) {
				if (!$beforeOverRange) {
					$item = $paginatorWrapped->createItem();
					$item->getContent()
						->setText('...')
						->setAttribute('href', '#')
						->setAttribute('class', '');

					$beforeOverRange = TRUE;
				}
				continue;
			}

			$item = $paginatorWrapped->createItem();
			if ($paginator->getCurrentPage() == $page + 1) {
				$item->setWrappedClass('active');
			}
			$item->getContent()
				->setText($page + 1)
				->setAttribute('href', $this->getLink($page + 1));
		}

		$next = $paginatorWrapped->createItem();
		$next->getContent()
			->addHtml($paginator->getNextPageSymbol())
			->setAttribute(
				'href',
				$this->getLink($paginator->getCurrentPage() + 1 > $paginator->getPages() ? $paginator->getPages() : $paginator->getCurrentPage() + 1)
			);

		$lastItem = $paginatorWrapped->createItem();
		$lastItem->getContent()
			->addHtml($paginator->getLastPageSymbol())
			->setAttribute('href', $this->getLink($paginator->getPages()));

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

	/**
	 * @return Listener
	 */
	public function getListener(): Listener
	{
		return $this->listener;
	}

	/**
	 * @param int $currentPage
	 * @return string
	 */
	public function getLink(int $currentPage)
	{
		$this->listener->emit(self::ON_LINK_CREATE, $this, $currentPage, $link);
		return $link;
	}

}
