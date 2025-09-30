<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Organization Invitation</title>
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
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>You're Invited!</h1>
    </div>
    
    <div class="content">
        <p>Hello!</p>
        
        <p><strong>{{ $invitation->inviter->name }}</strong> has invited you to join the organization <strong>"{{ $invitation->organization->name }}"</strong> on our photo management system.</p>
        
        @if($invitation->organization->description)
        <p><strong>About the organization:</strong><br>
        {{ $invitation->organization->description }}</p>
        @endif
        
        <p>You will be added as a <strong>{{ ucfirst($invitation->role) }}</strong> with access to:</p>
        <ul>
            <li>View and manage photos in the organization</li>
            <li>Create and manage albums</li>
            <li>Collaborate with other members</li>
        </ul>
        
        <p>To accept this invitation, click the button below:</p>
        
        <a href="{{ route('invitations.accept', $invitation->token) }}" class="button">Accept Invitation</a>
        
        <p><small>This invitation will expire on {{ $invitation->expires_at->format('F j, Y \a\t g:i A') }}.</small></p>
        
        <p>If you don't want to join this organization, you can simply ignore this email.</p>
    </div>
    
    <div class="footer">
        <p>If you're having trouble clicking the button, copy and paste the URL below into your web browser:</p>
        <p>{{ route('invitations.accept', $invitation->token) }}</p>
        
        <p>This invitation was sent by {{ $invitation->inviter->name }} ({{ $invitation->inviter->email }}) on {{ $invitation->created_at->format('F j, Y \a\t g:i A') }}.</p>
    </div>
</body>
</html>
