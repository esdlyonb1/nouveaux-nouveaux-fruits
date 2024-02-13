<?php

namespace App\Controller;

use App\Entity\Fruit;
use App\Form\FruitType;
use App\Repository\FruitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FruitController extends AbstractController
{
    #[Route('/fruit', name: 'app_fruit')]
    public function index(FruitRepository $fruitRepository): Response
    {
        return $this->render('fruit/index.html.twig', [
            'fruits' => $fruitRepository->findAll(),
        ]);
    }


    #[Route('/fruit/{id}', name:"show_fruit", priority: -1)]
    public function show(Fruit $fruit):Response
    {
       // $fruit = $fruitRepository->find($id);
        return $this->render("fruit/show.html.twig", [
            "fruit"=> $fruit
        ]);
    }

    #[Route('/fruit/new', name:'new_fruit')]
    public function create(Request $request, EntityManagerInterface $manager):Response
    {
        $fruit = new Fruit();
      $form =  $this->createForm(FruitType::class, $fruit);
      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid())
      {
          $manager->persist($fruit);

          $manager->flush();

          return $this->redirectToRoute('show_fruit', ["id"=>$fruit->getId()]);
      }


        return $this->render('fruit/create.html.twig',[
            'formulaire'=>$form->createView()
        ]);
    }

    #[Route('/fruit/delete/{id}', name:'delete_fruit')]
    public function delete(Fruit $fruit, EntityManagerInterface $manager):Response
    {
        $manager->remove($fruit);
        $manager->flush();

        return $this->redirectToRoute("app_fruit");
    }


    #[Route('/fruit/edit/{id}', name:'edit_fruit')]
    public function edit(Fruit $fruit,Request $request, EntityManagerInterface $manager):Response
    {

        $form =  $this->createForm(FruitType::class, $fruit);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($fruit);

            $manager->flush();

            return $this->redirectToRoute('show_fruit', ["id"=>$fruit->getId()]);
        }


        return $this->render('fruit/create.html.twig',[
            'formulaire'=>$form->createView()
        ]);
    }
}
