<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Category;
use App\Entity\Course;
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
    {
        $user=new User();
        $user->setUsername('takwa');
        $user->setLastname('mhadhbi');
        $user->setEmail('takwa@takwa.com');
        $user->setPassword($this->encoder->EncodePassword($user,'takwa'));
        $user->setRoles(['ROLE_ADMIN']);
       $manager->persist($user);

      
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
        $course->setAvailablePlaces(20);
        $course->setShortDesc("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vel gravida arcu. Vestibulum feugiat, sapien ultrices fermentum congue, quam velit venenatis sem");
        $course->setLongDesc("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vel gravida arcu. Vestibulum feugiat, sapien ultrices fermentum congue, quam velit venenatis semLorem ipsum dolor sit amet, ");
        $course->setStartDate(new \DateTime());
        $course->setDuration(30);
        $course->setImage("data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/9k=");
        $course->setCategory($manager->getRepository(Category::class)->find(5));
        $manager->persist($course);
        ///////3
           $course=new Course();
           $course->setLabel("Frensh");
           $course->setPrice(100);
           $course->setAvailablePlaces(20);
           $course->setShortDesc("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vel gravida arcu. Vestibulum feugiat, sapien ultrices fermentum congue, quam velit venenatis sem");
           $course->setLongDesc("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vel gravida arcu. Vestibulum feugiat, sapien ultrices fermentum congue, quam velit venenatis semLorem ipsum dolor sit amet, ");
           $course->setStartDate(new \DateTime());
           $course->setDuration(30);
           $course->setImage("data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxMTEhUTExIVFhUXFRUYFxUVFxYVGBUVFRUXGBUVFxUYHSggGB0lGxUVITEhJSkrLi4uFx8zODMtNygtLisBCgoKDg0OGxAQGi");
           $course->setCategory($manager->getRepository(Category::class)->find(5));
           $manager->persist($course); 
           $manager->flush();
    }
}
