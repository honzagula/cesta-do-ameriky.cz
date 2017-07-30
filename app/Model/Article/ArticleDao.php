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
        $qb = $this->getRepository()->createQueryBuilder('a');
        $qb->addSelect('COALESCE((a.nextArticle), 9999999999999) AS HIDDEN srt');

        if ($publishedOnly) {
            $qb->where('a.published = :published')
                ->setParameter('published', true);
        }

        $qb->orderBy('srt', 'DESC');
        $qb->addOrderBy('a.createdAt', 'DESC');

        $qb->setMaxResults($limit);
        $qb->setFirstResult($offset);

        return $qb->getQuery()->getResult();
    }

    public function findById(int $id): Article
    {
        $article = $this->getRepository()->find($id);

        if ($article === null) {
            throw new EntityNotFoundException('Article with id `' . $id . '` not found.');
        }

        return $article;
    }

    public function getCount(bool $publishedOnly = true): int
    {
        $qb = $this->getRepository()->createQueryBuilder('a')
            ->select('count(a)');

        if ($publishedOnly) {
            $qb->where('a.published = :published')
                ->setParameter('published', true);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getLastUpdate(bool $publishedOnly = true): \DateTimeInterface
    {
        $qb = $this->getRepository()->createQueryBuilder('a')
            ->select('max(a.updatedAt)');

        if ($publishedOnly) {
            $qb->where('a.published = :published')
                ->setParameter('published', true);
        }

        return new \DateTimeImmutable($qb->getQuery()->getSingleScalarResult());
    }
}
