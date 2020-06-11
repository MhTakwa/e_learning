<?php

namespace App\Controller;

use App\Entity\Teacher;
use App\Form\TeacherType;
use App\Repository\TeacherRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @Route("admin/teachers") 
 */
class TeacherController extends AbstractController
{
    /**
     * @Route("/", name="teacher_index", methods={"GET"})
     */
    public function index(TeacherRepository $teacherRepository): Response
    {
        return $this->render('teacher/index.html.twig', [
            'teachers' => $teacherRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="teacher_new", methods={"GET","POST"})
     */
    public function new(Request $request,SluggerInterface $slugger,UserPasswordEncoderInterface $encoder): Response
    {
        $teacher = new Teacher();
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
           
             if ($image) {
                $filename=$this->uploadImage($image,$slugger);
                if($filename) 
                    $teacher->setImage($filename);
               
            }
            $entityManager = $this->getDoctrine()->getManager();
            $teacher->setPassword($encoder->EncodePassword($teacher,$teacher->getPassword()));
            $teacher->setRoles(['ROLE_USER','ROLE_TEACHER']);
            $entityManager->persist($teacher);
            $entityManager->flush();

            return $this->redirectToRoute('teacher_index');
        }

        return $this->render('teacher/new.html.twig', [
            'teacher' => $teacher,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="teacher_show", methods={"GET"})
     */
    public function show(Teacher $teacher): Response
    {
        return $this->render('teacher/show.html.twig', [
            'teacher' => $teacher,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="teacher_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Teacher $teacher,SluggerInterface $slugger,UserPasswordEncoderInterface $encoder): Response
    {
       // $teacher->setImage(new File($this->getParameter('images_directory').'/'.$teacher->getImage())
       // );
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        $image = $form->get('image')->getData();
           if ($image) { ///die(var_dump($this->uploadImage($image,$slugger)));
            $filename=$this->uploadImage($image,$slugger);
            if($filename) 
                $teacher->setImage($filename);
           
        }
        $teacher->setPassword($encoder->EncodePassword($teacher,$teacher->getPassword()));
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('teacher_index');
        }

        return $this->render('teacher/edit.html.twig', [
            'teacher' => $teacher,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="teacher_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Teacher $teacher): Response
    {
       
        if ($this->isCsrfTokenValid('delete'.$teacher->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($teacher);
            $entityManager->flush();
        }

        return $this->redirectToRoute('teacher_index');
    }
    

     /**
     * @Route("/modify_profile", name="modify_profile", methods={"POST"})
     */
    public function modify_profile(Request $request, UserInterface $user): Response
    {
      $user->setUsername($request->get('name'));
      $user->setLastname($request->get('surname'));
      $user->setExperience($request->get('experience'));
      $manager=$this->getDoctrine()->getManager();
      $manager->persist($user);
      $manager->flush();
      return $this->redirectToRoute('user_profile');
    }
        /**
     * @Route("/password_reset", name="password_reset", methods={"POST"})
     */
    public function password_reset(Request $request, UserInterface $user,UserPasswordEncoderInterface $encoder): Response
    {
      $test=$encoder->isPasswordValid($user, $request->get('OldPassword'));
      if($test)
        $user->setPassword($encoder->EncodePassword($user,$request->get('NewPassword')));
     
      $manager=$this->getDoctrine()->getManager();
      $manager->flush();
      return $this->redirectToRoute('user_profile');
    }


    private function uploadImage($image, SluggerInterface $slugger){ 
        $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
       // die(var_dump($newFilename));
        // Move the file to the directory where brochures are stored
        try {
            $image->move(
                $this->getParameter('images_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
         return $newFilename;
      

    }
}
