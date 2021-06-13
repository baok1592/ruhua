<?php

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::PSR2_LEVEL)
    ->fixers(['-yoda_conditions', 'ordered_use', 'short_array_syntax'])
    ->finder(Symfony\CS\Finder\DefaultFinder::create()
        ->in(__DIR__.'/src/'));