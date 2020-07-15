<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ToDoAppController extends AbstractController
{
    /**
     * @Route("/toDoApp", name="home")
     */
    public function index()
    {
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findBy([], ['id'=>'DESC']);
        return $this->render('to_do_app/index.html.twig', ['tasks' => $tasks]);
    }

    /**
     * @Route("/toDoApp/create", name="create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = new Task();
        $title = $request->request->get('title');
        $title = trim($title);
        if (empty($title))
            return $this->redirectToRoute('home');
        $task->setTitle($title);
        $entityManager->persist($task);
        $entityManager->flush();
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/toDoApp/update/{id}", name="update", methods={"GET"})
     */
    public function update($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $update = $entityManager->getRepository(Task::class)->find($id);
        if (!$update) {
            throw $this->createNotFoundException('No Task found for id: ' . $id);
//                return $this->redirectToRoute('home');
        }
        $update->setStatus(! $update->getStatus());
        $entityManager->flush();
        return $this->redirectToRoute('home');

    }

    /**
     * @Route("/toDoApp/delete/{id}", name="delete", methods={"GET"})
     */
    public function delete(Task $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
//        $task = $entityManager->getRepository(Task::class)->find($id);
//        if (!$task) {
//            throw $this->createNotFoundException('No Task found for id: ' . $id);
//        }
        $entityManager->remove($id);
        $entityManager->flush();
        return $this->redirectToRoute('home');
    }
}
