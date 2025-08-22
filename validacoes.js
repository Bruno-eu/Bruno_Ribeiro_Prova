// Funções de validação para o sistema

// Validação de nome - apenas letras e espaços
function validarNome(input) {
    // Remove números e caracteres especiais, mantendo apenas letras e espaços
    input.value = input.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '');
    
    // Remove espaços duplos
    input.value = input.value.replace(/\s+/g, ' ');
    
    // Remove espaços no início e fim
    input.value = input.value.trim();
}

// Validação de email
function validarEmail(input) {
    const email = input.value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (!emailRegex.test(email)) {
        input.setCustomValidity('Digite um email válido');
    } else {
        input.setCustomValidity('');
    }
}

// Validação de senha
function validarSenha(input) {
    const senha = input.value;
    
    if (senha.length < 8) {
        input.setCustomValidity('A senha deve ter pelo menos 8 caracteres');
    } else {
        input.setCustomValidity('');
    }
}

// Validação de confirmação de senha
function validarConfirmarSenha(senhaInput, confirmarInput) {
    if (senhaInput.value !== confirmarInput.value) {
        confirmarInput.setCustomValidity('As senhas não coincidem');
    } else {
        confirmarInput.setCustomValidity('');
    }
}

// Função para mostrar/ocultar senha
function toggleSenha(inputId) {
    const input = document.getElementById(inputId);
    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
}

// Validação de busca - permite números e letras
function validarBusca(input) {
    // Remove caracteres especiais, mantendo apenas letras, números e espaços
    input.value = input.value.replace(/[^A-Za-zÀ-ÿ0-9\s]/g, '');
    
    // Remove espaços duplos
    input.value = input.value.replace(/\s+/g, ' ');
    
    // Remove espaços no início e fim
    input.value = input.value.trim();
}

function validarTelefone(input) {
    // Remove tudo que não for número
    let telefone = input.value.replace(/\D/g, '');

    // Limita a 11 dígitos (padrão Brasil: (99) 99999-9999)
    telefone = telefone.substring(0, 11);

    if (telefone.length > 6) {
        // Formato com 9 dígitos: (99) 99999-9999
        input.value = telefone.replace(/^(\d{2})(\d{5})(\d{0,4})$/, "($1) $2-$3");
    } else if (telefone.length > 2) {
        // Formato com 8 dígitos: (99) 9999-9999
        input.value = telefone.replace(/^(\d{2})(\d{0,4})(\d{0,4})$/, "($1) $2-$3");
    } else {
        // Apenas o DDD
        input.value = telefone.replace(/^(\d*)$/, "($1");
    }
}

