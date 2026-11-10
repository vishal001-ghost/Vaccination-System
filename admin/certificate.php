<?php
require_once '../config/db.php';
need('admin');

$id = (int)($_GET['id'] ?? 0);
$r = null;
foreach (q(BOOKING_SQL . " ORDER BY b.booking_id DESC")->fetchAll() as $row) {
    if ((int)$row['booking_id'] === $id) {
        $r = $row;
        break;
    }
}
// testing: allows Approved + Vaccinated (change to 'Vaccinated' only after testing)
if (!$r || !in_array($r['status'], ['Approved', 'Vaccinated'])) {
    http_response_code(404);
    exit('Certificate not available.');
}

$serial = 'PV-' . date('Y') . '-' . str_pad($id, 5, '0', STR_PAD_LEFT);
$date   = date('d F Y', strtotime($r['appointment_date']));
$issued = date('d F Y');

// 5-point star polygon for the flag
function star($cx, $cy, $R, $r, $rot)
{
    $p = [];
    for ($i = 0; $i < 10; $i++) {
        $a = deg2rad($rot + $i * 36);
        $rad = $i % 2 ? $r : $R;
        $p[] = round($cx + $rad * cos($a), 2) . ',' . round($cy + $rad * sin($a), 2);
    }
    return implode(' ', $p);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Vaccination Certificate - <?= e($r['child']) ?></title>
    <style>
        :root {
            --green: #01411c;
            --green2: #0b7a3b;
            --gold: #b8962e;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #e9ede9;
            font-family: Georgia, 'Times New Roman', serif;
            color: #111;
        }

        .toolbar {
            text-align: center;
            padding: 14px;
        }

        .toolbar button {
            background: var(--green);
            color: #fff;
            border: 0;
            padding: 10px 24px;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
        }

        .page {
            width: 1123px;
            height: 794px;
            margin: 0 auto 30px;
            background: #fff;
            position: relative;
            padding: 26px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .2);
        }

        .frame {
            height: 100%;
            border: 6px solid var(--green);
            padding: 8px;
        }

        .frame-in {
            height: 100%;
            border: 2px solid var(--gold);
            position: relative;
            padding: 26px 50px;
            text-align: center;
            overflow: hidden;
        }

        .watermark {
            position: absolute;
            left: 50%;
            top: 50%;
            width: 420px;
            height: 420px;
            transform: translate(-50%, -50%);
            opacity: .05;
        }

        .head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }

        .head .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-align: left;
        }

        .brand b {
            font-size: 30px;
            color: var(--green);
            letter-spacing: 1px;
            line-height: 1;
        }

        .brand b span {
            color: #111;
        }

        .brand small {
            display: block;
            font-size: 11px;
            color: #111;
            letter-spacing: 2px;
            margin-top: 3px;
        }

        .flagbox {
            text-align: center;
            font-size: 11px;
            color: #111;
        }

        .flagbox svg {
            border: 1px solid #999;
            display: block;
            margin: 0 auto 3px;
        }

        h1 {
            margin: 14px 0 2px;
            font-size: 46px;
            color: var(--green);
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .sub {
            font-size: 15px;
            letter-spacing: 4px;
            color: #111;
            text-transform: uppercase;
        }

        .line {
            height: 3px;
            width: 260px;
            margin: 12px auto;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
        }

        .txt {
            font-size: 18px;
            color: #111;
            margin: 6px 0;
        }

        .name {
            font-size: 44px;
            color: var(--green);
            font-style: italic;
            border-bottom: 2px solid #111;
            display: inline-block;
            padding: 0 40px 2px;
            margin: 6px 0;
        }

        .detail {
            font-size: 20px;
            line-height: 1.7;
            color: #111;
            max-width: 820px;
            margin: 8px auto;
        }

        .detail b {
            color: var(--green);
        }

        .foot {
            position: absolute;
            left: 50px;
            right: 50px;
            bottom: 26px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .sig {
            width: 230px;
            text-align: center;
            font-size: 13px;
            color: #111;
        }

        .sig .ln {
            border-top: 1.5px solid #111;
            padding-top: 4px;
            margin-top: 44px;
        }

        .sig .sg {
            font-family: 'Brush Script MT', 'Segoe Script', cursive;
            font-size: 26px;
            color: var(--green);
            position: absolute;
            margin-top: -4px;
            width: 230px;
        }

        .serial {
            font-size: 12px;
            color: #111;
            text-align: center;
        }

        .serial b {
            color: var(--green);
            font-size: 14px;
            letter-spacing: 1px;
        }

        .stamp {
            position: absolute;
            right: 250px;
            bottom: 46px;
            width: 190px;
            height: 190px;
            transform: rotate(-14deg);
            opacity: .88;
            mix-blend-mode: multiply;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }

            body {
                background: #fff;
            }

            .toolbar {
                display: none;
            }

            .page {
                box-shadow: none;
                margin: 0;
                width: 297mm;
                height: 210mm;
                padding: 8mm;
            }

            * {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="toolbar"><button onclick="window.print()">Print / Save as PDF</button></div>

    <div class="page">
        <div class="frame">
            <div class="frame-in">

                <!-- faint watermark -->
                <svg class="watermark" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="48" fill="#01411c" />
                    <path d="M50 18v64M18 50h64" stroke="#fff" stroke-width="14" stroke-linecap="round" />
                </svg>

                <div class="head">
                    <!-- PakVac logo -->
                    <div class="brand">
                        <svg width="64" height="64" viewBox="0 0 100 100">
                            <path d="M50 4 L90 18 V48 C90 74 70 90 50 97 C30 90 10 74 10 48 V18 Z" fill="#01411c" />
                            <path d="M50 10 L84 22 V48 C84 70 67 84 50 91 C33 84 16 70 16 48 V22 Z" fill="none" stroke="#b8962e" stroke-width="2" />
                            <rect x="41" y="26" width="18" height="48" rx="4" fill="#fff" />
                            <rect x="26" y="41" width="48" height="18" rx="4" fill="#fff" />
                            <circle cx="50" cy="50" r="5" fill="#01411c" />
                        </svg>
                        <div><b>Pak<span>Vac</span></b><small>NATIONAL VACCINATION PORTAL</small></div>
                    </div>

                    <!-- Pakistan flag -->
                    <div class="flagbox">
                        <svg width="120" height="80" viewBox="0 0 900 600">
                            <rect width="900" height="600" fill="#01411c" />
                            <rect width="225" height="600" fill="#fff" />
                            <circle cx="562" cy="300" r="170" fill="#fff" />
                            <circle cx="615" cy="270" r="140" fill="#01411c" />
                            <polygon points="<?= star(690, 235, 62, 25, -25) ?>" fill="#fff" />
                        </svg>
                        ISLAMIC REPUBLIC OF PAKISTAN
                    </div>
                </div>

                <h1>Certificate of Vaccination</h1>
                <div class="sub">Official Immunization Record</div>
                <div class="line"></div>

                <div class="txt">This is to certify that</div>
                <div class="name"><?= e($r['child']) ?></div>
                <div class="txt">child of <b style="color:var(--green)"><?= e($r['parent']) ?></b></div>

                <div class="detail">
                    has been successfully vaccinated against <b><?= e($r['vaccine']) ?></b><br>
                    at <b><?= e($r['hospital']) ?></b> on <b><?= e($date) ?></b>.
                </div>

                <!-- stamp -->
                <svg class="stamp" viewBox="0 0 200 200">
                    <defs>
                        <path id="sc" d="M100,100 m-72,0 a72,72 0 1,1 144,0 a72,72 0 1,1 -144,0" />
                    </defs>
                    <circle cx="100" cy="100" r="95" fill="none" stroke="#01411c" stroke-width="5" />
                    <circle cx="100" cy="100" r="88" fill="none" stroke="#01411c" stroke-width="1.5" />
                    <circle cx="100" cy="100" r="53" fill="none" stroke="#01411c" stroke-width="2.5" />
                    <text font-family="Arial,sans-serif" font-size="15" font-weight="bold" fill="#01411c" letter-spacing="2.5">
                        <textPath href="#sc" startOffset="0">PAKVAC &#9733; OFFICIALLY VERIFIED &#9733; IMMUNIZATION &#9733;</textPath>
                    </text>
                    <text x="100" y="86" text-anchor="middle" font-family="Arial,sans-serif" font-size="11" font-weight="bold" fill="#01411c" letter-spacing="1">PAKVAC</text>
                    <path d="M78 103 L93 118 L124 84" fill="none" stroke="#01411c" stroke-width="9" stroke-linecap="round" stroke-linejoin="round" />
                    <text x="100" y="139" text-anchor="middle" font-family="Arial,sans-serif" font-size="12" font-weight="bold" fill="#01411c" letter-spacing="1.5">VACCINATED</text>
                </svg>

                <div class="foot">
                    <div class="sig">
                        <div class="sg">PakVac Admin</div>
                        <div class="ln">Authorized Signature<br><b>PakVac Administration</b></div>
                    </div>
                    <div class="serial">Certificate No.<br><b><?= e($serial) ?></b><br>Issued: <?= e($issued) ?></div>
                    <div class="sig">
                        <div class="ln">Date of Vaccination<br><b><?= e($date) ?></b></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>