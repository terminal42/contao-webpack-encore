<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

PaletteManipulator::create()
    ->addLegend('encore_legend', 'style_legend')
    ->addField(['encoreEntrypoints', 'encoreScriptPosition', 'encoreStylePosition'], 'encore_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_layout')
;

$GLOBALS['TL_DCA']['tl_layout']['fields'] += [
    'encoreEntrypoints' => [
        'exclude' => true,
        'inputType' => 'checkbox',
        'eval' => ['multiple' => true, 'tl_class' => 'clr w50'],
        'sql' => 'text NULL',
    ],
    'encoreScriptPosition' => [
        'exclude' => true,
        'inputType' => 'select',
        'options' => ['TL_HEAD', 'TL_BODY'],
        'reference' => &$GLOBALS['TL_LANG']['tl_layout']['encoreScriptPosition'],
        'eval' => ['tl_class' => 'w50'],
        'sql' => "varchar(8) NOT NULL default ''",
    ],
];
