<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contract PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .container {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        h1, h2 {
            color: #333;
        }
        h1 {
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        h2 {
            margin-top: 40px;
            margin-bottom: 10px;
        }
        p {
            margin-bottom: 10px;
        }
        .signature-block {
            margin-top: 40px;
        }
        .signature {
            margin-top: 20px;
            padding: 10px;
            border-top: 1px solid #333;
        }
        .signature-info {
            display: flex;
            justify-content: space-between;
        }
        .timestamp {
            margin-top: 20px;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Title of the Contract -->
        <h1>{{ $contract->title }}</h1>

        <!-- Contract Information -->
        <h2>Contract Information</h2>
        <p><strong>Amount:</strong> ${{ number_format($contract->amount, 2) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($contract->status) }}</p>
        <p><strong>Version:</strong> {{ $contract->version }}</p>

        <!-- Contract Content -->
        <h2>Contract Content</h2>
        <p>{{ $contract->contract_content }}</p>

        <!-- Signatures -->
        <div class="signature-block">
            <h2>Signatures</h2>
            <div class="signature">
                <h3>Freelancer</h3>
                <p><strong>Public Key:</strong> {{ $contract->freelancer_public_key }}</p>
                <p><strong>Signature:</strong> {{ $contract->freelancer_signature }}</p>
            </div>
            <div class="signature">
                <h3>Seeker</h3>
                <p><strong>Public Key:</strong> {{ $contract->seeker_public_key }}</p>
                <p><strong>Signature:</strong> {{ $contract->seeker_signature }}</p>
            </div>
            @if($contract->admin_id)
            <div class="signature">
                <h3>Admin</h3>
                <p><strong>Public Key:</strong> {{ $contract->admin_public_key }}</p>
                <p><strong>Signature:</strong> {{ $contract->admin_signature }}</p>
            </div>
            @endif
        </div>

        <!-- Timestamp -->
        <p class="timestamp"><strong>Timestamp:</strong> {{ $contract->pdf_timestamp }}</p>

        <!-- Content Hash -->
        <p class="timestamp"><strong>Content Hash:</strong> {{ $contract->content_hash }}</p>
    </div>
</body>
</html>
