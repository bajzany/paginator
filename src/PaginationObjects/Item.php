<?php
/**
 * Author: Radek Zíka
 * Email: radek.zika@dipcom.cz
 * Created: 08.01.2019
 */

namespace Bajzany\Paginator\PaginationObjects;

use Nette\Utils\Html;

class Item extends Html
{
	/**
	 * @var Html
	 */
	private $wrapped;

	/**
	 * @var Html
	 */
	private $content;

	/**
	 * @var string
	 */
	private $wrappedClass = '';

	public function __construct($wrappedClass = '')
	{
		$this->wrappedClass = $wrappedClass;
		$this->wrapped = $this->createWrapped();
	}

	public function createWrapped()
	{
		$this->setName("li");
		$this->setAttribute('class', $this->getWrappedClass());
		$this->addHtml($this->createContent());
		return $this;
	}

	public function createContent()
	{
		$this->content = Html::el('a');
		$this->content->setAttribute('href','#');
		return $this->content;
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
		$this->setAttribute('class', $this->getWrappedClass());
		return $this;
	}

	/**
	 * @return Html
	 */
	public function getContent(): Html
	{
		return $this->content;
	}

}
