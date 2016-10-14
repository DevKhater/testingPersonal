<?php
namespace YallaWebsite\BackendBundle\Entity;

use DCS\TagBundle\Entity\Tag as BaseTag;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tag")
 */
class Tag extends BaseTag
{

}
