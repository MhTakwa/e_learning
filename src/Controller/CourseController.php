<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Category;
use App\Entity\Document;
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
 * @Route("/courses")
 */
class CourseController extends AbstractController
{
         /**
     * @Route("/enroll/{id}", name="course_enroll", methods={"GET"})
     */
    public function enroll(Request $request, Course $course,TokenStorageInterface $token): Response
    {    $this->denyAccessUnlessGranted('ROLE_USER');
         $token->getToken()->getUser()->addEnrolledCourse($course);
         $manager=$this->getDoctrine()->getManager();
         $manager->flush();
        return  $this->redirectToRoute('dashboard');
         
       
    }
        /**
     * @Route("/details/{id}", name="course_details", methods={"GET"})
     */
    public function details(Request $request, Course $course): Response
    {
         
        return $this->render('course/course_details.html.twig',['course'=>$course]);
    }


      /**
     * @Route("/{id}/courseware", name="courseware", methods={"GET"})
     */
    public function courseware(Request $request, Course $course): Response
    {
         
        return $this->render('course/courseware.html.twig',['course'=>$course]);
    }
    
    
      /**
     * @Route("/document", name="courseware_document", methods={"GET","POST"})
     */
    public function coursewareDocument (Request $request): Response
    {
        $document=$this->getDoctrine()->getRepository(Document::class)->find((int)$request->get('id'));
        if($document)
            $response='/uploads/documents/'.$document->getCourse()->getlabel().'/'.$document->getContent();
        else
            $response="";
       return new Response($response);
    }
    
}
?>