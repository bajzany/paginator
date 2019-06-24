<?php
/**
 * Author: Radek Zíka
 * Email: radek.zika@dipcom.cz
 * Created: 22.12.2018
 */

namespace Bajzany\Paginator;

interface IPaginationControl
{

	/**
	 * @param IPaginator $paginator
	 * @return PaginationControl
	 */
	public function create(IPaginator $paginator): PaginationControl;

}
