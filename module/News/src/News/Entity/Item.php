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
    * @ORM\Table(name="NewsItems")
    *
    * @author Sharikov Vladislav <sharikov.vladislav@gmail.com>
    */
class Item {
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
    protected $title;

    /**
    * @var text
    * @ORM\Column(type="text")
    */
    protected $text;

    /**
    * @var int
    * @ORM\ManyToOne(targetEntity="SamUser\Entity\User")
    * @ORM\JoinColumn(nullable=false)
    */
    protected $user;

    /**
    * @var int
    * @ORM\JoinColumn(nullable=false)
    * @ORM\ManyToOne(targetEntity="Category", inversedBy="items")
    */
    protected $category;
    
    /**
    * @var integer
    * @ORM\Column(type="integer", nullable=false)
    */
    protected $visible;
    
    /**
    * @var int
    * @ORM\Column(type="integer")
    */
    protected $created;

    public function setVisible($visible)
    {
        $this->visible = $visible;
    }
    
    public function getVisible()
    {
        return $this->visible;
    }
    
    /**
    * Get id.
    *
    * @return int
    */
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
* Set title.
*
* @param string $title
*
* @return void
*/
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
    * Get text.
    *
    * @return string
    */
    public function getText()
    {
        return $this->text;
    }

    /**
    * Set text.
    *
    * @param string $text
    *
    * @return void
    */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
    * Get user_id.
    *
    * @return int
    */
    public function getUser()
    {
        return $this->user;
    }

    /**
    * Set userId.
    *
    * @param int $userId
    *
    * @return void
    */
    public function setUser($userId)
    {
        $this->user = $userId;
    }

    /**
    * Get user_id.
    *
    * @return int
    */
    public function getCategory()
    {
        return $this->category;
    }

    /**
    * Set userId.
    *
    * @param int $userId
    *
    * @return void
    */
    public function setCategory($category_id)
    {
        $this->category = $category_id;
    }
    
    /**
    * Get created.
    *
    * @return int
    */
    public function getCreated()
    {
        return $this->created;
    }

    /**
    * Set created.
    *
    * @param int $created
    *
    * @return void
    */
    public function setCreated($created)
    {
        $this->created = $created;
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