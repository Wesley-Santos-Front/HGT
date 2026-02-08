const messagemDiv = document.getElementById("mensagemDiv");

//verifica se existe na pagina
if(messagemDiv){

  //define um cronomentro de 10 segundos
  setTimeout(() =>{

    //adiciona uma transição suave de opacidade
    messagemDiv.style.transition="opacity 1s ease";
    messagemDiv.style.opacity="0";

    //remova o elemento do html
    setTimeout(() =>{
      messagemDiv.style.display="none";

    }, 1000); // 1 segundo extra para a animação de fade completar
  }, 4000);
}