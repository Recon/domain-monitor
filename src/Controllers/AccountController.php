<?php

namespace Controllers;

use Exceptions\HTTP\JSON\UnauthorizedException;
use Models\UserQuery;
use Propel\Runtime\Map\TableMap;
use Symfony\Component\HttpFoundation\JsonResponse;

class AccountController extends AbstractController
{

    public function getUsers()
    {
        if (!$this->permissionChecker->isAdmin()) {
            throw new UnauthorizedException();
        }

        $users = UserQuery::create()->findByAccountId($this->getLoggedInUser()->getAccount()->getId());

        $data = [];

        foreach ($users as $user) {
            $user->eraseCredentials();
            $itemArr = $user->toArray(TableMap::TYPE_FIELDNAME, null, null, true);
            $itemArr['domains'] = $user->getDomains()->toArray(null, null, TableMap::TYPE_FIELDNAME);
            $data[] = $itemArr;
        }

        return new JsonResponse($data);
    }

}
