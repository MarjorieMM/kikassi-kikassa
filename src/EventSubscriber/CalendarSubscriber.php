<?php

namespace App\EventSubscriber;

use App\Entity\Objet;
use CalendarBundle\Entity\Event;
use CalendarBundle\CalendarEvents;
// use App\Repository\HoraireLieuRepository;
use App\Repository\ObjetRepository;
use App\Repository\EmpruntRepository;
use CalendarBundle\Event\CalendarEvent;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    // private $ouvertureRepository;
    private $bookingRepository;
    private $router;
    private $request;
    private $objet;

    public function __construct(
        // HoraireLieuRepository $ouvertureRepository,
        ObjetRepository $objetRepo,
        EmpruntRepository $bookingRepository,
        UrlGeneratorInterface $router
    ) {
        // $this->ouvertureRepository = $ouvertureRepository;
        $this->objetRepo = $objetRepo;
        $this->bookingRepository = $bookingRepository;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar, $router)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        // lien entre la bdd par le repo/entity emprunt
        // Modify the query to fit to your entity and needs
        // Change booking.beginAt by your start date property
        $objet = $this->objetRepo->findOneById($filters);
        $objet_id = $objet->getId();
        $bookings = $this->bookingRepository
            ->createQueryBuilder('booking')
            ->where(
                'booking.date_debut BETWEEN :start and :end OR booking.date_fin BETWEEN :start and :end'
            )
            ->andWhere('booking.objet = ' . $objet_id)
            ->setParameter('start', $start->format('Y-m-d'))
            ->setParameter('end', $end->format('Y-m-d'))
            ->getQuery()
            ->getResult();
        foreach ($bookings as $booking) {
            dump($bookings);
            $bookingEvent = new Event(
                'Réservé',
                // $booking->getTitre(),
                $booking->getDateDebut(),
                $booking->getDateFin()
            );

            $bookingEvent->setOptions([
                'backgroundColor' => '#5c995e',
                'borderColor' => '#5c995e',
            ]);
            $bookingEvent->addOption(
                'url',
                $this->router->generate('emprunt_show', [
                    'id' => $booking->getId(),
                ])
            );

            $calendar->addEvent($bookingEvent);
        }

        // // background sans bdd
        // $calendar->addEvent(new Event(
        //   'Event 1',
        //   new \DateTime('Wednesday this week'),
        //   new \DateTime('Saturday this week'),
        //   [
        //     // 'display' => 'background',
        //     'daysOfWeek' => ["4"],
        //     'backgroundColor' => '#5c995e',
        //   ]
        // ));

        //   // lien entre la bdd par le repo/entity HoraireLieu
        //   // Modify the query to fit to your entity and needs
        //   // Change booking.beginAt by your start date property
        //   $ouvertures = $this->ouvertureRepository
        //     // ->createQueryBuilder('ouverture')
        //     // ->where('ouverture.jour BETWEEN :start and :end OR ouverture.jour BETWEEN :start and :end')
        //     // ->setParameter('start', $start->format('l'))
        //     // ->setParameter('end', $end->format('l'))
        //     // ->getQuery()
        //     // ->getResult()
        //   ;
        //   foreach ($ouvertures as $ouverture) {
        //     // this create the events with your data (here booking data) to fill calendar
        //     $ouvertureEvent = new Event(
        //       "Réservé",
        //       new \DateTime('Wednesday this week'),
        //       // $ouverture->getDateFin() // If the end date is null or not defined, a all day event is created.
        //     );

        //     /*
        //        * Add custom options to events
        //        *
        //        * For more information see: https://fullcalendar.io/docs/event-object
        //        * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
        //        */

        //     $ouvertureEvent->setOptions([
        //       'backgroundColor' => '#5c995e',
        //       'borderColor' => '#5c995e',
        //       // 'display' => 'background',
        //       'daysOfWeek' => ["4"],
        //       // 'daysOfWeek' => ["$ouverture->getJour()"],
        //     ]);
        //     $ouvertureEvent->addOption(
        //       'url',
        //       $this->router->generate('emprunt_show', [
        //         'id' => $ouverture->getId(),
        //       ])
        //     );

        //     // finally, add the event to the CalendarEvent to fill the calendar
        //     $calendar->addEvent($ouvertureEvent);
        //   }
    }
}