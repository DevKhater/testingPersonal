<?php

namespace YallaWebsite\FrontendBundle\Model;


abstract class Homepage
{   
    /**
     * @var array
     */
    protected $sliderEntities;

    /**
     * @var string
     */
    protected $videoLink = null;

    /**
     * @var gallery
     */
    protected $selectedGallery = null;

    /**
     * @var article
     */
    protected $mainArticle = null;

    /**
     * @var array
     */
    protected $sideArticles = null;

    /**
     * @var array
     */
    protected $weekEvents = null;

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return 'Homepage_Model';
    }
}
