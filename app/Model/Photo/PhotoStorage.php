<?php

namespace App\Model\Photo;

use Nette\Http\FileUpload;
use Nette\Utils\Image;

class PhotoStorage
{
    /** @var string */
    private $photoFolder;

    /** @var string */
    private $wwwFolder;

    /**
     * @param string $wwwFolder
     * @param string $photoFolder
     */
    public function __construct(string $wwwFolder, string $photoFolder)
    {
        $this->wwwFolder = $wwwFolder;
        $this->photoFolder = $photoFolder;
    }

    public function storagePhoto(FileUpload $file, Photo $photo): void
    {
        $target = $this->wwwFolder . '/' . $this->photoFolder . '/' . $photo->getId();
        $file->move($target . '.' . self::getExtension($photo->getName()));

        $photoThumb = Image::fromString($file->getContents());
        $photoThumb->resize(400, null);

        $photoThumb->save($target . '_thumb.' . $this->getExtension($photo->getName()));
    }

    public static function getExtension(string $str): string
    {
        $parts = explode('.', $str);
        $count = count($parts);

        if ($count > 2) {
            return $parts[$count - 1];
        }

        return 'jpg';
    }

    public function getPhotoFolder(): string
    {
        return $this->photoFolder;
    }

    public function getWwwFolder(): string
    {
        return $this->wwwFolder;
    }

}