<?php

require __DIR__ . "/vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\EchangeProduit;
use App\Entity\EchangeService;
use App\Repository\EchangeProduitRepository;
use App\Repository\EchangeServiceRepository;

$echangeService = $echangeServiceRepository->find(['id' => $id]);
$html = $this->renderView('front_office_pages/transaction_service_validated.html.twig', [
    'id' => $id,
    'echangeService'=> $echangeService,
]);
/**
 * Set the Dompdf options
 */
$options = new Options;
$options->setChroot(__DIR__);
$options->setIsRemoteEnabled(true);

$dompdf = new Dompdf($options);

/**
 * Set the paper size and orientation
 */
$dompdf->setPaper("A4", "landscape");

$dompdf->loadHtml($html);
//$dompdf->loadHtmlFile("template.html");

/**
 * Create the PDF and set attributes
 */
$dompdf->render();

$dompdf->addInfo("Title", "An Example PDF"); // "add_info" in earlier versions of Dompdf

/**
 * Send the PDF to the browser
 */
$dompdf->stream("invoice.pdf", ["Attachment" => 0]);

/**
 * Save the PDF file locally
 */
$output = $dompdf->output();
file_put_contents("file.pdf", $output);