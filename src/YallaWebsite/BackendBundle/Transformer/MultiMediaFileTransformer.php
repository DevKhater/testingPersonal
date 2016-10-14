<?php

namespace YallaWebsite\BackendBundle\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Application\Sonata\MediaBundle\Entity\Media;

class MultiMediaFileTransformer implements DataTransformerInterface
{

    public function transform($mediaCollection)
    {
        
    }

    public function reverseTransform($fileMedia)
    {
        if (!is_array($fileMedia))
            return $fileMedia;
        if ($fileMedia['media'] == NULL)
            return $fileMedia;
        $Images = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($fileMedia['media'] as $uploadedFile) {
            $newmedia = new Media();
            $newmedia->setBinaryContent($uploadedFile);
            $newmedia->setProviderName('sonata.media.provider.image');

            $Images[] = $newmedia;
        }

        return $Images;
    }

}
