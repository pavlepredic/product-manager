<?php

namespace App\Controller;

use App\Entity\Product;
use App\Event\ProductCreated;
use App\Event\ProductDeleted;
use App\Event\ProductModified;
use App\EventBus\EventBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ProductController extends Controller
{
    /**
     * @Route("/show/{id}", name="product_show")
     * @param Product $product
     * @return Response
     */
    public function showAction(Product $product)
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="product_edit")
     * @param Product $product
     * @param Request $request
     * @return Response
     */
    public function editAction(Product $product, Request $request)
    {
        $form = $this->createFormBuilder($product)
            ->add('name', TextType::class)
            ->add('price', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Save'))
            ->add('delete', SubmitType::class, array('label' => 'Delete'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('delete')->isClicked()) {
                $event = new ProductDeleted($product);
            } else {
                $event = new ProductModified($product);
            }

            $this->getEventBus()->publish($event);
            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }

    /**
     * @Route("/create", name="product_create")
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $product = new Product();
        $form = $this->createFormBuilder($product)
            ->add('name', TextType::class)
            ->add('price', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Save'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = new ProductCreated($product);
            $this->getEventBus()->publish($event);
            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }

    /**
     * @Route("/", name="product_index")
     * @return Response
     */
    public function indexAction()
    {
        $products = $this->getDoctrine()->getManager()->getRepository(Product::class)->findAll();

        return $this->render('product/index.html.twig', ['products' => $products]);
    }

    /**
     * @return EventBusInterface
     */
    private function getEventBus()
    {
        return $this->get('app.event_bus.main');
    }
}