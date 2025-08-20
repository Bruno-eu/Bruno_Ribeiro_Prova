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
function mostrarSenha() {
    const senha1 = document.getElementById("nova_senha");
    const senha2 = document.getElementById("confirmar_senha");
    
    if (senha1 && senha2) {
        const tipo = senha1.type === "password" ? "text" : "password";
        senha1.type = tipo;
        senha2.type = tipo;
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
