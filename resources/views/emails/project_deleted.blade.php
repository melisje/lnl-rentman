<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f7;
        }

        .wrapper {
            width: 100%;
            padding: 40px 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #e3342f;
            color: #ffffff;
            padding: 25px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .content {
            padding: 30px;
        }

        .project-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 20px;
            margin-top: 10px;
        }

        .label {
            font-size: 12px;
            color: #718096;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .project-name {
            font-size: 18px;
            color: #1a202c;
            font-weight: bold;
        }

        .subproject-arrow {
            color: #a0aec0;
            margin: 0 10px;
        }

        .footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #a0aec0;
        }

        hr {
            border: 0;
            border-top: 1px solid #edf2f7;
            margin: 25px 0;

        }

        .status-badge {
            display: inline-block;
            background-color: #ebf8ff;
            color: #2b6cb0;
            border: 1px solid #bee3f8;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
        }

        .status-label {
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>⚠️ Project Wijziging</h1>
            </div>

            <div class="content">
                @if(isset($item->parent_project))
                <p>Het volgende <strong>subproject</strong> is gewijzigd:</p>
                <div class="project-box">
                    <div class="label">Hoofdproject</div>
                    <div class="project-name">{{ $item->parent_project->number }} - {{ $item->parent_project->name }}</div>

                    <div style="margin: 15px 0; text-align: center;">
                        <span class="subproject-arrow">▼</span>
                    </div>

                    <div class="label">Subproject</div>
                    <div class="project-name" style="color: #e3342f;">{{ $item->name }} </div>
                    <div class="label">Nieuwe status</div>
                    <div class="status-badge">
                        {{ $status->name }}
                    </div>

                </div>
                @else
                <p>Het volgende <strong>subproject</strong> is verwijderd of geannuleerd:</p>
                <div class="project-box">
                    <div class="label">Projectdetails</div>
                    <div class="project-name" style="color: #e3342f;">
                        {{ $item->number }} - {{ $item->name }}
                    </div>
                </div>
                @endif

                <hr>

                <p style="font-size: 14px; color: #4a5568;">
                    Deze actie is automatisch gedetecteerd vanuit <strong>Rentman</strong>.
                </p>
            </div>

            <div class="footer">
                Verstuurd op: {{ now()->format('d-m-Y H:i') }} • Automatische melding
            </div>
        </div>
    </div>
</body>

</html>