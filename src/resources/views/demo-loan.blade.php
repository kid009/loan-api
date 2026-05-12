<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ทดสอบระบบเบิกเงิน (Loan API)</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white">
            <h4 class="mb-0">💸 ระบบเบิกเงินสินเชื่อ</h4>
          </div>
          <div class="card-body">

            <form id="disbursementForm">
              <div class="mb-3">
                <label class="form-label">รหัสวงเงิน (Loan ID)</label>
                <input type="text" id="loanId" class="form-control" placeholder="เช่น 019e1... โค้ดจากข้อ 1">
              </div>
              <div class="mb-3">
                <label class="form-label">จำนวนเงินที่ต้องการเบิก</label>
                <input type="number" id="amount" class="form-control" value="3000">
              </div>
              <div class="mb-3">
                <label class="form-label">รหัสอ้างอิงบิล (Ref. No)</label>
                <input type="text" id="referenceNo" class="form-control" placeholder="เช่น TX-001">
              </div>

              <button type="button" onclick="submitDisbursement()" class="btn btn-success w-100">
                ยืนยันการเบิกเงิน
              </button>
            </form>

            <div id="resultBox" class="mt-4 d-none">
              <div class="alert alert-info">
                <h5>ผลลัพธ์จาก API:</h5>
                <pre id="responseJson" class="mb-0" style="white-space: pre-wrap; word-wrap: break-word;"></pre>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    async function submitDisbursement() {
      const payload = {
        loan_id: document.getElementById('loanId').value,
        amount: document.getElementById('amount').value,
        reference_no: document.getElementById('referenceNo').value
      };

      try {
        // ยิง Request ไปหา API ที่เราสร้างไว้
        const response = await fetch('/api/v1/disbursements', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(payload)
        });

        const data = await response.json();

        // แสดงกล่องผลลัพธ์
        document.getElementById('resultBox').classList.remove('d-none');

        // นำ Data มาโชว์หน้าเว็บ
        const resultElement = document.getElementById('responseJson');
        resultElement.innerText = JSON.stringify(data, null, 2);

        if (response.ok) {
          resultElement.parentElement.className = 'alert alert-success';
          alert('เบิกเงินสำเร็จ! ลองเช็คใน Database ดูครับว่ายอดลดลงไหม');
        } else {
          resultElement.parentElement.className = 'alert alert-danger';

          alert('เกิดข้อผิดพลาด: ' + (data.message || 'ข้อมูลไม่ถูกต้อง'));
        }

      } catch (error) {
        console.error('Error:', error);
        alert('เกิดข้อผิดพลาดในการเชื่อมต่อระบบ');
      }
    }
  </script>
</body>

</html>
