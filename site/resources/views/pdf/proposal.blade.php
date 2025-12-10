<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposta {{ substr($proposal->id, 0, 8) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 9pt;
            color: #323232;
            line-height: 1.3;
            padding: 15px;
        }
        
        /* Border */
        .page-border {
            border: 3px solid #228B22;
            border-radius: 5px;
            padding: 15px;
            min-height: 270mm;
        }
        
        /* Logo */
        .logo-section {
            text-align: center;
            margin-bottom: 5px;
        }
        
        .logo-section img {
            width: 100px;
            height: 100px;
        }
        
        /* CNPJ e Tel  */
        .company-info {
            text-align: center;
            color: #505050;
            font-size: 8pt;
            margin-bottom: 10px;
        }
        
        /* Título */
        .proposal-title {
            background-color: #E8F5E8;
            text-align: center;
            padding: 10px;
            border-radius: 3px;
            margin-bottom: 8px;
        }
        
        .proposal-title h1 {
            color: #228B22;
            font-size: 18pt;
            font-weight: bold;
            margin: 0;
        }
        
        /* Número e data */
        .proposal-meta {
            text-align: center;
            color: #646464;
            font-size: 8pt;
            margin-bottom: 12px;
        }
        
        /* Header verde escuro */
        .section-header {
            background-color: #228B22;
            color: white;
            font-size: 10pt;
            font-weight: bold;
            text-align: center;
            padding: 8px;
            margin-bottom: 0;
            margin-top: 12px;
        }
        
        /* Box de dados do cliente */
        .client-info {
            background-color: #FAFAFA;
            padding: 10px 12px;
            font-size: 8pt;
            margin-bottom: 0;
        }
        
        .client-info table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .client-info td {
            padding: 3px 0;
        }
        
        .client-info td:first-child {
            width: 50%;
        }
        
        /* Tabela de serviços */
        .services-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            font-size: 8pt;
        }
        
        .services-table thead {
            background-color: #90BE90;
        }
        
        .services-table th {
            color: #1E501E;
            font-weight: bold;
            padding: 6px 5px;
            text-align: left;
            font-size: 8pt;
        }
        
        .services-table tbody tr {
            background-color: #FFFFFF;
        }
        
        .services-table tbody tr:nth-child(even) {
            background-color: #F8FCF8;
        }
        
        .services-table td {
            padding: 5px;
            color: #323232;
            border-bottom: 1px solid #E0E0E0;
        }
        
        /* Linha de totais integrada na tabela */
        .totals-row {
            background-color: #FFFFFF !important;
            font-weight: normal;
        }
        
        .totals-row td {
            padding: 8px 5px !important;
            border-bottom: none !important;
        }
        
        .total-label {
            text-align: right;
            padding-right: 10px;
            color: #505050;
        }
        
        .total-value {
            text-align: right;
            font-weight: normal;
        }
        
        /* Linha do total final verde */
        .total-final-row {
            background-color: #228B22 !important;
        }
        
        .total-final-row td {
            color: white !important;
            font-weight: bold !important;
            font-size: 10pt !important;
            padding: 8px 5px !important;
        }
        
        /* Condições de pagamento */
        .payment-section {
            background-color: #FAFAFA;
            padding: 10px 12px;
            font-size: 8pt;
            margin-bottom: 0;
        }
        
        .validity-text {
            color: #646464;
            font-size: 7pt;
            margin-top: 8px;
            font-style: italic;
        }
        
        /* Observações */
        .notes-section {
            background-color: #FFFDF0;
            padding: 10px 12px;
            font-size: 8pt;
            margin-bottom: 0;
        }
        
        /* Assinaturas */
        .signatures {
            margin-top: 25px;
            width: 100%;
        }
        
        .signatures table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .signature-cell {
            width: 30%;
            text-align: center;
        }
        
        .signature-line {
            border-top: 1px solid #969696;
            width: 80px;
            margin: 0 auto 5px auto;
        }
        
        .signature-label {
            color: #646464;
            font-size: 7pt;
        }
        
        /* Página */
        @page {
            margin: 10mm;
            size: A4;
        }
    </style>
