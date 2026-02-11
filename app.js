document.addEventListener('DOMContentLoaded', () => {
    const logoContainer = document.getElementById('logo-container');
    const mainContent = document.getElementById('main-content');

    // Variáveis de estado
    let currentStep = 1;
    // Passo 5 é o último de input. O 7 é a tela de sucesso.
    // O formulário é enviado no Passo 5.

    // --- CAPTURA DE PARÂMETROS DE RASTREAMENTO ---
    function captureTrackingParams() {
        const urlParams = new URLSearchParams(window.location.search);

        // Parâmetros UTM padrão
        const trackingFields = {
            'utm_source': urlParams.get('utm_source') || '',
            'utm_medium': urlParams.get('utm_medium') || '',
            'utm_campaign': urlParams.get('utm_campaign') || '',
            'referrer': urlParams.get('ref') || urlParams.get('referrer') || urlParams.get('afiliado') || '',
            'referrer_url': document.referrer || ''
        };

        // Preenche os campos hidden
        for (const [field, value] of Object.entries(trackingFields)) {
            const el = document.getElementById(field);
            if (el) el.value = value;
        }

        // Captura Kwai Click ID (callback ou click_id)
        const kwaiClickId = urlParams.get('callback') || urlParams.get('click_id') || urlParams.get('kwai_click_id') || '';
        const kwaiClickIdField = document.getElementById('kwai_click_id');
        if (kwaiClickIdField) kwaiClickIdField.value = kwaiClickId;

        // Log para debug
        console.log('Tracking params:', trackingFields);
        console.log('Kwai Click ID:', kwaiClickId);
    }

    // Captura os parâmetros ao carregar
    captureTrackingParams();

    // Kwai Event API - Track Content View (server-side)
    const kwaiClickId = new URLSearchParams(window.location.search).get('callback') ||
        new URLSearchParams(window.location.search).get('click_id') ||
        new URLSearchParams(window.location.search).get('kwai_click_id') || '';

    fetch(`track_content_view.php?kwai_click_id=${encodeURIComponent(kwaiClickId)}`)
        .then(response => response.json())
        .then(data => console.log('Content View tracked:', data))
        .catch(error => console.error('Content View tracking error:', error));

    // --- FUNÇÕES DE MÁSCARA ---
    function formatPhone(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length > 11) value = value.substring(0, 11);

        if (value.length > 7) {
            value = `(${value.substring(0, 2)}) ${value.substring(2, 7)}-${value.substring(7)}`;
        } else if (value.length > 2) {
            value = `(${value.substring(0, 2)}) ${value.substring(2)}`;
        } else if (value.length > 0) {
            value = `(${value}`;
        }
        input.value = value;
    }

    function formatCPF(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length > 11) value = value.substring(0, 11);

        if (value.length > 9) {
            value = `${value.substring(0, 3)}.${value.substring(3, 6)}.${value.substring(6, 9)}-${value.substring(9)}`;
        } else if (value.length > 6) {
            value = `${value.substring(0, 3)}.${value.substring(3, 6)}.${value.substring(6)}`;
        } else if (value.length > 3) {
            value = `${value.substring(0, 3)}.${value.substring(3)}`;
        }
        input.value = value;
    }

    function formatRG(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length > 9) value = value.substring(0, 9);

        if (value.length > 8) {
            value = `${value.substring(0, 2)}.${value.substring(2, 5)}.${value.substring(5, 8)}-${value.substring(8)}`;
        } else if (value.length > 5) {
            value = `${value.substring(0, 2)}.${value.substring(2, 5)}.${value.substring(5)}`;
        } else if (value.length > 2) {
            value = `${value.substring(0, 2)}.${value.substring(2)}`;
        }
        input.value = value;
    }

    function formatCEP(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length > 8) value = value.substring(0, 8);
        if (value.length > 5) {
            value = `${value.substring(0, 5)}-${value.substring(5)}`;
        }
        input.value = value;
    }

    // --- LISTENERS DE MÁSCARA ---
    const inputs = {
        telefone: formatPhone,
        cpf: formatCPF,
        rg: formatRG,
        cep: formatCEP
    };

    for (const [id, formatter] of Object.entries(inputs)) {
        const el = document.getElementById(id);
        if (el) el.addEventListener('input', (e) => formatter(e.target));
    }

    // --- INTEGRAÇÃO VIACEP ---
    window.meu_callback = function (conteudo) {
        if (!("erro" in conteudo)) {
            document.getElementById('logradouro').value = conteudo.logradouro;
            document.getElementById('bairro').value = conteudo.bairro;
            document.getElementById('cidade').value = conteudo.localidade;
            document.getElementById('uf').value = conteudo.uf;
            document.getElementById('numero').focus();
        } else {
            alert("CEP não encontrado.");
            limpa_cep();
        }
    }

    function limpa_cep() {
        ['logradouro', 'bairro', 'cidade', 'uf'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = "";
        });
    }

    const cepInput = document.getElementById('cep');
    if (cepInput) {
        cepInput.addEventListener('blur', function () {
            var cep = this.value.replace(/\D/g, '');
            if (cep != "") {
                var validacep = /^[0-9]{8}$/;
                if (validacep.test(cep)) {
                    document.getElementById('logradouro').value = "...";
                    document.getElementById('bairro').value = "...";
                    document.getElementById('cidade').value = "...";
                    document.getElementById('uf').value = "...";

                    var script = document.createElement('script');
                    script.src = 'https://viacep.com.br/ws/' + cep + '/json/?callback=meu_callback';
                    document.body.appendChild(script);
                } else {
                    alert("Formato de CEP inválido.");
                    limpa_cep();
                }
            } else {
                limpa_cep();
            }
        });
    }

    // --- NAVEGAÇÃO E ANIMAÇÃO ---
    window.nextStep = function (stepNumber) {
        // Se estiver avançando (e não voltando), valida o passo atual
        if (stepNumber > currentStep && !validateStep(currentStep)) return;

        // Salva os dados da etapa atual no banco
        if (stepNumber > currentStep) {
            saveStepData(currentStep);

            // Pixels: quando passar da Step 2 (Dados Iniciais) para a Step 3
            if (currentStep === 2 && stepNumber === 3) {
                // Kwai Pixel: AddToCart
                if (typeof PIXEL_CONFIG !== 'undefined' && PIXEL_CONFIG.KWAI.ENABLED && typeof kwaiq !== 'undefined') {
                    try {
                        kwaiq.instance(PIXEL_CONFIG.KWAI.PIXEL_ID).track('addToCart');
                    } catch (e) {
                        console.error('Kwai Pixel Error:', e);
                    }
                }
                // Facebook Pixel: Lead
                if (typeof PIXEL_CONFIG !== 'undefined' && PIXEL_CONFIG.FACEBOOK.ENABLED && typeof fbq !== 'undefined') {
                    try {
                        fbq('track', 'Lead');
                    } catch (e) {
                        console.error('Facebook Pixel Error:', e);
                    }
                }
            }
        }

        const currentEl = document.getElementById(`step-${currentStep}`);
        const nextEl = document.getElementById(`step-${stepNumber}`);

        // Logo -> Form
        if (currentStep === 1 && stepNumber === 2) {
            animateLogoToHeader();
            if (currentEl) currentEl.classList.remove('active');
            setTimeout(() => {
                currentStep = stepNumber;
                if (nextEl) nextEl.classList.add('active');
            }, 500);
            return;
        }

        // Form -> Form (com animação de saída)
        if (currentEl) {
            currentEl.classList.add('exiting');
            currentEl.classList.remove('active');

            setTimeout(() => {
                currentEl.classList.remove('exiting');
                currentStep = stepNumber;
                if (nextEl) {
                    nextEl.classList.add('active');
                    const firstInput = nextEl.querySelector('input, select');
                    if (firstInput) firstInput.focus();
                }
            }, 400); // Sincronizado com CSS transition
        }
    };

    function animateLogoToHeader() {
        logoContainer.classList.add('header-mode');
        mainContent.classList.add('visible');
    }

    // Iniciar ao clicar na logo (opcional)
    logoContainer.addEventListener('click', () => {
        if (currentStep === 1) nextStep(2);
    });

    // --- VALIDAÇÃO ---
    function validateStep(step) {
        const stepEl = document.getElementById(`step-${step}`);
        if (!stepEl) return true;

        let valid = true;

        // 1. Inputs Normais e Selects
        const inputs = stepEl.querySelectorAll('input:not([type="radio"]), select');
        inputs.forEach(input => {
            if (input.id === 'complemento') return; // Opcional

            // Reset visual
            input.style.borderColor = 'rgba(255, 255, 255, 0.2)';

            // Required Check
            if (input.hasAttribute('required') && !input.value.trim()) {
                valid = false;
                input.style.borderColor = '#ef4444'; // Red
                input.addEventListener('input', () => input.style.borderColor = 'rgba(255, 255, 255, 0.2)', { once: true });
            }

            // Length Checks (se tiver valor)
            if (input.value.trim()) {
                const val = input.value.replace(/\D/g, '');
                if (input.id === 'telefone' && val.length < 10) valid = false; // Aceita fixo ou cel
                if (input.id === 'cpf' && val.length !== 11) valid = false;
                // RG validação mais solta
                if (input.id === 'rg' && val.length < 5) valid = false;

                if (!valid && input.style.borderColor !== '#ef4444') {
                    input.style.borderColor = '#ef4444';
                    input.addEventListener('input', () => input.style.borderColor = 'rgba(255, 255, 255, 0.2)', { once: true });
                }
            }
        });

        // 2. Radio Buttons (FGTS)
        const radioGroups = stepEl.querySelectorAll('.radio-group');
        radioGroups.forEach(group => {
            const requiredRadio = group.querySelector('input[type="radio"][required]');
            if (requiredRadio) {
                const radios = group.querySelectorAll('input[type="radio"]');
                let isChecked = false;
                radios.forEach(r => { if (r.checked) isChecked = true; });

                if (!isChecked) {
                    valid = false;
                    const spans = group.querySelectorAll('span');
                    spans.forEach(s => s.style.borderColor = '#ef4444');

                    // Limpar erro ao selecionar
                    radios.forEach(r => {
                        r.addEventListener('change', () => {
                            spans.forEach(s => s.style.borderColor = 'rgba(255, 255, 255, 0.2)');
                        }, { once: true });
                    });
                }
            }
        });

        if (!valid) {
            // Feedback vibrante ou shake poderia ser adicionado aqui
            // alert('Por favor, verifique os campos destacados.'); 
            // Alert removido para ser mais elegante, apenas bordas vermelhas.
        }
        return valid;
    }

    function saveStepData(step) {
        const form = document.getElementById('lead-form');
        if (!form) return;

        const formData = new FormData(form);
        formData.append('step', step);

        fetch('save_step.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.lead_id) {
                    const idField = document.getElementById('lead_id');
                    if (idField && !idField.value) {
                        idField.value = data.lead_id;
                    }
                    console.log('Step ' + step + ' saved. ID: ' + data.lead_id);
                }
            })
            .catch(error => {
                console.error('Error saving step:', error);
            });
    }

    // --- ENVIO DO FORMULÁRIO ---
    const form = document.getElementById('lead-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            console.log('Tentando enviar formulario. Step:', currentStep);

            // Valida o passo atual (Passo 5 - FGTS) antes de enviar
            if (!validateStep(currentStep)) {
                console.warn('Validacao falhou no step:', currentStep);
                return;
            }
            console.log('Validacao OK. Prosseguindo...');

            const btnSubmit = document.getElementById('btn-submit');
            const originalText = btnSubmit.innerText;

            // Feedback Visual de Carregamento
            btnSubmit.innerText = 'ENVIANDO...';
            btnSubmit.style.opacity = '0.7';
            btnSubmit.disabled = true;

            const formData = new FormData(form);

            // Limpeza final dos dados antes do envio (redundante com backend mas bom)
            ['telefone', 'cpf', 'rg', 'cep'].forEach(key => {
                if (formData.has(key)) {
                    formData.set(key, formData.get(key).replace(/\D/g, ''));
                }
            });

            fetch('process.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Kwai Pixel: CompleteRegistration e Purchase
                        if (typeof PIXEL_CONFIG !== 'undefined' && PIXEL_CONFIG.KWAI.ENABLED && typeof kwaiq !== 'undefined') {
                            try {
                                kwaiq.instance(PIXEL_CONFIG.KWAI.PIXEL_ID).track('completeRegistration');
                                kwaiq.instance(PIXEL_CONFIG.KWAI.PIXEL_ID).track('purchase');
                            } catch (e) {
                                console.error('Kwai Pixel Error:', e);
                            }
                        }

                        // Facebook Pixel: CompleteRegistration e Purchase
                        if (typeof PIXEL_CONFIG !== 'undefined' && PIXEL_CONFIG.FACEBOOK.ENABLED && typeof fbq !== 'undefined') {
                            try {
                                fbq('track', 'CompleteRegistration');
                                fbq('track', 'Purchase', { currency: 'BRL', value: 0 });
                            } catch (e) {
                                console.error('Facebook Pixel Error:', e);
                            }
                        }

                        // Transição Suave para Sucesso (Passo 7)
                        const currentEl = document.getElementById(`step-${currentStep}`);
                        const successEl = document.getElementById('step-7'); // ID fixo no HTML

                        if (currentEl) {
                            currentEl.classList.add('exiting');
                            currentEl.classList.remove('active');

                            setTimeout(() => {
                                currentEl.classList.remove('exiting');
                                // Pula passo 6 inexistente
                                currentStep = 7;
                                if (successEl) successEl.classList.add('active');
                            }, 400);
                        }
                    } else {
                        alert('Erro no envio: ' + data.message);
                        btnSubmit.innerText = originalText;
                        btnSubmit.style.opacity = '1';
                        btnSubmit.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Ocorreu um erro técnico. Tente novamente.');
                    btnSubmit.innerText = originalText;
                    btnSubmit.style.opacity = '1';
                    btnSubmit.disabled = false;
                });
        });
    }
});