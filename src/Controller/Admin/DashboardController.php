<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Entity\Don;
use App\Entity\Produit;
use App\Entity\Recyclage;
use App\Entity\Speciality;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\Crud\UserCrudController;
use App\Controller\Admin\Crud\CategorieCrudController;
use App\Controller\Admin\Crud\ProduitCrudController;
use App\Controller\Admin\Crud\SpecialityCrudController;
use App\Controller\Admin\Crud\DonCrudController;
use App\Controller\Admin\Crud\RecyclageCrudController;
use App\Controller\Admin\Crud\ProfileCrudController;
use App\Controller\ChartController;
use App\Entity\AffectationService;
use App\Entity\Category;
use App\Entity\Commande;
use App\Entity\Event;
use App\Entity\Facture;
use App\Entity\Service;
use App\Entity\TypeDechet;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProfileFormType;
use League\Csv\Writer;



class DashboardController extends AbstractDashboardController
{
    private $adminUrlGenerator;
    private $chartBuilder;

    public function __construct(AdminUrlGenerator $adminUrlGenerator,ChartBuilderInterface $chartBuilder)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->chartBuilder = $chartBuilder;
     
        
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        
        $chart = $this->adminUrlGenerator->setRoute('app_chart')->generateUrl();
        $chartDon = $this->adminUrlGenerator->setRoute('app_chart_don')->generateUrl();
        $export = $this->adminUrlGenerator->setRoute('don_export_csv')->generateUrl();

        $userUrl = $this->adminUrlGenerator->setController(UserCrudController::class)->generateUrl();
        $categoryUrl = $this->adminUrlGenerator->setController(CategorieCrudController::class)->generateUrl();
        $productUrl = $this->adminUrlGenerator->setController(ProduitCrudController::class)->generateUrl();
        $specialtyUrl = $this->adminUrlGenerator->setController(SpecialityCrudController::class)->generateUrl();
        $donUrl = $this->adminUrlGenerator->setController(DonCrudController::class)->generateUrl();
        $recyclageUrl = $this->adminUrlGenerator->setController(RecyclageCrudController::class)->generateUrl();
        // $profileUrl = $this->adminUrlGenerator->setRoute('app_profile')->generateUrl();
        $profileUrl = $this->adminUrlGenerator->setController(ProfileController::class)->generateUrl();
        $serviceUrl = $this->adminUrlGenerator->setController(ServiceCrudController::class)->generateUrl();
        $AffectationserviceUrl = $this->adminUrlGenerator->setController(AffectationServiceCrudController::class)->generateUrl();
        $eventUrl = $this->adminUrlGenerator->setController(EventCrudController::class)->generateUrl();
        $categoryUrl = $this->adminUrlGenerator->setController(CategoryCrudController::class)->generateUrl();
        $TypeDechetUrl = $this->adminUrlGenerator->setController(TypeDechetCrudController::class)->generateUrl();
        $FactureUrl = $this->adminUrlGenerator->setController(FactureCrudController::class)->generateUrl();
        $CommandeUrl = $this->adminUrlGenerator->setController(CommandeCrudController::class)->generateUrl();








        
        return $this->render('admin/dashboard.html.twig', [
            'userUrl' => $userUrl,
            'categoryUrl' => $categoryUrl,
            'productUrl' => $productUrl,
            'specialtyUrl' => $specialtyUrl,
            'donUrl' => $donUrl,
            'recyclageUrl' => $recyclageUrl,
            'profileUrl' => $profileUrl,
            'chart'=>$chart,
            'chartDon'=>$chartDon,
            'serviceUrl'=>$serviceUrl,
            'AffectationserviceUrl'=>$AffectationserviceUrl,
            'categoryUrl'=>$categoryUrl,
            'eventUrl'=>$eventUrl,
            'TypeDechetUrl'=>$TypeDechetUrl,
            'export'=>$export,
            'FactureUrl'=>$FactureUrl,
            'CommandeUrl'=>$CommandeUrl,
        ]);
    }
 

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('GreenIT');
    }

    public function configureMenuItems(): iterable
    {
        // $profileUrl = $this->adminUrlGenerator->setController(ProfileCrudController::class)->generateUrl();

        return [
            MenuItem::linkToDashboard('Dashboard'),

            MenuItem::section('Products Managment'),
            MenuItem::linkToCrud('Product', 'fa fa-product-hunt', Produit::class),
            MenuItem::linkToCrud('Category', 'fa fa-list', Categorie::class),

    
            MenuItem::section('Users Managment'),
            MenuItem::linkToCrud('User', 'fa fa-user', User::class),
            MenuItem::linkToCrud('Specialty', '	fas fa-graduation-cap', Speciality::class),
            MenuItem::linkToRoute('Statics', 'fa fa-pie-chart', 'app_chart'),


            MenuItem::section('Recyclage Management'),
            MenuItem::linkToCrud('Recyclage', 'fa fa-recycle', Recyclage::class),
            MenuItem::linkToCrud('Don', 'fas fa-thumbs-up', Don::class),
            MenuItem::linkToCrud('TypeDechet', 'fas fa-cart-plus', TypeDechet::class),
            MenuItem::linkToRoute('ExportCSV', 'fas fa-clipboard-list', 'don_export_csv'),
            MenuItem::linkToRoute('Statics', 'fa fa-bar-chart', 'app_chart_don'),




            MenuItem::section('settings'),
            // MenuItem::linkTo
            // MenuItem::linkToCrud('Profile', 'fa fa-recycle', $profileUrl),
            
            // MenuItem::linkToRoute('Profile', 'fa fa-user', 'app_profile'),
            MenuItem::linkToRoute('Profile', 'fa fa-gear', 'app_profile'),
            // MenuItem::linkToRoute('Lougout', 'fa fa-gear', '/logout'),

            MenuItem::section('Services Management'),
            MenuItem::linkToCrud('Service', 'fas fa-leaf', Service::class),
            MenuItem::linkToCrud('AffectationService', 'fa fa-plus-square', AffectationService::class),

            MenuItem::section('Event Management'),
            MenuItem::linkToCrud('Event', '	fas fa-poll', Event::class),
            MenuItem::linkToCrud('Category', 'fas fa-poll-h', Category::class),
            
            MenuItem::section('Payment Management'),
            MenuItem::linkToCrud('Factures', 'fas fa-money-check-alt', Facture::class),
            MenuItem::linkToCrud('Commandes', 'fas fa-receipt', Commande::class),






        ];
    }
}








   // #[Route('/admin/profile', name: 'app_admin_profile')]
    // public function profile(Request $request): Response
    // {
    //     $user = $this->getUser();

    //     $form = $this->createForm(ProfileFormType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $this->getDoctrine()->getManager()->flush();

    //         return $this->redirectToRoute('app_admin');
    //     }

    //     return $this->render('/admin/index.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }








