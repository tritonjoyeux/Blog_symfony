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
        $categories = $this->getAllCategories();
        return $this->render('BlogBundle:Default:index.html.twig', array("posts" => $posts, "categories" => $categories));
    }

    /**
     * @Route("/category/{slug}", name="get_by_category")
     */
    public function getByCategoryAction($slug, Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository('BlogBundle:Category');
        $category = $repository->findOneBy(array("slug" => $slug));
        $posts = $category->getPost();
        $categories = $this->getAllCategories();
        return $this->render('BlogBundle:Default:index.html.twig', array("posts" => $posts, "categories" => $categories));
    }

    /**
     * @Route("/show/{slug}", name="show_article")
     */
    public function showArticleAction($slug, Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository('BlogBundle:Post');
        $post = $repository->findOneBy(array("slug" => $slug));

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

        $categories = $this->getAllCategories();
        return $this->render('BlogBundle:Default:show.html.twig', array("post" => $post, "form" => $form->createView(), "categories" => $categories));
    }

    private function getAllCategories()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository('BlogBundle:Category');
        $categories = $repository->findAll();
        return $categories;
    }
}
