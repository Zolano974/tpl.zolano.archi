<?php

namespace FirstBundle\Controller;

use FirstBundle\Entity\Reminder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * Reminder controller.
 *
 */
class ReminderController extends Controller
{

    public function displayAction($workset_id){

        $user_id = 1;

        $em = $this->getDoctrine()->getManager();

        $reminderDao = $em->getRepository('FirstBundle:Reminder');

//        $reminderDao->createReminderSet($user_id, $workset_id);

        $reminders = $reminderDao->fetchAllInMatrix($user_id, $workset_id);

//        dump($reminders);die;

        return $this->render('FirstBundle:Reminder:display.html.twig', array(
            'workset_id'=> $workset_id,
            'matrix'    => $reminders,
            'xcoords'   => array('A','B','C'),
            'ycoords'   => array(1,2,3,4),
        ));
    }

    /**
     * Lists all reminder entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $reminders = $em->getRepository('FirstBundle:Reminder')->findAll();

//        dump($reminders);die;

        return $this->render('FirstBundle:Reminder:index.html.twig', array(
            'reminders' => $reminders,
        ));
    }

    /**
     * Creates a new reminder entity.
     *
     */
    public function newAction(Request $request)
    {
        $reminder = new Reminder();
        $form = $this->createForm('FirstBundle\Form\ReminderType', $reminder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reminder);
            $em->flush($reminder);

            return $this->redirectToRoute('reminder_show', array('id' => $reminder->getId()));
        }

        return $this->render('FirstBundle:Reminder:new.html.twig', array(
            'reminder' => $reminder,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a reminder entity.
     *
     */
    public function showAction(Reminder $reminder)
    {
        $deleteForm = $this->createDeleteForm($reminder);

        return $this->render('reminder/show.html.twig', array(
            'reminder' => $reminder,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing reminder entity.
     *
     */
    public function editAction(Request $request, Reminder $reminder)
    {
        $deleteForm = $this->createDeleteForm($reminder);
        $editForm = $this->createForm('FirstBundle\Form\ReminderType', $reminder);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reminder_edit', array('id' => $reminder->getId()));
        }

        return $this->render('FirstBundle:Reminder:edit.html.twig', array(
            'reminder' => $reminder,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function setTextAction($workset_id){

        $user_id = 1;

        $request = Request::createFromGlobals();

        $message = "not an ajax requet as expected";

        if($request->isXmlHttpRequest()){

            $x = $request->request->get('x');
            $y = $request->request->get('y');
            $text = $request->request->get('text');

            $em = $this->getDoctrine()->getManager();


            $reminders =  $em->getRepository('FirstBundle:Reminder')->fetchOneInMatrix($user_id, $workset_id, $x, $y);

            if(isset($reminders[0])){
                $reminder = $reminders[0];

            $reminder->setText($text);



            $em->persist($reminder);
            $em->flush();

            $message = "text changed";
            }

        }

        $json_data= json_encode(array(
            'msg'   => $message,
        ));

        $response = new Response($json_data);

        $response->headers->set('Content-Type','application/json');

        return $response; //on utilise pas de template généralement en ajax
    }

    /**
     * Deletes a reminder entity.
     *
     */
    public function deleteAction(Request $request, Reminder $reminder)
    {
        $form = $this->createDeleteForm($reminder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reminder);
            $em->flush($reminder);
        }

        return $this->redirectToRoute('reminder_index');
    }

    /**
     * Creates a form to delete a reminder entity.
     *
     * @param Reminder $reminder The reminder entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Reminder $reminder)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reminder_delete', array('id' => $reminder->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
