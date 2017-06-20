<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository('BlogBundle:Post');
        $posts = $repository->findAll();
        return $this->render('BlogBundle:Default:index.html.twig', array("posts" => $posts));
    }

    /**
     * @Route("/show/{id}", name="show_article")
     */
    public function showArticleAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository('BlogBundle:Post');
        $post = $repository->findOneBy(array("id" => $id));

        $comment = new Comment();
        $comment->setAuthor($this->getUser());
        $comment->setPost($post);
        $comment->setPubdate(new \DateTime());

        $form = $this->createFormBuilder($comment)
            ->add('content', TextType::class, array('label' => 'Contenu : ', 'data' => ''))
            ->add('save', SubmitType::class, array('label' => 'Commenter'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $em->persist($comment);
            $em->flush();
        }

        return $this->render('BlogBundle:Default:show.html.twig', array("post" => $post, "form" => $form->createView()));
    }
}
