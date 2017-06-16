<?php

namespace App\AdminModule\Datagrid;

use App\Model\Article\Article;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Ublaboo\DataGrid\DataGrid;

final class ArticleDatagridFactory
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    private function getDataSource(): QueryBuilder
    {
        return $this->em->createQueryBuilder()
            ->select('a')
            ->from(Article::class, 'a');
    }

    public function create(): DataGrid
    {
        $datagrid = new DataGrid();
        $datagrid->setDataSource($this->getDataSource());

        $datagrid->addColumnNumber('id', 'ID')
            ->setSortable();
        $datagrid->addColumnText('title', 'Nadpis')
            ->setSortable();
        $datagrid->addColumnText('published', 'Status')
            ->setTemplate(__DIR__ . '/../templates/Datagrid/isPublished.latte')
            ->setSortable();
        $datagrid->addColumnDateTime('createdAt', 'Vytvořeno')
            ->setSortable();
        $datagrid->addColumnDateTime('updatedAt', 'Upraveno')
            ->setSortable();
        $datagrid->addAction('id', 'Upravit', 'Edit')
            ->setClass('btn btn-warning btn-xs');

        return $datagrid;
    }
}