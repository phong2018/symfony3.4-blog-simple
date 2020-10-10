<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
/**
 * Comment controller.
 *
 * @Route("admin/comment")
 */
class CommentController extends Controller
{
	/**
     * Lists all comment entities.
     *
     * @Route("/", name="admin_comment_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();

        $comments = $em->getRepository('AppBundle:Comment')->findAll();

        return [
            'comments' => $comments,
        ];
        //return new Response('<html><body>COMMENT PAGE!</body></html>');
    }
    /**
     * Creates a new comment entity.
     *
     * @Route("/new", name="admin_comment_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $comment = new Comment();
        $form = $this->createForm('MainBundle\Form\CommentType', $comment);
        $form->remove('createdAt');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $comment->setHashId(md5(date('ymdHis')));

            $em->persist($comment);
            $em->flush($comment);

            return $this->redirectToRoute('secure_comment_show', array('id' => $comment->getId()));
        }

        return [
            'comment' => $comment,
            'form' => $form->createView(),
        ];
    }

}