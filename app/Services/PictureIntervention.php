<?php

namespace App\Services;

use App\Services\Contracts\PictureContract;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PictureIntervention implements PictureContract
{
    public $pathStorePictures = 'app/public/recipes/';

    public $pathPublicPictures = 'storage/recipes/';

    public $pictureFitSize = 380;

    /**Load a new picture for in storage within fit throw Intervention
     * @param $picture
     * @return string
     * @internal param Request $request
     * @internal param $file
     */
    public function save($picture):string
    {
        $filename = str_random(30) . '.' . $picture->getClientOriginalExtension();

        if (!file_exists($this->getStoragePicturePath()))
            mkdir($this->getStoragePicturePath(), 0777, true);

        Image::make($picture)->fit($this->pictureFitSize, $this->pictureFitSize)->save($this->getStoragePicturePath() . $filename);

        return asset($this->pathPublicPictures . $filename);
    }

    /**
     * Remove the picture from storage
     * return void
     * @param $picture
     * @return bool
     */
    public function delete($picture):bool
    {
        $server_name = request()->server->get('HTTP_ORIGIN') . '/storage/';
        $picture_store = preg_replace("~$server_name~", '', $picture);
        Storage::disk('public')->delete($picture_store);
        return true;
    }

    /**
     * @return string
     */
    protected function getStoragePicturePath()
    {
        return storage_path($this->pathStorePictures);
    }
}