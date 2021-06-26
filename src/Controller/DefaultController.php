<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/about", name="about")
     */
    public function aboutAction()
    {
        return $this->render('default/about.html.twig');
    }

    /**
     * @Route("/customer-service", name="customer_service")
     */
    public function customerServiceAction()
    {
        return $this->render('default/customer-service.html.twig');
    }

    /**
     * @Route("/orders-and-returns", name="orders_returns")
     */
    public function ordersAndReturnsAction()
    {
        return $this->render('default/orders-returns.html.twig');
    }

    /**
     * @Route("/privacy-and-cookie-policy", name="privacy_cookie")
     */
    public function privacyAndCookiePolicyAction()
    {
        return $this->render('default/privacy-cookie.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction(Request $request)
    {
        // Build a form, with validation rules in place
        $form = $this->createFormBuilder()
            ->add('name', TextType::class, array(
                'constraints' => new NotBlank()
            ))
            ->add('email', EmailType::class, array(
                'constraints' => new Email()
            ))
            ->add('message', TextareaType::class, array(
                'constraints' => new Length(array('min' => 3))
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Reach Out!',
                'attr' => array('class' => 'button'),
            ))
            ->getForm();

        // Check if this is a POST type request and if so, handle form
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->addFlash(
                    'success',
                    'Your form has been submitted. Thank you.'
                );

                // todo: Send an email out to website admin or something...

                return $this->redirect($this->generateUrl('contact'));
            }
        }

        // Render "contact us" page
        return $this->render('default/contact.html.twig', array(
            'form' => $form->createView()
        ));
    }

}
