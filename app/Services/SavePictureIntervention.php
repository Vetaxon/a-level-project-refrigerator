<?php

namespace App\Services;

use App\Contracts\SavePictureContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class SavePictureIntervention implements SavePictureContract
{

    public $pathStorePictures = 'app/public/recipes/';

    public $pathPublicPictures = 'storage/recipes/';

    public $pictureFitSize = 380;

    /**Load a new picture in storage within fit throw Intervention
     * @param Request $request
     * @return string
     * @internal param $file
     */
    public function save(Request $request)
    {
        $picture = $request->file('picture');

        $filename = str_random(30) . '.' . $picture->getClientOriginalExtension();

        if (!file_exists($this->getStoragePicturePath()))
            mkdir($this->getStoragePicturePath(), 0777, true);

        Image::make($picture)
            ->fit($this->pictureFitSize, $this->pictureFitSize)
            ->save($this->getStoragePicturePath() . $filename);

        return asset($this->pathPublicPictures . $filename);
    }

    /**
     * Remove the picture of specified recipe
     * return void
     * @param $picture
     */
    public function delete($picture)
    {
        $server_name = request()->server->get('HTTP_ORIGIN') . '/storage/';
        $picture_store = preg_replace("~$server_name~", '', $picture);

        Storage::disk('public')->delete($picture_store);
    }

    /**
     * @return string
     */
    protected function getStoragePicturePath()
    {
        return storage_path($this->pathStorePictures);
    }
}