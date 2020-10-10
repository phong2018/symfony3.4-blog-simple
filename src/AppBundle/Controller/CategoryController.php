<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use AppBundle\Helper\StringHelper;

/**
     * @Route("admin/category")
     */
class CategoryController extends Controller
{
	/**
     * Lists all category entities.
     *
     * @Route("/", name="admin_category_index") 
     * @Method("GET")
     * @Template()
     */
    
    public function indexAction()
    {
    	
    	$em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('AppBundle:Category')->findAll();

        return ['categories' => $categories];

          //return new Response('<html><body>CATEGORY PAGE!</body></html>');
    }
    
    /**
     * Creates a new category entity.
     *
     * @Route("/new", name="admin_category_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->remove('code');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // Calculating the code for the URL
            $code = StringHelper::to_slug(strtolower($form->get('name')->getData()));
            //-------
            $category->setCode($code);
            $em->persist($category);
            $em->flush($category);

            return $this->redirectToRoute('admin_category_index', array('id' => $category->getId()));
        }

        //dùng cách return này thì bắt buộc phải khai Template phí trên
        //và tên template trùng tên  new.html.twig
        return [
            'category' => $category,
            'form' => $form->createView()
        ];
    }
    /**
    * @Route("/{id}/edit", name="admin_category_edit")
    * @Method({"GET", "POST"})
    * @Template() 
    */
     public function editAction(Request $request, Category $category)
    {
        $editForm = $this->createForm('AppBundle\Form\CategoryType', $category);
        $editForm->handleRequest($request);
        // xử lý submit
        
       
         if ($editForm->isSubmitted() && $editForm->isValid()) {

            //lưu vào db
            $this->getDoctrine()->getManager()->flush();

            // chuyển đến trang edit xem lại
            return $this->redirectToRoute('admin_category_edit', array('id' => $category->getId()));
        }

        //hien thi form edit
        //dùng Template, để chỉ return thì tên view phài là edit.html.twig
        /*
         return [
            'category' => $category,
            'edit_form' => $editForm->createView(),
        ];
        */
        /* hoặc dùng cách này */
        return $this->render('@App/Category/edit.html.twig', array(
            'category' => $category,
            'edit_form' => $editForm->createView(), 
        ));
    }   

    /**
     * Finds and displays a category entity.
     *
     * @Route("/{id}", name="admin_category_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Category $category)
    {
        $deleteForm =$this->createDeleteForm($category);

        return [
            'category' => $category,
            'delete_form' => $deleteForm->createView()
        ];
    }
   
    /**
     * Deletes a category entity.
     *
     * @Route("/del/{id}", name="admin_category_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Category $category)
    {
        $form = $this->createDeleteForm($category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        }
        return $this->redirectToRoute('admin_category_index');
    }

     /**
     * Creates a form to delete a category entity.
     *
     * @param Category $category The category entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Category $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_category_delete', array('id' => $category->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
   
    
}
