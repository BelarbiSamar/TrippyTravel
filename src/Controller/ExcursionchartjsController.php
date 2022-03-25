<?php

namespace App\Controller;

use App\Entity\Excursionreservation;
use App\Repository\ExcursioncategorieRepository;
use App\Repository\ExcursionRepository;
use App\Repository\ExcursionreservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ExcursionchartjsController extends AbstractController
{
    /**
     * @Route("/excursionchartjs", name="excursionchartjs")
     */
    public function index(ExcursioncategorieRepository $excursioncategorieRepository, ExcursionreservationRepository $dailyResultRepository,ExcursionRepository $excursionRepository, ChartBuilderInterface $chartBuilder): Response
    {

        $dailyResults = $dailyResultRepository->findAll();
        $excursions = $excursionRepository->findAll();
        $categories = $excursioncategorieRepository->findAll();

        $labels = [];
        $data = [];

        foreach ($dailyResults as $dailyResult) {
            $labels[] = $dailyResult->getCreatedAt()->format('d/m/Y');
            $data[] = $dailyResult->getPrix();
        }

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Réservation',
                    'backgroundColor' => '#16587a',
                    'borderColor' => '#16587a',
                    'data' => $data,
                ],
            ],
        ]);
        /**chart2**/
        $prix_non_paye =0;
        $prix_success =0;
        foreach ($dailyResults as $dailyResult) {
            $status = $dailyResult->getStatus();
            if ($status==Excursionreservation::RESERVATION_EXCURSION_SUCCESS){
                $prix_success += $dailyResult->getPrix();
            }
            if ($status==Excursionreservation::RESERVATION_EXCURSION_DEFAULT){
                $prix_non_paye += $dailyResult->getPrix();
            }

        }
        $chart2 = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
        $chart2->setData([
            'labels' => ['Réservation non payé','Réservation payé'],
            'datasets' => [
                [
                    'label' => 'Réservation',
                    'data' => [$prix_non_paye, $prix_success],
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)'
                    ],
                    'hoverOffset' => 4
                ],
            ],
        ]);
//chart categorie
$categorieLib = [];
$categorieCount = [];
$categorieColor = [];
        foreach ($categories as $categorie) {
            $categorieLib[] = $categorie->getLibelle();
            $categorieCount[] = count($categorie->getExcursions());
            $categorieColor[] =  "#".dechex(rand(0x000000, 0xFFFFFF)) ;
        }
//        dd($categorieColor);
        $chart3 = $chartBuilder->createChart(Chart::TYPE_PIE);
        $chart3->setData([
            'labels' => $categorieLib,
            'datasets' => [
                [
                    'label' => 'Réservation',
                    'data' => $categorieCount,
                    'backgroundColor' => $categorieColor,
                    'hoverOffset' => 4
                ],
            ],
        ]);
        $chart->setOptions([]);

        return $this->render('excursionchartjs/index.html.twig', [
            'controller_name' => 'ChartjsController',
            'chart' => $chart,
            'chart2' => $chart2,
            'chart3' => $chart3,
        ]);
    }
    function random_color_part() {
        return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    }
    function random_color() {
        return random_color_part() . random_color_part() . random_color_part();
    }
}
