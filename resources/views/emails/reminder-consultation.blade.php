<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Consultation Reminder</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #28a745; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
        .button { display: inline-block; margin-top: 15px; padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px; }
        .footer { font-size: 12px; color: #666; margin-top: 20px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Jadwalkan Konsultasi dengan Dokter</h1>
        </div>
        <div class="content">
            <p>Halo {{ $patient->name }},</p>
            
            <p>Kami ingin mengingatkan Anda untuk menjadwalkan konsultasi dengan dokter Anda. Konsultasi rutin sangat penting untuk memantau kesehatan Anda.</p>
            
            <h3>Mengapa konsultasi rutin penting?</h3>
            <ul>
                <li>Membahas progress kesehatan Anda</li>
                <li>Mendapatkan saran dari profesional kesehatan</li>
                <li>Menyesuaikan rencana perawatan jika diperlukan</li>
                <li>Mencegah komplikasi kesehatan</li>
            </ul>

            <p>
                <a href="{{ config('app.url') }}/dashboard/consultations" class="button">
                    Jadwalkan Konsultasi
                </a>
            </p>

            <p>Terima kasih telah menjaga kesehatan Anda.</p>

            <p>Salam hangat,<br>
            Tim Telehealth</p>
        </div>
        <div class="footer">
            <p>Email ini dikirim karena Anda terdaftar sebagai pengguna di platform telehealth kami.</p>
        </div>
    </div>
</body>
</html>
