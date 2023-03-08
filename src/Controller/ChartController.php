<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;


class ChartController extends AbstractController
{
  
    private $entityManager;
     private $chartBuilder;

    public function __construct(ChartBuilderInterface $chartBuilder,EntityManagerInterface $entityManager)
    {
        $this->chartBuilder = $chartBuilder;
        $this->entityManager = $entityManager;
    }
    #[Route('/chart', name: 'app_chart')]
    public function index(): Response
    {
            $chart = $this->chartBuilder->createChart(Chart::TYPE_DOUGHNUT);

            $data = $this->entityManager->getRepository(User::class)->CountByRole();
            // dd($data);
            $labels = array_keys($data);
            $counts = array_values($data);
    
            $chart->setData([
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Users by Role',
                        'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56'],
                        'data' => $counts,
                    ],
                ],
            ]);

        return $this->render('chart/index.html.twig', [
            'chart' => $chart,
        ]);
    }
}
