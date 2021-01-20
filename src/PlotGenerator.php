<?php

declare(strict_types=1);

namespace GuitarHero;

class PlotGenerator
{
    public static function generate($data, $plotName, $path, $minValue)
    {
        $plot = new \PHPlot(800, 600);
        $plot->SetImageBorderType('plain');
        $plot->SetPlotType('lines');
        $plot->SetDataType('data-data');
        $plot->SetDataValues($data);
        $plot->SetTitle($plotName);
        $plot->SetPlotAreaWorld(NULL, $minValue, NULL, NULL);
        $plot->SetIsInline(true);
        $plot->SetOutputFile($path);
        $plot->DrawGraph();
    }
}