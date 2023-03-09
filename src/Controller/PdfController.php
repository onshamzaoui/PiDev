<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commande;
use App\Repository\CommandeRepository;
use TCPDF;
use Doctrine\Persistence\ManagerRegistry;

class PdfController extends AbstractController
{
    #[Route("/pdf/confirmation/{id}", name: "pdf_confirmation")]
    public function pdf_confirmation(int $id, CommandeRepository $commandeRepository,ManagerRegistry $doctrine)
    {    
         $em=$doctrine->getManager();
        // Récupérer la liste des utilisateurs depuis la base de données
        $commande =$em->getRepository(Commande::class)->find($id);

        // Générer le contenu du PDF avec la liste des utilisateurs
        $html = $this->renderView('pdf/confirmation.html.twig', [
            'commande' => $commande,
        ]);


        // Récupérer l'heure actuelle
        $date = new \DateTime();
        $heure = $date->format('d/m/Y H:i:s');

        // Créer une nouvelle instance de TCPDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Définir les propriétés du document PDF
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Mon application');
        $pdf->SetTitle('Confirmation commande');
        $pdf->SetSubject('Confirmation commande');
        $pdf->SetKeywords('Confirmation commande');



        // Ajouter une page au document PDF
        $pdf->AddPage();



        // Écrire le contenu HTML dans le document PDF
        $pdf->writeHTML($html, true, false, true, false, '');



        // Ajouter l'heure en bas de la dernière page
        $pdf->SetY(260);
        $pdf->SetFont('helvetica', 'I', 12);
        $pdf->Cell(0, 10, 'Date et heure de création : ' . $heure, 0, false, 'C', 0, '', 0, false, 'T', 'M');
        // Générer le fichier PDF et l'envoyer au navigateur
        return new Response($pdf->Output('Confirmation.pdf', 'I'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="ConfirmationCommande.pdf"',
        ]);
    }
}
