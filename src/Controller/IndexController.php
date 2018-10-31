<?php

namespace App\Controller;

use App\Service\TrackService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        return $this->render('fixer/index.html.twig', []);
    }

    /**
     * @Route("/upload", name="upload_file")
     * @param Request $request
     * @param TrackService $trackService
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function uploadAction(Request $request, TrackService $trackService)
    {
        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
        $file = $request->files->get('track', false);

        if(!$file) {
            throw new BadRequestHttpException('Upload gpx file.');
        }

        if ($file->getClientOriginalExtension() !== 'gpx' && $file->getMimeType() !== 'text/xml') {
            throw new BadRequestHttpException('Wrong file type. Only gpx allowed.');
        }

        $fixedTrack = $trackService->handleFile($file);

        if(!$fixedTrack) {
            throw new BadRequestHttpException('Something wrong.');
        }

        return $this->file($fixedTrack, 'Fixed_' . $file->getClientOriginalName());
    }
}