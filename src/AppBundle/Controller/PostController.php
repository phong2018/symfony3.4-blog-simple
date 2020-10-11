<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\Category;
use AppBundle\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Helper\StringHelper;


/**
 * Post controller.
 *
 * @Route("admin/post")
 */
class PostController extends Controller
{
	/**
     * Lists all post entities.
     *
     * @Route("/", name="admin_post_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')->findAll();

        return [
            'posts' => $posts,
        ];
    	//return new Response('<html><body>POST PAGE!</body></html>');
    }
    /**
     * Creates a new post entity.
     *
     * @Route("/new", name="admin_post_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        // Remove certain fiels that are calculated like created_at and code
        $form->remove('createdAt');
        $form->remove('code'); 

        $data = $request->request->all();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // Calculating the code for the URL
            $code = StringHelper::to_slug(strtolower($form->get('title')->getData()));

            $post= $form->getData();

            $post->setCode($code);

             $ct = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneById($data['AppBundle_post']['category']);

            $post->setCategory($ct);

            $em->persist($post);
            $em->flush($post);

            return $this->redirectToRoute('admin_post_index');
        }

        return [
            'post' => $post,
            'form' => $form->createView(),
        ];
    }
}
  