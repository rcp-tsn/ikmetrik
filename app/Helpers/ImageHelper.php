<?php

namespace App\Helpers;

use A6digital\Image\DefaultProfileImage;
use App\Models\Employee;
use App\User;
use Illuminate\Http\Request;

class ImageHelper
{
    /**
     * File upload
     *
     * @param \Illuminate\Foundation\Http\FormRequest|Request $request
     * @param string $inputName
     * @param string $subFolder
     *
     * @return null|string
     */
    public function upload($request, $inputName, $subFolder = null)
    {
        if ($request->hasFile($inputName)) {
            $file = $request->file($inputName);
            $fileExtension = $file->getClientOriginalExtension();
            if (! $subFolder) {
                $destinationPath = 'uploads/' . \Auth::user()->company_id;
            } else {
                $destinationPath = 'uploads/' . \Auth::user()->company_id . '/' . $subFolder;
            }
            $fileName = md5(time() . '-' . $file->getClientOriginalName());
            $file->move($destinationPath, $fileName. '.' . $fileExtension);

            return $destinationPath . '/' . $fileName . '.' . $fileExtension;
        }

        return null;
    }

    /**
     * image upload and model update
     *
     * @param Request $request
     * @param string $inputName
     * @param string $subFolder
     * @param $object
     * @param string $oldImage
     *
     * @return string
     */
    public function updatePicture($request, $inputName, $subFolder, $object, $oldImage)
    {
        $file = $request->file($inputName);
        if ($file) {
            $picture = $this->upload($request, $inputName, $subFolder);

            if ($picture) {
                $object->update(['picture' => $picture]);
                if ($oldImage) {
                    \File::delete($oldImage);
                }
            }

            return 'has updated';
        } else {
            $object->update(['picture' => $oldImage]);

            return 'no updates';
        }
    }

    /**
     * upload and add model pictures
     *
     * @param Request $request
     * @param string $inputName
     * @param string $subFolder
     * @param $object
     *
     * @return string
     */
    public function storePicture($request, $inputName, $subFolder, $object)
    {
        $file = $request->file($inputName);
        if ($file) {
            $picture = $this->upload($request, $inputName, $subFolder);
            if ($picture) {
                $object->update([
                    'picture' => $picture,
                ]);

                return 'added picture';
            }
        }

        return null;
    }

    /**
     * Create user logos with initials
     *
     * @param User $user
     *
     * @return bool
     */
    public function createUserImage(User $user)
    {
        $colors = [
            '#324D5C',
            '#46B29D',
            '#F0CA4D',
            '#E37B40',
            '#DE5B49'
        ];

        $slugName = str_slug($user->name);
        $imageFullPath = $user->company->id . '/user/' . $slugName . '.png';

        $randKeys = array_rand($colors, 1);

        $img = DefaultProfileImage::create($user->name, 512, $colors[$randKeys], '#FFF');

        \Storage::disk('uploads')->put($imageFullPath, $img->encode());
        $fullPath = 'uploads/' . $imageFullPath;

        $user->picture = $fullPath;
        $user->save();

        return true;
    }


    public function createCompanyImage($company)
    {
        $colors = [
            '#324D5C',
            '#46B29D',
            '#F0CA4D',
            '#E37B40',
            '#DE5B49'
        ];

        $slugName = str_slug($company->name);
        $imageFullPath = $company->id . '/company/' . $slugName . '.png';

        $randKeys = array_rand($colors, 1);

        $img = DefaultProfileImage::create($company->name, 512, $colors[$randKeys], '#FFF');

        \Storage::disk('uploads')->put($imageFullPath, $img->encode());
        $fullPath = 'uploads/' . $imageFullPath;

        $company->picture = $fullPath;
        $company->save();

        return true;
    }

    public function createEmployeeImage($employee)
    {
        $colors = [
            '#324D5C',
            '#46B29D',
            '#F0CA4D',
            '#E37B40',
            '#DE5B49'
        ];
        $slugName = str_slug($employee->first_name.' '.$employee->last_name);
        $imageFullPath = '/'.$employee->id . '/employee/' . $slugName . '.png';

        $randKeys = array_rand($colors, 1);

        $img = DefaultProfileImage::create($employee->first_name.' '. $employee->last_name, 512, $colors[$randKeys], '#FFF');

        \Storage::disk('uploads')->put($imageFullPath, $img->encode());
        $fullPath = 'uploads/' . $imageFullPath;


        $employee->avatar = $fullPath;
        $employee->save();

        return true;
    }
    public function createAnyEmployeeImage($row,$id)
    {
        $colors = [
            '#324D5C',
            '#46B29D',
            '#F0CA4D',
            '#E37B40',
            '#DE5B49'
        ];

        $first_name = explode(' ',$row[0]);
        $last_name = explode(' ',$row[1]);
        $slugName = str_slug($first_name[0].' '.$last_name[0]);

        $imageFullPath = '/'.$id . '/employee/' . $slugName . '.png';

        $randKeys = array_rand($colors, 1);

        $img = DefaultProfileImage::create($first_name[0].' '.$last_name[0], 512, $colors[$randKeys], '#FFF');

        \Storage::disk('uploads')->put($imageFullPath, $img->encode());
        $fullPath = 'uploads/' . $imageFullPath;

        $employee = Employee::find($id);
        $employee->avatar = $fullPath;
        $employee->save();

        return true;
    }
}
