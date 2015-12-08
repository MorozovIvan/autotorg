<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Auto;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Country;
use AppBundle\Entity\Region;
use AppBundle\Entity\City;

use AppBundle\Form\Type\AddAutoType;



class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {


        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }

    /**
     * @Route("/auto/list", name="auto_list")
     */
    public function autoListAction(Request $request)
    {
        $automobiles =  $this->getDoctrine()
            ->getRepository('AppBundle:Auto')
            ->findAll();

        return $this->render('default/auto_list.html.twig', ['automobiles' => $automobiles]);
    }

    /**
     * @Route("/contacts", name="contacts")
     */
    public function contactsAction()
    {
        return $this->render('default/contacts.html.twig');
    }

    /**
     * @Route("/about", name="about")
     */
    public function aboutAction()
    {
        return $this->render('default/about.html.twig');
    }

   /**
    * @Route("/auto/{id}", defaults={"id": 1}, name="auto_details", requirements={
    *     "id": "\d+"
    * })
    */
    public function autoDetailsAction(Request $request, $id)
    {
        $auto =  $this->getDoctrine()
            ->getRepository('AppBundle:Auto')
            ->find($id);

        return $this->render('default/auto_details.html.twig', ['auto' => $auto]);
    }

    /**
     * @Route("/auto/create", name="auto_create")
     */
    public function createAutoAction(Request $request)
    {
        $auto = new Auto();

        $form = $this->createForm(AddAutoType::class, $auto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $auto = $form->getData();

            $auto->setCreatedAt( new \DateTime(date('Y-m-d H:i:s')) );
            $auto->setUpdatedAt( new \DateTime(date('Y-m-d H:i:s')) );

            $file = $auto->getImage();

            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            $imageDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/auto/' . $auto->getBrand();

            $file->move($imageDir, $fileName);

            $image = new Image();

            $image->setTitle($auto->getBrand() . ' image');
            $image->setImage($fileName);
            $image->setMain('1');

            $auto->addImage($image);

            $em = $this->getDoctrine()->getManager();

            $em->persist($image);
            $em->persist($auto);

            $em->flush();

            return $this->redirectToRoute('auto_create_success', ['id' => $auto->getId()]);
        }

        return $this->render('default/auto_create_form.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/auto/success", name="auto_create_success")
     */
    public function autoSuccessAction()
    {


        return $this->render('default/auto_create_success.html.twig');
    }

    /**
     * @Route("/country/create", name="country_create")
     */
    public function createCountryAction()
    {
        $country = new Country();
        $country->setName('USA');

        $em = $this->getDoctrine()->getManager();
        $em->persist($country);

        $em->flush();

        return new Response('Created country id '.$country->getId());
    }

    /**
     * @Route("/region/create", name="region_create")
     */
    public function createRegionAction()
    {
        $country =  $this->getDoctrine()
            ->getRepository('AppBundle:Country')
            ->findOneByName("Ukraine");

        $region = new Region();
        $region->setName('Kiev area');
        $region->setCountry($country);

        $em = $this->getDoctrine()->getManager();
        $em->persist($country);
        $em->persist($region);
        $em->flush();

        return new Response('Created region id '.$region->getId());
    }

    /**
     * @Route("/city/create", name="city_create")
     */
    public function createCityAction()
    {
        $region =  $this->getDoctrine()
            ->getRepository('AppBundle:Region')
            ->findOneByName("Poltava area");

        $city = new City();
        $city->setName('Gadiach');
        $city->setRegion($region);

        $em = $this->getDoctrine()->getManager();
        $em->persist($region);
        $em->persist($city);
        $em->flush();

        return new Response('Created city id '.$city->getId());
    }
}
