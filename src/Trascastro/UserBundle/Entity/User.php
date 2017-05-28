<?php
/**
 * (c) Ismael Trascastro <i.trascastro@gmail.com>
 *
 * @link        http://www.ismaeltrascastro.com
 * @copyright   Copyright (c) Ismael Trascastro. (http://www.ismaeltrascastro.com)
 * @license     MIT License - http://en.wikipedia.org/wiki/MIT_License
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trascastro\UserBundle\Entity;

use AppBundle\Entity\Revista;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Texto;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;



/**
 * @ORM\Table(name="app_user")
 * @ORM\Entity(repositoryClass="Trascastro\UserBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class User extends BaseUser
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function getId()
    {
        return parent::getId(); // TODO: Change the autogenerated stub
    }

    //CreatedAt

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    public function setCreatedAt()
    {
        // never used
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }


    //UpdatedAt


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="upated_at", type="datetime")
     */
    private $updatedAt;


    /**
     * Set updatedAt
     *
     * @ORM\PreUpdate()
     *
     * @return User
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    //Textos

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Texto", mappedBy="author", cascade={"all"});
     */

    private $textos;

    /**
     * @param mixed $textos
     */
    public function setTextos($textos)
    {
        $this->textos =  $textos;
    }

    /*
     * @return Texto
     */
    public function getTextos()
    {
        return $this->textos;
    }

    //Comentarios

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comentario", mappedBy="author")
     */

    private $comentarios;



    // Suscriptores


    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="suscripciones", cascade={"persist"})
     */
    private $suscriptores;


    /**
     * @param User $suscriptor
     */
    public function addSuscriptor(User $suscriptor)
    {
        if (!$this->suscriptores->contains($suscriptor)) {
            $this->suscriptores->add($suscriptor);
            $suscriptor->addSuscripcion($this);
        }
    }

    /**
     * @return array
     */
    public function getSuscriptores()
    {
        return $this->suscriptores->toArray();
    }

    /**
     * @param User $suscriptor
     */
    public function removeSuscriptor(User $suscriptor)
    {
        if (!$this->suscriptores->contains($suscriptor)) {
            return;
        }
        $this->suscriptores->removeElement($suscriptor);
        $suscriptor->removeSuscripcion($this);
    }

    /**
     *
     */
    public function removeAllSuscriptores()
    {
        $this->suscriptores->clear();
    }


    //Suscripciones


    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="suscriptores", cascade={"persist"})
     * @ORM\JoinTable(name="usuarios_suscripciones",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="suscripcion_id", referencedColumnName="id")}
     *      )
     */
    private $suscripciones;

    /**
     * @param User $suscripcion
     *
     */
    public function addSuscripcion(User $suscripcion )
    {
        if (!$this->suscripciones->contains($suscripcion)) {
            $this->suscripciones->add($suscripcion);
            $suscripcion->addSuscriptor($this);
        }
    }

    /**
     * @return array
     *
     */
    public function getSuscripciones()
    {
        return $this->suscripciones->toArray();
    }


    /**
     * @param User $suscripcion
     *
     */
    public function removeSuscripcion(User $suscripcion)
    {
        if (!$this->suscripciones->contains($suscripcion)) {
            return;
        }
        $this->suscripciones->removeElement($suscripcion);
        $suscripcion->removeSuscriptor($this);
    }

    /**
     *
     */
    public function removeAllSuscripciones()
    {
        $this->suscripciones->clear();
    }




    //Categoria


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Categoria", inversedBy="usuarios")
     */

    private $categoria;

    /**
     * @return mixed
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * @param mixed $categoria
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    }


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="images_upload", fileNameProperty="image" ,nullable=true)
     * @var File
     */
    private $imageFile;


    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
     */
    private $descripcion;

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Revista", mappedBy="dueño", cascade={"all"})
     */

    private $revista;

    public function setRevista(Revista $revista)
    {
        $this->revista = $revista;
        $revista->setDueño($this);


        return $this;
    }

    public function getRevista()
    {
        return $this->revista;
    }







    public function __construct()
    {
        parent::__construct();

        $this->createdAt    = new \DateTime();
        $this->updatedAt    = $this->createdAt;
        $this->suscriptores = new ArrayCollection();
        $this->suscripciones = new ArrayCollection();
        $this->setDescripcion("Articulista en Mygazine");
    }


    public function __toString()
    {
        return $this->username;
    }



}