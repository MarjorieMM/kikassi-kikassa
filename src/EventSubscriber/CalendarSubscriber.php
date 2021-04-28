<?php

namespace App\EventSubscriber;

use App\Repository\CalendrierRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
  private $bookingRepository;
  private $router;

  public function __construct(
    CalendrierRepository $bookingRepository,
    UrlGeneratorInterface $router
  ) {
    $this->bookingRepository = $bookingRepository;
    $this->router = $router;
  }

  public static function getSubscribedEvents()
  {
    return [
      CalendarEvents::SET_DATA => 'onCalendarSetData',
    ];
  }

  public function onCalendarSetData(CalendarEvent $calendar)
  {
    $start = $calendar->getStart();
    $end = $calendar->getEnd();
    $filters = $calendar->getFilters();


    // exemple 
    // You may want to make a custom query from your database to fill the calendar

    // $calendar->addEvent(new Event(
    //   'Event 1',
    //   new \DateTime('Tuesday this week'),
    //   new \DateTime('Wednesdays this week')
    // ));

    // // If the end date is null or not defined, it creates a all day event
    // $calendar->addEvent(new Event(
    //   'All day event',
    //   new \DateTime('Friday this week')
    // ));

    // $calendar->addEvent(new Event(
    //   'essai',
    //   new \DateTime('2021-04-29'),
    //   new \DateTime('2021-05-20'),
    // ));

    // essai 1
    // $events = $calendrier->findAll();
    // dd($events);
    // $rdvs = [];
    // foreach ($events as $event) {
    //   $calendar->addEvent(new Event(
    //     $event->getTitle(),
    //     new \DateTime($event->getstart()),
    //     new \DateTime($event->getEnd()),
    //     // $rdvs[] = [
    //     // 'id' => $event->getId(),
    //     // 'description' => $event->getDescription(),
    //     // 'backgroundColor' => $event->getBackgroundColor(),
    //     // 'borderColor' => $event->getBorderColor(),
    //     // 'textColor' => $event->getTextColor(),
    //     // 'AllWeek' => $event->getAllWeek(),
    //     // ]
    //   ));
    // }


    // essai2
    // Modify the query to fit to your entity and needs
    // Change booking.beginAt by your start date property
    $bookings = $this->bookingRepository
      ->createQueryBuilder('booking')
      ->where('booking.start BETWEEN :start and :end OR booking.end BETWEEN :start and :end')
      ->setParameter('start', $start->format('Y-m-d'))
      ->setParameter('end', $end->format('Y-m-d'))
      ->getQuery()
      ->getResult();
    foreach ($bookings as $booking) {
      // this create the events with your data (here booking data) to fill calendar
      $bookingEvent = new Event(
        $booking->getTitle(),
        $booking->getStart(),
        $booking->getEnd() // If the end date is null or not defined, a all day event is created.
      );

      /*
         * Add custom options to events
         *
         * For more information see: https://fullcalendar.io/docs/event-object
         * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
         */

      $bookingEvent->setOptions([
        'backgroundColor' => 'red',
        'borderColor' => 'red',
      ]);
      $bookingEvent->addOption(
        'url',
        $this->router->generate('calendrier_show', [
          'id' => $booking->getId(),
        ])
      );

      // finally, add the event to the CalendarEvent to fill the calendar
      $calendar->addEvent($bookingEvent);
    }
  }
}