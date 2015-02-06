<?php
namespace RestApi\Bundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class ApiKeyUserProvider implements UserProviderInterface
{
    private $em;
    
    public function __construct($em) {
        $this->em = $em;
    }
    
    public function getUsernameForApiKey($apiKey)
    {
        $userLogin = $this->em->getRepository("RestApiBundle:User")->getLoginByApiKey($apiKey);
        return $userLogin;
    }
    
    public function loadUserByUsername($username) {
        
        $user = $this->em->getRepository("RestApiBundle:User")->findOneBy(array('login' => $username));
        
        if($user)
            return $user;
        
        return new User(
            $username,
            null,
            // the roles for the user - you may choose to determine
            // these dynamically somehow based on the user
            array('ROLE_USER')
        );
    }

    public function refreshUser(UserInterface $user) {
        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        throw new UnsupportedUserException();
    }

    public function supportsClass($class) 
    {
        return 'RestApi\Bundle\Entity\User' === $class;
    }

}
