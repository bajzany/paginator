<?php
/**
 * Author: Radek Zíka
 * Email: radek.zika@dipcom.cz
 * Created: 08.01.2019
 */

namespace Bajzany\Paginator\PaginationObjects;

use Nette\Utils\Html;

class PaginatorWrapped extends Html
{

	/**
	 * @var Html
	 */
	private $wrapped;

	/**
	 * @var string
	 */
	private $wrappedClass;

	/**
	 * @var string
	 */
	private $itemWrappedClass = '';

	/**
	 * @var string
	 */
	private $linkClass = '';

	public function __construct($wrappedClass = 'pagination pagination-sm no-margin')
	{
		$this->wrappedClass = $wrappedClass;
		$this->wrapped = $this->createWrapped();
	}

	public function render($indent = NULL)
	{
		echo parent::render($indent);
	}

	public function createWrapped()
	{
		$this->setName("ul");
		$this->setAttribute('class', $this->getWrappedClass());
		return $this;
	}

	/**
	 * @return string
	 */
	public function getWrappedClass(): string
	{
		return $this->wrappedClass;
	}

	/**
	 * @param string $wrappedClass
	 * @return $this
	 */
	public function setWrappedClass($wrappedClass)
	{
		$this->wrappedClass = $wrappedClass;
		return $this;
	}

	/**
	 * @return Item[]
	 */
	public function getItems(): array
	{
		return $this->getWrapped()->getChildren();
	}

	/**
	 * @return Item
	 */
	public function createItem()
	{
		$item = new Item($this->getItemWrappedClass(), $this->getLinkClass());
		$this->getWrapped()->addHtml($item);
		return $item;
	}

	/**
	 * @param Item $item
	 * @return $this
	 */
	public function addItem(Item $item)
	{
		$this->getWrapped()->addHtml($item);
		return $this;
	}

	/**
	 * @return Html
	 */
	public function getWrapped(): Html
	{
		return $this->wrapped;
	}

	/**
	 * @return string
	 */
	public function getItemWrappedClass(): string
	{
		return $this->itemWrappedClass;
	}

	/**
	 * @param string $itemWrappedClass
	 */
	public function setItemWrappedClass(string $itemWrappedClass)
	{
		$this->itemWrappedClass = $itemWrappedClass;
	}

	/**
	 * @return string
	 */
	public function getLinkClass(): string
	{
		return $this->linkClass;
	}

	/**
	 * @param string $linkClass
	 */
	public function setLinkClass(string $linkClass)
	{
		$this->linkClass = $linkClass;
	}

}
