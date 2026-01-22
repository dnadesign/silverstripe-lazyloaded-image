<?php

declare(strict_types=1);

use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeLevelSetList;
use Cambis\SilverstripeRector\Set\ValueObject\SilverstripeSetList;
use Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddDBFieldPropertyAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddExtensionMixinAnnotationsToExtensibleRector;
use Cambis\SilverstripeRector\Silverstripe413\Rector\Class_\AddHasOnePropertyAndMethodAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddBelongsManyManyMethodAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddExtendsAnnotationToContentControllerRector;
use Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddExtendsAnnotationToExtensionRector;
use Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddHasManyMethodAnnotationsToDataObjectRector;
use Cambis\SilverstripeRector\Silverstripe52\Rector\Class_\AddManyManyMethodAnnotationsToDataObjectRector;
use Rector\Config\RectorConfig;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;

return RectorConfig::configure()
    ->withImportNames(removeUnusedImports: true)
    ->withPaths([
        __DIR__ . '/src',
    ])
    ->withPhpSets()
    ->withSets([
        SilverstripeLevelSetList::UP_TO_SILVERSTRIPE_60,
        SilverstripeSetList::CODE_QUALITY,
    ])
    ->withSkip([
        AddOverrideAttributeToOverriddenMethodsRector::class,
        ClosureToArrowFunctionRector::class,
        // Skip the add annotation rectors
        AddDBFieldPropertyAnnotationsToDataObjectRector::class,
        AddBelongsToPropertyAndMethodAnnotationsToDataObjectRector::class,
        AddHasOnePropertyAndMethodAnnotationsToDataObjectRector::class,
        AddHasManyMethodAnnotationsToDataObjectRector::class,
        AddBelongsManyManyMethodAnnotationsToDataObjectRector::class,
        AddManyManyMethodAnnotationsToDataObjectRector::class,
        AddExtensionMixinAnnotationsToExtensibleRector::class,
        AddExtendsAnnotationToContentControllerRector::class,
        AddExtendsAnnotationToExtensionRector::class,
    ]);
