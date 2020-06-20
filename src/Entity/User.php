<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 *  @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface,\Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     */
    private $Roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Course",inversedBy="students")
     */
    private $EnrolledCourses;

     public function __construct(){
        $Roles=new ArrayCollection();
        $this->EnrolledCourses = new ArrayCollection();
      
      
    }

       public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    public function getRoles(): array
    {
        //return $this;
        return  $this->Roles;
    }
    public function getSalt()
    {
       
       return "jklmoiuytghj" ;
    }
    public function eraseCredentials()
    {
       
    }
    public function serialize(){
        return serialize([
            $this->id,
            $this->username,
            $this->password
        ]);
    }
    public function unserialize($serilized){
        list(
            $this->id,
            $this->username,
            $this->password
        )=unserialize($serilized,['allowed_classes'=>false]);

    }

    public function setRoles(array $Roles): self
    {
        $this->Roles = $Roles;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function __toString(){
        return  $this->lastname."  ".$this->username;
    }

    /**
     * @return Collection|Course[]
     */
    public function getEnrolledCourses(): Collection
    {
        return $this->EnrolledCourses;
    }

    public function addEnrolledCourse(Course $enrolledCourse): self
    {
        if (!$this->EnrolledCourses->contains($enrolledCourse)) {
            $this->EnrolledCourses[] = $enrolledCourse;
        }

        return $this;
    }

    public function removeEnrolledCourse(Course $enrolledCourse): self
    {
       
        if ($this->EnrolledCourses->contains($enrolledCourse)) {
            $this->EnrolledCourses->removeElement($enrolledCourse);
        }

        return $this;
    }

    public function isGranted($role)
{
    return in_array($role, $this->getRoles());
}
 
}
