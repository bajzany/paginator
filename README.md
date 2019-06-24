### Paginator

Nette paginator for using doctrine query

Required:
- php: ^7.2
- [nette/di](https://packagist.org/packages/nette/di)
- [nette/application](https://packagist.org/packages/nette/application)
- [nette/bootstrap](https://packagist.org/packages/nette/bootstrap)
- [latte/latte](https://packagist.org/packages/latte/latte)
- [nette/utils](https://packagist.org/packages/nette/utils)
- [doctrine/orm](https://packagist.org/packages/doctrine/orm)

![Paginator](src/Doc/image1.PNG?raw=true)

#### Installation

- Composer installation

		composer require bajzany/paginator dev-master


- Register into Nette Application

		extensions:
    		BajzanyPaginator: Bajzany\Paginator\DI\PaginationExtension

#### How to use into another component
For example i show you this component in bajzany/table

Register paginatorControl into constructor

	/**
	 * @var IPaginationControl
	 */
	private $paginationControl;

	public function __construct(ITable $table, IPaginationControl $paginationControl, $name = NULL)
	{
		parent::__construct($name);
		$this->table = $table;
		$this->paginationControl = $paginationControl;
	}
	
Now into same file create functions renderPaginator, createComponentPaginator and getPaginationControl

	public function renderPaginator()
	{
		$paginatorComponent = $this->getComponent(self::PAGINATOR_NAME);
		$paginatorComponent->render();
	}

	/**
	 * @return \Bajzany\Paginator\PaginationControl
	 * @throws TableException
	 */
	public function createComponentPaginator()
	{
		$paginator = $this->table->getPaginator();
		if (empty($paginator)) {
			throw TableException::paginatorIsNotSet(get_class($this->table));
		}

		return $this->getPaginationControl()->create($paginator);
	}
	
	/**
	 * @return IPaginationControl
	 */
	public function getPaginationControl(): IPaginationControl
	{
		return $this->paginationControl;
	}
	
Next point is into EntityTable.php 

Constructor create new instance QueryPaginator() and save this into paginator property

	/**
	 * @param string $entityClass
	 * @param EntityManager $entityManager
	 */
	public function __construct(string $entityClass, EntityManager $entityManager)
	{
		parent::__construct();
		$this->entityClass = $entityClass;
		$this->entityManager = $entityManager;
		$this->entityRepository = $this->getEntityManager()->getRepository($this->getEntityClass());
		$this->queryBuilder = $this->entityRepository->createQueryBuilder('e');

		$this->paginator = new QueryPaginator();

	}
	
Important is function build into EntityTable.php

Here is queryBuilder which have function getQuery() necessarily for function $paginator->setQuery($query)
Paginator return updated Query and create pagination items (1,2,3, ...)

	/**
	 * @param IContainer $container
	 * @return ITable
	 */
	public function build(IContainer $container): ITable
	{
		if ($this->isBuild()) {
			return $this;
		}

		$this->queryBuilder->whereCriteria($this->getWhere());
		foreach ($this->getSort() as $by => $sort) {
			$this->queryBuilder->addOrderBy($by, $sort);
		}

		$query = $this->queryBuilder->getQuery();
		$paginator = $this->getPaginator();

		if ($paginator instanceof QueryPaginator) {
			$paginator->setQuery($query);
			$query = $paginator->getQuery();
			// IT'S BECAUSE ATTACH FUNCTION IN TABLECONTROL
			$container->getComponent(TableControl::PAGINATOR_NAME);
		}

		$this->entities = $query->getResult();

		return parent::build($container);
	}
	
In latte for rendering pagination please use control nameYourComponent:paginator

	<div class="box-footer clearfix">
 		{control tableEntity:paginator}
	</div>
	
In Presenter where i have function createComponentTableEntity you can change paginator pageSize, add another items into list pagination... 


	public function createComponentTableEntity()
	{
		$table = $this->tableFactory->createEntityTable(User::class);
		$paginator = $table->getPaginator();
		$item = $paginator->getPaginatorWrapped()->createItem();
		$item->getContent()->addHtml(Html::el('')->setText('-1 Léňa'));
		$table->getPaginator()->setPageSize(2);
		
		...
		...
		...
		
		return $this->tableFactory->createComponentTable($table);
	}
	
PaginatiorWrapped is nette Html object it's very easy edit it or adding another children

![Paginator](src/Doc/image2.PNG?raw=true)
