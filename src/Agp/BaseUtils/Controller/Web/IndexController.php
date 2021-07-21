<?php


namespace Agp\BaseUtils\Controller\Web;


class IndexController
{
    public function offline()
    {
        return view(config("base-utils.view_offline") ?? 'BaseUtils::errors.offline');
    }
}
