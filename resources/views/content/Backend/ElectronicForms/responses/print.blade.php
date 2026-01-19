<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>طباعة نتائج الاستمارة</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      direction: rtl;
      background-color: #f5f5f5;
      margin: 0;
      padding: 20px;
    }

    .print-container {
      max-width: 1200px;
      margin: 0 auto;
      background: white;
      padding: 30px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .header {
      text-align: center;
      margin-bottom: 30px;
      border-bottom: 3px solid #007bff;
      padding-bottom: 20px;
    }

    .header h1 {
      margin: 0;
      color: #333;
      font-size: 24px;
    }

    .header p {
      margin: 5px 0;
      color: #666;
      font-size: 14px;
    }

    .stats {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
      margin-bottom: 30px;
    }

    .stat-box {
      text-align: center;
      padding: 15px;
      border: 1px solid #ddd;
      border-radius: 5px;
      background-color: #f9f9f9;
    }

    .stat-box h3 {
      margin: 0;
      color: #666;
      font-size: 14px;
    }

    .stat-box .value {
      font-size: 28px;
      font-weight: bold;
      color: #007bff;
      margin: 10px 0 0 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    thead {
      background-color: #007bff;
      color: white;
    }

    th {
      padding: 12px;
      text-align: right;
      font-weight: bold;
      border: 1px solid #ddd;
      font-size: 13px;
    }

    td {
      padding: 10px;
      border: 1px solid #ddd;
      font-size: 12px;
      text-align: right;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .badge {
      display: inline-block;
      padding: 3px 8px;
      border-radius: 3px;
      font-size: 11px;
      font-weight: bold;
      color: white;
    }

    .badge.pending {
      background-color: #ffc107;
    }

    .badge.approved {
      background-color: #28a745;
    }

    .badge.rejected {
      background-color: #dc3545;
    }

    .badge.under_review {
      background-color: #17a2b8;
    }

    .footer {
      margin-top: 30px;
      padding-top: 20px;
      border-top: 1px solid #ddd;
      text-align: center;
      color: #999;
      font-size: 12px;
    }

    .no-print {
      display: none;
    }

    @media print {
      body {
        background: none;
        padding: 0;
      }

      .print-container {
        box-shadow: none;
        max-width: 100%;
      }

      .no-print {
        display: none !important;
      }
    }

    @media (max-width: 768px) {
      .stats {
        grid-template-columns: repeat(2, 1fr);
      }

      table {
        font-size: 11px;
      }

      th,
      td {
        padding: 8px;
      }
    }

    .response-data {
      max-width: 200px;
      word-break: break-word;
      white-space: normal;
    }
  </style>
</head>

<body>
  <div class="print-container">
    <!-- رأس الصفحة -->
    <div class="header">
      <h1><i style="margin-left: 10px;">📋</i>نتائج الاستمارة</h1>
      <p><strong>{{ $form->title }}</strong></p>
      <p>{{ $form->description }}</p>
      <p style="color: #999; font-size: 12px;">
        تم الطباعة في: {{ now()->format('Y-m-d H:i:s') }}
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
        <div class="value" style="color: #007bff;">
          {{ $responses->count() }}
        </div>
      </div>
    </div>

    <!-- جدول النتائج -->
    @if($responses->count() > 0)
    <table>
      <thead>
        <tr>
          <th style="width: 60px;">#</th>
          <th>المستخدم</th>
          <th>عنوان IP</th>
          <th>بصمة المتصفح</th>
          <th>الحالة</th>
          <th>تاريخ التقديم</th>
          <th style="width: 150px;">البيانات</th>
        </tr>
      </thead>
      <tbody>
        @foreach($responses as $response)
        <tr>
          <td>{{ $response->id }}</td>
          <td>
            @if($response->user)
            {{ $response->user->name }}
            @else
            <em>غير مسجل</em>
            @endif
          </td>
          <td>{{ $response->ip_address }}</td>
          <td>
            @if($response->browser_fingerprint)
            {{ substr($response->browser_fingerprint, 0, 10) }}...
            @else
            -
            @endif
          </td>
          <td>
            <span class="badge {{ str_replace('_', '-', $response->status) }}">
              {{ $statuses[$response->status] ?? $response->status }}
            </span>
          </td>
          <td>{{ $response->created_at->format('Y-m-d H:i') }}</td>
          <td class="response-data">
            @if($response->response_data)
            <small>
              @foreach($response->response_data as $key => $value)
              <strong>{{ $key }}:</strong> {{ is_array($value) ? json_encode($value) : $value }}<br>
              @endforeach
            </small>
            @else
            -
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px; color: #999;">
      لا توجد نتائج لعرضها
    </div>
    @endif

    <!-- تذييل الصفحة -->
    <div class="footer">
      <p>تم إنشاء هذا التقرير بواسطة نظام الاستمارات الإلكترونية</p>
    </div>
  </div>

  <script>
    window.addEventListener('load', function() {
            window.print();
        });
  </script>
</body>

</html>