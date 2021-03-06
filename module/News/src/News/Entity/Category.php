<?php
/**
* @link https://github.com/romka/zend-blog-example
*/

namespace News\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

    /**
    * An example of how to implement a BlogPost entity.
    *
    * @ORM\Entity
    * @ORM\Table(name="NewsCategories")
    *
    * @author Sharikov Vladislav <sharikov.vladislav@gmail.com>
    */
class Category {
    /**
    * @var int
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /**
    * @var string
    * @ORM\Column(type="string", length=255, nullable=false)
    */
    protected $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Item", mappedBy="category")     
     */
    protected $items;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $url;
    
    public function __construct(){
        $this->items = new ArrayCollection();
    }
    
    public function setUrl($url)
    {
        $this->url = $url;
    }
    
    public function getUrl()
    {
        return $this->url;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    /**
    * Set id.
    *
    * @param int $id
    *
    * @return void
    */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
    * Get title.
    *
    * @return string
    */
    public function getName()
    {
        return $this->name;
    }

    /**
    * Set name.
    *
    * @param string $name
    *
    * @return void
    */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
    * Helper function.
    */
    public function exchangeArray($data)
    {
        foreach ($data as $key => $val) {
            if (property_exists($this, $key)) {
                $this->$key = ($val !== null) ? $val : null;
            }
        }
    }

    /**
    * Helper function
    */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}