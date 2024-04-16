<?php

namespace EvolutionCMS\Generator\Controllers\Matrix;

use Closure;
use EvolutionCMS\Generator\Models\EntitieFolder;
use EvolutionCMS\Generator\Models\Matrix;
use EvolutionCMS\Models\SiteContent;
use EvolutionCMS\Models\SiteTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class CreateUpdateMatrix
{
    public $foldersFieldName = 'tventfolders';
    public function __invoke(Request $request, ?Matrix $matrix)
    {
        $isUpdateRequest = $request->header('hx-request') == true;
        if ($isUpdateRequest) {
            $this->foldersFieldName = 'tv'.$matrix->id.'entfolders';
        }

        $validator = Validator::make($request->all(), $this->rules());
        if ($validator->fails()) {
            if ($isUpdateRequest) {
                Session::flash('_old_input', $request->all());
                return view('generator::generator.form', [
                    'matrix' => $matrix,
                    'matrices' => Matrix::all(),
                    'templates' => SiteTemplate::all(),
                ])->withErrors($validator->errors());
            }
            return back()->withErrors($validator->errors())->withInput();
        }
        $validated = $validator->validated();
        //$entfolders = EntitieFolder::query()->whereIn('id', explode(",", $validated['tventfolders']))->get();

        $message = 'Матрица ';
        if (!isset($matrix)) {
            $matrix = Matrix::create();
            $message .= 'создана';
        } else {
            $message .= 'обновлена';
        }

        $matrix->site_content_parent_id = $validated['parent_id'];
        $matrix->site_content_template = $validated['template'];
        $matrix->site_content_content = $validated['content'];
        $matrix->folders_id = $validated[$this->foldersFieldName];
        $matrix->pagetitle_template = $validated['pagetitle_template'];
        $matrix->pagetitle_categories_tvlist = $validated['pagetitle_categories_tvlist'];
        $matrix->pagetitle_entities_tvlist = $validated['pagetitle_entities_tvlist'];
        $matrix->save();

        session()->flash('success', $message);
        if ($isUpdateRequest) {
            return response('', 200, ['HX-Refresh' => 'true']);
        }
        return back();
    }

    private function rules()
    {
        return [
            $this->foldersFieldName    => 'required|regex:/^[0-9,]+$/',
            'parent_id' => [
                'required', 'integer', 'gt:-1',
                function (string $attribute, mixed $value, Closure $fail) {
                    if ($value != 0) {
                        $boolExistDocument = SiteContent::where('id', '=', $value)->exists();
                        if (!$boolExistDocument) {
                            $fail('Документ с id ' . $value . ' не существует');
                        }
                    }
                },
            ],
            'template'  => [
                'required', 'integer', 'gt:0',
                function (string $attribute, mixed $value, Closure $fail) {
                    if ($value != 0) {
                        $boolExistTemplate = SiteTemplate::where('id', '=', $value)->exists();
                        if (!$boolExistTemplate) {
                            $fail('Шаблона с id ' . $value . ' не существует');
                        }
                    }
                },
            ],
            'pagetitle_template' => 'required|regex:/^[^<]+$/',
            'pagetitle_categories_tvlist' => 'string',
            'pagetitle_entities_tvlist' => 'string',
            'content' => 'string',
        ];
    }
}