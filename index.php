<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Cadastro - BMP</title>
    <link rel="stylesheet" href="style.css">
    <!-- Ícone de aba (opcional) -->
    <link rel="icon" type="image/png" href="BMP/images/logo.png">

    <!-- =====================================================
         CONFIGURAÇÃO DE PIXELS - Edite em pixel_config.js
         ===================================================== -->
    <script src="pixel_config.js"></script>

    <!-- Kwai Pixel Code -->
    <script>
        !function (e, t) { "object" == typeof exports && "object" == typeof module ? module.exports = t() : "function" == typeof define && define.amd ? define([], t) : "object" == typeof exports ? exports.install = t() : e.install = t() }("undefined" != typeof window ? window : self, (function () { return function (e) { var t = {}; function n(o) { if (t[o]) return t[o].exports; var r = t[o] = { i: o, l: !1, exports: {} }; return e[o].call(r.exports, r, r.exports, n), r.l = !0, r.exports } return n.m = e, n.c = t, n.d = function (e, t, o) { n.o(e, t) || Object.defineProperty(e, t, { enumerable: !0, get: o }) }, n.r = function (e) { "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, { value: "Module" }), Object.defineProperty(e, "__esModule", { value: !0 }) }, n.t = function (e, t) { if (1 & t && (e = n(e)), 8 & t) return e; if (4 & t && "object" == typeof e && e && e.__esModule) return e; var o = Object.create(null); if (n.r(o), Object.defineProperty(o, "default", { enumerable: !0, value: e }), 2 & t && "string" != typeof e) for (var r in e) n.d(o, r, function (t) { return e[t] }.bind(null, r)); return o }, n.n = function (e) { var t = e && e.__esModule ? function () { return e.default } : function () { return e }; return n.d(t, "a", t), t }, n.o = function (e, t) { return Object.prototype.hasOwnProperty.call(e, t) }, n.p = "", n(n.s = 72) }({ 72: function (e, t, n) { "use strict"; var o = this && this.__spreadArray || function (e, t, n) { if (n || 2 === arguments.length) for (var o, r = 0, i = t.length; r < i; r++)!o && r in t || (o || (o = Array.prototype.slice.call(t, 0, r)), o[r] = t[r]); return e.concat(o || Array.prototype.slice.call(t)) }; Object.defineProperty(t, "__esModule", { value: !0 }); var r = function (e, t, n) { var o, i = e.createElement("script"); i.type = "text/javascript", i.async = !0, i.src = t, n && (i.onerror = function () { r(e, n) }); var a = e.getElementsByTagName("script")[0]; null === (o = a.parentNode) || void 0 === o || o.insertBefore(i, a) }; !function (e, t, n) { e.KwaiAnalyticsObject = n; var i = e[n] = e[n] || []; i.methods = ["page", "track", "identify", "instances", "debug", "on", "off", "once", "ready", "alias", "group", "enableCookie", "disableCookie"]; var a = function (e, t) { e[t] = function () { for (var n = [], r = 0; r < arguments.length; r++)n[r] = arguments[r]; var i = o([t], n, !0); e.push(i) } }; i.methods.forEach((function (e) { a(i, e) })), i.instance = function (e) { var t, n = (null === (t = i._i) || void 0 === t ? void 0 : t[e]) || []; return i.methods.forEach((function (e) { a(n, e) })), n }, i.load = function (e, o) { var a = "https://s21-def.ap4r.com/kos/s101/nlav112572/pixel/events.js"; i._i = i._i || {}, i._i[e] = [], i._i[e]._u = a, i._t = i._t || {}, i._t[e] = +new Date, i._o = i._o || {}, i._o[e] = o || {}; var c = "?sdkid=".concat(e, "&lib=").concat(n); r(t, a + c, "https://s21-def.ks-la.net/kos/s101/nlav112572/pixel/events.js" + c) } }(window, document, "kwaiq") } }) }));
    </script>
    <script>
        if (PIXEL_CONFIG.KWAI.ENABLED) {
            kwaiq.load(PIXEL_CONFIG.KWAI.PIXEL_ID);
            kwaiq.page();
            kwaiq.instance(PIXEL_CONFIG.KWAI.PIXEL_ID).track('contentView');
        }
    </script>
    <!-- End Kwai Pixel Code -->

    <!-- Facebook Pixel Code -->
    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return; n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n; n.push = n; n.loaded = !0; n.version = '2.0';
            n.queue = []; t = b.createElement(e); t.async = !0;
            t.src = v; s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');

        if (PIXEL_CONFIG.FACEBOOK.ENABLED) {
            fbq('init', PIXEL_CONFIG.FACEBOOK.PIXEL_ID);
            fbq('track', 'PageView');
            fbq('track', 'ViewContent');
        }
    </script>
    <!-- End Facebook Pixel Code -->
</head>

<body>

    <!-- Container da Animação da Logo -->
    <div id="logo-container">
        <!-- Caminho relativo da imagem baseado na sua estrutura de pastas -->
        <img src="BMP/images/lgoosemfundo.png" alt="Logo BMP" id="logo-img">

        <!-- Passo 1: Botão de Start (dentro do container para centralizar junto inicialmente) -->
        <div id="step-1" class="step active" style="margin-top: 20px;">
            <p style="color: #e0e0e0; margin-bottom: 15px;">Bem-vindo ao nosso portal.</p>
            <button type="button" class="btn" onclick="nextStep(2)">COMEÇAR</button>
        </div>
    </div>

    <!-- Container do Formulário (Aparece após a logo subir) -->
    <div id="main-content">
        <form id="lead-form" novalidate>
            <input type="hidden" id="lead_id" name="lead_id" value="">

            <!-- Campos de Rastreamento (capturados automaticamente da URL) -->
            <input type="hidden" id="utm_source" name="utm_source" value="">
            <input type="hidden" id="utm_medium" name="utm_medium" value="">
            <input type="hidden" id="utm_campaign" name="utm_campaign" value="">
            <input type="hidden" id="referrer" name="referrer" value="">
            <input type="hidden" id="referrer_url" name="referrer_url" value="">

            <!-- Passo 2: Dados Pessoais e Tempo de Trabalho -->
            <div id="step-2" class="step">
                <h2>Seus Dados Iniciais</h2>
                <p class="description">Por favor, preencha as informações abaixo.</p>

                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" placeholder="Seu nome" required>
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone (WhatsApp)</label>
                    <input type="tel" id="telefone" name="telefone" placeholder="(XX) XXXXX-XXXX" maxlength="15"
                        required>
                </div>

                <div class="form-group">
                    <label for="tempo_trabalho">Há quanto tempo trabalha?</label>
                    <select id="tempo_trabalho" name="tempo_trabalho" required>
                        <option value="">Selecione...</option>
                        <option value="menos_1_ano">Menos de 1 ano</option>
                        <option value="1_3_anos">1 a 3 anos</option>
                        <option value="3_5_anos">3 a 5 anos</option>
                        <option value="5_10_anos">5 a 10 anos</option>
                    </select>
                </div>

                <button type="button" class="btn" onclick="nextStep(3)">PRÓXIMO</button>
            </div>

            <!-- Passo 3: Documentos -->
            <div id="step-3" class="step">
                <h2>Seus Documentos</h2>
                <p class="description">Necessário para verificação de crédito.</p>

                <div class="form-group">
                    <label for="cpf">CPF (somente números)</label>
                    <input type="tel" id="cpf" name="cpf" placeholder="000.000.000-00" maxlength="14" required>
                </div>

                <div class="form-group">
                    <label for="rg">RG (somente números)</label>
                    <input type="tel" id="rg" name="rg" placeholder="00.000.000-0" maxlength="12" required>
                </div>

                <div class="form-group">
                    <label for="data_nascimento">Data de Nascimento</label>
                    <input type="date" id="data_nascimento" name="data_nascimento" min="1900-01-01" max="2025-12-31"
                        required>
                </div>

                <button type="button" class="btn" onclick="nextStep(4)">PRÓXIMO</button>
            </div>

            <!-- Passo 4: Endereço -->
            <div id="step-4" class="step">
                <h2>Seu Endereço</h2>
                <p class="description">Digite o CEP para buscar automaticamente.</p>

                <div class="form-group">
                    <label for="cep">CEP</label>
                    <input type="tel" id="cep" name="cep" placeholder="00000-000" maxlength="9" required>
                </div>

                <div class="form-group">
                    <label for="logradouro">Rua / Endereço</label>
                    <input type="text" id="logradouro" name="logradouro" placeholder="Carregando..." readonly required>
                </div>

                <div class="form-group">
                    <label for="numero">Número</label>
                    <input type="tel" id="numero" name="numero" placeholder="Digite o número" required>
                </div>

                <div class="form-group">
                    <label for="complemento">Complemento (Opcional)</label>
                    <input type="text" id="complemento" name="complemento" placeholder="Apto, Bloco...">
                </div>

                <div class="form-group">
                    <label for="bairro">Bairro</label>
                    <input type="text" id="bairro" name="bairro" readonly required>
                </div>

                <div class="form-group">
                    <label for="cidade">Cidade</label>
                    <input type="text" id="cidade" name="cidade" readonly required>
                </div>

                <div class="form-group">
                    <label for="uf">Estado (UF)</label>
                    <input type="text" id="uf" name="uf" readonly required>
                </div>

                <button type="button" class="btn" onclick="nextStep(5)">PRÓXIMO</button>
            </div>

            <!-- Passo 5: Aplicativo FGTS -->
            <div id="step-5" class="step">
                <h2>Aplicativo FGTS</h2>
                <p class="description">Você possui o aplicativo do FGTS instalado?</p>

                <div class="form-group radio-group">
                    <label class="radio-box">
                        <input type="radio" name="app_fgts" value="sim" required>
                        <span>Sim</span>
                    </label>
                    <label class="radio-box">
                        <input type="radio" name="app_fgts" value="nao" required>
                        <span>Não</span>
                    </label>
                </div>

                <!-- Botão de Envio Real -->
                <button type="submit" id="btn-submit" class="btn">FINALIZAR CADASTRO</button>
            </div>

            <!-- Passo 7: Agradecimento -->
            <div id="step-7" class="step" style="text-align: center;">
                <h2 style="color: #28a745;">Sucesso!</h2>
                <p class="description">Seus dados foram enviados.</p>
                <p style="font-size: 1.1em; margin-top: 10px;">Em breve um de nossos atendentes lhe chamará no WhatsApp.
                </p>

                <img src="BMP/images/logo.png" alt="Icone Sucesso" style="width: 80px; margin-top: 30px; opacity: 0.8;">
            </div>

        </form>
    </div>

    <script src="app.js?v=2"></script>
</body>

</html>