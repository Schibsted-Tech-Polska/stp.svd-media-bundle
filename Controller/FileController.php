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
        $files = $this->getFiles($request->files);
        $fs = new Filesystem();
        $tmpFiles = [];

        /** @var UploadedFile $file */
        foreach ($files as $file) {
            $file->getPathname();
            dump($file->getClientOriginalName());
            $tmpFilePath = sys_get_temp_dir() . '/' . md5(time());
            $fs->copy($file->getPathname(), $tmpFilePath);

            $tmpFile = new File($tmpFilePath);

            $tmpFiles[] = [
                'pathname' => $tmpFile->getPathname(),
                'originalName' => $file->getClientOriginalName(),
                'originalExtension' => $file->getClientOriginalExtension(),
            ];
        }

        return new JsonResponse($tmpFiles);
    }

    /**
     * Get files
     *
     * @param FileBag $bag bag
     *
     * @return array
     */
    protected function getFiles(FileBag $bag)
    {
        $files = array();
        $fileBag = $bag->all();
        foreach ($fileBag as $file) {
            if (is_array($file) || null === $file) {
                continue;
            }
            $files[] = $file;
        }
        return $files;
    }
}
