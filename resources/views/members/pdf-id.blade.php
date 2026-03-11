<!DOCTYPE html>
<html>
<head>
    <style>
        @page { margin: 0; }
        body { font-family: 'Helvetica', sans-serif; margin: 0; background: #ffffff; }
        .card { width: 100%; height: 100%; position: relative; overflow: hidden; }
        .header { 
            height: 120px; 
            background: {{ $member->lsa_level === 'Platinum' ? '#0f172a' : '#059669' }}; 
            color: white; 
            padding: 20px;
            text-align: center;
        }
        .header h4 { margin: 0; font-size: 10px; letter-spacing: 2px; text-transform: uppercase; }
        .avatar-container {
            margin-top: -50px;
            text-align: center;
        }
        .avatar {
            width: 100px;
            height: 100px;
            background: #f1f5f9;
            border: 5px solid white;
            border-radius: 20px;
            display: inline-block;
            line-height: 100px;
            font-size: 30px;
            font-weight: bold;
            color: #059669;
        }
        .info { text-align: center; padding: 20px; }
        .name { font-size: 20px; font-weight: 900; color: #1e293b; margin: 0; }
        .spec { 
            font-size: 9px; 
            color: #059669; 
            background: #ecfdf5; 
            padding: 5px 15px; 
            border-radius: 50px;
            display: inline-block;
            margin-top: 10px;
            text-transform: uppercase;
        }
        .qr-section { text-align: center; margin-top: 20px; }
        .footer {
            position: absolute;
            bottom: 20px;
            width: 100%;
            font-size: 8px;
            color: #94a3b8;
            padding: 0 30px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h4>LSA Official Member</h4>
            <div style="font-size: 8px; margin-top: 5px; opacity: 0.8;">{{ $member->lsa_level ?? 'Standard' }} Tier</div>
        </div>

        <div class="avatar-container">
            <div class="avatar">
                {{ substr($member->first_name, 0, 1) }}{{ substr($member->last_name, 0, 1) }}
            </div>
        </div>

        <div class="info">
            <div class="name">{{ $member->first_name }} {{ $member->last_name }}</div>
            <div class="spec">{{ $member->specialization }}</div>
        </div>

        <div class="qr-section">
            {{-- Note: Use base64 for images in DomPDF --}}
            <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(120)->generate($member->email)) !!} ">
            <div style="font-family: monospace; font-size: 8px; color: #64748b; margin-top: 5px;">
                {{ $member->email }}
            </div>
        </div>

        <div class="footer">
            <table width="100%">
                <tr>
                    <td align="left">REGION: {{ $member->region->region_code ?? 'N/A' }}</td>
                    <td align="right">ISSUED: {{ now()->format('m/Y') }}</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>