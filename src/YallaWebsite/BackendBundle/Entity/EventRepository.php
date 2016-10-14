<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace YallaWebsite\BackendBundle\Entity;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{

    public function findIndexList()
    {
        return $this->getEntityManager()->createQuery(
                'SELECT e0.id as id0,
                e0.title as title1,
                e0.date as date3,
                v1.title as venuename4
                FROM YallaWebsiteBackendBundle:Event e0 
                INNER JOIN YallaWebsiteBackendBundle:Venue v1
                WITH e0.venue = v1.id'
            )->getResult();
    }

    public function findEventsBetweenDates(\DateTime $start, \DateTime $end)
    {
        return $this->getEntityManager()
                ->createQuery(
                    'SELECT a FROM YallaWebsiteBackendBundle:Event a WHERE a.startDate BETWEEN :start AND :end')
                ->setParameter('start', $start)
                ->setParameter('end', $end)
                ->getResult();
    }

    public function findEventsByDates($theDay)
    {
        $data = $this->getEntityManager()
            ->createQuery(
                'SELECT a FROM YallaWebsiteBackendBundle:Event a WHERE a.startDate LIKE :start ORDER BY a.startDate ASC')
            ->setParameter('start', $theDay);
        return $data->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT);
    }

    public function findBySlug($id)
    {
        return $this->getEntityManager()
                ->createQuery(
                    'SELECT a FROM YallaWebsiteBackendBundle:Event a WHERE a.slug = :id'
                )->setParameter('id', $id)
                ->getSingleResult();
    }

    public function getLastTen()
    {
        return $this->getEntityManager()
                ->createQuery(
                    'SELECT a FROM YallaWebsiteBackendBundle:Event a ORDER BY a.startDate DESC')
                ->setMaxResults(10)
                ->getResult();
    }
    
    public function getLastAll()
    {
        return $this->getEntityManager()
                ->createQuery(
                    'SELECT a FROM YallaWebsiteBackendBundle:Event a ORDER BY a.createdAt DESC')
                ->getResult();
    }

    public function getEventsbyDay($d)
    {
        return $this->getEntityManager()
                ->createQuery(
                    'SELECT a FROM YallaWebsiteBackendBundle:Event a WHERE DAYOFWEEK(a.startDate) = :d ORDER BY a.startDate DESC')
                ->setParameter('d', $d)
                ->setMaxResults(10)
                ->getResult();
    }

    public function getEventDaysinMonthYear($m, $y)
    {
        $eventsDays = $this->getEntityManager()
            ->createQuery(
                'SELECT DAY(a.startDate) as days, a.slug as slug FROM YallaWebsiteBackendBundle:Event a WHERE YEAR(a.startDate) = :y AND MONTH(a.startDate) = :m ORDER BY a.startDate DESC')
            ->setParameter('y', $y)
            ->setParameter('m', $m)
            ->getResult();
        $days = array();
        foreach ($eventsDays as $ent) {
            $days[$ent['days']] = $ent['slug'];
        }
        return $days;
    }

    public function getUpcomigEventsByArtist($artist)
    {
        $events = $this->getEntityManager()->createQueryBuilder()
            ->select('e')
            ->from('YallaWebsiteBackendBundle:Event', 'e')
            ->leftJoin('e.similarArtist', 'a')
            ->where('a = :artist')
            ->andWhere('e.startDate > :today')
            ->setParameter('today', new \DateTime())
            ->setParameter('artist', $artist);
        return $events->getQuery()->getResult();
    }
}
