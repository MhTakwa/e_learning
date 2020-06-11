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
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


/**
 * @Route("admin/courses")
 */
class Admin_CourseController extends AbstractController
{
    /**
     * @Route("/", name="course_index", methods={"GET"})
     */
    public function index(Request $request,CourseRepository $courseRepository,UserInterface $user): Response
    {

      if(\in_array('ROLE_ADMIN',$user->getRoles())){
        $count_all=$courseRepository->findAll();
        $count_ongoing=$courseRepository->findBy(['state'=>1]);
        $count_coming=$courseRepository->findBy(['state'=>2]);
        $count_canceled=$courseRepository->findBy(['state'=>0]);
        $filter=(int)$request->get('filter'); 
        if($filter)
        $courses=$courseRepository->findBy(['state'=>$filter]);
        else
             $courses=$courseRepository->findAll();
      }
      else{
        $count_all=$courseRepository->findBy(['teacher'=>$user]);
        $count_ongoing=$courseRepository->findBy(['state'=>1,'teacher'=>$user]);
        $count_coming=$courseRepository->findBy(['state'=>2,'teacher'=>$user]);
        $count_canceled=$courseRepository->findBy(['state'=>0,'teacher'=>$user]);
        $filter=(int)$request->get('filter'); 
        if($filter)
        $courses=$courseRepository->findBy(['state'=>$filter,'teacher'=>$user]);
        else
             $courses=$courseRepository->findBy(['teacher'=>$user]);
      }

        return $this->render('course/index.html.twig', [
            'courses' => $courses,
            'count_all'=>$count_all,
            'count_ongoing'=>$count_ongoing,
            'count_coming'=>$count_coming,
            'count_canceled'=>$count_canceled
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
            $course->setState(2);
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
    public function edit(Request $request, Course $course,UserInterface $user): Response
    {
     if(\in_array('ROLE_ADMIN',$user->getRoles())) {
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
     else{
        
        return $this->render('course/teacher_edit.html.twig',['course'=>$course]);

     }
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
        



}
