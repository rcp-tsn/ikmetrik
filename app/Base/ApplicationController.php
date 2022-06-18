<?php
namespace App\Base;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\SelectLists;

abstract class ApplicationController extends Controller
{

    use SelectLists;
    public $user = '';

    /**
     * Model name
     *
     * @var string
     */
    protected $model = '';

    /**
     * @var string
     */
    protected $customResource = false;

    /**
     * Are you encrypted for id?
     *
     * @var bool
     */
    protected $hashId = false;

    /**
     * ApplicationController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            return $next($request);
        });

        $this->model = str_singular($this->getModel());
    }

    /**
     * Returns view path for the admin
     *
     * @param string $path
     * @param bool|false $object
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function viewPath($path = 'index', $object = false)
    {
        $path = str_plural(snake_case($this->model)) . '.' . $path;

        if ($object !== false) {
            return view($path, compact('object'));
        } else {
            return $path;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $modelClassName = 'App\\Models\\' . $this->model;

        $data = [
            snake_case($this->model) => new $modelClassName,
            camel_case($this->model) => new $modelClassName,
        ];

        $createResource = 'standards';
        if ($this->customResource) {
            $createResource = snake_case(str_plural($this->model));
        }

        return view($createResource . '.create', $data);
    }

    /**
     * Create, flash success or error then redirect
     *
     * @param $class
     * @param $request
     * @param string $path
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function createFlashRedirect($class, $request, $path = 'index')
    {
        $request->offsetUnset('_token');

        $model = $class::create($request->all());

        $model->id ? session()->flash('success', 'Kayıt başarılı bir şekilde oluşturuldu.') : session()->flash('danger', "Kayıt oluşturulamadı. Kontrol ederek tekrar deneyiniz.");

        return $this->redirectRoutePath($path);
    }

    /**
     * Save, flash success or error then redirect
     *
     * @param $model
     * @param $request
     * @param string $path
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveFlashRedirect($model, $request, $path = 'index')
    {
        $model->fill($request->all());
        $model->save() ? session()->flash('success', 'Kayıt başarılı bir şekilde güncellendi.') : session()->flash('danger', "Kayıt güncelleştirilemedi. Kontrol ederek tekrar deneyiniz.");

        return $this->redirectRoutePath($path, null, $model);
    }

    /**
     * Delete and flash success or fail then redirect to path
     *
     * @param $model
     * @param string $imageColumn
     * @param string $path
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroyFlashRedirect($model, $imageColumn = null, $path = 'index')
    {
        if ($model->$imageColumn) {
            \File::delete($model->$imageColumn);
        }

        $model->delete() ? session()->flash('success', 'Kayıt başarılı bir şekilde silindi.') : session()->flash('danger', "Kayıt silinemedi. Kontrol ederek tekrar deneyiniz.");
        return $this->redirectRoutePath($path);
    }

    /**
     * Archived and flash success then redirect to path
     *
     * @param Model $model
     * @param null|string $returnUrl
     * @param string $response
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function archivedFlashRedirect(Model $model, $returnUrl = null, $response = 'json')
    {
        $model->update([
            'is_archived' => 1
        ]);

        if (! $returnUrl) {
            $returnUrl = $this->urlRoutePath('index', $model);
        }

        Alert::success('Kayıt başarılı bir şekilde arşivlendi.', 'Başarılı');

        if ($response === 'json') {
            return response()->json([
                'redirect' => $returnUrl
            ]);
        } else {
            return redirect($returnUrl);
        }
    }

    /**
     * Published and flash success then redirect to path
     *
     * @param Model $model
     * @param null|string $returnUrl
     * @param string $response
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function publishedFlashRedirect(Model $model, $returnUrl = null, $response = 'json')
    {
        $model->update([
            'is_archived' => 0
        ]);

        if (! $returnUrl) {
            $returnUrl = $this->urlRoutePath('index', $model);
        }

        Alert::success('Kayıt başarılı bir şekilde yayınlandı.', 'Başarılı');

        if ($response === 'json') {
            return response()->json([
                'redirect' => $returnUrl
            ]);
        } else {
            return redirect($returnUrl);
        }
    }

    /**
     * Flash message redirect to url
     *
     * @param $url
     * @param string $type
     * @param null $message
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function flashRedirect($url = null, $type = 'success', $message = null)
    {
        if ($type == 'success') {
            session()->flash('success', $message ? $message : 'İşlem başarılı bir şekilde gerçekleştirildi.');
        } else {
            session()->flash('danger', $message ? $message : "İşlem sırasında hata oluştu. Kontrol ederek tekrar deneyiniz.");
        }


        if (! $url) {
            return redirect()->back();
        }

        return redirect($url);

    }

    /**
     * Flash message redirect to ajax
     *
     * @param $url
     * @param string $type
     * @param null $message
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function flashAjaxRedirect($url = null, $type = 'success', $message = null)
    {
        if ($type == 'success') {
            $message = $message ?? 'İşlem başarılı bir şekilde gerçekleştirildi.';
            $messageTitle = 'Başarılı';
        } else {
            $message = $message ?? 'İşlem sırasında hata oluştu.';
            $messageTitle = 'Başarısız';
        }

        Alert::$type($message, $messageTitle);

        if (! $url) {
            return response()->json([
                'status' => $type,
                'redirect' => route('root'),
                'redirect_url' => route('root'),
            ]);
        }

        return response()->json([
            'status' => $type,
            'redirect' => $url,
            'redirect_url' => $url,
        ]);
    }

    /**
     * Returns route path as string
     *
     * @param string $path
     * @return string
     */
    public function routePath($path = 'index')
    {
        return str_plural(snake_case(str_replace('-', '_',$this->model))) . '.' . $path;
    }

    /**
     * Returns full url
     *
     * @param string $path
     * @param bool|false $model
     * @return string
     */
    protected function urlRoutePath($path = 'index', $model = false)
    {
        if ($model) {
            $currentId = $this->hashId ? createHashId($model->id) : $model->id;
            if ($path == 'index') {
                return route($this->routePath($path));
            }

            return route($this->routePath($path), ['id' => $currentId]);
        } else {
            return route($this->routePath($path));
        }
    }

    /**
     * Returns redirect url path, if error is passed, show it
     *
     * @param string $path
     * @param null $error
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirectRoutePath($path = 'index', $error = null, $model = false)
    {
        if ($error) {
            Alert::error($error, 'Başarısız');
        }
        return redirect($this->urlRoutePath($path, $model));
    }

    /**
     * Get model name, if isset the model parameter, then get it, if not then get the class name, strip "Controller" out
     *
     * @return string
     */
    protected function getModel()
    {
        return empty($this->model) ?
            explode('Controller', substr(strrchr(get_class($this), '\\'), 1))[0]  :
            $this->model;
    }

    /**
     * image upload and model update
     *
     * @param $request
     * @param string $inputName
     * @param string $subFolder
     * @param $object
     * @param string $oldImage
     *
     * @return string
     */
    protected function updatePicture($request, $inputName, $subFolder, $object, $oldImage)
    {
        $imageHelper = new ImageHelper;

        return $imageHelper->updatePicture($request, $inputName, $subFolder, $object, $oldImage);
    }

    /**
     * upload and add model pictures
     *
     * @param $request
     * @param string $inputName
     * @param string $subFolder
     * @param $object
     *
     * @return string
     */
    protected function storePicture($request, $inputName, $subFolder, $object)
    {
        $imageHelper = new ImageHelper;

        return $imageHelper->storePicture($request, $inputName, $subFolder, $object);
    }
}