// // use App\Entity\Categorie;
// // use App\Entity\Produit;
// // use App\Entity\User;
// // use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
// // use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
// // use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
// // use Symfony\Component\HttpFoundation\Response;
// // use Symfony\Component\Routing\Annotation\Route;
// // use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
// use App\Entity\Categorie;
// use App\Entity\Produit;
// use App\Entity\User;
// use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
// use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
// use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;
// use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;



// class DashboardController extends AbstractDashboardController
// {
//     private $adminUrlGenerator;

//     public function __construct(AdminUrlGenerator $adminUrlGenerator)
//     {
//         $this->adminUrlGenerator = $adminUrlGenerator;
//     }

//     #[Route('/admin', name: 'app_admin')]
//     public function index(): Response
//     {

//         $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

//         return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
//         // Option 1. You can make your dashboard redirect to some common page of your backend
//         //
//         // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
//         // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

//         // Option 2. You can make your dashboard redirect to different pages depending on the user
//         //
//         // if ('jane' === $this->getUser()->getUsername()) {
//         //     return $this->redirect('...');
//         // }

//         // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
//         // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
//         //
//         // return $this->render('some/path/my-dashboard.html.twig');
//     }

//     public function configureDashboard(): Dashboard
//     {
//         return Dashboard::new()
//             ->setTitle('GreenIT');
//             // ->setTranslationDomain('admin');

//     }

//     public function configureMenuItems(): iterable
//     {
//         // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
//         // yield MenuItem::linkToCrud('Products', 'fas fa-shop', Produit::class);
//         // yield MenuItem::linkToCrud('Users', 'fas fa-list', User::class);
//         // yield MenuItem::linkToCrud('Categories', 'fas fa-list', Categorie::class);
//       return[ 
//          MenuItem::linktoDashboard('Dashboard', 'fa fa-home'),
//          MenuItem::section('Products'),
//          MenuItem::linkToCrud('Product', 'fa fa-product-hunt', Produit::class),
//          MenuItem::linkToCrud('Category', 'fa fa-list', Categorie::class),
//          MenuItem::section('Users'),
//          MenuItem::linkToCrud('User', 'fa fa-user', User::class),
//          MenuItem::linkToCrud('Role', 'fa fa-lock', Role::class),

//     ];
//     }
// }
