<?php
//src/AppBundle/Controller/SecurityController.php
namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller{

	/**
	* @Route("/register", name="register")
	*/
	public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder){
		$user=new User();
	 	$form=$this->createForm(UserType::class,$user);
	 	$form->handleRequest($request);


	 	if($form->isSubmitted() && $form->isValid() ){

	 		$password=$passwordEncoder->encodePassword($user,$user->getPlainPassword());
	 		$user->setPassword($password);
	 		$roles = ['ROLE_ADMIN'];
	 		$user->setRoles($roles);
	 		//-------
	 		$em=$this->getDoctrine()->getManager();
	 		$em->persist($user);
	 		$em->flush();
	 		return $this->redirectToRoute('hellopage');
	 	}

	 	return $this->render('@App/security/register.html.twig',['form'=>$form->createView()]); 

	}
	/**
	* @Route("/login", name="login")
	* @param AuthenticationUtils $authUtils
	* @return Response
	*/
	public function loginAction(AuthenticationUtils $authUtils){
		$error = $authUtils->getLastAuthenticationError();
		$lastUsername = $authUtils->getLastUsername();

        return $this->render('@App/security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
	}
	 /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout()
    {
        // controller can be blank: it will never be executed!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}