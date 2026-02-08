// Função para preencher a data atual local
function configurarDataInput() {
    const dataTeste = document.getElementById("data_teste");
    
    if (dataTeste) {
        const hoje = new Date();
        // Usamos fuso local para garantir que pegue o dia 03 e não o dia 04 antecipado
        const ano = hoje.getFullYear();
        const mes = String(hoje.getMonth() + 1).padStart(2, '0');
        const dia = String(hoje.getDate()).padStart(2, '0');

        const dataFormatada = `${ano}-${mes}-${dia}`;
        dataTeste.value = dataFormatada;
    }
}


//logica botão modal
const butModal = document.getElementById("butModal");
const modal = document.getElementById("modal");
const closeModal = document.getElementById("closeModal");

butModal.addEventListener("click", function(){
modal.style.display="block";
configurarDataInput();
})
closeModal.addEventListener("click", function(){
    modal.style.display="none";
})


//logica do grafico Chart.js
const ctx = document.getElementById('myChart').getContext('2d');

//data atual
const dataAtual = new Date();
const dataFormatada = dataAtual.toLocaleDateString('pt-BR');

// 1. Mapeamos apenas os valores numéricos das colunas que nos interessam
  // Se o valor for nulo ou vazio, usamos null para o gráfico ignorar ou 0
  const valoresGrafico = [
    dadosTeste.madrugada || null,
    dadosTeste.antesCafe || null,
    dadosTeste.depoisCafe || null,
    dadosTeste.antesAlmoco || null,
    dadosTeste.depoisAlmoco || null,
    dadosTeste.antesJantar || null,
    dadosTeste.depoisJantar || null
  ];
  new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Madrugada', 'Antes do Café', 'Depois do Café', 'Antes do Almoço', 'Depois do Almoço', 'Antes da Janta', 'Depois da Janta'],
        datasets: [{
            label: 'Glicemia (mg/dL) - ' + dataFormatada,
            data: valoresGrafico, // USAMOS O ARRAY FILTRADO AQUI
            borderColor: '#3498db',
            backgroundColor: 'rgba(52, 152, 219, 0.1)',
            pointRadius: 6,
            pointBackgroundColor: '#fff',
            tension: 0.2,
            fill: true
        }]
    },
    options: {
        plugins: {
            // Se você estiver usando o plugin 'chartjs-plugin-datalabels'
            datalabels: {
                display: function(context) {
                    return context.dataset.data[context.dataIndex] !== null; // Esconde se for nulo
                },
                align: 'top',
                anchor: 'end',
                formatter: function(value) {
                    return value + ' mg/dL';
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true // Recomendado para gráficos de saúde
            }
        }
    }
  });

//formatar cores para os dados ultimo teste
const ultimoTest = document.getElementById("ultimoTeste");
const ultimoTesteP = document.getElementById("ultimoTesteP");

ultimoTest.innerHTML = ultimoTeste;

if(ultimoTeste >= 80 && ultimoTeste <= 115 ){
    ultimoTest.style.color = "#2ecc71";
    ultimoTesteP.style.color = "#2ecc71";
} else if(ultimoTeste > 115){
    ultimoTest.style.color = "#e74c3c";
    ultimoTesteP.style.color = "#e74c3c";
} else{
    ultimoTest.style.color = "#F1C40F";
    ultimoTesteP.style.color = "#F1C40F";
}


//Formatar cores para os dados média semana
const mediaSemana = document.getElementById("mediaSemana");
const mediaSemanaP = document.getElementById("mediaSemanaP");

mediaSemana.innerHTML = mediaSemanaV;

if(mediaSemanaV >= 80 && mediaSemanaV <= 115 ){
    mediaSemana.style.color = "#2ecc71";
    mediaSemanaP.style.color = "#2ecc71";
} else if(mediaSemanaV > 115){
    mediaSemana.style.color = "#e74c3c";
    mediaSemanaP.style.color = "#e74c3c";
} else{
    mediaSemana.style.color = "#F1C40F";
    mediaSemanaP.style.color = "#F1C40F";
}



//formatar cores para os dados média mês
const mediaMes = document.getElementById("mediaMes");
const mediaMesP = document.getElementById("mediaMesP");

mediaMes.innerHTML = mediaMesV;

if(mediaMesV >= 80 && mediaMesV <= 115 ){
    mediaMes.style.color = "#2ecc71";
    mediaMesP.style.color = "#2ecc71";
} else if(mediaMesV > 115){
    mediaMes.style.color = "#e74c3c";
    mediaMesP.style.color = "#e74c3c";
} else {
    mediaMes.style.color = "#F1C40F";
    mediaMesP.style.color = "#F1C40F";
}




