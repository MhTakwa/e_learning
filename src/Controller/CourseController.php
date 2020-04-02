<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Category;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/course")
 */
class CourseController extends AbstractController
{
    /**
     * @Route("/", name="course_index", methods={"GET"})
     */
    public function index(CourseRepository $courseRepository): Response
    {
        return $this->render('course/index.html.twig', [
            'courses' => $courseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="course_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($course);
            $entityManager->flush();

            return $this->redirectToRoute('course_index');
        }

        return $this->render('course/new.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="course_show", methods={"GET"})
     */
    public function show(Course $course): Response
    {
      
        return $this->render('course/show.html.twig', [
            'course' => $course,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="course_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Course $course): Response
    {
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('course_index');
        }

        return $this->render('course/edit.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="course_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Course $course): Response
    {
        if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($course);
            $entityManager->flush();
        }

        return $this->redirectToRoute('course_index');
    }
        /**
     * @Route("/details/{id}", name="course_details", methods={"GET"})
     */
    public function details(Request $request, Course $course): Response
    {
         
        return $this->render('course/course_details.html.twig',['course'=>$course]);
    }
      /**
     * @Route("/search", name="course_search")
     */
    public function search(Request $request): Response
    {       
        $category=$this->getDoctrine()->getManager()->getRepository(Category::class)->find((int)$request->get('id_category'));
        $var=[];
        if($category!=null)
            $var['Category']=$category;
        $label=$request->get('label');
        if($label!=null)
            $var['label']='%'.$label.'%';
        
            ////die(var_dump($var));
         $courses=$this->getDoctrine()->getManager()->getRepository(Course::class)->findBy($var);                 
         $categories=$this->getDoctrine()->getManager()->getRepository(Category::class)->findAll();
        return $this->render('default/courses.html.twig',[
            'courses'=>$courses,
            'categories'=>$categories]);
    }
}
