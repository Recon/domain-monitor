<?php

namespace Controllers;

use \Exceptions\HTTP\JSON\ResourceNotFoundException;
use \Exceptions\HTTP\JSON\UnauthorizedException;
use \Models\Domain;
use \Models\DomainQuery;
use \Models\Test;
use \Propel\Runtime\Map\TableMap;
use \Propel\Runtime\Propel;
use \Symfony\Component\HttpFoundation\JsonResponse;

class DomainsController extends AbstractController
{

    public function getDomainList()
    {
        if (!$this->permissionChecker->isLoggedIn()) {
            throw new UnauthorizedException();
        }

        if ($this->permissionChecker->isAdmin()) {
            $domains = DomainQuery::create()
                ->joinWith("Domain.Test", \Propel\Runtime\ActiveQuery\Criteria::LEFT_JOIN)
                ->filterByAccountId($this->getLoggedInUser()->getAccount()->getId())
                ->find();
        } else {
            $domains = DomainQuery::create()
                ->filterByUser($this->getLoggedInUser())
                ->filterByAccountId($this->getLoggedInUser()->getAccount()->getId())
                ->joinWithTest()
                ->find();
        }

        return new JsonResponse($domains->toArray(null, null, TableMap::TYPE_FIELDNAME));
    }

    public function getDomainInfo()
    {
        if (!$this->permissionChecker->isLoggedIn()) {
            throw new UnauthorizedException();
        }

        $result = DomainQuery::create()
            ->filterById((int) $this->request->get('id'))
            ->filterByUser($this->getLoggedInUser())
            ->find();

        if (!$result->count()) {
            throw new ResourceNotFoundException();
        }

        $domain = $result->pop();
        $result = $domain->toArray(TableMap::TYPE_FIELDNAME, true, [], true);
        $result['tests'] = [];
        foreach ($domain->getTests() As $test) {
            $result['tests'][] = $test->toArray(TableMap::TYPE_FIELDNAME, true, [], true);
        }

        return new JsonResponse($result);
    }

    public function setEnabled()
    {
        if (!$this->permissionChecker->isAdmin()) {
            throw new UnauthorizedException();
        }

        $result = DomainQuery::create()
            ->filterById((int) $this->request->get('id'))
            ->filterByAccount($this->getLoggedInUser()->getAccount())
            ->find();

        if (!$result->count()) {
            throw new ResourceNotFoundException();
        }

        /* @var $domain Domain */
        $domain = $result->pop();
        $domain->setIsEnabled(filter_var($this->request->get('enabled'), FILTER_VALIDATE_BOOLEAN));
        $domain->save();

        return new JsonResponse([
            'success' => true
        ]);
    }

    public function addDomain()
    {
        if (!$this->permissionChecker->isAdmin()) {
            throw new UnauthorizedException();
        }

        $messages = [];
        $user = $this->getLoggedInUser();
        $connection = Propel::getConnection();
        $connection->beginTransaction();

        $domain = new Domain();
        $domain->setUri($this->request->get('uri'));
        $domain->setAccount($this->getLoggedInUser()->getAccount());
        $domain->save();

        $user->addDomain($domain);
        $user->save();

        $messages = array_merge($messages, $this->getErrorMessages($domain));

        $testsData = $this->request->get('tests', []);
        foreach ($testsData As $testData) {
            $test = new Test();
            $test->setTestName($testData['type']['name'])
                ->setDomain($domain)
                ->setStatus(0)
                ->save();

            $messages = array_merge($messages, $this->getErrorMessages($test));
        }

        if (count($messages)) {
            $connection->rollBack();
            return new JsonResponse([
                'success' => false,
                'messages' => $messages
                ], 400);
        }

        $connection->commit();

        return new JsonResponse([
            'success' => true
        ]);
    }

    public function updateDomain()
    {
        if (!$this->permissionChecker->isAdmin()) {
            throw new UnauthorizedException();
        }

        $result = DomainQuery::create()
            ->filterById((int) $this->request->get('id'))
            ->filterByAccount($this->getLoggedInUser()->getAccount())
            ->find();

        if (!$result->count()) {
            throw new ResourceNotFoundException();
        }

        /* @var $domain Domain */
        $domain = $result->pop();
        $user = $this->getLoggedInUser();
        $messages = [];

        $connection = Propel::getConnection();
        $connection->beginTransaction();

        $domain->setUri($this->request->get('uri'));
        $domain->save();

        $messages = array_merge($messages, $this->getErrorMessages($domain));

        $tests = $domain->getTests();
        foreach ($tests as $test) {
            $key = array_search($test->getId(), array_column($this->request->get('tests', []), 'id'));
            if ($key === false) {
                $test->delete();
            } else {
                $test->setTestName($this->request->get('tests')[$key]['type']['name']);
                $test->save();

                $messages = array_merge($messages, $this->getErrorMessages($test));
            }
        }

        foreach ($this->request->get('tests', []) As $testData) {
            if (isset($testData['id']))
                continue;

            $test = new Test();
            $test->setTestName($testData['type']['name'])
                ->setDomain($domain)
                ->setStatus(0)
                ->save();

            $messages = array_merge($messages, $this->getErrorMessages($test));
        }

        if (count($messages)) {
            $connection->rollBack();
            return new JsonResponse([
                'success' => false,
                'messages' => $messages
                ], 400);
        }

        $connection->commit();

        return new JsonResponse([
            'success' => true
        ]);
    }

    public function deleteDomain()
    {
        if (!$this->permissionChecker->isAdmin()) {
            throw new UnauthorizedException();
        }

        $result = DomainQuery::create()
            ->filterById((int) $this->request->get('id'))
            ->filterByAccount($this->getLoggedInUser()->getAccount())
            ->find();

        if (!$result->count()) {
            throw new ResourceNotFoundException();
        }

        /* @var $domain Domain */
        $domain = $result->pop();
        $domain->delete();

        return new JsonResponse([
            'success' => true
        ]);
    }

}
