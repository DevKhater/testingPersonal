<?php namespace YallaWebsite\FrontendBundle\EventListener;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

class CalendarEventListener
{

    private $entityManager;
    private $container;

    public function __construct(EntityManager $entityManager, Container $container)
    {
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    public function loadEvents(CalendarEvent $calendarEvent)
    {
        $startDate = $calendarEvent->getStartDatetime();
        $endDate = $calendarEvent->getEndDatetime();

        //$startDate = new \DateTime('2016-05-01');
        //$endDate = new \DateTime('2016-05-28');

        // The original request so you can get filters from the calendar
        // Use the filter in your query for example

        $request = $calendarEvent->getRequest();
        $filter = $request->get('filter');

        // load events using your custom logic here,
        // for instance, retrieving events from a repository

        $allEventsMine = $this->entityManager->getRepository('YallaWebsiteBackendBundle:Event');
        $allEvents = $allEventsMine->findEventsBetweenDates($startDate, $endDate);

        foreach ($allEvents as $allEvent) {
            if (date('Y-m-d', $allEvent->getStartDate()->getTimeStamp()) == date('Y-m-d', $allEvent->getEndDate()->getTimeStamp())) {
                $eventEntity = new EventEntity($allEvent->getTitle(), $allEvent->getStartDate(), $allEvent->getEndDate());
                $eventEntity->setAllDay(true);
            } else {
                $eventEntity = new EventEntity($allEvent->getTitle(), $allEvent->getStartDate(), null, true);
                $eventEntity->setAllDay(false);
            }
            //optional calendar event settings
             // default is false, set to true if this is an all day event
            $eventEntity->setBgColor('#FF0000'); //set the background color of the event's label
            $eventEntity->setFgColor('#FFFFFF'); //set the foreground color of the event's label
            $eventEntity->setUrl($this->container->get('router')->generate('yalla_website_frontened_event', array('id' => $allEvent->getSlug()))); // url to send user to when event label is clicked
            $eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels
            //finally, add the event to the CalendarEvent for displaying on the calendar
            $calendarEvent->addEvent($eventEntity);
        }
    }
}
