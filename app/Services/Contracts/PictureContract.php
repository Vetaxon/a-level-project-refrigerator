<?php

namespace App\Services\Contracts;

/**Contract for saving or deleting pictures
 * Interface PictureContract
 * @package App\Services\Contracts
 */


interface PictureContract
{
    public function save($file):string;

    public function delete($file):bool;
    
}