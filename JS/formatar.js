// Função para aplicar máscara no CPF
function aplicarMascaraCPF(cpf) {
    cpf = cpf.replace(/\D/g, ""); // Remove qualquer caractere que não seja número
    cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2"); // Adiciona o primeiro ponto
    cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2"); // Adiciona o segundo ponto
    cpf = cpf.replace(/(\d{3})(\d{1,2})$/, "$1-$2"); // Adiciona o traço
    return cpf;
}

// Função para aplicar máscara no telefone
function aplicarMascaraTelefone(telefone) {
    telefone = telefone.replace(/\D/g, ""); // Remove qualquer caractere que não seja número
    telefone = telefone.replace(/^(\d{2})(\d)/g, "($1)$2"); // Adiciona os parênteses ao DDD
    telefone = telefone.r// Função para aplicar máscara no CPF
    function aplicarMascaraCPF(cpf) {
        cpf = cpf.replace(/\D/g, ""); // Remove qualquer caractere que não seja número
        cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2"); // Adiciona o primeiro ponto
        cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2"); // Adiciona o segundo ponto
        cpf = cpf.replace(/(\d{3})(\d{1,2})$/, "$1-$2"); // Adiciona o traço
        return cpf;
    }
    
    // Função para aplicar máscara no telefone
    function aplicarMascaraTelefone(telefone) {
        telefone = telefone.replace(/\D/g, ""); // Remove qualquer caractere que não seja número
        telefone = telefone.replace(/^(\d{2})(\d)/g, "($1)$2"); // Adiciona os parênteses ao DDD
        telefone = telefone.replace(/(\d{5})(\d{4})$/, "$1-$2"); // Adiciona o traço no número
        return telefone;
    }
    
    // Função para aplicar máscara no CEP
    function aplicarMascaraCEP(cep) {
        cep = cep.replace(/\D/g, ""); // Remove qualquer caractere que não seja número
        cep = cep.replace(/(\d{5})(\d{3})$/, "$1-$2"); // Adiciona o traço
        return cep;
    }
    
    // Adiciona eventos aos campos para aplicar as máscaras
    document.addEventListener("DOMContentLoaded", function() {
        const cpfInput = document.querySelector('input[name="cpf"]');
        const telefoneInput = document.querySelector('input[name="telefone"]');
        const cepInput = document.querySelector('input[name="cep"]');
    
        cpfInput.addEventListener("input", function() {
            this.value = aplicarMascaraCPF(this.value);
        });
    
        telefoneInput.addEventListener("input", function() {
            this.value = aplicarMascaraTelefone(this.value);
        });
    
        cepInput.addEventListener("input", function() {
            this.value = aplicarMascaraCEP(this.value);
        });
    });
    eplace(/(\d{5})(\d{4})$/, "$1-$2"); // Adiciona o traço no número
    return telefone;
}

// Função para aplicar máscara no CEP
function aplicarMascaraCEP(cep) {
    cep = cep.replace(/\D/g, ""); // Remove qualquer caractere que não seja número
    cep = cep.replace(/(\d{5})(\d{3})$/, "$1-$2"); // Adiciona o traço
    return cep;
}

document.addEventListener("DOMContentLoaded", function() {
    const cpfInput = document.querySelector('input[name="cpf"]');
    const telefoneInput = document.querySelector('input[name="telefone"]');
    const cepInput = document.querySelector('input[name="cep"]');

    cpfInput.addEventListener("input", function() {
        this.value = aplicarMascaraCPF(this.value);
    });

    telefoneInput.addEventListener("input", function() {
        this.value = aplicarMascaraTelefone(this.value);
    });

    cepInput.addEventListener("input", function() {
        this.value = aplicarMascaraCEP(this.value);
    });
});
