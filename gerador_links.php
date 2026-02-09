<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerador de Links de Afiliados</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 500px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        h1 {
            color: #fff;
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.8em;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: #a0a0a0;
            margin-bottom: 8px;
            font-size: 0.9em;
        }

        input,
        select {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            font-size: 1em;
            transition: all 0.3s;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }

        input::placeholder {
            color: #666;
        }

        .btn {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #fff;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(79, 70, 229, 0.4);
        }

        .result {
            margin-top: 30px;
            padding: 20px;
            background: rgba(79, 70, 229, 0.1);
            border-radius: 10px;
            border: 1px solid rgba(79, 70, 229, 0.3);
            display: none;
        }

        .result.show {
            display: block;
        }

        .result-label {
            color: #a0a0a0;
            font-size: 0.85em;
            margin-bottom: 10px;
        }

        .result-link {
            background: rgba(0, 0, 0, 0.3);
            padding: 14px;
            border-radius: 8px;
            color: #4ade80;
            word-break: break-all;
            font-family: monospace;
            font-size: 0.95em;
        }

        .btn-copy {
            margin-top: 15px;
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .btn-copy:hover {
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4);
        }

        .copied {
            text-align: center;
            color: #4ade80;
            margin-top: 10px;
            font-size: 0.9em;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .copied.show {
            opacity: 1;
        }

        .optional {
            color: #666;
            font-size: 0.8em;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🔗 Gerador de Links</h1>

        <div class="form-group">
            <label>URL Base do Site</label>
            <input type="url" id="baseUrl" placeholder="https://seusite.com"
                value="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']); ?>">
        </div>

        <div class="form-group">
            <label>Código do Afiliado (ref) <span style="color:#ef4444">*</span></label>
            <input type="text" id="refCode" placeholder="ex: joao, maria, vendedor01">
        </div>

        <div class="form-group">
            <label>Fonte de Tráfego <span class="optional">(opcional)</span></label>
            <select id="utmSource">
                <option value="">Selecione...</option>
                <option value="facebook">Facebook</option>
                <option value="instagram">Instagram</option>
                <option value="kwai">Kwai</option>
                <option value="tiktok">TikTok</option>
                <option value="google">Google</option>
                <option value="whatsapp">WhatsApp</option>
                <option value="telegram">Telegram</option>
                <option value="youtube">YouTube</option>
                <option value="email">E-mail</option>
                <option value="outro">Outro</option>
            </select>
        </div>

        <div class="form-group">
            <label>Nome da Campanha <span class="optional">(opcional)</span></label>
            <input type="text" id="utmCampaign" placeholder="ex: natal2024, promocao_janeiro">
        </div>

        <button type="button" class="btn" onclick="generateLink()">GERAR LINK</button>

        <div class="result" id="result">
            <div class="result-label">Link gerado:</div>
            <div class="result-link" id="generatedLink"></div>
            <button type="button" class="btn btn-copy" onclick="copyLink()">📋 COPIAR LINK</button>
            <div class="copied" id="copiedMsg">✓ Link copiado!</div>
        </div>
    </div>

    <script>
        function generateLink() {
            const baseUrl = document.getElementById('baseUrl').value.trim().replace(/\/$/, '');
            const refCode = document.getElementById('refCode').value.trim();
            const utmSource = document.getElementById('utmSource').value;
            const utmCampaign = document.getElementById('utmCampaign').value.trim();

            if (!baseUrl) {
                alert('Por favor, insira a URL base do site.');
                return;
            }
            if (!refCode) {
                alert('Por favor, insira o código do afiliado.');
                return;
            }

            // Monta os parâmetros
            const params = new URLSearchParams();
            if (utmSource) params.append('utm_source', utmSource);
            if (utmCampaign) params.append('utm_campaign', utmCampaign);
            params.append('ref', refCode);

            const finalUrl = baseUrl + '/?' + params.toString();

            document.getElementById('generatedLink').textContent = finalUrl;
            document.getElementById('result').classList.add('show');
            document.getElementById('copiedMsg').classList.remove('show');
        }

        function copyLink() {
            const link = document.getElementById('generatedLink').textContent;
            navigator.clipboard.writeText(link).then(() => {
                document.getElementById('copiedMsg').classList.add('show');
                setTimeout(() => {
                    document.getElementById('copiedMsg').classList.remove('show');
                }, 2000);
            });
        }

        // Enter para gerar
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') generateLink();
            });
        });
    </script>
</body>

</html>