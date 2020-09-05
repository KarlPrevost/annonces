window.onload = () => {
    //Gestion des boutons de suppression
    let links = document.querySelectorAll("[data-delete]")
    
    //on boucle sur links
    for(link of links){
        // ecouter le clic
        link.addEventListener("click", function(e){
            // supprime la navigation au clic sur le lien par exemple
            e.preventDefault()

            // demande de confirmation
            if(confirm("Do you want to delete this Image?")){
                // requete ajax(sous forme de promesse) vers le href du lien avec la methode DELETE
                fetch(this.getAttribute('href'), {
                    method: "DELETE",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({"_token": this.dataset.token})
                }).then(
                    // recup la reponse en JSON
                    response => response.json()
                ).then(data => {
                    if(data.success)
                        this.parentElement.remove()
                    else
                    alert(data.error)
                }).catch(e => alert(e))
            }
        })
    }


    
}