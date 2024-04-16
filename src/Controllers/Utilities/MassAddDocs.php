<?php

namespace EvolutionCMS\Generator\Controllers\Utilities;

use Closure;
use EvolutionCMS\DocumentManager\Facades\DocumentManager;
use EvolutionCMS\Models\SiteContent;
use EvolutionCMS\Models\SiteTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MassAddDocs
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->only(['util-mass-add_parent_id', 'util-mass-add_template', 'util-mass-add_pagetitles']), [
            'util-mass-add_parent_id' => [
                'required', 'integer', 'gt:0',
                function (string $attribute, mixed $value, Closure $fail) {
                    if ($value != 0) {
                        $boolExistDocument = SiteContent::where('id', '=', $value)->exists();
                        if (!$boolExistDocument) {
                            $fail('Документ с id ' . $value . ' не существует');
                        }
                    }
                },
            ],
            'util-mass-add_template'  => [
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
            'util-mass-add_pagetitles' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        } else {
            $validated = $validator->validated();
            $pagetitles = preg_split("/[\r\n\s+,]+/", $validated['util-mass-add_pagetitles'], -1, PREG_SPLIT_NO_EMPTY);

            try {
                foreach ($pagetitles as $pagetitle) {
                    $document = ['pagetitle' => $pagetitle, 'template' => $validated['util-mass-add_template'], 'parent'=>$validated['util-mass-add_parent_id'],'published'=> 1];
                    DocumentManager::create($document,true,true);
                }
                session()->flash('success', 'Документы созданы');
                return back();
            }
            catch (\Exception $exception) {
                session()->flash('success', 'Произошла ошибка: ' . $exception->getMessage());
                return back();
            }
        }
    }
}