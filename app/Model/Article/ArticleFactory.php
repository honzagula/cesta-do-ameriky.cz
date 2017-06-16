<?php

namespace App\Model\Article;

use App\Model\ORM\BaseFactory;
use App\Model\User\User;

class ArticleFactory extends BaseFactory
{
    public function createArticle(User $user, $title, $slug, $content, $published): Article
    {
        $article = new Article($user, $title, $slug);
        $article->setContent($content);
        $article->setPublished($published);
        
        $this->persister->save($article);
        
        return $article;
    }
}
