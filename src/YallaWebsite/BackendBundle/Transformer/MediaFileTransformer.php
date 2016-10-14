<?php

namespace YallaWebsite\BackendBundle\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Application\Sonata\MediaBundle\Entity\Media;

class MediaFileTransformer implements DataTransformerInterface
{

    public function transform($mediaCollection)
    {
        //dump($mediaCollection);die();
//
//        if ($mediaCollection == null) return;
//        dump($mediaCollection);die();
////        if ($mediaCollection->isEmpty()) return;
//        
//        
//                dump($mediaCollection);die();
//        
//        $location = array();
//
//        $location['address'] = $mediaCollection->getAddress();
//        $location['telephone'] = $mediaCollection->getTelephone();
//        
//        return $location;
    }

    public function reverseTransform($fileMedia)
    {
        if (!is_array($fileMedia))
            return $fileMedia;
        if ($fileMedia['media'] == NULL)
            return null;

        foreach ($fileMedia as $uploadedFile) {


            $newmedia = new Media();
            $newmedia->setBinaryContent($uploadedFile);
            $newmedia->setProviderName('sonata.media.provider.image');
        }

        return $newmedia;
    }

}
