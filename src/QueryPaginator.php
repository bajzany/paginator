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
	 * @return $this
	 * @throws PaginatorException
	 */
	public function setQuery(Query $query)
	{
		$this->doctrinePaginator = new DoctrinePaginator($query);
		$count = count($this->doctrinePaginator);
		$this->count = $count;

		if (empty($this->doctrinePaginator)) {
			throw PaginatorException::emptyQuery();
		}

		$query->setFirstResult($this->getPageSize() * ($this->getCurrentPage() - 1));
		$query->setMaxResults($this->getPageSize());

		$this->query = $query;
		return $this;
	}

	/**
	 * @return Query
	 */
	public function getQuery()
	{
		return $this->query;
	}

}
