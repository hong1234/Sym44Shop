<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

// use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

use App\Form\CategoryType;
// use App\Service\ImageUploader;
use App\Service\FileUploader;

use App\Entity\Category;

/**
 * Category controller.
 *
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * Lists all Category entities.
     * @Route("/", name="category_index", methods={"GET"})
     */
    public function indexAction()
    {
        // $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repository->findAll();

        return $this->render('category/index.html.twig', array(
            'categories' => $categories,
        ));
    }

    /**
     * Creates a new Category entity.
     * @Route("/new", name="category_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request, FileUploader $imageUploader)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            if ($imageFile = $form->get('image')->getData()) {
                $name = $imageUploader->upload($imageFile);
                $category->setImage($name);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_show', array('id' => $category->getId()));
            // return $this->redirectToRoute('category_index', array());
        }

        return $this->render('category/new.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Category entity.
     * @Route("/{id}", name="category_show", methods={"GET"})
     */
    public function showAction(int $id)
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $category = $repository->find($id);
        // $deleteForm = $this->createDeleteForm($category);

        $deleteForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('category_delete', array('id' => $category->getId())))
            ->setMethod('DELETE')
            ->getForm();

        return $this->render('category/show.html.twig', array(
            'category' => $category,
            'delete_form' => $deleteForm->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Category entity.
     * @Route("/{id}/edit", name="category_edit", methods={"GET", "POST"})
     */
    public function editAction(int $id, Request $request, FileUploader $imageUploader)
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $category = $repository->find($id);
        /**
         * When creating a form to edit an already persisted item, the file form type still expects a File instance.
         * As the persisted entity now contains only the relative file path, you first have to concatenate the
         * configured upload path with the stored filename and create a new File class:
         */
        $existingImage = $category->getImage();
        if ($existingImage) {
            $category->setImage(new File($this->getParameter('catalog_images_directory') . '/' . $existingImage));
        }

        $deleteForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('category_delete', array('id' => $category->getId())))
            ->setMethod('DELETE')
            ->getForm();

        $editForm = $this->createForm(CategoryType::class, $category);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if ($imageFile = $category->getImage()) {
                $name = $imageUploader->upload($imageFile);
                $category->setImage($name);
            } elseif ($existingImage) {
                $category->setImage($existingImage);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            // return $this->redirectToRoute('category_edit', array('id' => $category->getId()));
            return $this->redirectToRoute('category_show', array('id' => $category->getId()));
        }

        return $this->render('category/edit.html.twig', array(
            'category' => $category,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Category entity.
     * @Route("/{id}", name="category_delete", methods={"DELETE"})
     */
    public function deleteAction(int $id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $category = $repository->find($id);

        $deleteForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('category_delete', array('id' => $category->getId())))
            ->setMethod('DELETE')
            ->getForm();

        $deleteForm->handleRequest($request);

        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        }

        return $this->redirectToRoute('category_index');
    }

}
