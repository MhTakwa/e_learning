<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
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
        return $this->render('default/courses.html.twig', [
            'controller_name' => 'DefaultController',
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
     * @Route("/login", name="login")
     */
    public function login()
    {
        return $this->render('default/login.html.twig', [
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
