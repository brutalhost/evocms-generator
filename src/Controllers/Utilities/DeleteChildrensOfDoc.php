<?php

namespace EvolutionCMS\Generator\Controllers\Utilities;

use Closure;
use EvolutionCMS\Models\SiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DeleteChildrensOfDoc
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->only('util-delete-all_parent_id'), [
            'util-delete-all_parent_id' => [
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
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        } else {
            $validated = $validator->validated();
            try {
                DB::table('site_content')->where('parent', $validated['util-delete-all_parent_id'])->delete();
                session()->flash('success', 'Документы удалены');
                return back();
            }
            catch (\Exception $exception) {
                session()->flash('success', 'Произошла ошибка: ' . $exception->getMessage());
                return back();
            }
        }
    }
}