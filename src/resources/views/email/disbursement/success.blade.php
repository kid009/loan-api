<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>แจ้งเตือนการเบิกเงินสำเร็จ</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
      color: #333333;
      background-color: #f4f4f4;
      padding: 20px;
    }

    .container {
      max-width: 600px;
      margin: 0 auto;
      background-color: #ffffff;
      padding: 20px;
      border: 1px solid #dddddd;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .header {
      background-color: #4CAF50;
      color: white;
      padding: 15px;
      text-align: center;
      border-radius: 5px 5px 0 0;
    }

    .content {
      padding: 20px;
    }

    .footer {
      margin-top: 20px;
      font-size: 0.85em;
      text-align: center;
      color: #777777;
      border-top: 1px solid #eeeeee;
      padding-top: 15px;
    }

    .amount {
      font-size: 1.4em;
      font-weight: bold;
      color: #2E7D32;
    }

    ul {
      list-style-type: none;
      padding: 0;
    }

    li {
      margin-bottom: 10px;
      padding: 10px;
      background-color: #f9f9f9;
      border-left: 4px solid #4CAF50;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <h2>ทำรายการเบิกเงินสำเร็จ</h2>
    </div>
    <div class="content">
      <p>เรียน ลูกค้าผู้มีอุปการคุณ,</p>
      <p>ระบบได้ทำการบันทึกรายการเบิกเงินสินเชื่อของคุณเรียบร้อยแล้ว โดยมีรายละเอียดดังนี้:</p>

      <ul>
        <li><strong>รหัสอ้างอิง (Ref No):</strong> {{ $transaction->reference_no }}</li>
        <li><strong>จำนวนเงินที่เบิก:</strong> <span class="amount">{{ number_format($transaction->amount, 2) }}
            บาท</span></li>
        <li><strong>วันที่ทำรายการ:</strong> {{ $transaction->created_at->format('d/m/Y H:i:s') }}</li>
      </ul>

      <p>ยอดเงินจะถูกโอนเข้าบัญชีที่ท่านได้ผูกไว้ หากมีข้อสงสัยเพิ่มเติม สามารถติดต่อฝ่ายบริการลูกค้าของเราได้ทันทีครับ
      </p>
    </div>
    <div class="footer">
      <p>ขอบคุณที่ไว้วางใจใช้บริการกับเรา</p>
      <p><em>นี่คืออีเมลแจ้งเตือนอัตโนมัติ กรุณาอย่าตอบกลับ</em></p>
    </div>
  </div>
</body>

</html>
