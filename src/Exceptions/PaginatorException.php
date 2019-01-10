<?php
/**
 * Author: Radek Zíka
 * Email: radek.zika@dipcom.cz
 * Created: 02.01.2019
 */

namespace Bajzany\Paginator\Exceptions;

class PaginatorException extends \Exception
{

	/**
	 * @return PaginatorException
	 */
	public static function paginatorIsNotSet()
	{
		return new self("Paginator is not set, please first set paginator!");
	}

	/**
	 * @return PaginatorException
	 */
	public static function emptyQuery()
	{
		return new self("Query is empty");
	}
}
