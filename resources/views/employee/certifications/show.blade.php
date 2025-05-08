<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Certification - {{ $certification->employee->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            color: #333;
        }
        .certificate {
            border: 2px solid #1a5276;
            padding: 30px;
            text-align: center;
            background-color: #f9f9f9;
        }
        h1 {
            color: #1a5276;
            margin-bottom: 30px;
        }
        .congrats {
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
            color: #27ae60;
        }
        .details {
            margin: 30px 0;
            text-align: left;
            padding: 0 50px;
        }
        .signature {
            margin-top: 50px;
            border-top: 1px solid #333;
            display: inline-block;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <h1>CERTIFICATE OF COMPLETION</h1>

        <div class="congrats">Congratulations!</div>

        <p>This certificate is proudly presented to:</p>

        <h2>{{ $certification->employee->name }}</h2>

        <div class="details">
            <p>For successfully completing the course:</p>
            <h3>"{{ $certification->course->title }}"</h3>

            <p>Completed on: {{ $certification->created_at->format('F j, Y') }}</p>
        </div>

        <p>We acknowledge your dedication and hard work in achieving this accomplishment.</p>

        <div class="signature">
            <p>Authorized Signature</p>
        </div>
    </div>
</body>
</html>
