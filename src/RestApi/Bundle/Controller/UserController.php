<?php

namespace RestApi\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Mcfedr\AwsPushBundle\Controller\ApiController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use RestApi\Bundle\Entity\User;
use RestApi\Bundle\Form\UserType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/user")
 */
class UserController extends ApiController
{
    /**
     * @Route("/register")
     * @Method({"POST"})
     */
    public function registerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $form = $this->createForm(new UserType(), $user);
        $this->handleJsonForm($form, $request);
        
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $user->setPassword($encoder->encodePassword($user->getPassword(), $user->getSalt()));
        $em->persist($user);
        $em->flush();
        
        return new JsonResponse([
            'user' => $user
        ]);
    }

}
