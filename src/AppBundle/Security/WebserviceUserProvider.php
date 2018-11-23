<?php

namespace AppBundle\Security;

use AppBundle\Security\WebserviceUser;
use AppBundle\Service\RrhhApiService;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class WebserviceUserProvider implements UserProviderInterface
{

    private $rrhhApi;

    public function __construct(RrhhApiService $rrhhApi) {
        $this->rrhhApi = $rrhhApi;
    }

    public function loadUserByUsername($username)
    {
        return $this->fetchUser($username);
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof WebserviceUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        $username = $user->getUsername();

        return $this->fetchUser($username);
    }

    public function supportsClass($class)
    {
        return WebserviceUser::class === $class;
    }

    private function fetchUser($username)
    {
        // make a call to your webservice here
        $response = $this->rrhhApi->getEmployeeByUsername($username);
        // pretend it returns an array on success, false if there is no user
        if ($response["code"] == 200) {
            $data = $response["data"];
            return new WebserviceUser($data->id, $data->email, $data->password, $data->firstname, $data->surname, ["ROLE_EMPLOYEE"]);
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }
}
