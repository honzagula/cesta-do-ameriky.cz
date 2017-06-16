<?php
namespace App\AdminModule\Presenters;

use App\Analytics\AnalyticsToken;
use App\Model\Social\SocialConfig;

final class HomepagePresenter extends AdminPresenter
{
    /** @var SocialConfig @inject */
    public $socialConfig;

    /** @var AnalyticsToken @inject */
    public $analyticsToken;

    public function beforeRender(): void
    {
        parent::beforeRender();
        $template = $this->getTemplate();
        $template->socialConfig = $this->socialConfig;
        $template->analyticsToken = $this->analyticsToken->get();
    }
}
