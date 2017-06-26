<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Offer;
use AppBundle\Form\Offer\CreateOfferType;
use AppBundle\Response\ErrorResponse;
use AppBundle\Response\SuccessResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class OfferController
 * @package AppBundle\Controller
 */
class OfferController extends Controller
{
    const LIST_LIMIT = 5;

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $dql = <<<SQL
SELECT o FROM AppBundle:Offer o
SQL;
        $query = $em->createQuery($dql);

        //Knp paginate
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), self::LIST_LIMIT);

        return $this->render('full/Offer/list.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @param $id
     *
     * @return Response
     */
    public function itemAction($id)
    {
        $offerRepository = $this->getDoctrine()->getRepository(Offer::class);
        $offer = $offerRepository->findOneBy(['id' => $id]);

        if (!$offer instanceof Offer) {
            throw new NotFoundHttpException();
        }

        return $this->render('full/Offer/item.html.twig', [
            'content' => $offer
        ]);
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $Offer = new Offer();
        $form = $this->createForm(CreateOfferType::class, $Offer);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $Offer->setCreateAuthor($this->getUser());

                $em = $this->getDoctrine()->getManager();
                $em->persist($Offer);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Oferta została dodana!');

                return $this->redirect($this->generateUrl('offer_create'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'Uzupełnij poprawnie wszystkie wymagane pola!');
            }
        }

        return $this->render('full/Offer/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     *
     * @return ErrorResponse|SuccessResponse
     */
    public function removeAction(Request $request)
    {
        $id = $request->request->get('id');

        $offerRepository = $this->getDoctrine()->getRepository(Offer::class);
        $offer = $offerRepository->findOneBy(['id' => $id]);

        if (!$offer instanceof offer) {
            return new ErrorResponse(['message' => 'Oferta o takim ID nie istnieje!']);
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($offer);
            $em->flush();
        } catch (\Exception $e) {
            return new ErrorResponse(['message' => 'Wystąpił błąd podczas usuwania spróbuj ponownie!']);
        }

        return new SuccessResponse(['message' => 'Oferta zostałą usunięta!']);
    }
}