</head>
<body>
    <div class="page-border">
        <!-- Logo -->
        <div class="logo-section">
            @php
                $logoPath = public_path('images/rmjardim-logo.png');
                if(file_exists($logoPath)) {
                    $logoData = base64_encode(file_get_contents($logoPath));
                    $logoSrc = 'data:image/png;base64,' . $logoData;
                }
            @endphp
            @if(isset($logoSrc))
                <img src="{{ $logoSrc }}" alt="Logo">
            @endif
        </div>

        <!-- CNPJ e Tel -->
        <div class="company-info">
            CNPJ: {{ config('company.cnpj') }} | Tel: {{ config('company.phone') }}
        </div>

        <!-- Título -->
        <div class="proposal-title">
            <h1>PROPOSTA DE SERVIÇO</h1>
        </div>

        <!-- Número e data -->
        <div class="proposal-meta">
            Nº {{ strtoupper(substr($proposal->id, 0, 8)) }} | Data: {{ $proposal->created_at->format('d/m/Y') }}
        </div>

        <!-- Dados do cliente -->
        <div class="section-header">DADOS DO CLIENTE</div>
        <div class="client-info">
            <table>
                <tr>
                    <td><strong>Nome:</strong> {{ $proposal->client_name ?? 'Não informado' }}</td>
                    <td><strong>Telefone:</strong> {{ $proposal->client_phone ?? 'Não informado' }}</td>
                </tr>
                @if($proposal->client_email)
                <tr>
                    <td colspan="2"><strong>Email:</strong> {{ $proposal->client_email }}</td>
                </tr>
                @endif
                <tr>
                    <td colspan="2"><strong>Endereço:</strong> {{ $proposal->client_address ?? 'Não informado' }}</td>
                </tr>
            </table>
        </div>

        <!-- Serviços -->
        <div class="section-header">SERVIÇOS</div>
        <table class="services-table">
            <thead>
                <tr>
                    <th style="width: 45%;">Descrição</th>
                    <th style="width: 10%; text-align: center;">Unid.</th>
                    <th style="width: 10%; text-align: center;">Qtd.</th>
                    <th style="width: 17%; text-align: right;">Valor Unit.</th>
                    <th style="width: 18%; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proposal->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td style="text-align: center;">{{ $item->unit }}</td>
                    <td style="text-align: center;">{{ number_format($item->quantity, 0) }}</td>
                    <td style="text-align: right;">R$ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                    <td style="text-align: right;">R$ {{ number_format($item->total, 2, ',', '.') }}</td>
                </tr>
                @endforeach
                
                <!-- Subtotal -->
                <tr class="totals-row">
                    <td colspan="4" class="total-label">Subtotal:</td>
                    <td class="total-value">R$ {{ number_format($proposal->subtotal, 2, ',', '.') }}</td>
                </tr>
                
                <!-- Desconto (se houver) -->
                @if($proposal->discount > 0)
                <tr class="totals-row">
                    <td colspan="4" class="total-label" style="color: #B43232;">Desconto:</td>
                    <td class="total-value" style="color: #B43232;">- R$ {{ number_format($proposal->discount, 2, ',', '.') }}</td>
                </tr>
                @endif
                
                <!-- Total Final -->
                <tr class="total-final-row">
                    <td colspan="4" class="total-label">TOTAL:</td>
                    <td class="total-value">R$ {{ number_format($proposal->total, 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Condições de pagamento -->
        <div class="section-header">CONDIÇÕES DE PAGAMENTO</div>
        <div class="payment-section">
            {{ $proposal->payment_conditions }}
            <div class="validity-text">* Esta proposta tem validade de {{ $proposal->validity_days }} dias.</div>
        </div>

        <!-- Observações -->
        @if($proposal->notes)
        <div class="section-header">OBSERVAÇÕES</div>
        <div class="notes-section">
            {{ $proposal->notes }}
        </div>
        @endif

        <!-- Assinaturas -->
        <div class="signatures">
            <table>
                <tr>
                    <td class="signature-cell">
                        <div class="signature-line"></div>
                        <div class="signature-label">RMJardim</div>
                    </td>
                    <td style="width: 40%;"></td>
                    <td class="signature-cell">
                        <div class="signature-line"></div>
                        <div class="signature-label">Cliente</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>