<?php

namespace App\Controller;

use App\Entity\Event as EventEntity;
use App\Service\EventReplay;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class EventController extends Controller
{
    /**
     * @Route("/events", name="event_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $events = $this->getDoctrine()->getRepository(EventEntity::class)->findBy([], ['date' => 'asc']);
        return $this->render('event/index.html.twig', ['events' => $events]);
    }

    /**
     * @Route("/events/replay", name="event_replay")
     * @param Request $request
     * @param EventReplay $eventReplay
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function replayAction(Request $request, EventReplay $eventReplay)
    {
        $data = ['date' => new \DateTime()];
        $form = $this->createFormBuilder($data)
            ->add('date', DateTimeType::class, ['label' => 'Replay until', 'with_seconds' => true])
            ->add('replay', SubmitType::class, ['label' => 'Replay'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventReplay->replayUntil($form->getData()['date']);

            return $this->redirectToRoute('event_replay');
        }


        return $this->render('event/replay.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}