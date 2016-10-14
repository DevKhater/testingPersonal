<?php

namespace YallaWebsite\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use YallaWebsite\BackendBundle\Entity\BaseEntity;

/**
 * Article
 * @ORM\HasLifecycleCallbacks 
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="YallaWebsite\BackendBundle\Entity\ArticleRepository")
 */
class Article extends BaseEntity
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    protected $ogType = 'article';
    
    protected $metaTags;
    protected $metaDescription;
    protected $url;
    protected $mediaUrl;
    
    /**
     * @ORM\ManyToOne(targetEntity="YallaWebsite\BackendBundle\Entity\UserProfile")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;
    
    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $media;

    /**
     * @ORM\ManyToMany(targetEntity="YallaWebsite\BackendBundle\Entity\Tag", cascade={"remove", "persist"})
     * @ORM\JoinTable(name="article_has_tag",
     *      joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     */
    protected $tags;

    public function __construct()
    {
        parent::__construct();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @param MediaInterface $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    public function getMediaId()
    {
        return $this->getMedia()->getId();
    }

    /**
     * @return MediaInterface
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Article
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

  
    /**
     * Add tag
     *
     * @param \DCS\TagBundle\Model\TagInterface $tag
     * @return Post
     */
    public function addTag(\DCS\TagBundle\Model\TagInterface $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \DCS\TagBundle\Model\TagInterface $tag
     */
    public function removeTag(\DCS\TagBundle\Model\TagInterface $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }


    /**
     * Set author
     *
     * @param \YallaWebsite\BackendBundle\Entity\UserProfile $author
     *
     * @return Article
     */
    public function setAuthor(\YallaWebsite\BackendBundle\Entity\UserProfile $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \YallaWebsite\BackendBundle\Entity\UserProfile
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Article
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Article
     */
    public function setDescription($description)
    {
        $this->description = substr($description, 0, 150);;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
}
