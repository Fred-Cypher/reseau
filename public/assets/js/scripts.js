window.onload = () => {
    let visibility = document.querySelectorAll("[type=checkbox]")

    for (let visibilityButton of visibility){
        visibilityButton.addEventListener("click", function() {
            let xmlhttp = new XMLHttpRequest;

            xmlhttp.open("post", 'articles/index.html.twig')
            xmlhttp.send()
        })
    } 

}

/* activeAnnonce 
window.onload = () => {
    let visibilityButtons = document.querySelectorAll(".form-check-input")

    for(let visibilityButton of visibilityButtons){
        visibilityButton.addEventListener("click", makeVisible)
    }
}

function makeVisible(){
    let xmlhttp = new XMLHttpRequest;

    xmlhttp.open('GET', '/admin/articles_visibility/'+this.dataset.id)

    xmlhttp.send()
}

*/

/**let modal = new Modal('#modal-delete');
			let activer = document.querySelectorAll("[type=checkbox]")
			for (let bouton of activer) {
				bouton.addEventListener("click", function () {
					let xmlhttp = new XMLHttpRequest;
					xmlhttp.open("get", `/admin/annonces/activer/${this.dataset.id}`)
					xmlhttp.send()
				})
			} */