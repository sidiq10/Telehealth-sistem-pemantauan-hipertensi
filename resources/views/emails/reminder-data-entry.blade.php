<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Entry Reminder</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #007bff; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
        .button { display: inline-block; margin-top: 15px; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        .footer { font-size: 12px; color: #666; margin-top: 20px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pengingat: Masukkan Data Kesehatan Anda</h1>
        </div>
        <div class="content">
            <p>Halo {{ $patient->name }},</p>
            
            <p>Kami ingin mengingatkan Anda untuk mencatat data tekanan darah Anda hari ini. Dengan rutin memantau data kesehatan, Anda membantu dokter memberikan perawatan yang lebih baik.</p>
            
            <h3>Manfaat mencatat data secara rutin:</h3>
            <ul>
                <li>Memantau kesehatan kardiovaskular Anda</li>
                <li>Mendapatkan rekomendasi kesehatan yang dipersonalisasi</li>
                <li>Membantu dokter membuat keputusan medis yang lebih baik</li>
                <li>Dapatkan poin dan badge untuk konsistensi</li>
            </ul>

            <p>
                <a href="{{ config('app.url') }}/dashboard/health-records/create" class="button">
                    Masukkan Data Sekarang
                </a>
            </p>

            <p>Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.</p>

            <p>Salam hangat,<br>
            Tim Telehealth</p>
        </div>
        <div class="footer">
            <p>Email ini dikirim karena Anda terdaftar sebagai pengguna di platform telehealth kami.</p>
        </div>
    </div>
</body>
</html>
