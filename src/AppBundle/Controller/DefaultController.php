<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Car_condition;
use AppBundle\Entity\Image;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Auto;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Country;
use AppBundle\Entity\Region;
use AppBundle\Entity\City;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;



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
        //return new Response('Created product id '.$auto->getId());

        $auto = new Auto();

        $conditions_arr = array();
        $regions_arr = array();

        $regions =  $this->getDoctrine()
            ->getRepository('AppBundle:Region')
            ->findAll();

        $conditions =  $this->getDoctrine()
            ->getRepository('AppBundle:Car_condition')
            ->findAll();

        foreach ($conditions as $condition) {
            $conditions_arr[$condition->getName()] = $condition;
        }

        foreach ($regions as $region) {
            $regions_arr[$region->getName()] = $region;
        }

        $form = $this->createFormBuilder($auto)
            ->add('brand', TextType::class)
            ->add('model', TextType::class)
            ->add('price', TextType::class)
            ->add('year', TextType::class)
            ->add('description', TextType::class)
            ->add('region', ChoiceType::class, array(
                'placeholder' => 'Choose an option',
                'choices' => $regions_arr,
            ))
            ->add('car_condition', ChoiceType::class, array(
                'placeholder' => 'Choose an option',
                'choices' => $conditions_arr,
            ))
            ->add('save', SubmitType::class, array('label' => 'Create auto'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {

//dump($request); die;
            $region =  $this->getDoctrine()
                ->getRepository('AppBundle:Region')
                ->find(4);

            $car_condition = new Car_condition();
            $car_condition->setName('old');

            $image = new Image();
            $image->setTitle('Mega image');
            $image->setImage('path/img.jpg');
            $image->setMain('1');

            $auto = new Auto();
            $auto->setBrand('Lamborgini');
            $auto->setModel('Murcielago');
            $auto->setPrice('99999999,55');
            $auto->setYear('2005');
            $auto->setDescription('Mega car, super just super');
            $auto->setRegion($region);
            $auto->setCarCondition($car_condition);
            $auto->addImage($image);
            $auto->setCreatedAt( new \DateTime(date('Y-m-d H:i:s')) );
            $auto->setUpdatedAt( new \DateTime(date('Y-m-d H:i:s')) );

            $em = $this->getDoctrine()->getManager();

            $em->persist($region);
            $em->persist($car_condition);
            $em->persist($image);
            $em->persist($auto);

            $em->flush();

            return $this->redirectToRoute('auto_create_success');
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
