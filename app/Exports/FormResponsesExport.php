<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FormResponsesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
  protected $responses;
  protected $columns;

  public function __construct($responses, $columns)
  {
    $this->responses = $responses;
    $this->columns = $columns;
  }

  public function collection()
  {
    return $this->responses;
  }

  public function headings(): array
  {
    return array_merge(['رقم التقديم', 'الحالة', 'تاريخ التقديم'], $this->columns);
  }

  public function map($row): array
  {
    $data = [
      $row->id,
      $row->status,
      $row->created_at->format('Y-m-d H:i'),
    ];

    foreach ($this->columns as $column) {
      $answer = $row->response_data[$column] ?? '-';
      $data[] = is_array($answer) ? implode(', ', $answer) : $answer;
    }

    return $data;
  }
}
