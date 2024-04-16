<?php


use EvolutionCMS\Generator\Controllers\Generator\GenerateArticles;
use EvolutionCMS\Generator\Controllers\Generator\GenerateOneArticle;
use EvolutionCMS\Generator\Controllers\Generator\KillProcess;
use EvolutionCMS\Generator\Controllers\IndexController;
use EvolutionCMS\Generator\Controllers\Matrix\CreateUpdateMatrix;
use EvolutionCMS\Generator\Controllers\Matrix\DeleteMatrix;
use EvolutionCMS\Generator\Controllers\Matrix\GetModalFormUpdate;
use EvolutionCMS\Generator\Controllers\Utilities\DeleteChildrensOfDoc;
use EvolutionCMS\Generator\Controllers\Utilities\MassAddDocs;
use Illuminate\Support\Facades\Route;


Route::name('generator::')->group(function () {
    Route::match(['get'], '', IndexController::class)->name('index');

    Route::prefix('matrix')->name('matrix.')->group(function() {
        Route::post('create', CreateUpdateMatrix::class)->name('create');
        Route::post('update/{matrix}', CreateUpdateMatrix::class)->name('update');

        Route::post('delete/{matrix}',DeleteMatrix::class)->name('delete');
        Route::get('modal/edit/{matrix}',GetModalFormUpdate::class)->name('getmodal.edit');
    });

    Route::prefix('command')->name('command.')->group(function() {
        Route::get('onearticle/{matrix}/{option}', GenerateOneArticle::class)->name('onearticle');
        Route::post('allarticles/{matrix}', GenerateArticles::class)->name('allarticles');
        Route::get('killprocess/{id}', KillProcess::class)->name('killprocess');
    });

    Route::prefix('utilities')->name('utilities.')->group(function() {
        Route::post('delete-childrens', DeleteChildrensOfDoc::class)->name('deletechildrens');
        Route::post('mass-add', MassAddDocs::class)->name('massadd');
    });
});