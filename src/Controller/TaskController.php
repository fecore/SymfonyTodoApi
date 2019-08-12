<?php

namespace App\Controller;

use App\Entity\Task;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

// All routes are auto-generated for this REST-controller
// So now you can use default REST methods

// Validation could be done with Request Body Converter Listener
// But for more flexibility I preferred to use default validation tool
// with some auto-resolving.

class TaskController extends AbstractFOSRestController
{

    private $validator;

    // Inject ValidatorInterface in constructor
    // Because of route ---> type=rest (auto method discovery)
    // Can't find needed method when too many classes are injected in controller methods


    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function getTasksAction()
    {
        $repository = $this->getDoctrine()->getRepository(Task::class);
        $tasks = $repository->findall();
        return $this->handleView($this->view($tasks));
    }

    public function postTasksAction(Request $request)
    {
        $task = new Task();

        // All VALIDATION-RULES are defined in App\Entity\Task Entity Class!!!
        // As annotations
        // created_at and updated are automatically generating

        $entityManager = $this->getDoctrine()->getManager();

        $task->setTitle($request->request->get('title'));
        $task->setDescription($request->request->get('description'));
        $task->setDone($request->request->get('done'));

        $errors = $this->validator->validate($task);

        if (count($errors) > 0) {
            return $this->handleView($this->view($errors, Response::HTTP_BAD_REQUEST));
        }

        $entityManager->persist($task);
        $entityManager->flush();

        // Return success
        return $this->handleView($this->view(
            [
                "status_code"=> Response::HTTP_CREATED,
                'reason_phrase' => 'Successfully created',
            ],
            Response::HTTP_CREATED)
        );
    }

    public function getTaskAction(Task $task)
    {
        // Task entity was type-hinted
        // If not found will returns 404 exception automatically

        // Return Task-entity object
        return $this->handleView($this->view($task));
    }

    public function putTaskAction(Task $task, Request $request)
    {
        // Task entity was type-hinted
        // If not found will returns 404 exception automatically

        // All VALIDATION-RULES are defined in App\Entity\Task Entity Class!!!
        // As annotations
        // created_at and updated are automatically generating

        // Getting request form data (method PUT)
        $data = json_decode($request->getContent(), true);

        // If empty request
        if (empty($data))
        {
            return $this->handleView($this->view(
                [
                    "status_code"=> Response::HTTP_BAD_REQUEST,
                    'reason_phrase' => 'At least one property must be updated',
                ],
                Response::HTTP_BAD_REQUEST)
            );
        }

        $entityManager = $this->getDoctrine()->getManager();

        // If property = null
        // Or is emtpy
        // Then replace with old property


        $task->setTitle($data['title'] ?? $task->getTitle());
        $task->setDescription($data['description'] ?? $task->getDescription());
        $task->setDone($data['done'] ?? $task->getDone());

        $errors = $this->validator->validate($task);


        if (count($errors) > 0) {
            return $this->handleView($this->view($errors, Response::HTTP_BAD_REQUEST));
        }

        // Update
        $entityManager->flush();

        // Return success
        return $this->handleView($this->view(
            [
                "status_code"=> Response::HTTP_ACCEPTED,
                'reason_phrase' => 'Successfully updated',
            ],
            Response::HTTP_ACCEPTED)
        );

    }

    public function deleteTaskAction(Task $task)
    {
        // Task entity was type-hinted
        // If not found will return 404 exception automatically

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($task);
        $entityManager->flush();

        // Return success
        return $this->handleView($this->view(
            [
                "status_code"=> Response::HTTP_ACCEPTED,
                'reason_phrase' => 'Successfully deleted',
            ],
            Response::HTTP_ACCEPTED)
        );
    }
}
