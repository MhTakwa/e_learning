<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Course;
use App\Entity\Category;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {
       
        $courses=$this->getDoctrine()->getRepository(Course::class)->findAll();
        $categories=$this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'courses'=>$courses,
            'categories'
        ]);
    }
    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        return $this->render('default/about.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
        /**
     * @Route("/courses", name="courses")
     */
    public function courses()
    {
        $courses=$this->getDoctrine()->getManager()->getRepository(Course::class)->findAll();
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

}
