<?php
/**
 * Author: Radek Zíka
 * Email: radek.zika@dipcom.cz
 * Created: 09.01.2019
 */

namespace Bajzany\Paginator;

use Bajzany\Paginator\Exceptions\PaginatorException;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class QueryPaginator extends Paginator
{

	/**
	 * @var Query
	 */
	private $query;

	/**
	 * @var DoctrinePaginator
	 */
	private $doctrinePaginator;

	/**
	 * @param Query $query
	 */
	public function setQuery(Query $query)
	{
		$this->doctrinePaginator = new DoctrinePaginator($query);
		$count = count($this->doctrinePaginator);
		$this->count = $count;
		$this->query = $query;
	}

	/**
	 * @return Query
	 */
	public function getQuery()
	{
		return $this->query;
	}

	/**
	 * @param int $currentPage
	 * @return Paginator
	 * @throws PaginatorException]]
	 */
	public function setCurrentPage(int $currentPage): Paginator
	{
		parent::setCurrentPage($currentPage);

		if (empty($this->doctrinePaginator)) {
			throw PaginatorException::emptyQuery();
		}

		$this->query
			->setFirstResult($this->getPageSize() * ($this->getCurrentPage() - 1)) // set the offset
			->setMaxResults($this->getPageSize()); // set the limit

		return $this;
	}

}
