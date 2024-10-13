<?php

namespace lib\HtmlComponent;

use lib\Html\TmhHtmlElementFactory;

class TmhMeasurementListHtmlComponent implements TmhHtmlComponent
{
    private TmhHtmlElementFactory $elementFactory;

    public function __construct(TmhHtmlElementFactory $elementFactory)
    {
        $this->elementFactory = $elementFactory;
    }

    public function get(array $entity): array
    {
        $br = $this->elementFactory->br();
        $listItemNodes = [$br];
        $listItemNodes[] = $this->table($entity['list']['items']);
        return $listItemNodes;
    }

    private function table(array $listItems): array
    {
        $rows = [];
        foreach ($listItems as $rowCells) {
            $cells = [];
            foreach ($rowCells as $rowCell) {
                $cells[] = $this->elementFactory->td(
                    ['colspan' => '1', 'class' => 'tmh_table_cell_small'],
                    $rowCell
                );
            }
            $rows[] = $this->elementFactory->tr('tmh_table_row', $cells);
        }
        return $this->elementFactory->table('tmh_table_70', $rows);
    }
}