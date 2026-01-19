<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>نتائج الاستمارة - PDF</title>
  <style>
    body {
      font-family: 'DejaVu Sans', sans-serif;
      direction: rtl;
      margin: 0;
      padding: 10px;
      background-color: white;
    }

    .header {
      text-align: center;
      margin-bottom: 20px;
      border-bottom: 2px solid #007bff;
      padding-bottom: 15px;
    }

    .header h1 {
      margin: 0 0 10px 0;
      color: #333;
      font-size: 20px;
    }

    .header p {
      margin: 3px 0;
      color: #666;
      font-size: 12px;
    }

    .stats {
      display: table;
      width: 100%;
      margin-bottom: 20px;
      table-layout: fixed;
    }

    .stat-box {
      display: table-cell;
      text-align: center;
      border: 1px solid #ddd;
      padding: 10px;
      background-color: #f9f9f9;
      width: 25%;
    }

    .stat-box h3 {
      margin: 0;
      color: #666;
      font-size: 11px;
    }

    .stat-box .value {
      font-size: 18px;
      font-weight: bold;
      color: #007bff;
      margin: 5px 0 0 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin: 15px 0;
      font-size: 10px;
    }

    thead {
      background-color: #007bff;
      color: white;
    }

    th {
      padding: 8px 5px;
      text-align: right;
      font-weight: bold;
      border: 1px solid #ddd;
    }

    td {
      padding: 6px 5px;
      border: 1px solid #ddd;
      text-align: right;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .badge {
      display: inline-block;
      padding: 2px 5px;
      border-radius: 2px;
      font-size: 9px;
      font-weight: bold;
      color: white;
    }

    .badge-pending {
      background-color: #ffc107;
    }

    .badge-approved {
      background-color: #28a745;
    }

    .badge-rejected {
      background-color: #dc3545;
    }

    .badge-under-review {
      background-color: #17a2b8;
    }

    .footer {
      margin-top: 20px;
      padding-top: 10px;
      border-top: 1px solid #ddd;
      text-align: center;
      color: #999;
      font-size: 9px;
    }

    .response-data {
      font-size: 8px;
      word-break: break-word;
    }

    .page-break {
      page-break-after: always;
    }
  </style>
</head>

<body>
  <!-- رأس الصفحة -->
  <div class="header">
    <h1>نتائج الاستمارة</h1>
    <p><strong>{{ $form->title }}</strong></p>
    @if($form->description)
    <p>{{ substr($form->description, 0, 100) }}</p>
    @endif
    <p style="color: #999; font-size: 10px;">
      تاريخ إنشاء التقرير: {{ now()->format('Y-m-d H:i:s') }}
    </p>
  </div>

  <!-- الإحصائيات -->
  <div class="stats">
    <div class="stat-box">
      <h3>قيد الانتظار</h3>
      <div class="value" style="color: #ffc107;">
        {{ $responses->where('status', 'pending')->count() }}
      </div>
    </div>
    <div class="stat-box">
      <h3>موافق عليه</h3>
      <div class="value" style="color: #28a745;">
        {{ $responses->where('status', 'approved')->count() }}
      </div>
    </div>
    <div class="stat-box">
      <h3>مرفوض</h3>
      <div class="value" style="color: #dc3545;">
        {{ $responses->where('status', 'rejected')->count() }}
      </div>
    </div>
    <div class="stat-box">
      <h3>الإجمالي</h3>
      <div class="value">
        {{ $responses->count() }}
      </div>
    </div>
  </div>

  <!-- جدول النتائج -->
  @if($responses->count() > 0)
  <table>
    <thead>
      <tr>
        <th style="width: 5%;">#</th>
        <th style="width: 15%;">المستخدم</th>
        <th style="width: 15%;">عنوان IP</th>
        <th style="width: 10%;">الحالة</th>
        <th style="width: 15%;">تاريخ التقديم</th>
        <th style="width: 40%;">البيانات</th>
      </tr>
    </thead>
    <tbody>
      @foreach($responses as $response)
      <tr>
        <td>{{ $response->id }}</td>
        <td>
          @if($response->user)
          {{ substr($response->user->name, 0, 20) }}
          @else
          <em>غير مسجل</em>
          @endif
        </td>
        <td>{{ $response->ip_address }}</td>
        <td>
          <span class="badge badge-{{ str_replace('_', '-', $response->status) }}">
            {{ $statuses[$response->status] ?? $response->status }}
          </span>
        </td>
        <td>{{ $response->created_at->format('Y-m-d') }}</td>
        <td class="response-data">
          @if($response->response_data)
          @foreach($response->response_data as $key => $value)
          <strong>{{ substr($key, 0, 15) }}:</strong>
          {{ is_array($value) ? json_encode($value) : substr($value, 0, 30) }}<br>
          @endforeach
          @else
          -
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @else
  <p style="text-align: center; color: #999; padding: 40px 0;">
    لا توجد نتائج لعرضها
  </p>
  @endif

  <!-- تذييل الصفحة -->
  <div class="footer">
    <p>تم إنشاء هذا التقرير بواسطة نظام الاستمارات الإلكترونية - {{ config('app.name') }}</p>
  </div>
</body>

</html>