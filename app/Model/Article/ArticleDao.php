<?php

namespace App\Model\Article;

use App\Model\ORM\BaseDao;
use Doctrine\ORM\EntityNotFoundException;

class ArticleDao extends BaseDao implements ArticleDaoInterface
{
    private function getRepository()
    {
        return $this->em->getRepository(Article::class);
    }

    /**
     * @return Article[]|array
     */
    public function findByNewest(int $limit = 10, int $offset = 0, bool $publishedOnly = true): array
    {
        $conditions = [];
        if ($publishedOnly) {
            $conditions['published'] = true;
        }

        return $this->getRepository()->findBy(
            $conditions,
            ['createdAt' => 'DESC'],
            $limit,
            $offset
        );
    }

    public function findById(int $id): Article
    {
        $article = $this->getRepository()->find($id);

        if ($article === null) {
            throw new EntityNotFoundException('Article with id `' . $id . '` not found.');
        }

        return $article;
    }
}
