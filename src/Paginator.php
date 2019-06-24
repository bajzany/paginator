<?php
/**
 * Author: Radek Zíka
 * Email: radek.zika@dipcom.cz
 * Created: 09.01.2019
 */

namespace Bajzany\Paginator;

use Bajzany\Paginator\PaginationObjects\PaginatorWrapped;
use Nette\Utils\Html;

class Paginator implements IPaginator
{

	/**
	 * @var Html
	 */
	protected $firstPageSymbol;

	/**
	 * @var Html
	 */
	protected $lastPageSymbol;

	/**
	 * @var Html
	 */
	protected $previousPageSymbol;

	/**
	 * @var Html
	 */
	protected $nextPageSymbol;

	/**
	 * @var int
	 */
	protected $currentPage = 1;

	/**
	 * @var int
	 */
	protected $pageSize = 20;

	/**
	 * @var int
	 */
	protected $count = 0;

	/**
	 * @var int
	 */
	protected $range = 5;

	/**
	 * @var PaginatorWrapped
	 */
	protected $paginatorWrapped;

	public function __construct(int $count = 0)
	{
		$this->count = $count;
		$this->paginatorWrapped = new PaginatorWrapped();
		$this->createFirstPageSymbol('«');
		$this->createLastPageSymbol('»');
		$this->createPreviousPageSymbol('❮');
		$this->createNextPageSymbol('❯');
	}

	/**
	 * @param string $symbol
	 * @return Html
	 */
	public function createFirstPageSymbol(string $symbol = ''): Html
	{
		$this->firstPageSymbol = Html::el();
		$this->firstPageSymbol->setText($symbol);
		return $this->firstPageSymbol;
	}

	/**
	 * @param string $symbol
	 * @return Html
	 */
	public function createLastPageSymbol(string $symbol = ''): Html
	{
		$this->lastPageSymbol = Html::el();
		$this->lastPageSymbol->setText($symbol);
		return $this->lastPageSymbol;
	}

	/**
	 * @param string $symbol
	 * @return Html
	 */
	public function createPreviousPageSymbol(string $symbol = ''): Html
	{
		$this->previousPageSymbol = Html::el();
		$this->previousPageSymbol->setText($symbol);
		return $this->previousPageSymbol;
	}

	/**
	 * @param string $symbol
	 * @return Html
	 */
	public function createNextPageSymbol(string $symbol = ''): Html
	{
		$this->nextPageSymbol = Html::el();
		$this->nextPageSymbol->setText($symbol);
		return $this->nextPageSymbol;
	}

	/**
	 * @return int
	 */
	public function getCurrentPage(): int
	{
		return $this->currentPage;
	}

	/**
	 * @return int
	 */
	public function getPageSize(): int
	{
		return $this->pageSize;
	}

	/**
	 * @param int $pageSize
	 * @return $this
	 */
	public function setPageSize(int $pageSize)
	{
		$this->pageSize = $pageSize;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getPages(): int
	{
		return ceil($this->count / $this->pageSize);
	}

	/**
	 * @return PaginatorWrapped
	 */
	public function getPaginatorWrapped(): PaginatorWrapped
	{
		return $this->paginatorWrapped;
	}

	/**
	 * @return Html
	 */
	public function getFirstPageSymbol(): Html
	{
		return $this->firstPageSymbol;
	}

	/**
	 * @return Html
	 */
	public function getLastPageSymbol(): Html
	{
		return $this->lastPageSymbol;
	}

	/**
	 * @return Html
	 */
	public function getPreviousPageSymbol(): Html
	{
		return $this->previousPageSymbol;
	}

	/**
	 * @return Html
	 */
	public function getNextPageSymbol(): Html
	{
		return $this->nextPageSymbol;
	}

	/**
	 * @param int $currentPage
	 */
	public function setCurrentPage(int $currentPage)
	{
		$this->currentPage = $currentPage;
	}

	/**
	 * @return int
	 */
	public function getCount(): int
	{
		return $this->count;
	}

	/**
	 * @param int $count
	 */
	public function setCount(int $count): void
	{
		$this->count = $count;
	}

	/**
	 * @return int
	 */
	public function getRange(): int
	{
		return $this->range;
	}

	/**
	 * @param int $range
	 */
	public function setRange(int $range)
	{
		$this->range = $range;
	}

}
