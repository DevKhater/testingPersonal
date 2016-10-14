<?php
/**
 * @author DevKhate <m.f.khater@gmail.com>
 * 
 */
namespace YallaWebsite\BackendBundle\Factory;

use Symfony\Component\Yaml\Parser;

class YamlManager
{

    protected $mediaManager;
    protected $parser;

    public function __construct(\Sonata\MediaBundle\Entity\MediaManager $mediaManager)
    {
        $this->mediaManager = $mediaManager;
        $this->parser = new Parser();
    }

    public function getAdv($url, $id)
    {

        try {
            $data = $this->parser->parse(file_get_contents($url));
        } catch (ParseException $e) {
            throw new ParseException('Unable to parse the YAML string:' . $e->getMessage());
        }

        try {
            return $data = $data[$id];
        } catch (\Exception $e) {
            throw new \Exception('Cannot find `' + $id + '` id in configuration:' . $e->getMessage());
        }
    }

    public function getAllAdv($url)
    {
        try {
            $data = $this->parser->parse(file_get_contents($url));
        } catch (ParseException $e) {
            throw new ParseException('Unable to parse the YAML string:' . $e->getMessage());
        }
        return $data;
    }

    public function saveAdvMedia($media, $oldMediaId)
    {
        $media->setContext('adv');
        $media->setProviderName('sonata.media.provider.image');
        $this->mediaManager->save($media);
        if ($oldMediaId != 0 && !is_null($oldMediaId)) {
            try {
                $oldImg = $this->mediaManager->find($oldMediaId);
                if ($oldImg) {$this->mediaManager->delete($oldImg);}                
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
        return $media;
    }
}
