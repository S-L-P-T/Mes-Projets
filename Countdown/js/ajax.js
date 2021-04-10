function getMessages(){
    const requeteAjax = new XMLHttpRequest();
    requeteAjax.open("GET", "send.php");
        requeteAjax.onload = function() {
            const resultat = JSON.parse(requeteAjax.responseText);
            console.log(resultat);
            const html = resultat.map(function(message){
                return `<div id="complete" style="padding: 10px; color: white">
                            <span class="statut">${message.statut}</span>
                            <span class="author" style="color: ${message.color};"> ${message.pseudo}: </span>
                            <span class="content">${message.chat}</span>
                        </div>`
            }).join('');
            const messages = document.querySelector('.tchat');
            messages.innerHTML = html;
            messages.scrollTop = messages.scrollHeight;
        }
    requeteAjax.send();
}

function postMessage(event){
    event.preventDefault();
    const content = document.querySelector("#message");
    const radios = document.getElementsByName('color');
    const statut = document.querySelector('.jaco').textContent;
    const data = new FormData();
    data.append('message', content.value);
    for (var i = 0, length = radios.length; i < length; i++) {
        if (radios[i].checked) {
          data.append('color', radios[i].value);
          // only one radio can be logically checked, don't check the rest
          break;
        }
      }
    data.append('statut', statut);
    const requeteAjax = new XMLHttpRequest();
    requeteAjax.open("POST", 'send.php?task=write');
    requeteAjax.onload = function(){
        content.value = "";
        content.focus();
        getMessages();
    }
    requeteAjax.send(data)

}
document.querySelector('form').addEventListener('submit', postMessage);
getMessages();
const interval = window.setInterval(getMessages, 5000);
