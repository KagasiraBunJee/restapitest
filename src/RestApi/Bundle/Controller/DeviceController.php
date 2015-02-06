<?php

namespace RestApi\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mcfedr\AwsPushBundle\Controller\ApiController;
use Mcfedr\AwsPushBundle\Form\Model\Broadcast;
use Mcfedr\AwsPushBundle\Form\BroadcastType;
use Mcfedr\AwsPushBundle\Exception\PlatformNotConfiguredException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RestApi\Bundle\Entity\Device;
use RestApi\Bundle\Form\DeviceType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


/**
 * @Route("/device")
 */
class DeviceController extends ApiController {

    /**
     * @ApiDoc(
     *  description="Register your device",
     *  input="RestApi\Bundle\Form\DeviceType",
     *  statusCodes={
     *      200="Device registered",
     *      500="Cant resolve connection or ARN for the platform name is not configured"
     *  }
     * )
     * @Route("")
     * @Method("POST")
     */
    public function registerAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $device = new Device();
        $form = $this->createForm(new DeviceType(), $device);
        $this->handleJsonForm($form, $request);

        $arnKey = $this->get('mcfedr_aws_push.devices')->registerDevice($device->getDeviceId(), $device->getPlatform());
        if ($this->container->getParameter('mcfedr_aws_push.topic_arn')) {
            $topicArn = $this->container->getParameter('mcfedr_aws_push.topic_arn');
            $this->get('mcfedr_aws_push.topics')->registerDeviceOnTopic($arnKey, $topicArn);
        }

        $user = $this->getUser();
        $device->setUser($user);
        $em->persist($device);
        $em->flush();
        
        return new JsonResponse([
            'result' => 'Device has been registered.'
        ]);
    }

    /**
     * @ApiDoc(
     *  description="Send notify message to platforms",
     *  input="Mcfedr\AwsPushBundle\Form\BroadcastType",
     *  statusCodes={
     *      200="Message sent",
     *      500="Cant resolve connection or some parameters are invalid"
     *  }
     * )
     * @Route("/notify")
     * @Method("POST")
     * @Security("has_role('ROLE_USER')")
     */
    public function NotifyAction(Request $request) {
        $broadcast = new Broadcast();
        $form = $this->createForm(new BroadcastType(), $broadcast);
        $this->handleJsonForm($form, $request);

        try {
            if ($this->container->getParameter('mcfedr_aws_push.topic_arn') && !$broadcast->getPlatform()) {
                $topicArn = $this->container->getParameter('mcfedr_aws_push.topic_arn');
                $this->get('mcfedr_aws_push.topics')->broadcast($broadcast->getMessage(), $topicArn);
            } else
                $this->get('mcfedr_aws_push.topics')->broadcast($broadcast->getMessage(), $broadcast->getPlatform());

            return new JsonResponse([
                'result' => 'Message has been sent'
            ]);
        } catch (PlatformNotConfiguredException $ex) {
            return new JsonResponse([
                'result' => 'Unknown platform'
            ]);
        }
    }
}
