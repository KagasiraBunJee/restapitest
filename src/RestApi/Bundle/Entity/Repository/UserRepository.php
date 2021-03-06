<?php

namespace RestApi\Bundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    public function getLoginByApiKey($apiKey)
    {
        $em = $this->getEntityManager();
        
        $user = $em->getRepository("RestApiBundle:User")->findOneBy(array('apiKey' => $apiKey));
        
        if($user)
            return $user->getLogin();
        
        return null;
    }
}
