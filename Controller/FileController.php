<?php

namespace Svd\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\FileBag;
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
     */
    public function uploadAction(Request $request)
    {
        $files = $this->getFiles($request->files);
        $fs = new Filesystem();

        /** @var UploadedFile $file */
        foreach ($files as $file) {
            $file->getPathname();
            $tmpFilePath = sys_get_temp_dir() . '/' . md5(time());
            $fs->copy($file->getPathname(), $tmpFilePath);
        }

        die;
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
