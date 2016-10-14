<?php namespace YallaWebsite\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AjaxController extends Controller
{

    public function getEventByDateAction(Request $request)
    {
        
        //$theJSdate = substr($request->get('id'), 0, strpos($request->get('id'), '('));
        $theJSdate = $request->get('id');
        $date = date("Y-m-d", strtotime($theJSdate));
        $theDay = $date . '%';
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('YallaWebsiteBackendBundle:Event');
        $allEvents = $entities->findEventsByDates($theDay);
        $template = $this->render('YallaWebsiteFrontendBundle:Template:eventsAjax.html.twig', array('entities' => $allEvents))->getContent();
        $json = json_encode($template);
        $response = new Response($json, 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
        
        
        //$serializedEntity = $this->container->get('serializer')->serialize($allEvents, 'json');
        //return new Response($serializedEntity);
    }
}
