<?php

namespace App\Controller;

use App\Entity\AffectationService;
use App\Form\AffectationServiceType;
use App\Repository\AffectationServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Dompdf\Dompdf;
use Dompdf\Options;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Label\Margin\Margin;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Renderer\StringRenderer;
use Endroid\QrCode\Writer\WriterInterface;
use Endroid\QrCode\Renderer\RendererInterface;
use Endroid\QrCode\Writer\WriterRegistry;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Renderer\ImageRenderer;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Response\QrCodeResponse;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
#[Route('/affectation/service')]
class AffectationServiceController extends AbstractController
{
    #[Route('/', name: 'app_affectation_service_index', methods: ['GET'])]
    public function index(AffectationServiceRepository $affectationServiceRepository): Response
    {
        return $this->render('affectation_service/index.html.twig', [
            'affectation_services' => $affectationServiceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_affectation_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AffectationServiceRepository $affectationServiceRepository): Response
    {   
        $affectationService = new AffectationService();
        $form = $this->createForm(AffectationServiceType::class, $affectationService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $affectationServiceRepository->save($affectationService, true);

            return $this->redirectToRoute('app_affectation_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('affectation_service/new.html.twig', [
            'affectation_service' => $affectationService,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_affectation_service_show', methods: ['GET'])]
    public function show(AffectationService $affectationService): Response
    {
        
        return $this->render('affectation_service/show.html.twig', [
            'affectation_service' => $affectationService,
        ])
        
        ;
    }

    #[Route('/{id}/edit', name: 'app_affectation_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AffectationService $affectationService, AffectationServiceRepository $affectationServiceRepository): Response
    {
        $form = $this->createForm(AffectationServiceType::class, $affectationService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $affectationServiceRepository->save($affectationService, true);

            return $this->redirectToRoute('app_affectation_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('affectation_service/edit.html.twig', [
            'affectation_service' => $affectationService,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_affectation_service_delete', methods: ['POST'])]
    public function delete(Request $request, AffectationService $affectationService, AffectationServiceRepository $affectationServiceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$affectationService->getId(), $request->request->get('_token'))) {
            $affectationServiceRepository->remove($affectationService, true);
        }

        return $this->redirectToRoute('app_affectation_service_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/pdf', name: 'app_affectation_service_pdf', methods: ['GET'])]
    public function pdf(Request $request, AffectationService $affectationService): Response
    {
        $session = $request->getSession();
        $membre = $session->get('user');

        // create PDF options
        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        // create PDF instance
        $dompdf = new Dompdf($options);

        // generate PDF content
        $html = $this->renderView('affectation_service/pdf.html.twig', [
            'affectation_service' => $affectationService,           
             'user' => $membre,
        ]);

        // load HTML into PDF
        $dompdf->loadHtml($html);

        // render PDF
        $dompdf->render();

        // output PDF to the browser
        $response = new Response();
        $response->headers->set('Content-Type', 'application/pdf');
        $response->setContent($dompdf->output());
        return $response;
    }
    // #[Route('/{id}/qrcode', name: 'app_affectation_service_qrcode', methods: ['GET'])]
    // public function qrcode(AffectationService $affectationService): Response

    // $qrCode = new QrCode('https://example.com/affectation_service/' . $affectationService->getId());

    // // Set advanced options
    // $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh());
    // $qrCode->setEncoding(new Encoding('UTF-8'));

    // $roundBlockSizeMode = new MarginRoundBlockSizeMode(2, 2, 0);
    
    // // Set the round block size mode on the QrCode object
    // $qrcode = new Endroid\QrCode\QrCode();
    // $qrcode->setRoundBlockSizeMode($roundBlockSizeMode);
    //     $qrCode->setValidateResult(false);
    // $qrCode->setWriterByName('png');
    // $qrCode->setMargin(new Margin(10));
    // $qrCode->setForegroundColor(new Color(0, 0, 0));
    // $qrCode->setBackgroundColor(new Color(255, 255, 255));
    // $qrCode->setLabel('Affectation Service ' . $affectationService->getId(), 16, null, LabelAlignmentCenter::class);
    // $qrCode->setLogoPath(__DIR__ . '/your-logo.png');
    // $qrCode->setLogoWidth(50);
    // $qrCode->setValidateResult(true);

    // // Stream QR code to response
    // $response = new Response($qrCode->writeString(), Response::HTTP_OK, ['Content-Type' => $qrCode->getContentType()]);

    // return $response;

   
    
    //  public function showqr($id)
    // {
    //     $affectationService = $this->getDoctrine()
    //         ->getRepository(AffectationService::class)
    //         ->find($id);

    //     if (!$affectationService) {
    //         throw $this->createNotFoundException('Service non trouvé pour l\'ID : ' . $id);
    //     }

    //     // Créer un nouvel objet Builder
    //     $builder = new Builder();

    //     // Configurer les options du code QR avec le constructeur
    //     $qrCode = $builder->data($affectationService->getNomService())
    //         ->errorCorrectionLevel('high')
    //         ->setSize(300)
    //         ->setMargin(10)
    //         ->build();

    //     // Rendre le template qrcode.html.twig avec le code QR en paramètre
    //     return $this->render('qrcode.html.twig', [
    //         'qrCodeData' => $qrCode->getContentType(),
    //     ]);
    // }
//     public function showqr(): QrCodeResponse
// {
//     // Generate a QR code with "Hello, world!" as the data
//     $qrCode = $this->get('endroid.qr_code')
//         ->setText('Hello, world!')
//         ->setSize(300)
//         ->setPadding(10)
//         ->setErrorCorrectionLevel('high')
//         ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0])
//         ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0])
//         ->setLabel('My QR code')
//         ->setLabelFontSize(16)
//         ->setImageType('png');

//     // Return a response containing the QR code image
//     return new QrCodeResponse($qrCode);
// }
// }
 /**
     * @Route("/qrcode/{id}", name="app_affectation_service_qrcode")
     */
public function showqr($id , Request $request)
    {
        $affectationService = $this->getDoctrine()
            ->getRepository(AffectationService::class)
            ->find($id);

        if (!$affectationService) {
            throw $this->createNotFoundException('Service non trouvé pour l\'ID : ' . $id);
        }

        // Créer un nouvel objet Builder
        $builder = new Builder();

        // Configurer les options du code QR avec le constructeur
        $qrCode = $builder->data($affectationService->getLieuxService())
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(300)
            ->margin(10)
            ->build();

        // Rendre le template qrcode.html.twig avec le code QR en paramètre
        // return $this->render('qrcode.html.twig', [
        //     'qrCodeData' => $qrCode->getDataUri(),
        // ]);
        $response = new Response();
        $response->headers->set('Content-Type', 'image/png');
        $response->headers->set('Content-Disposition', 'attachment; filename="qr-code.png"');
        $response->setContent($qrCode->getString());
        $redirectUrl = $this->generateUrl('app_affectation_service_index');
        $redirectResponse = new RedirectResponse($redirectUrl);
        $redirectResponse->headers->set('Content-Type', 'image/png');
        $redirectResponse->setContent($response->getContent());
        return $response;
        // return $this->render('affectation_service/qrcode.html.twig',['response'=>$response]);
        
    }
}


    
    


