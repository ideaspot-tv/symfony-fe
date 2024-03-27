<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
class TaskController extends AbstractController
{
    #[Route('/')]
    public function index(
        TaskRepository $repository,
        #[MapQueryParameter] string $query = null,
    ): Response
    {
        list($incomplete, $complete) = $repository->findForIndex($query);

        return $this->render('task/index.html.twig', [
            'incomplete' => $incomplete,
            'complete' => $complete,
            'query' => $query,
        ]);
    }

    #[Route('/new')]
    public function new(
        Request $request,
        TaskRepository $repository,
        #[MapQueryParameter] string $query = null,
    ): Response
    {
        list($incomplete, $complete) = $repository->findForIndex($query);

        $task = new Task();
        $task->setIsComplete(false);

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($task, true);

            $this->addFlash("success", "Successfully added task {$task->getSummary()}");

            return $this->redirectToRoute('app_task_index');
        }

        return $this->render('task/new.html.twig', [
            'incomplete' => $incomplete,
            'complete' => $complete,
            'query' => $query,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit')]
    public function edit(
        Task $task,
        Request $request,
        TaskRepository $repository,
        #[MapQueryParameter] string $query = null,
    ): Response
    {
        list($incomplete, $complete) = $repository->findForIndex($query);

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($task, true);

            $this->addFlash("success", "Successfully edited task {$task->getSummary()}");

            return $this->redirectToRoute('app_task_index');
        }

        return $this->render('task/new.html.twig', [
            'incomplete' => $incomplete,
            'complete' => $complete,
            'query' => $query,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete')]
    public function delete(
        Task $task,
        TaskRepository $taskRepository,
        #[MapQueryParameter] string $query = null,
    ): Response
    {
        $taskRepository->remove($task, true);
        $this->addFlash("info", "Successfully deleted task {$task->getSummary()}");

        return $this->redirectToRoute('app_task_index', [
            'query' => $query,
        ]);
    }

    #[Route('/{id}/complete/{isComplete}')]
    public function complete(
        Task $task,
        bool $isComplete,
        TaskRepository $taskRepository,
        #[MapQueryParameter] string $query = null,
    ): Response
    {
        $task->setIsComplete($isComplete);
        $taskRepository->save($task, true);
        $this->addFlash("info", "Successfully updated task {$task->getSummary()}");

        return $this->redirectToRoute('app_task_index', [
            'query' => $query,
        ]);
    }

    #[Route('/delete-completed')]
    public function deleteCompleted(
        TaskRepository $taskRepository,
        #[MapQueryParameter] string $query = null,
    ): Response
    {
        $taskRepository->removeCompleted();
        $this->addFlash("info", "Successfully deleted completed tasks");

        return $this->redirectToRoute('app_task_index', [
            'query' => $query,
        ]);
    }

}
