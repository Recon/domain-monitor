<?php
namespace Tests\Util;

use Models\Account;
use Models\User;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Tests\TestCase;
use Util\UserPermissionChecker;

class UserPermissionCheckerTest extends TestCase
{
    public function testIsLoggedIn()
    {
        $authorizationChecker = $this->getMockBuilder(AuthorizationCheckerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['isGranted'])
            ->getMock();
        $authorizationChecker->expects($this->once())
            ->method('isGranted')
            ->willReturn(true);

        $checker = new UserPermissionChecker($authorizationChecker, $this->getSession());

        $this->assertTrue($checker->isLoggedIn());
    }

    public function testIsAdmin()
    {
        $authorizationChecker = $this->getMockBuilder(AuthorizationCheckerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['isGranted'])
            ->getMock();
        $authorizationChecker->expects($this->once())
            ->method('isGranted')
            ->willReturn(true);

        $checker = new UserPermissionChecker($authorizationChecker, $this->getSession());

        $this->assertTrue($checker->isAdmin());
    }

    public function testHasAccountRights()
    {
        $account = (new Account())->setId(1);

        $authorizationChecker = $this->getMockBuilder(AuthorizationCheckerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['isGranted'])
            ->getMock();
        $authorizationChecker->expects($this->once())
            ->method('isGranted')
            ->willReturn(true);

        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAccount'])
            ->getMock();
        $user->expects($this->once())
            ->method('getAccount')
            ->willReturn($account);

        $authToken = $this->getMockBuilder(UsernamePasswordToken::class)
            ->disableOriginalConstructor()
            ->setMethods(['getUser'])
            ->getMock();
        $authToken->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $session = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $session->expects($this->once())
            ->method('get')
            ->willReturn($authToken);

        $checker = new UserPermissionChecker($authorizationChecker, $session);

        $this->assertTrue($checker->hasAccountRights($account));
    }

    protected function getSession()
    {
        $session = new Session(new MockArraySessionStorage());
        $session->start();

        return $session;
    }
}
