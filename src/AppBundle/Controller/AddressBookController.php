<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Entity\AddressBook;
use AppBundle\Form\AddressBookFormType;
use AppBundle\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddressBookController extends Controller
{
    private const ADD = 'add';
    private const EDIT = 'edit';

    /**
     * @Route("/addressbook", name="address_book_list")
     */
    public function indexAction()
    {
        $addressBooks = $this->getDoctrine()->getRepository(AddressBook::class)->findAll();

        return $this->render('addressbook/list.html.twig', [
            'addressbooks' => $addressBooks
        ]);
    }

    /**
     * @Route("/addressbook/new", name="address_book_new")
     */
    public function newAction(Request $request, FileUploader $fileUploader)
    {
        return $this->handleAddressBookRequest($request, $fileUploader, self::ADD);
    }

    /**
     * @Route("/addressbook/{id}/edit", name="address_book_edit")
     */
    public function editAction(Request $request, AddressBook $addressBook, FileUploader $fileUploader)
    {
        return $this->handleAddressBookRequest($request, $fileUploader, self::EDIT, $addressBook);
    }

    /**
     * @Route("/addressbook/{id}/delete", name="address_book_delete")
     */
    public function deleteAction(Request $request, AddressBook $addressBook, FileUploader $fileUploader)
    {
        $fileToDelete = $addressBook->getPicture();
        $em = $this->getDoctrine()->getManager();
        $em->remove($addressBook);
        $em->flush();

        if(null !== $fileToDelete) {
            $fileUploader->delete($fileToDelete);
        }
        return new Response(null, 204);
    }

    private function handleAddressBookRequest(
        Request $request,
        FileUploader $fileUploader,
        string $action,
        ?AddressBook $addressBook = null
    ) {
        $form = $this->createForm(AddressBookFormType::class, $addressBook);
        $oldFileName = (null !== $addressBook) ? $addressBook->getPicture() : null;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $addressBook = $form->getData();
            $addressBookPic = $form->get('picture')->getData();
            if ($addressBookPic) {
                $fileName = $fileUploader->upload($addressBookPic);
                $addressBook->setPicture($fileName);
                if (null !== $oldFileName) {
                    $fileUploader->delete($oldFileName);
                }
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($addressBook);
            $em->flush();

            $this->addFlash('success', self::ADD === $action ? 'Address Book created!' : 'Address Book Updated!');

            return $this->redirectToRoute('address_book_list');
        }

        return $this->render(self::ADD === $action? 'addressbook/new.html.twig' : 'addressbook/edit.html.twig', [
            'addressBookForm' => $form->createView()
        ]);
    }
}
