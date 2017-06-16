<?php

namespace App\Model\Article;

interface ArticleDaoInterface
{
    /**
     * @return Article[]|array
     */
    public function findByNewest(int $limit = 10, int $offset = 0, bool $publishedOnly = true): array;

    public function findById(int $previousId): Article;
}