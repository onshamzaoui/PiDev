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

class DashboardController extends AbstractDashboardController
{
    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        // return $this->redirect($this->adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
        $userUrl = $this->adminUrlGenerator->setController(UserCrudController::class)->generateUrl();
        $categoryUrl = $this->adminUrlGenerator->setController(CategorieCrudController::class)->generateUrl();
        $productUrl = $this->adminUrlGenerator->setController(ProduitCrudController::class)->generateUrl();
        $specialtyUrl = $this->adminUrlGenerator->setController(SpecialityCrudController::class)->generateUrl();
        $donUrl = $this->adminUrlGenerator->setController(DonCrudController::class)->generateUrl();
        $recyclageUrl = $this->adminUrlGenerator->setController(RecyclageCrudController::class)->generateUrl();

        
        return $this->render('admin/dashboard.html.twig', [
            'userUrl' => $userUrl,
            'categoryUrl' => $categoryUrl,
            'productUrl' => $productUrl,
            'specialtyUrl' => $specialtyUrl,
            'donUrl' => $donUrl,
            'recyclageUrl' => $recyclageUrl,

        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('GreenIT');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('Products'),
            MenuItem::linkToCrud('Product', 'fa fa-product-hunt', Produit::class),
            MenuItem::linkToCrud('Category', 'fa fa-list', Categorie::class),
    
            MenuItem::section('Users'),
            MenuItem::linkToCrud('User', 'fa fa-user', User::class),
            MenuItem::linkToCrud('Specialty', '	fas fa-graduation-cap', Speciality::class),

            MenuItem::section('Recyclage'),
            MenuItem::linkToCrud('Recyclage', 'fa fa-recycle', Recyclage::class),
            MenuItem::linkToCrud('Don', 'fas fa-thumbs-up', Don::class),

        ];
    }
}

















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