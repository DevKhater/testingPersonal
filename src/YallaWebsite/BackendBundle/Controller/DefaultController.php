<?php namespace YallaWebsite\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
#use YallaWebsite\BackendBundle\Entity\Article;
#use YallaWebsite\BackendBundle\Entity\Event;
#use YallaWebsite\BackendBundle\Entity\Venue;
#use Application\Sonata\MediaBundle\Entity\Media;
#use Application\Sonata\MediaBundle\Entity\Gallery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    public function indexAction()
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        
        $curentProfile = $currentUser->getProfile();
        if (!$curentProfile && $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN_A') && !$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) { return New RedirectResponse ($this->generateUrl('backend_profile_index')); }
        $em = $this->getDoctrine()->getManager();
        $numbArt = count($em->getRepository('YallaWebsiteBackendBundle:Article')->findAll());
        $numbVen = count($em->getRepository('YallaWebsiteBackendBundle:Venue')->findAll());
        $numbEve = count($em->getRepository('YallaWebsiteBackendBundle:Event')->findAll());
        $numbUser = count($em->getRepository('YallaWebsiteBackendBundle:User')->findAll());
        $numbMemb = count($em->getRepository('YallaWebsiteBackendBundle:Member')->findAll());
        $numbArtis = count($em->getRepository('YallaWebsiteBackendBundle:Artist')->findAll());
        $numbGal = count($em->getRepository('ApplicationSonataMediaBundle:Gallery')->findAll());
        return $this->render('YallaWebsiteBackendBundle:Default:index.html.twig', array(
            'user' => $currentUser,
            'profile' => $curentProfile,
            'articles' =>$numbArt, 'venues' => $numbVen, 'events' => $numbEve, 'galleries' => $numbGal, 'artists' => $numbArtis , 'members' => $numbMemb, 'users' => $numbUser
        ));
    }

   
    public function deleteTagAction (\Symfony\Component\HttpFoundation\Request $request)
            {
        $data1 = $request->get('id');
        //$tagID = $request->get('data3');
        $gID = $request->get('g');
        
        $em = $this->getDoctrine()->getManager();
        $GP = $em->getRepository("YallaWebsiteBackendBundle:GalleryPreview")->findGallery($gID);
        dump($GP);
        $tagManager = $this->container->get('dcs_tag.manager')->add($data1);
        dump($tagManager);
        $tags = $GP[0]->getTags();
        dump($tags);
        $tags = $GP[0]->getTags();
        dump($tags);
        $GP[0]->removeTag($tagManager);
            
        $tags = $GP[0]->getTags();
        dump($tags);
        $GP[0]->removeTag($tagManager);
        $em->persist($GP[0]);
                flush();
        $tags = $GP[0]->getTags();
        dump($tags);
        
        $em->persist($GP[0]);
                $em->flush();
            return new RedirectResponse($this->generateUrl('backend_gallery_set_preview', array('id' => $gID)));
        }
    

}
