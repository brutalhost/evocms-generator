<?php

use EvolutionCMS\Facades\UrlProcessor;
use Illuminate\Support\Facades\Event;

Event::listen(['evolution.OnWebPageInit'], function () {
    $q = $_GET['q'];

    if (strpos($q, config('docshaker.generator_folder_alias')) === 0) {
        evo()->sendRedirect(UrlProcessor::makeUrl(evo()->getConfig('site_start')));
    }
});