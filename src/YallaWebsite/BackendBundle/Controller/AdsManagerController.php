<?php
/**
 * @author DevKhate<m.f.khater@gmail.com>
 * 
 */
namespace YallaWebsite\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use YallaWebsite\BackendBundle\Form\AdvTypeForm;
use Symfony\Component\Yaml\Dumper;
use YallaWebsite\BackendBundle\Transformer\MediaFileTransformer;

class AdsManagerController extends Controller
{

    public function displayAction(Request $request)
    {
        $id = $request->get('id');
        $YManager = $this->container->get('yaml_manager.manager');
        $data = $YManager->getAdv($this->container->getParameter('ads')['uri'], $id);
        return $this->render('YallaWebsiteFrontendBundle:Ads:display.html.twig', array(
                'allowed_type' => $this->container->getParameter('ads')['allowed_types'],
                'content' => $data,
                'id' => $id
        ));
    }

    public function indexAction()
    {
        $YManager = $this->container->get('yaml_manager.manager');
        $data = $YManager->getAllAdv($this->container->getParameter('ads')['uri']);
        return $this->render('YallaWebsiteBackendBundle:AdsManager:index.html.twig', array(
                'allowed_type' => $this->container->getParameter('ads')['allowed_types'],
                'content' => $data
        ));
    }

    public function changeAction($id, $index)
    {
        $YManager = $this->container->get('yaml_manager.manager');
        $data = $YManager->getAdv($this->container->getParameter('ads')['uri'], $id);
        $manager = $this->getDoctrine()->getManager();
        $createVenueForm = $this->createForm(new AdvTypeForm($manager));

        return $this->render('YallaWebsiteBackendBundle:AdsManager:edit.html.twig', array(
                'allowed_type' => $this->container->getParameter('ads')['allowed_types'],
                'content' => $data,
                'form' => $createVenueForm->createView(),
                'id' => $id,
                'index' => $index
        ));
    }

    public function modifyAction(Request $request)
    {
        $id = $request->get('id');
        if (!$id) {
            throw $this->createNotFoundException('No Advertisment Submited to Edit');
        }
        $index = $request->get('index');
        if (!$index) {
            throw $this->createNotFoundException('No Advertisment Submited to Edit');
        }
        
        
        if ($this->getRequest()->isMethod('POST')) {
            //$newmedia = MediaFileTransformer::reverseTransform($request->files->get('adve_form')['media']);
            $mt = new MediaFileTransformer;
            $newmedia = $mt->reverseTransform($request->files->get('adve_form')['media']);
            $YManager = $this->container->get('yaml_manager.manager');
            $old = $YManager->getAllAdv($this->container->getParameter('ads')['uri']);
            $YManager->saveAdvMedia($newmedia, $old[$id]['media'][$index]);
            $url = $request->request->get('adve_form')['linkfor'];
            $this->addAdvYaml($id, $newmedia,$index,$url);
            
            return new RedirectResponse($this->generateUrl('backend_ads_manager_index'));
        }
        return new RedirectResponse($this->generateUrl('backend_ads_manager_change', array('id' => $id)));
    }

    private function addAdvYaml($id, $image, $index, $adv_url)
    {
        $YManager = $this->container->get('yaml_manager.manager');
        $url = $this->container->getParameter('ads')['uri'];
        $allData = $YManager->getAllAdv($url);
        $allData[$id]['data'][$index]['link'] = $this->container->get('sonata.media.twig.extension')->path($image, $allData[$id]['format']);
        $allData[$id]['data'][$index]['target'] = $adv_url;
        $allData[$id]['data'][$index]['type'] = 'image';
        $dumper = new Dumper();
        $yaml = $dumper->dump($allData, 2);
        file_put_contents($url, $yaml);
    }
}
