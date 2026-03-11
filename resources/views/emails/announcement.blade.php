<div style="font-family: sans-serif; max-width: 600px; margin: auto; border: 1px solid #e2e8f0; border-radius: 20px; padding: 40px;">
    <span style="background: #eef2ff; color: #4f46e5; padding: 5px 10px; border-radius: 5px; font-size: 10px; font-weight: bold; uppercase;">
        {{ $announcement->category }}
    </span>
    <h1 style="color: #1e293b; margin-top: 15px;">{{ $announcement->title }}</h1>
    <p style="color: #64748b; line-height: 1.6;">{{ $announcement->content }}</p>
    <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 30px 0;">
    <p style="font-size: 11px; color: #94a3b8;">Sent from 4-H Admin Control Center</p>
</div>