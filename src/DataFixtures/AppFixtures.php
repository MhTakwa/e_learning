<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Category;
use App\Entity\Course;
use App\Entity\Teacher;
use Symfony\Component\Validator\Constraints\Date;
use Faker;
class AppFixtures extends Fixture
{  
    private $encoder;
    private $faker;
    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder=$encoder;
        $this->faker = Faker\Factory::create();
    }
    public function load(ObjectManager $manager)
    {   ///////Admin
        $user=new User();
        $user->setUsername('takwa');
        $user->setLastname('mhadhbi');
        $user->setEmail('takwa@takwa.com');
        $user->setPassword($this->encoder->EncodePassword($user,'takwa'));
        $user->setRoles(['ROLE_ADMIN']);
        
       $manager->persist($user);
         ////user
         $user=new User();
         $user->setUsername('malek');
         $user->setLastname('mhadhbi');
         $user->setEmail('malek@malek.com');
         $user->setPassword($this->encoder->EncodePassword($user,'malek'));
         $user->setRoles(['ROLE_USER']);
         $manager->persist($user);
         


       ////teacher
       $teacher=new Teacher();
       $teacher->setUsername('mahdi');
       $teacher->setLastname('mhadhbi');
       $teacher->setEmail('mahdi@mahdi.com');
       $teacher->setJob("maketting manager");
       $teacher->setPassword($this->encoder->EncodePassword($user,'mahdi'));
       $teacher->setRoles(['ROLE_USER','ROLE_TEACHER']);
       $manager->persist($teacher);
     
        ///////////////categories
        ///1
        $category=new Category();
        $category->setLabel("Languages");
        $manager->persist($category);
        ///2
        $category=new Category();
        $category->setLabel("Programming");
        $manager->persist($category);
        //3
        $category=new Category();
        $category->setLabel("Frameworks");
        $manager->persist($category);
        ////4
        $category=new Category();
        $category->setLabel("maths");
        $manager->persist($category);
        /////5
        $category=new Category();
        $category->setLabel("chemestry");
        $manager->persist($category);
  


        ////////Courses
        ///////1
        $course=new Course();
        $course->setLabel("Frensh");
        $course->setPrice(100);
        $course->setState(1);
        $course->setShortDesc("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vel gravida arcu. Vestibulum feugiat, sapien ultrices fermentum congue, quam velit venenatis sem");
        $course->setLongDesc("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vel gravida arcu. Vestibulum feugiat, sapien ultrices fermentum congue, quam velit venenatis semLorem ipsum dolor sit amet, ");
        $course->setStartDate(new \DateTime());
        $course->setDuration(30);

        $course->setImage("https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcTGh8UbgIZWSTEoKn02fEd1wtNeevEPQFBuqlRrgMQV2c4Y9BQi&usqp=CAU");
        $course->setCategory($manager->getRepository(Category::class)->find(5));
        $course->setTeacher($teacher);
        $manager->persist($course);
        ///////3
           $course=new Course();
           $course->setLabel("PHP");
           $course->setPrice(100);
           
           $course->setShortDesc("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vel gravida arcu. Vestibulum feugiat, sapien ultrices fermentum congue, quam velit venenatis sem");
           $course->setLongDesc("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vel gravida arcu. Vestibulum feugiat, sapien ultrices fermentum congue, quam velit venenatis semLorem ipsum dolor sit amet, ");
           $course->setStartDate(new \DateTime());
           $course->setDuration(30);
           $course->setImage("https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcTGh8UbgIZWSTEoKn02fEd1wtNeevEPQFBuqlRrgMQV2c4Y9BQi&usqp=CAU");
           $course->setCategory($manager->getRepository(Category::class)->find(5));
           $course->setTeacher($teacher);
           $manager->persist($course); 
           $manager->flush();
    }
}
