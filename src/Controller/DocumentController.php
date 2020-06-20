<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Course;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @Route("/document")
 */
class DocumentController extends AbstractController
{
    /**
     * @Route("/", name="document_index", methods={"GET"})
     */
    public function index(DocumentRepository $documentRepository): Response
    {
        return $this->render('document/index.html.twig', [
            'documents' => $documentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{course_id}", name="document_new", methods={"GET","POST"})
     */
    public function new(Request $request,SluggerInterface $slugger,Course $course_id): Response
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pdf = $form->get('content')->getData();
            $document->setCourse($course_id);
            if($course_id->getState()==2)
                $course_id->setState(1);
            if ($pdf) {
               $filename=$this->uploadFile($pdf,$slugger,$course_id);
               if($filename) 
                   $document->setContent($filename);
            //  dd($document->getContent());
           }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($document);
            $entityManager->flush();

            return $this->redirectToRoute('course_edit',['id'=>$course_id->getId()]);
        }

        return $this->render('document/new.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="document_show", methods={"GET"})
     */
    public function show(Document $document): Response
    {
        return $this->render('document/show.html.twig', [
            'document' => $document,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="document_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Document $document,SluggerInterface $slugger): Response
    {
      
       
        $form = $this->createForm(DocumentType::class, $document);
        $document->setContent( new File($this->getParameter('document_directory').'/'. $document->getCourse()->getLabel() .'/'.$document->getContent()));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pdf = $form->get('content')->getData();
            if ($pdf) {
                $filename=$this->uploadFile($pdf,$slugger,$document->getCourse());
                if($filename) 
                    $document->setContent($filename);
             //  dd($document->getContent());
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('course_edit',['id'=>$document->getCourse()->getId()]);
        }

        return $this->render('document/edit.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="document_delete", methods={"GET"})
     */
    public function delete(Request $request, Document $document): Response
    {
     
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($document);
            $entityManager->flush();
        

            return $this->redirectToRoute('course_edit',['id'=>$document->getCourse()->getId()]);
    }
    private function uploadFile($file, SluggerInterface $slugger,Course $course){ 
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
       // die(var_dump($newFilename));
        // Move the file to the directory where brochures are stored
        try {
            $file->move($this->getParameter('document_directory').'/'. $course->getLabel() ,$newFilename);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
         return $newFilename;
      

    }
}
