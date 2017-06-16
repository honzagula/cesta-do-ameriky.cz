<?php
namespace Instante\Tests\Presenters;

use App\Model\Article\Article;
use App\Model\Article\ArticleDaoInterface;
use App\Model\User\User;
use App\Presenters\HomepagePresenter;
use Instante\Tests\TestBootstrap;
use Nette\Http\Request;
use Nette\Http\UrlScript;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$httpRequest = new Request(new UrlScript('http://localhost', '/'));

$articleDao = new class () implements ArticleDaoInterface {
    function getUser(): User
    {
        return new User('test@test.test', 'test', 'test', 'test');
    }

    public function findByNewest(int $limit = 10, int $offset = 0, bool $publishedOnly = true): array
    {
        return [
            0 => new Article($this->getUser(), 'First article', 'first-article'),
        ];
    }

    public function findById(int $previousId): Article
    {
    }
};
$tester = new PresenterTester(HomepagePresenter::class, TestBootstrap::$tempDir);
$dc = $tester->getDependencyContainer();
$dc->addDependencies([
    'articleDao' => new $articleDao(),
    'httpRequest' => $httpRequest,
]);
$result = $tester->runPresenter();
Assert::match('~^{block meta}~', $result->getResponseBody());
