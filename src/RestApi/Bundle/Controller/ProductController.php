<?php

namespace RestApi\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Mcfedr\AwsPushBundle\Controller\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use RestApi\Bundle\Entity\Product;
use RestApi\Bundle\Form\ProductType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * @Route("/product")
 */
class ProductController extends ApiController
{
    /**
     * @ApiDoc(
     *  description="Creating product",
     *  section="Product",
     *  input="RestApi\Bundle\Form\ProductType",
     *  output="RestApi\Bundle\Entity\Product"
     * )
     * @Route("/")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(new ProductType(), $product);
        $jsonForm = $this->handleJsonForm($form, $request);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();
        
        return new JsonResponse([
            'result' => $product
        ]);
    }

    /**
     * @ApiDoc(
     *  description="Retrieving product from database",
     *  section="Product",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true}
     *  },
     *  output="RestApi\Bundle\Entity\Product"
     * )
     * @Route("/{id}")
     * @ParamConverter("product", class="RestApiBundle:Product")
     * @Method({"GET"})
     */
    public function viewAction(Product $product)
    {
        return new JsonResponse([
            'result' => $product
        ]);
    }

    /**
     * @ApiDoc(
     *  description="Updating product",
     *  section="Product",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true}
     *  },
     *  input="RestApi\Bundle\Form\ProductType",
     *  output="RestApi\Bundle\Entity\Product"
     * )
     * @Route("/{id}")
     * @ParamConverter("product", class="RestApiBundle:Product")
     * @Method({"PUT"})
     */
    public function updateAction(Request $request, Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new ProductType(), $product);
        $jsonForm = $this->handleJsonForm($form, $request);
        
        $em->flush();
        
        return new JsonResponse([
            'result' => $product
        ]);
    }

    /**
     * @ApiDoc(
     *  description="Deleting product",
     *  section="Product",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true}
     *  }
     * )
     * @Route("/{id}")
     * @ParamConverter("product", class="RestApiBundle:Product")
     * @Method({"DELETE"})
     */
    public function deleteAction(Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();
        
        return new JsonResponse([
            'result' => 'ok'
        ]);
    }
    
    /**
     * @ApiDoc(
     *  description="Products list",
     *  section="Product"
     * )
     * @Route("/")
     * @Method("GET")
     */
    public function listAction()
    {
        $products = $this->getDoctrine()->getRepository("RestApiBundle:Product")->findAll();
        
        if(!empty($products))
        {
            return new JsonResponse([
                'result' => [
                    'products' => $products
                ]
            ]);
        }
        else
        {
            return new JsonResponse([
                'result' => 'There are no products here.'
            ]);
        }
    }

}
