<?php

namespace App\Controller;

use App\Entity\Task;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

// All routes are auto-generated for this REST-controller
// So now you can use default REST methods

class TaskController extends AbstractFOSRestController
{

    private $validator;

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

        // All VALIDATION is defined in App\Entity\Task Entity Class!!!
        // As annotations

        $task->setTitle($request->request->get('title'));
        $task->setDescription($request->request->get('description'));
        $task->setDone($request->request->get('done'));

//        return $request->request->get('done');

        $errors = $this->validator->validate($task);

        if (count($errors) > 0) {
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging.
             */
            $errorsString = (string) $errors;

            return $this->handleView($this->view($errors));
        }

        return $this->handleView($this->view($task));
    }
}
