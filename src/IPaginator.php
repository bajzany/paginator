<?php
/**
 * Author: Radek Zíka
 * Email: radek.zika@dipcom.cz
 * Created: 09.01.2019
 */

namespace Bajzany\Paginator;

use Bajzany\Paginator\PaginationObjects\PaginatorWrapped;
use Nette\Utils\Html;

interface IPaginator
{

	/**
	 * @param string $symbol
	 * @return Html
	 */
	public function createFirstPageSymbol(string $symbol = ''): Html;

	/**
	 * @param string $symbol
	 * @return Html
	 */
	public function createLastPageSymbol(string $symbol = ''): Html;

	/**
	 * @param string $symbol
	 * @return Html
	 */
	public function createPreviousPageSymbol(string $symbol = ''): Html;

	/**
	 * @return Html
	 */
	public function getFirstPageSymbol(): Html;

	/**
	 * @return Html
	 */
	public function getLastPageSymbol(): Html;

	/**
	 * @return Html
	 */
	public function getPreviousPageSymbol(): Html;

	/**
	 * @return Html
	 */
	public function getNextPageSymbol(): Html;

	/**
	 * @param int $currentPage
	 * @return mixed
	 */
	public function setCurrentPage(int $currentPage);

	/**
	 * @return int
	 */
	public function getCount(): int;

	/**
	 * @param int $count
	 */
	public function setCount(int $count): void;

	/**
	 * @param string $symbol
	 * @return Html
	 */
	public function createNextPageSymbol(string $symbol = ''): Html;

	/**
	 * @return int
	 */
	public function getCurrentPage(): int;

	/**
	 * @return int
	 */
	public function getPageSize(): int;

	/**
	 * @param int $pageSize
	 * @return mixed
	 */
	public function setPageSize(int $pageSize);

	/**
	 * @return int
	 */
	public function getPages(): int;

	/**
	 * @return PaginatorWrapped
	 */
	public function getPaginatorWrapped(): PaginatorWrapped;


	/**
	 * @return int
	 */
	public function getRange(): int;

	/**
	 * @param int $range
	 */
	public function setRange(int $range);

}
