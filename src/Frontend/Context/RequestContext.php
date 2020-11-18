<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Frontend\Context;

use Psr\Http\Message\ServerRequestInterface;

class RequestContext extends Context
{
    public function __construct(ServerRequestInterface $request)
    {
        parent::__construct($request->getAttributes());
        $this->set('_target', $request->getRequestTarget());
    }
}
