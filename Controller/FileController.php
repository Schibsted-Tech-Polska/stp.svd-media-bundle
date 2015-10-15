<?php

namespace Svd\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
        $file = $this->getFile($request->files);
        $fs = new Filesystem();

        $tmpFilePath = sys_get_temp_dir() . '/' . md5(time());
        $fs->copy($file->getPathname(), $tmpFilePath);

        $tmpFile = new File($tmpFilePath);

        $response = [
            'pathname' => $tmpFile->getPathname(),
            'originalName' => $file->getClientOriginalName(),
            'originalExtension' => $file->getClientOriginalExtension(),

        ];

        return new JsonResponse($response);
    }

    /**
     * Get files
     *
     * @param FileBag $bag bag
     *
     * @return UploadedFile|null
     */
    protected function getFile(FileBag $bag)
    {
        $files = [];
        $fileBag = $bag->all();
        foreach ($fileBag as $file) {
            if (is_array($file) || null === $file) {
                continue;
            }
            $files[] = $file;
        }

        $ret = null;
        if (count($files) > 0) {
            $ret = $files[0];
        }

        return $ret;
    }
}
