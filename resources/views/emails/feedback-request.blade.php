<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Feedback Request</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #ffc107; color: #333; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
        .button { display: inline-block; margin-top: 15px; padding: 10px 20px; background-color: #ffc107; color: #333; text-decoration: none; border-radius: 5px; }
        .footer { font-size: 12px; color: #666; margin-top: 20px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Kami Ingin Mendengar Pendapat Anda!</h1>
        </div>
        <div class="content">
            <p>Halo {{ $patient->name }},</p>
            
            <p>Terima kasih telah menggunakan layanan telehealth kami. Kami ingin terus meningkatkan layanan untuk memberikan pengalaman terbaik bagi Anda.</p>
            
            <p>Kami sangat menghargai feedback Anda tentang:</p>
            <ul>
                <li>Pengalaman Anda menggunakan platform</li>
                <li>Kualitas layanan dari dokter</li>
                <li>Fitur yang ingin ditambahkan</li>
                <li>Saran untuk perbaikan</li>
            </ul>

            <p>
                <a href="{{ config('app.url') }}/dashboard/feedbacks/create" class="button">
                    Bagikan Feedback Anda
                </a>
            </p>

            <p><em>Semua feedback akan dijaga kerahasiaannya dan membantu kami melayani Anda lebih baik.</em></p>

            <p>Terima kasih,<br>
            Tim Telehealth</p>
        </div>
        <div class="footer">
            <p>Email ini dikirim karena Anda terdaftar sebagai pengguna di platform telehealth kami.</p>
        </div>
    </div>
</body>
</html>
