<?php

namespace Svd\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller
 */
class FileController extends Controller
{
    /**
     * Upload action
     *
     * @param Request $request request
     *
     * @return JsonResponse
     */
    public function uploadAction(Request $request)
    {
        $fileManager = $this->get('svd_media.manager.file');
        $response = $fileManager->saveFile($request->files);

        return new JsonResponse($response);
    }
}
