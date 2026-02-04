<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 40px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #007AFF;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #007AFF;
        }

        .doc-title {
            font-size: 28px;
            margin-top: 10px;
            color: #111;
        }

        .meta {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        h2 {
            color: #007AFF;
            border-left: 4px solid #007AFF;
            padding-left: 10px;
            margin-top: 30px;
        }

        h3 {
            color: #444;
            margin-top: 25px;
        }

        p {
            margin-bottom: 15px;
        }

        ul,
        ol {
            margin-bottom: 20px;
            padding-left: 20px;
        }

        li {
            margin-bottom: 8px;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">OBOUN ERP</div>
        <div class="doc-title">{{ $title }}</div>
        <div class="meta">System Documentation &bull; Generated on {{ $date }}</div>
    </div>

    <div class="content">
        {!! $content !!}
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Oboun ERP Systems. All rights reserved.
    </div>
</body>

</html>
