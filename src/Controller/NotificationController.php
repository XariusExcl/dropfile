<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/notifications", name="api_notifications_")
 */
class NotificationController extends AbstractController
{
    /**
     * @Route(methods={"GET"}, name="get_unread")
     */
    public function getNotifications(NotificationRepository $notificationRepository)
    {
        $user = $this->getUser();

        $data['notifications'] = [];
        foreach ($notificationRepository->findNotRead() as $n) {
            array_push($data['notifications'], $n->serialize);
        }
        return new JsonResponse(json_encode($data));
    }

    /**
     * @Route(methods={"POST"}, name="mark_as_read")
     * @param Notification $notification
     */
    public function markAsReadNotifications(Notification $notification): JsonResponse
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if ($notification->getUser()->getId() === $user->getId() || !is_null($user->getAdmin()))
        {
            $notification->setIsRead(true);
            $manager->flush();
        }
        return new JsonResponse(["Notification marked as read!"], 200);
    }
}
