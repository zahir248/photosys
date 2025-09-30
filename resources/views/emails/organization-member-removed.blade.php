<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>You have been removed from {{ $organization->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .alert {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            margin: 20px 0;
        }
        .info-box {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border: 1px solid #bee5eb;
            border-radius: 4px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Organization Access Removed</h1>
    </div>
    
    <div class="content">
        <h2>Hello {{ $removedUser->name }},</h2>
        
        <div class="alert">
            <strong>Important:</strong> You have been removed from the organization <strong>{{ $organization->name }}</strong>.
        </div>
        
        <p>This email is to inform you that your access to the organization <strong>{{ $organization->name }}</strong> has been revoked by {{ $removedBy->name }}.</p>
        
        <div class="info-box">
            <h3>What this means:</h3>
            <ul>
                <li>You no longer have access to organization albums and photos</li>
                <li>You cannot view or manage organization content</li>
                <li>Your personal photos and albums remain unaffected</li>
            </ul>
        </div>
        
        <p>If you believe this was done in error, please contact {{ $removedBy->name }} directly at {{ $removedBy->email }}.</p>
        
        <p>Thank you for your understanding.</p>
        
        <div class="footer">
            <p>This is an automated notification from the Photo Management System.</p>
            <p>If you have any questions, please contact the organization owner.</p>
        </div>
    </div>
</body>
</html>
