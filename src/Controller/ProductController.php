<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

use App\Entity\Product;
use App\Form\ProductType;
use App\Service\ImageUploader;

/**
 * Product controller.
 *
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * Lists all Product entities.
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository(Product::class)->findAll();

        return $this->render('product/index.html.twig', array(
            'products' => $products,
        ));
    }

    /**
     * Creates a new Product entity.
     * @Route("/new", name="product_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request, ImageUploader $imageUploader)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($image = $form->get('image')->getData()) {  // $image \Symfony\Component\HttpFoundation\File\UploadedFile
                $name = $imageUploader->upload($image);
                $product->setImage($name);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('product/new.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Product entity.
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function showAction(int $id)
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $product = $repository->find($id);

        $deleteForm = $this->createDeleteForm($product);

        return $this->render('product/show.html.twig', array(
            'product' => $product,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Product entity.
     * @Route("/{id}/edit", name="product_edit", methods={"GET", "POST"})
     */
    public function editAction($id, Request $request, ImageUploader $imageUploader)
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $product = $repository->find($id);
        /**
         * When creating a form to edit an already persisted item, the file form type still expects a File instance.
         * As the persisted entity now contains only the relative file path, you first have to concatenate the
         * configured upload path with the stored filename and create a new File class:
         */
        $existingImage = $product->getImage();
        if ($existingImage) {
            $product->setImage(new File($this->getParameter('catalog_images_directory') . '/' . $existingImage));
        }

        $deleteForm = $this->createDeleteForm($product);

        $editForm = $this->createForm(ProductType::class, $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if ($imageFile = $product->getImage()) {  // $image \Symfony\Component\HttpFoundation\File\UploadedFile
                // $name = $this->get('foggyline_catalog.image_uploader')->upload($imageFile);
                $name = $imageUploader->upload($imageFile);
                $product->setImage($name);
            } elseif ($existingImage) {
                $product->setImage($existingImage);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            // return $this->redirectToRoute('product_edit', array('id' => $product->getId()));
            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('product/edit.html.twig', array(
            'product' => $product,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Product entity.
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     */
    public function deleteAction(int $id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $product = $repository->find($id);

        $form = $this->createDeleteForm($product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * Creates a form to delete a Product entity.
     *
     * @param Product $product The Product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('product_delete', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
