<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Course;
use App\Entity\Category;
use App\Entity\Teacher;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\Common\Collections\ArrayCollection;
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {
       
        $courses=$this->getDoctrine()->getRepository(Course::class)->findBy(['state'=>1]);
        $categories=$this->getDoctrine()->getRepository(Category::class)->findAll();
        $teachers=$this->getDoctrine()->getRepository(Teacher::class)->findAll();
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'categories'=>$categories,
            'courses'=>array_slice($courses,0,3),
            'teachers'=>array_slice($teachers,0,3)
        ]);
    }
    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        $teachers=$this->getDoctrine()->getRepository(Teacher::class)->findAll();
        return $this->render('default/about.html.twig', [
            'controller_name' => 'DefaultController',
            'teachers'=>array_slice($teachers,0,3)
        ]);
    }
        /**
     * @Route("/courses", name="courses")
     */
    public function courses()
    {
        $courses=$this->getDoctrine()->getManager()->getRepository(Course::class)->findBy(['state'=>1]);
        $categories=$this->getDoctrine()->getManager()->getRepository(Category::class)->findAll();
        return $this->render('default/courses.html.twig', [
            'controller_name' => 'DefaultController',
            'courses'=>$courses,
            'categories'=>$categories
        ]);
    }
            /**
     * @Route("/blog", name="blog")
     */
    public function blog()
    {
        return $this->render('default/blog.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    
        /**
     * @Route("/cart", name="cart")
     */
    public function cart()
    {
        return $this->render('default/cart.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
            /**
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard(UserInterface $user)
    {
        if($user->isGranted('ROLE_ADMIN'))
            return  $this->redirectToRoute('admin');
         
       
        
            return $this->render('default/dashboard.html.twig',['courses'=>$user->getEnrolledCourses()]);
    }
     /**
     * @Route("/search/", name="course_search")
     */
    public function search(Request $request): Response
    {       
        $category=$this->getDoctrine()->getManager()->getRepository(Category::class)->find((int)$request->get('id'));
        $var=[];
        if($category!=null)
            $var['Category']=$category;
        $label=$request->get('label');
        if($label!=null)
            $var['label']='%'.$label.'%';
        
          ///  die(var_dump($var));
         $courses=$this->getDoctrine()->getManager()->getRepository(Course::class)->findBy($var);     
         $categories=$this->getDoctrine()->getManager()->getRepository(Category::class)->findAll();
        return $this->render('default/courses.html.twig',[
            'courses'=>$courses,
            'categories'=>$categories]);
    }
    

}
