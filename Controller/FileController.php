<?php

namespace Svd\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Svd\MediaBundle\Entity\File as FileEntity;

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
        $em = $this->get('doctrine')
            ->getManager();
        $file = $this->getFile($request->files);
        $fs = new Filesystem();

        $tmpFilePath = sys_get_temp_dir() . '/' . $this->generateName($file);
        $fs->copy($file->getPathname(), $tmpFilePath);

        $tmpFile = new File($tmpFilePath);
        $newFile = new FileEntity();
        $newFile->setFilename($tmpFile->getFilename());
        $newFile->setStatus(FileEntity::STATUS_WAITING);
        $newFile->setMimeType($tmpFile->getMimeType());
        $newFile->setSize($tmpFile->getSize());
        $newFile->setUsagesCount(0);

        $em->persist($newFile);
        $em->flush();

        $response = [
            'id' => $newFile->getId(),
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

    /**
     * Generate name
     *
     * @param UploadedFile $file file
     *
     * @return string
     */
    protected function generateName(UploadedFile $file)
    {
        $filename = md5(microtime());
        $filename .= '.' . $file->getClientOriginalExtension();

        return $filename;
    }
}
